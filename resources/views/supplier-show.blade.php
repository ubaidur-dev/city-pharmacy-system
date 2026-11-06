@extends('layouts.app')

@section('title', $supplier->name)

@section('content')
@php $inp = 'w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none bg-white'; @endphp
<a href="/purchasing" class="text-sm font-bold text-navy-600 hover:underline">← Purchasing</a>
<div class="mt-3 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">{{ $supplier->name }}</h1>
        <p class="text-base text-slate-500 mt-2">{{ collect([$supplier->contact_person, $supplier->phone, $supplier->email])->filter()->implode(' · ') ?: 'No contact details yet' }}</p>
    </div>
    <div class="flex gap-3">
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-3 shadow-sm"><p class="text-xs font-semibold text-slate-500 uppercase">Balance owed</p><p class="text-2xl font-extrabold {{ $owed > 0 ? 'text-rose-600' : 'text-slate-900' }}">{{ $cur }} {{ number_format($owed, 2) }}</p></div>
        <a href="/purchasing/orders/create?supplier={{ $supplier->id }}" class="self-center px-6 py-3.5 rounded-2xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md transition">+ Order</a>
    </div>
</div>

@if(session('success'))<div class="theme-success-msg mb-6 px-5 py-4 rounded-2xl border font-semibold">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-6 px-5 py-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 font-semibold">{{ $errors->first() }}</div>@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Purchase orders</h3></div>
            @forelse($orders as $o)
                <a href="/purchasing/orders/{{ $o->id }}" class="flex items-center justify-between px-6 py-3.5 border-b border-slate-100 hover:bg-slate-50">
                    <span><b class="text-navy-700">{{ $o->po_number }}</b> <span class="text-sm text-slate-500">· {{ $o->created_at->format('d M Y') }} · {{ ucfirst($o->status) }}</span></span>
                    <span class="font-bold text-slate-900">{{ $cur }} {{ number_format($o->status === 'received' ? $o->total_cost : $o->orderedTotal(), 2) }}</span>
                </a>
            @empty<p class="px-6 py-8 text-slate-500 text-sm">No orders yet.</p>@endforelse
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Payments made</h3></div>
            @forelse($payments as $p)
                <div class="flex items-center justify-between px-6 py-3.5 border-b border-slate-100">
                    <span class="text-slate-700">{{ $p->created_at->format('d M Y') }} · {{ $p->method ?: 'Payment' }}{{ $p->note ? ' · ' . $p->note : '' }}</span>
                    <span class="font-bold text-navy-700">{{ $cur }} {{ number_format($p->amount, 2) }}</span>
                </div>
            @empty<p class="px-6 py-8 text-slate-500 text-sm">No payments yet.</p>@endforelse
        </div>
    </div>

    <form method="POST" action="/purchasing/suppliers/{{ $supplier->id }}/payments" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 h-fit space-y-3">
        @csrf
        <h3 class="font-bold text-slate-900 text-lg">Record payment</h3>
        <input type="number" step="0.01" min="0.01" name="amount" placeholder="Amount ({{ $cur }})" required class="{{ $inp }}">
        <select name="method" class="{{ $inp }}"><option>Cash</option><option>Bank transfer</option><option>Cheque</option><option>Card</option></select>
        <input name="note" placeholder="Note (e.g. invoice number)" class="{{ $inp }}">
        <button type="submit" class="w-full py-3 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold transition">Save payment</button>
    </form>
</div>
@endsection
