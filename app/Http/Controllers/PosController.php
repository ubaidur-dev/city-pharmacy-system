<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\HeldBill;
use App\Support\DrugKnowledge;
use App\Models\Medicine;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /** Barcode scan (or exact name) -> medicine for the cart. */
    public function lookup(Request $request)
    {
        $code = trim((string) $request->query('code', ''));
        abort_if($code === '', 422, 'No code given.');

        $medicine = Medicine::where('barcode', $code)->first()
            ?? Medicine::where('name', $code)->first();

        if (! $medicine) {
            return response()->json(['found' => false, 'message' => "No medicine matches \"{$code}\"."], 404);
        }

        return response()->json([
            'found' => true,
            'id' => $medicine->id,
            'name' => $medicine->name,
            'price' => (float) $medicine->price,
            'sellable' => $medicine->sellableStock(),
        ]);
    }

    /** Safety net while the cart changes: interactions, allergies, controlled drugs, out-of-stock alternatives. */
    public function check(Request $request)
    {
        $data = $request->validate([
            'phone' => 'nullable|string|max:40',
            'items' => 'present|array',
            'items.*.id' => 'required',
            'items.*.qty' => 'nullable|integer|min:1',
        ]);

        $qtyById = collect($data['items'])->filter(fn ($i) => is_numeric($i['id']))->pluck('qty', 'id');
        $medicines = Medicine::whereIn('id', $qtyById->keys())->get();
        $key = Customer::keyFor($data['phone'] ?? null);
        $customer = $key ? Customer::where('phone_key', $key)->first() : null;

        $warnings = array_merge(DrugKnowledge::allergies($customer, $medicines), DrugKnowledge::interactions($medicines));

        $alternatives = [];
        foreach ($medicines as $m) {
            $sellable = $m->sellableStock();
            if ($sellable < ($qtyById[$m->id] ?? 1)) {
                $warnings[] = ['level' => 'moderate', 'type' => 'stock', 'text' => "{$m->name}: only {$sellable} sellable unit(s) in stock."];
                $alternatives[$m->id] = DrugKnowledge::alternatives($m)->map(fn ($a) => [
                    'id' => $a->id, 'name' => $a->name, 'price' => (float) $a->price, 'sellable' => $a->sellableStock(),
                ])->all();
            }
            if ($m->is_controlled) {
                $warnings[] = ['level' => 'info', 'type' => 'controlled', 'text' => "{$m->name} is a controlled drug: prescriber and Rx number are required."];
            }
        }

        return response()->json([
            'customer' => $customer ? ['name' => $customer->name, 'allergies' => $customer->allergies, 'points' => $customer->points] : null,
            'warnings' => $warnings,
            'alternatives' => $alternatives,
        ]);
    }

    public function held(Request $request)
    {
        return HeldBill::latest()->get()->map(fn ($h) => [
            'id' => $h->id,
            'label' => $h->label,
            'items' => collect($h->payload['cart'] ?? [])->sum('qty'),
            'held_at' => $h->created_at->diffForHumans(),
            'by' => optional(\App\Models\User::find($h->user_id))->name,
        ]);
    }

    public function hold(Request $request)
    {
        $data = $request->validate([
            'label' => 'nullable|string|max:80',
            'payload' => 'required|array',
            'payload.cart' => 'required|array|min:1',
        ]);

        $bill = HeldBill::create([
            'user_id' => $request->user()->id,
            'label' => $data['label'] ?: ($data['payload']['customer_name'] ?? null) ?: 'Held bill',
            'payload' => $data['payload'],
        ]);

        return response()->json(['id' => $bill->id]);
    }

    /** Recalling a bill removes it from the held list. */
    public function resume(HeldBill $bill)
    {
        $payload = $bill->payload;
        $bill->delete();

        return response()->json($payload);
    }

    public function discard(HeldBill $bill)
    {
        $bill->delete();

        return response()->json(['ok' => true]);
    }
}
