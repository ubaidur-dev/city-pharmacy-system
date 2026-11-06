@extends('layouts.app')

@section('title', $medicine->name . ' - Stock & Batches')

@section('content')
@php
    $sellable = $batches->filter(fn ($b) => ! $b->isExpired())->sum('quantity');
    $warnDays = $store->expiry_warning_days;
@endphp

<a href="/medicines" class="text-sm font-bold text-navy-600 hover:underline">← Back to inventory</a>

<div class="mt-3 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">{{ $medicine->name }}</h1>
        <p class="text-base text-slate-500 mt-2">{{ $medicine->company }} · {{ $medicine->category }} · {{ $cur }} {{ number_format($medicine->price, 2) }} per unit</p>
    </div>
    <div class="flex gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-4 shadow-sm"><p class="text-xs font-semibold text-slate-500 uppercase">On hand</p><p class="text-2xl font-extrabold text-slate-900">{{ $medicine->stock }}</p></div>
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-4 shadow-sm"><p class="text-xs font-semibold text-slate-500 uppercase">Sellable</p><p class="text-2xl font-extrabold text-navy-700">{{ $sellable }}</p></div>
    </div>
</div>

@if(session('success'))
    <div class="theme-success-msg mb-6 px-5 py-4 rounded-2xl border font-semibold">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="mb-6 px-5 py-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 font-semibold">{{ $errors->first() }}</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-lg">Batches</h3>
            <p class="text-sm text-slate-500">Sales always use the batch that expires first. Expired batches are never sold.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr><th class="px-6 py-3">Batch</th><th class="px-6 py-3">Expiry</th><th class="px-6 py-3">Status</th><th class="px-6 py-3 text-right">Qty</th><th class="px-6 py-3 text-right">Cost</th>@if(auth()->user()->role === 'owner')<th class="px-6 py-3"></th>@endif</tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($batches as $b)
                        @php
                            $days = (int) today()->diffInDays($b->expiry_date, false);
                            [$label, $chip] = $b->quantity === 0 ? ['Empty', 'bg-slate-100 text-slate-500']
                                : ($days < 0 ? ['Expired', 'bg-red-100 text-red-700']
                                : ($days <= $warnDays ? ["Expires in {$days}d", 'bg-amber-100 text-amber-700'] : ['Good', 'bg-navy-50 text-navy-700']));
                        @endphp
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $b->batch_no }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $b->expiry_date->format('d M Y') }}</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $chip }}">{{ $label }}</span></td>
                            <td class="px-6 py-4 text-right font-extrabold text-slate-900">{{ $b->quantity }}</td>
                            <td class="px-6 py-4 text-right text-slate-600">{{ $b->cost_price !== null ? $cur . ' ' . number_format($b->cost_price, 2) : '-' }}</td>
                            @if(auth()->user()->role === 'owner')
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="/medicines/{{ $medicine->id }}/batches/{{ $b->id }}" onsubmit="return confirm('Remove this batch and its {{ $b->quantity }} units from stock?')">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="if(confirm('Remove this batch and its {{ $b->quantity }} units from stock?')) this.form.submit()" class="dropdown-btn text-rose-600 hover:underline font-bold bg-transparent">Remove</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">No batches yet. Receive your first stock on the right.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
    @if($medicine->generic_name)
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
        <h3 class="font-bold text-slate-900 text-lg">Same ingredient: {{ $medicine->generic_name }}</h3>
        <p class="text-sm text-slate-500 mb-3">Other brands in stock, cheapest first.</p>
        @forelse($alternatives as $alt)
            <a href="/medicines/{{ $alt->id }}/batches" class="flex items-center justify-between py-2.5 border-b border-slate-100 hover:text-navy-700"><span class="font-semibold">{{ $alt->name }}</span><span class="text-sm text-slate-500">{{ $cur }} {{ number_format($alt->price, 2) }} · {{ $alt->sellableStock() }} left</span></a>
        @empty<p class="text-sm text-slate-500">No other brand in stock.</p>@endforelse
    </div>
    @endif
    <form method="POST" action="/medicines/{{ $medicine->id }}/batches" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 h-fit">
        @csrf
        <h3 class="font-bold text-slate-900 text-lg mb-1">Receive stock</h3>
        <p class="text-sm text-slate-500 mb-5">Add a new delivery as its own batch.</p>

        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Batch number</label>
        <input name="batch_no" value="{{ old('batch_no') }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">

        <label class="block text-sm font-semibold text-slate-700 mt-4 mb-1.5">Expiry date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" min="{{ today()->addDay()->toDateString() }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Quantity</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity') }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cost / unit ({{ $cur }})</label>
                <input type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price') }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
            </div>
        </div>
        <button type="submit" class="mt-6 w-full py-3.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md transition">Add batch</button>
    </form>
    </div>
</div>
@endsection
