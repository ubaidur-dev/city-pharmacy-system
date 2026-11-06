<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'reason' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.index' => 'required|integer|min:0',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            $return = DB::transaction(function () use ($order, $data, $request) {
                $order = Order::lockForUpdate()->findOrFail($order->id);
                $lines = $order->cart_items ?? [];
                $already = $order->returned_map;
                $ratio = $order->subtotal > 0 ? $order->total_amount / $order->subtotal : 1;
                $refundedSoFar = (float) $order->returns->sum('refund_amount');

                $returned = [];
                $refund = 0;

                foreach ($data['items'] as $row) {
                    $i = $row['index'];
                    $line = $lines[$i] ?? throw new \RuntimeException('Invoice line not found.');
                    $left = $line['qty'] - ($already[$i] ?? 0);
                    if ($row['qty'] > $left) {
                        throw new \RuntimeException("Only {$left} unit(s) of {$line['name']} can still be returned.");
                    }

                    $amount = round($line['price'] * $row['qty'] * $ratio, 2);
                    $refund += $amount;
                    $returned[] = ['index' => $i, 'name' => $line['name'], 'qty' => $row['qty'], 'amount' => $amount];

                    if (is_numeric($line['id'] ?? null) && $medicine = Medicine::find($line['id'])) {
                        $this->restock($medicine, $line, $already[$i] ?? 0, $row['qty']);
                    }
                }

                // Never refund more than the customer paid, even after rounding.
                $refund = min(round($refund, 2), round($order->total_amount - $refundedSoFar, 2));

                // Points earned on goods that came back are taken back too.
                if ($order->customer_id && $order->points_earned > 0 && $order->total_amount > 0) {
                    $clawback = (int) floor($order->points_earned * min(1, $refund / $order->total_amount));
                    $customer = \App\Models\Customer::lockForUpdate()->find($order->customer_id);
                    $customer?->update(['points' => max(0, $customer->points - $clawback)]);
                }

                return OrderReturn::create([
                    'order_id' => $order->id,
                    'user_id' => $request->user()->id,
                    'reason' => $data['reason'] ?? null,
                    'refund_amount' => $refund,
                    'items' => $returned,
                ]);
            });
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        AuditLog::record('sale.returned', "Return on {$order->invoice_number}: refund {$return->refund_amount}", ['reason' => $data['reason'] ?? null]);

        return response()->json(['success' => true, 'refund' => $return->refund_amount, 'message' => 'Return recorded and stock restored.']);
    }

    /**
     * Give units back to the batches they were sold from (in the order they were taken),
     * skipping the units already returned earlier.
     */
    private function restock(Medicine $medicine, array $line, int $alreadyReturned, int $qty): void
    {
        $skip = $alreadyReturned;
        $left = $qty;

        foreach ($line['batches'] ?? [] as $b) {
            if ($left <= 0) {
                break;
            }
            $available = $b['qty'] - min($skip, $b['qty']);
            $skip = max(0, $skip - $b['qty']);
            $give = min($left, $available);
            if ($give <= 0) {
                continue;
            }

            $batch = $medicine->batches()->where('batch_no', $b['batch_no'])->whereDate('expiry_date', $b['expiry_date'])->first();
            $batch ? $batch->increment('quantity', $give) : $medicine->batches()->create([
                'batch_no' => $b['batch_no'], 'expiry_date' => $b['expiry_date'], 'quantity' => $give,
            ]);
            $left -= $give;
        }

        // Older invoices carry no batch info: return into a dedicated batch.
        if ($left > 0) {
            $expiry = $medicine->batches()->max('expiry_date') ?? $medicine->expiry_date;
            $batch = $medicine->batches()->where('batch_no', 'RETURNED')->whereDate('expiry_date', $expiry)->first();
            $batch ? $batch->increment('quantity', $left) : $medicine->batches()->create([
                'batch_no' => 'RETURNED', 'expiry_date' => $expiry, 'quantity' => $left,
            ]);
        }

        $medicine->syncFromBatches();
    }
}
