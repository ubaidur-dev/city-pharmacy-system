@extends('layouts.app')

@section('title', 'Dashboard - City Pharmacy')

@section('content')
@php
    $max = max(1, $trend->max('total'));
    $hour = now()->hour;
    $greeting = __($hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening'));
@endphp

<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">{{ $greeting }}, {{ explode(' ', trim(auth()->user()->name))[0] }}</h1>
        <p class="text-base text-slate-500 mt-2">{{ __('Here is what is happening in your pharmacy today') }}, {{ now()->translatedFormat('l, d M Y') }}.</p>
    </div>
    <a href="/sales-billing" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        {{ __("New Sale") }}
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm font-semibold text-slate-500">{{ __("Today's Sales") }}</p>
        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $cur }} {{ number_format($todaySales, 2) }}</p>
        <p class="mt-2 text-sm font-semibold {{ ($salesChange ?? 0) >= 0 ? 'text-navy-600' : 'text-red-600' }}">
            @if($salesChange === null) No sales yesterday to compare
            @else {{ $salesChange >= 0 ? '▲' : '▼' }} {{ abs($salesChange) }}% vs yesterday @endif
        </p>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm font-semibold text-slate-500">{{ __("Invoices Today") }}</p>
        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $todayCount }}</p>
        <p class="mt-2 text-sm text-slate-500">Avg {{ $cur }} {{ number_format($todayCount ? $todaySales / $todayCount : 0, 2) }} per bill</p>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <p class="text-sm font-semibold text-slate-500">{{ __("Medicines in Catalog") }}</p>
        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($totalMedicines) }}</p>
        <p class="mt-2 text-sm text-slate-500">Stock value {{ $cur }} {{ number_format($stockValue, 2) }}</p>
    </div>
    <a href="/stock-alerts" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:border-navy-500 transition block">
        <p class="text-sm font-semibold text-slate-500">{{ __("Needs Attention") }}</p>
        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $outOfStock + $lowStock + $expired + $expiringSoon }}</p>
        <p class="mt-2 text-sm text-slate-500">{{ $outOfStock }} empty · {{ $lowStock }} low · {{ $expired }} expired · {{ $expiringSoon }} expiring</p>
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">
    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="font-bold text-slate-900 text-lg mb-6">{{ __("Sales, last 7 days") }}</h3>
        <div class="flex items-end gap-3 h-56">
            @foreach($trend as $day)
                <div class="flex-1 flex flex-col items-center justify-end h-full group">
                    <span class="text-xs font-semibold text-slate-500 mb-1 opacity-0 group-hover:opacity-100 transition">{{ number_format($day['total']) }}</span>
                    <div class="w-full rounded-t-xl {{ $loop->last ? 'bg-navy-600' : 'bg-navy-200' }} transition-all"
                         style="height: {{ max(3, round($day['total'] / $max * 100)) }}%"></div>
                    <span class="text-xs font-semibold text-slate-500 mt-2">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h3 class="font-bold text-slate-900 text-lg mb-5">{{ __("Top sellers (30 days)") }}</h3>
        @forelse($topSellers as $name => $qty)
            <div class="flex items-center justify-between py-3 {{ ! $loop->last ? 'border-b border-slate-100' : '' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-7 h-7 rounded-full bg-navy-50 text-navy-700 text-xs font-bold flex items-center justify-center">{{ $loop->iteration }}</span>
                    <span class="font-semibold text-slate-800 truncate">{{ $name }}</span>
                </div>
                <span class="text-sm font-bold text-navy-700">{{ $qty }} sold</span>
            </div>
        @empty
            <p class="text-slate-500 text-sm">{{ __("No sales recorded yet.") }}</p>
        @endforelse
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-slate-100">
        <h3 class="font-bold text-slate-900 text-lg">{{ __("Reorder suggestions") }}</h3>
        <p class="text-sm text-slate-500">Based on the last 30 days of sales. Shows items that will run out within 14 days.</p>
    </div>
    @forelse($reorder as $r)
        <a href="/medicines/{{ $r['id'] }}/batches" class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition {{ ! $loop->last ? 'border-b border-slate-100' : '' }}">
            <div>
                <p class="font-bold text-slate-900">{{ $r['name'] }}</p>
                <p class="text-sm text-slate-500">{{ $r['stock'] }} on hand · about {{ $r['days_left'] }} day(s) left</p>
            </div>
            <span class="px-4 py-1.5 rounded-full bg-navy-50 text-navy-700 text-sm font-bold">Order ~{{ $r['order_qty'] }}</span>
        </a>
    @empty
        <p class="px-6 py-8 text-slate-500 text-sm">Nothing urgent. Stock levels cover the next two weeks.</p>
    @endforelse
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-900 text-lg">{{ __("Recent invoices") }}</h3>
        <a href="/sales-billing" class="text-sm font-bold text-navy-600 hover:underline">{{ __("View all") }}</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                <tr><th class="px-6 py-3">{{ __("Invoice") }}</th><th class="px-6 py-3">{{ __("Customer") }}</th><th class="px-6 py-3">{{ __("Items") }}</th><th class="px-6 py-3">{{ __("Payment") }}</th><th class="px-6 py-3 text-right">{{ __("Total") }}</th><th class="px-6 py-3 text-right">{{ __("When") }}</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentOrders as $o)
                    <tr>
                        <td class="px-6 py-3.5 font-bold text-navy-700">{{ $o->invoice_number }}</td>
                        <td class="px-6 py-3.5 font-semibold text-slate-800">{{ $o->patient_name }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $o->total_items }}</td>
                        <td class="px-6 py-3.5 text-slate-600 capitalize">{{ $o->payment_method }}</td>
                        <td class="px-6 py-3.5 text-right font-bold text-slate-900">{{ $cur }} {{ number_format($o->total_amount, 2) }}</td>
                        <td class="px-6 py-3.5 text-right text-slate-500">{{ $o->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">No invoices yet. Create your first sale.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
