@extends('layouts.app')

@section('title', $po->po_number)

@section('content')
@php $inp = 'w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none bg-white'; @endphp
<a href="/purchasing" class="text-sm font-bold text-navy-600 hover:underline">← Purchasing</a>
<div class="mt-3 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">{{ $po->po_number }}</h1>
        <p class="text-base text-slate-500 mt-2"><a href="/purchasing/suppliers/{{ $po->supplier_id }}" class="font-bold text-navy-600 hover:underline">{{ $po->supplier->name }}</a> · {{ $po->created_at->format('d M Y') }} · <b>{{ ucfirst($po->status) }}</b>@if($po->notes) · {{ $po->notes }}@endif</p>
    </div>
    <div class="flex gap-3">
        <button type="button" onclick="window.print()" class="dropdown-btn px-5 py-3 rounded-xl border border-slate-300 bg-white font-bold text-slate-700 hover:bg-slate-50">Print</button>
        @if($po->status === 'ordered')
        <form method="POST" action="/purchasing/orders/{{ $po->id }}/cancel" onsubmit="return confirm('Cancel this order?')">@csrf<button type="button" onclick="if(confirm('Cancel this order?')) this.form.submit()" class="dropdown-btn px-5 py-3 rounded-xl bg-rose-50 text-rose-700 font-bold">Cancel order</button></form>
        @endif
    </div>
</div>

@if(session('success'))<div class="theme-success-msg mb-6 px-5 py-4 rounded-2xl border font-semibold">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-6 px-5 py-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 font-semibold">{{ $errors->first() }}</div>@endif

<form method="POST" action="/purchasing/orders/{{ $po->id }}/receive" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    @csrf
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                <tr><th class="px-6 py-3">Medicine</th><th class="px-6 py-3 text-right">Ordered</th><th class="px-6 py-3 text-right">Unit cost</th>
                @if($po->status === 'ordered')<th class="px-6 py-3">Received qty</th><th class="px-6 py-3">Batch no.</th><th class="px-6 py-3">Expiry</th>@else<th class="px-6 py-3 text-right">Received</th>@endif
                <th class="px-6 py-3 text-right">Line</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @foreach($po->items as $it)
                <tr>
                    <td class="px-6 py-4 font-bold text-slate-900">{{ $it->medicine->name }}</td>
                    <td class="px-6 py-4 text-right">{{ $it->quantity }}</td>
                    <td class="px-6 py-4 text-right">{{ $cur }} {{ number_format($it->unit_cost, 2) }}</td>
                    @if($po->status === 'ordered')
                        <td class="px-6 py-4"><input type="number" min="0" name="lines[{{ $it->id }}][received_quantity]" value="{{ $it->quantity }}" class="{{ $inp }} w-24"></td>
                        <td class="px-6 py-4"><input name="lines[{{ $it->id }}][batch_no]" value="{{ $po->po_number }}" class="{{ $inp }} w-40"></td>
                        <td class="px-6 py-4"><input type="date" name="lines[{{ $it->id }}][expiry_date]" min="{{ today()->addDay()->toDateString() }}" class="{{ $inp }}"></td>
                    @else
                        <td class="px-6 py-4 text-right">{{ $it->received_quantity }}</td>
                    @endif
                    <td class="px-6 py-4 text-right font-bold">{{ $cur }} {{ number_format(($po->status === 'received' ? $it->received_quantity : $it->quantity) * $it->unit_cost, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-5 border-t border-slate-100 flex items-center justify-between">
        <p class="text-lg font-bold text-slate-900">Total: {{ $cur }} {{ number_format($po->status === 'received' ? $po->total_cost : $po->orderedTotal(), 2) }}</p>
        @if($po->status === 'ordered')
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md transition">Receive stock</button>
        @endif
    </div>
</form>
@endsection
