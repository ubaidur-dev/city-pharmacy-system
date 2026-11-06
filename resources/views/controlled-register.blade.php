@extends('layouts.app')

@section('title', 'Controlled Drug Register')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 no-print">
    <div><h1 class="text-3xl font-extrabold text-slate-900">Controlled Drug Register</h1><p class="text-base text-slate-500 mt-2">Every controlled-drug sale with customer, prescriber and prescription number. Mark a medicine as controlled in Inventory.</p></div>
    <div class="flex gap-3"><button type="button" onclick="window.print()" class="dropdown-btn px-5 py-3 rounded-xl border border-slate-300 bg-white font-bold text-slate-700">Print</button><a href="/compliance/controlled?export=1" class="px-5 py-3 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold">Export CSV</a></div>
</div>
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-x-auto">
<table class="w-full text-left text-sm">
    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider"><tr><th class="px-5 py-3">Date</th><th class="px-5 py-3">Invoice</th><th class="px-5 py-3">Medicine</th><th class="px-5 py-3 text-right">Qty</th><th class="px-5 py-3">Batch</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Prescriber</th><th class="px-5 py-3">Rx no.</th></tr></thead>
    <tbody class="divide-y divide-slate-100">
    @forelse($rows as $r)
        <tr><td class="px-5 py-3.5 whitespace-nowrap">{{ $r['date']->format('d M Y H:i') }}</td><td class="px-5 py-3.5 font-bold text-navy-700">{{ $r['invoice'] }}</td><td class="px-5 py-3.5 font-semibold">{{ $r['medicine'] }}</td><td class="px-5 py-3.5 text-right">{{ $r['qty'] }}</td><td class="px-5 py-3.5">{{ $r['batches'] }}</td><td class="px-5 py-3.5">{{ $r['customer'] }}<br><span class="text-xs text-slate-500">{{ $r['phone'] }}</span></td><td class="px-5 py-3.5">{{ $r['prescriber'] }}</td><td class="px-5 py-3.5">{{ $r['rx'] }}</td></tr>
    @empty
        <tr><td colspan="8" class="px-5 py-10 text-center text-slate-500">No controlled-drug sales recorded yet.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
