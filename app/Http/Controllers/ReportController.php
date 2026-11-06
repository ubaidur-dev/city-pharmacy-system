<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Order;
use App\Models\OrderReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function range(Request $request): array
    {
        $to = $request->filled('to') ? Carbon::parse($request->to)->endOfDay() : now()->endOfDay();
        $from = $request->filled('from') ? Carbon::parse($request->from)->startOfDay() : $to->copy()->subDays(29)->startOfDay();

        return [$from, $to];
    }

    public function index(Request $request)
    {
        [$from, $to] = $this->range($request);

        $orders = Order::whereBetween('created_at', [$from, $to])->get();
        $refunds = (float) OrderReturn::whereBetween('created_at', [$from, $to])->sum('refund_amount');

        // Cost of goods: only lines whose batches have a recorded cost count towards margin.
        $costs = MedicineBatch::whereNotNull('cost_price')->get()
            ->mapWithKeys(fn ($b) => [$b->medicine_id . '|' . $b->batch_no . '|' . $b->expiry_date->toDateString() => (float) $b->cost_price]);

        $products = [];
        $coveredRevenue = $cogs = 0;
        foreach ($orders as $order) {
            foreach ($order->cart_items ?? [] as $line) {
                $rev = $line['price'] * $line['qty'];
                $p = &$products[$line['name']];
                $p['qty'] = ($p['qty'] ?? 0) + $line['qty'];
                $p['revenue'] = ($p['revenue'] ?? 0) + $rev;
                unset($p);

                $lineCost = 0;
                $known = ! empty($line['batches']);
                foreach ($line['batches'] ?? [] as $b) {
                    $c = $costs[($line['id'] ?? 0) . '|' . $b['batch_no'] . '|' . $b['expiry_date']] ?? null;
                    if ($c === null) { $known = false; break; }
                    $lineCost += $c * $b['qty'];
                }
                if ($known) {
                    $coveredRevenue += $rev;
                    $cogs += $lineCost;
                }
            }
        }
        uasort($products, fn ($a, $b) => $b['revenue'] <=> $a['revenue']);

        $expired = MedicineBatch::with('medicine')->where('quantity', '>', 0)->whereDate('expiry_date', '<', today())->get();

        return view('reports', [
            'from' => $from, 'to' => $to,
            'revenue' => (float) $orders->sum('total_amount'),
            'discounts' => (float) $orders->sum('discount'),
            'tax' => (float) $orders->sum('tax'),
            'refunds' => $refunds,
            'invoiceCount' => $orders->count(),
            'daily' => $orders->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))->map(fn ($g) => ['count' => $g->count(), 'total' => $g->sum('total_amount')])->sortKeysDesc(),
            'payments' => $orders->groupBy('payment_method')->map(fn ($g) => $g->sum('total_amount'))->sortDesc(),
            'products' => array_slice($products, 0, 10, true),
            'coveredRevenue' => $coveredRevenue,
            'cogs' => $cogs,
            'retailValue' => (float) Medicine::selectRaw('COALESCE(SUM(price * stock),0) v')->value('v'),
            'expiredUnits' => $expired->sum('quantity'),
            'expiredValue' => $expired->sum(fn ($b) => $b->quantity * ($b->cost_price ?? $b->medicine->price)),
        ]);
    }

    public function export(Request $request, string $type)
    {
        abort_unless(in_array($type, ['sales', 'inventory', 'expiry'], true), 404);
        [$from, $to] = $this->range($request);

        [$header, $rows] = match ($type) {
            'sales' => [
                ['Invoice', 'Date', 'Customer', 'Phone', 'Channel', 'Payment', 'Items', 'Subtotal', 'Discount', 'Tax', 'Total'],
                Order::whereBetween('created_at', [$from, $to])->orderBy('created_at')->get()->map(fn ($o) => [
                    $o->invoice_number, $o->created_at->format('Y-m-d H:i'), $o->patient_name, $o->phone, $o->channel,
                    $o->payment_method, $o->total_items, $o->subtotal, $o->discount, $o->tax, $o->total_amount,
                ]),
            ],
            'inventory' => [
                ['Medicine', 'Barcode', 'Category', 'Company', 'Price', 'On hand', 'Earliest expiry', 'Retail value'],
                Medicine::orderBy('name')->get()->map(fn ($m) => [$m->name, $m->barcode, $m->category, $m->company, $m->price, $m->stock, $m->expiry_date, $m->price * $m->stock]),
            ],
            'expiry' => [
                ['Medicine', 'Batch', 'Expiry', 'Quantity', 'Status'],
                MedicineBatch::with('medicine')->where('quantity', '>', 0)->orderBy('expiry_date')->get()->map(fn ($b) => [
                    $b->medicine->name, $b->batch_no, $b->expiry_date->toDateString(), $b->quantity, $b->isExpired() ? 'EXPIRED' : 'ok',
                ]),
            ],
        };

        return response()->streamDownload(function () use ($header, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // so Excel reads UTF-8
            fputcsv($out, $header);
            foreach ($rows as $row) {
                // Stop spreadsheet formula injection from user-typed text.
                fputcsv($out, array_map(fn ($v) => is_string($v) && preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v, $row));
            }
            fclose($out);
        }, "{$type}-" . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
