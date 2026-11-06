@extends('layouts.app')

@section('title', 'Customers - City Pharmacy')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">{{ __("Customers") }}</h1>
        <p class="text-base text-slate-500 mt-2">Profiles are created automatically from billing. Add allergies once, and every future sale is checked.</p>
    </div>
    <form method="GET" class="w-full md:w-96"><input name="q" value="{{ $q }}" placeholder="Search name or phone…" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none"></form>
</div>

@if($refills->count())
<div class="bg-white border border-amber-200 rounded-2xl shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-amber-100 bg-amber-50/70"><h3 class="font-bold text-slate-900 text-lg">Refills due</h3><p class="text-sm text-slate-500">Based on how often each customer usually buys the same medicine. A reminder brings them back before they run out.</p></div>
    @foreach($refills as $r)
        @php
            $digits = preg_replace('/\D/', '', (string) $r['customer']->phone);
            $msg = rawurlencode("Hello {$r['customer']->name}, this is {$store->store_name}. Your {$r['medicine']} may be running low. We have it in stock whenever you need a refill.");
        @endphp
        <div class="flex items-center justify-between gap-3 px-6 py-3.5 border-b border-slate-100">
            <div><a href="/customers/{{ $r['customer']->id }}" class="font-bold text-navy-700 hover:underline">{{ $r['customer']->name }}</a> <span class="text-slate-600">· {{ $r['medicine'] }}</span>
                <p class="text-xs text-slate-500">Buys about every {{ $r['every'] }} days · {{ $r['late'] < 0 ? abs($r['late']) . ' day(s) overdue' : ($r['late'] === 0 ? 'due today' : 'due in ' . $r['late'] . ' day(s)') }}</p></div>
            @if($digits)<a target="_blank" rel="noopener" href="https://wa.me/{{ $digits }}?text={{ $msg }}" class="px-4 py-2 rounded-xl bg-navy-600 hover:bg-navy-700 text-white text-sm font-bold whitespace-nowrap">Send WhatsApp</a>@endif
        </div>
    @endforeach
</div>
@endif

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider"><tr><th class="px-6 py-3">{{ __("Customer") }}</th><th class="px-6 py-3">Phone</th><th class="px-6 py-3">Allergies</th><th class="px-6 py-3 text-right">Visits</th><th class="px-6 py-3 text-right">Total spent</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
        @forelse($customers as $c)
            <tr class="hover:bg-slate-50 cursor-pointer" onclick="location='/customers/{{ $c->id }}'">
                <td class="px-6 py-4 font-bold text-slate-900">{{ $c->name }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $c->phone }}</td>
                <td class="px-6 py-4">@if($c->allergies)<span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">{{ \Illuminate\Support\Str::limit($c->allergies, 30) }}</span>@else<span class="text-slate-400">None recorded</span>@endif</td>
                <td class="px-6 py-4 text-right">{{ $c->orders_count }}</td>
                <td class="px-6 py-4 text-right font-bold">{{ $cur }} {{ number_format($c->orders_sum_total_amount ?? 0, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">No customers yet. They appear after a billing with a phone number.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-4">{{ $customers->links() }}</div>
</div>
@endsection
