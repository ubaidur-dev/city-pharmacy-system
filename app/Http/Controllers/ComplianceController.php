<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    /** Every sale line of a controlled drug, with who it was dispensed to and on whose prescription. */
    public function controlled(Request $request)
    {
        $controlledIds = \App\Models\Medicine::where('is_controlled', true)->pluck('id')->all();
        $rows = [];

        Order::whereNotNull('rx_number')->latest()->take(500)->get()->each(function ($order) use ($controlledIds, &$rows) {
            foreach ($order->cart_items ?? [] as $line) {
                if (in_array((int) ($line['id'] ?? 0), $controlledIds, true)) {
                    $rows[] = [
                        'date' => $order->created_at, 'invoice' => $order->invoice_number, 'customer' => $order->patient_name,
                        'phone' => $order->phone, 'medicine' => $line['name'], 'qty' => $line['qty'],
                        'batches' => collect($line['batches'] ?? [])->pluck('batch_no')->implode(', '),
                        'prescriber' => $order->rx_prescriber, 'rx' => $order->rx_number,
                    ];
                }
            }
        });

        if ($request->query('export')) {
            return response()->streamDownload(function () use ($rows) {
                $out = fopen('php://output', 'w');
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv($out, ['Date', 'Invoice', 'Customer', 'Phone', 'Medicine', 'Qty', 'Batches', 'Prescriber', 'Rx number']);
                foreach ($rows as $r) {
                    fputcsv($out, array_map(fn ($v) => is_string($v) && preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v,
                        [$r['date']->format('Y-m-d H:i'), $r['invoice'], $r['customer'], $r['phone'], $r['medicine'], $r['qty'], $r['batches'], $r['prescriber'], $r['rx']]));
                }
                fclose($out);
            }, 'controlled-drug-register-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
        }

        return view('controlled-register', ['rows' => $rows]);
    }

    public function audit(Request $request)
    {
        $logs = AuditLog::with('user')->latest('id')
            ->when($request->query('action'), fn ($q, $a) => $q->where('action', 'like', $a . '%'))
            ->paginate(40)->withQueryString();

        return view('audit', ['logs' => $logs, 'actions' => AuditLog::distinct()->pluck('action')->sort()->values()]);
    }
}
