@extends('layouts.app')

@section('title', 'Purchasing - City Pharmacy')

@section('content')
@php $inp = 'w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none'; @endphp

<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">Purchasing</h1>
        <p class="text-base text-slate-500 mt-2">Order from suppliers, receive stock into batches, and track what you owe.</p>
    </div>
    <div class="flex gap-3">
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-3 shadow-sm"><p class="text-xs font-semibold text-slate-500 uppercase">You owe suppliers</p><p class="text-2xl font-extrabold {{ $totalOwed > 0 ? 'text-rose-600' : 'text-slate-900' }}">{{ $cur }} {{ number_format($totalOwed, 2) }}</p></div>
        <a href="/purchasing/orders/create" class="self-center px-6 py-3.5 rounded-2xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md transition">+ New Purchase Order</a>
    </div>
</div>

@if(session('success'))<div class="theme-success-msg mb-6 px-5 py-4 rounded-2xl border font-semibold">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-6 px-5 py-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 font-semibold">{{ $errors->first() }}</div>@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Recent purchase orders</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider"><tr><th class="px-6 py-3">PO</th><th class="px-6 py-3">Supplier</th><th class="px-6 py-3">Status</th><th class="px-6 py-3 text-right">Value</th><th class="px-6 py-3 text-right">Date</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($orders as $o)
                    <tr class="hover:bg-slate-50 cursor-pointer" onclick="location='/purchasing/orders/{{ $o->id }}'">
                        <td class="px-6 py-4 font-bold text-navy-700">{{ $o->po_number }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $o->supplier->name }}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold {{ ['ordered' => 'bg-amber-100 text-amber-700', 'received' => 'bg-navy-50 text-navy-700', 'cancelled' => 'bg-slate-100 text-slate-500'][$o->status] }}">{{ ucfirst($o->status) }}</span></td>
                        <td class="px-6 py-4 text-right font-bold text-slate-900">{{ $cur }} {{ number_format($o->status === 'received' ? $o->total_cost : $o->orderedTotal(), 2) }}</td>
                        <td class="px-6 py-4 text-right text-slate-500">{{ $o->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">No purchase orders yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <form method="POST" action="/purchasing/suppliers" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-3">
            @csrf
            <h3 class="font-bold text-slate-900 text-lg">Add supplier</h3>
            <input name="name" placeholder="Supplier / distributor name" required class="{{ $inp }}">
            <input name="contact_person" placeholder="Contact person" class="{{ $inp }}">
            <div class="grid grid-cols-2 gap-3"><input name="phone" placeholder="Phone" class="{{ $inp }}"><input type="email" name="email" placeholder="Email" class="{{ $inp }}"></div>
            <button type="submit" class="w-full py-3 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold transition">Save supplier</button>
        </form>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Supplier balances</h3></div>
            <div class="max-h-96 overflow-y-auto divide-y divide-slate-100">
                @foreach($suppliers->sortByDesc('owed') as $s)
                    <a href="/purchasing/suppliers/{{ $s->id }}" class="flex items-center justify-between px-6 py-3.5 hover:bg-slate-50 transition">
                        <span class="font-semibold text-slate-800 truncate">{{ $s->name }}</span>
                        <span class="text-sm font-bold {{ $s->owed > 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ $s->owed > 0 ? $cur . ' ' . number_format($s->owed, 2) : 'Settled' }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
