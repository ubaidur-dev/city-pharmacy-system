<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Medicine;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasingController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get()->each(fn ($s) => $s->owed = $s->balance());
        $orders = PurchaseOrder::with(['supplier', 'items'])->latest()->take(50)->get();

        return view('purchasing', [
            'suppliers' => $suppliers,
            'orders' => $orders,
            'totalOwed' => $suppliers->sum('owed'),
        ]);
    }

    public function storeSupplier(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);
        Supplier::create($data);

        return back()->with('success', 'Supplier added.');
    }

    public function showSupplier(Supplier $supplier)
    {
        return view('supplier-show', [
            'supplier' => $supplier,
            'orders' => $supplier->purchaseOrders()->with('items')->latest()->get(),
            'payments' => $supplier->payments()->latest()->get(),
            'owed' => $supplier->balance(),
        ]);
    }

    public function storePayment(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:100000000',
            'method' => 'nullable|string|max:30',
            'note' => 'nullable|string|max:255',
        ]);
        $supplier->payments()->create($data);
        AuditLog::record('supplier.payment', "Paid {$data['amount']} to {$supplier->name}");

        return back()->with('success', 'Payment recorded.');
    }

    public function createOrder(Request $request)
    {
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'company', 'stock'])->map(function ($m) {
            $m->last_cost = DB::table('medicine_batches')->where('medicine_id', $m->id)->whereNotNull('cost_price')->orderByDesc('id')->value('cost_price');
            return $m;
        });

        return view('purchase-create', [
            'suppliers' => Supplier::orderBy('name')->get(),
            'medicines' => $medicines,
            'supplierId' => $request->query('supplier'),
            'prefill' => $request->query('medicine'),
        ]);
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000000',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $po = DB::transaction(function () use ($data) {
            $po = PurchaseOrder::create(['supplier_id' => $data['supplier_id'], 'notes' => $data['notes'] ?? null]);
            $po->update(['po_number' => 'PO-' . now()->format('Y') . '-' . str_pad($po->id, 5, '0', STR_PAD_LEFT)]);
            $po->items()->createMany($data['items']);

            return $po;
        });

        return redirect("/purchasing/orders/{$po->id}")->with('success', "{$po->po_number} created.");
    }

    public function showOrder(PurchaseOrder $order)
    {
        return view('purchase-show', ['po' => $order->load(['supplier', 'items.medicine'])]);
    }

    /** Goods arrive: each line becomes a batch, and the supplier balance grows by what was received. */
    public function receive(Request $request, PurchaseOrder $order)
    {
        abort_unless($order->status === 'ordered', 422, 'This order is already closed.');

        $data = $request->validate([
            'lines' => 'required|array',
            'lines.*.received_quantity' => 'required|integer|min:0|max:1000000',
            'lines.*.batch_no' => 'nullable|string|max:100',
            'lines.*.expiry_date' => 'nullable|date',
        ]);

        $total = 0;
        DB::transaction(function () use ($order, $data, &$total) {
            foreach ($order->items()->with('medicine')->get() as $item) {
                $line = $data['lines'][$item->id] ?? null;
                $qty = (int) ($line['received_quantity'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }
                if (empty($line['batch_no']) || empty($line['expiry_date']) || $line['expiry_date'] <= today()->toDateString()) {
                    abort(422, "{$item->medicine->name}: enter a batch number and a future expiry date.");
                }
                $item->medicine->addBatch($line['batch_no'], $line['expiry_date'], $qty, (float) $item->unit_cost);
                $item->update(['received_quantity' => $qty]);
                $total += $qty * $item->unit_cost;
            }
            $order->update(['status' => 'received', 'total_cost' => round($total, 2), 'received_at' => now()]);
        });

        AuditLog::record('po.received', "{$order->po_number} received, value {$total}");

        return redirect("/purchasing/orders/{$order->id}")->with('success', 'Stock received into batches.');
    }

    public function cancel(PurchaseOrder $order)
    {
        abort_unless($order->status === 'ordered', 422, 'Only open orders can be cancelled.');
        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order cancelled.');
    }
}
