@extends('layouts.app')

@section('title', 'New Purchase Order')

@section('content')
@php $inp = 'w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none bg-white'; @endphp
<a href="/purchasing" class="text-sm font-bold text-navy-600 hover:underline">← Purchasing</a>
<h1 class="mt-3 text-3xl font-extrabold text-slate-900">New Purchase Order</h1>
@if($errors->any())<div class="mt-4 px-5 py-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 font-semibold">{{ $errors->first() }}</div>@endif

<form method="POST" action="/purchasing/orders" class="mt-8 bg-white border border-slate-200 rounded-2xl shadow-sm p-8"
      x-data='{ meds: @json($medicines), lines: [{ medicine_id: "{{ $prefill }}", quantity: 1, unit_cost: "" }],
                pick(l) { const m = this.meds.find(m => m.id == l.medicine_id); if (m && m.last_cost && !l.unit_cost) l.unit_cost = m.last_cost; },
                get total() { return this.lines.reduce((a, l) => a + (l.quantity * l.unit_cost || 0), 0) } }'>
    @csrf
    <div class="grid md:grid-cols-2 gap-5 mb-8">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Supplier</label>
            <select name="supplier_id" required class="{{ $inp }}">
                <option value="">Choose supplier…</option>
                @foreach($suppliers as $s)<option value="{{ $s->id }}" @selected($supplierId == $s->id)>{{ $s->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Notes (optional)</label>
            <input name="notes" class="{{ $inp }}">
        </div>
    </div>

    <div class="space-y-3">
        <template x-for="(l, i) in lines" :key="i">
            <div class="grid grid-cols-12 gap-3 items-end">
                <div class="col-span-12 md:col-span-6">
                    <label class="block text-xs font-semibold text-slate-500 mb-1" x-show="i === 0">Medicine</label>
                    <select :name="`items[${i}][medicine_id]`" x-model="l.medicine_id" @change="pick(l)" required class="{{ $inp }}">
                        <option value="">Choose medicine…</option>
                        <template x-for="m in meds" :key="m.id"><option :value="m.id" x-text="`${m.name} (${m.stock} in stock)`"></option></template>
                    </select>
                </div>
                <div class="col-span-4 md:col-span-2"><label class="block text-xs font-semibold text-slate-500 mb-1" x-show="i === 0">Qty</label><input type="number" min="1" :name="`items[${i}][quantity]`" x-model.number="l.quantity" required class="{{ $inp }}"></div>
                <div class="col-span-5 md:col-span-3"><label class="block text-xs font-semibold text-slate-500 mb-1" x-show="i === 0">Unit cost ({{ $cur }})</label><input type="number" step="0.01" min="0" :name="`items[${i}][unit_cost]`" x-model="l.unit_cost" required class="{{ $inp }}"></div>
                <div class="col-span-3 md:col-span-1"><button type="button" @click="lines.splice(i, 1)" x-show="lines.length > 1" class="dropdown-btn w-full py-3 rounded-xl bg-rose-50 text-rose-700 font-bold">✕</button></div>
            </div>
        </template>
    </div>

    <button type="button" @click="lines.push({ medicine_id: '', quantity: 1, unit_cost: '' })" class="dropdown-btn mt-5 px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold hover:bg-slate-50">+ Add line</button>

    <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
        <p class="text-lg font-bold text-slate-900">Order value: <span class="text-navy-700" x-text="'{{ $cur }} ' + total.toFixed(2)"></span></p>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md transition">Create order</button>
    </div>
</form>
@endsection
