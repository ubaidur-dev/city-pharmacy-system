@extends('layouts.app')

@section('title', 'Reports - City Pharmacy')

@section('content')
@php
    $net = $revenue - $refunds;
    $margin = $coveredRevenue > 0 ? ($coveredRevenue - $cogs) : null;
    $q = http_build_query(['from' => $from->toDateString(), 'to' => $to->toDateString()]);
    $tile = 'bg-white border border-slate-200 rounded-2xl p-6 shadow-sm';
@endphp

<div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-4 no-print">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">Reports</h1>
        <p class="text-base text-slate-500 mt-2">{{ $from->format('d M Y') }} to {{ $to->format('d M Y') }}</p>
    </div>
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div><label class="block text-xs font-semibold text-slate-500 mb-1">From</label><input type="date" name="from" value="{{ $from->toDateString() }}" class="px-4 py-2.5 border border-slate-300 rounded-xl bg-white"></div>
        <div><label class="block text-xs font-semibold text-slate-500 mb-1">To</label><input type="date" name="to" value="{{ $to->toDateString() }}" class="px-4 py-2.5 border border-slate-300 rounded-xl bg-white"></div>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold">Apply</button>
        <button type="button" onclick="window.print()" class="dropdown-btn px-5 py-2.5 rounded-xl border border-slate-300 bg-white font-bold text-slate-700 hover:bg-slate-50">Print / Save PDF</button>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="{{ $tile }}"><p class="text-sm font-semibold text-slate-500">Net revenue</p><p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $cur }} {{ number_format($net, 2) }}</p><p class="mt-2 text-sm text-slate-500">{{ $invoiceCount }} invoices · {{ $cur }} {{ number_format($refunds, 2) }} refunded</p></div>
    <div class="{{ $tile }}"><p class="text-sm font-semibold text-slate-500">Gross profit (est.)</p><p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $margin === null ? 'n/a' : $cur . ' ' . number_format($margin, 2) }}</p><p class="mt-2 text-sm text-slate-500">@if($margin !== null){{ round($margin / $coveredRevenue * 100, 1) }}% margin on {{ round($coveredRevenue / max(1, $revenue) * 100) }}% of sales with known cost @else Receive stock via Purchasing so costs are recorded @endif</p></div>
    <div class="{{ $tile }}"><p class="text-sm font-semibold text-slate-500">Discounts given · Tax collected</p><p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $cur }} {{ number_format($discounts, 2) }}</p><p class="mt-2 text-sm text-slate-500">Tax {{ $cur }} {{ number_format($tax, 2) }}</p></div>
    <div class="{{ $tile }}"><p class="text-sm font-semibold text-slate-500">Expired stock on shelf</p><p class="mt-2 text-3xl font-extrabold {{ $expiredUnits ? 'text-rose-600' : 'text-slate-900' }}">{{ number_format($expiredUnits) }} units</p><p class="mt-2 text-sm text-slate-500">≈ {{ $cur }} {{ number_format($expiredValue, 2) }} at cost · shelf value {{ $cur }} {{ number_format($retailValue, 0) }}</p></div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Top products by revenue</h3></div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider"><tr><th class="px-6 py-3">Product</th><th class="px-6 py-3 text-right">Units</th><th class="px-6 py-3 text-right">Revenue</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($products as $name => $p)
                <tr><td class="px-6 py-3.5 font-semibold text-slate-800">{{ $name }}</td><td class="px-6 py-3.5 text-right">{{ $p['qty'] }}</td><td class="px-6 py-3.5 text-right font-bold">{{ $cur }} {{ number_format($p['revenue'], 2) }}</td></tr>
            @empty<tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">No sales in this period.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">By payment method</h3></div>
        @forelse($payments as $method => $amt)
            <div class="flex items-center justify-between px-6 py-3.5 border-b border-slate-100"><span class="capitalize font-semibold text-slate-700">{{ $method }}</span><span class="font-bold">{{ $cur }} {{ number_format($amt, 2) }}</span></div>
        @empty<p class="px-6 py-8 text-slate-500 text-sm">No sales in this period.</p>@endforelse
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Daily sales</h3></div>
    <div class="max-h-96 overflow-y-auto">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider"><tr><th class="px-6 py-3">Date</th><th class="px-6 py-3 text-right">Invoices</th><th class="px-6 py-3 text-right">Revenue</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
        @forelse($daily as $day => $d)
            <tr><td class="px-6 py-3.5 font-semibold text-slate-800">{{ \Carbon\Carbon::parse($day)->format('D, d M Y') }}</td><td class="px-6 py-3.5 text-right">{{ $d['count'] }}</td><td class="px-6 py-3.5 text-right font-bold">{{ $cur }} {{ number_format($d['total'], 2) }}</td></tr>
        @empty<tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">No sales in this period.</td></tr>@endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 no-print">
    <h3 class="font-bold text-slate-900 text-lg mb-1">Export to Excel / CSV</h3>
    <p class="text-sm text-slate-500 mb-4">Opens directly in Excel, Google Sheets and accounting software.</p>
    <div class="flex flex-wrap gap-3">
        <a href="/reports/export/sales?{{ $q }}" class="px-5 py-2.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold">Sales (selected dates)</a>
        <a href="/reports/export/inventory" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white font-bold text-slate-700 hover:bg-slate-50">Inventory</a>
        <a href="/reports/export/expiry" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white font-bold text-slate-700 hover:bg-slate-50">Batches &amp; expiry</a>
    </div>
</div>
@endsection
