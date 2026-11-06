@extends('layouts.app')

@section('title', $customer->name)

@section('content')
@php $inp = 'w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none bg-white'; @endphp
<a href="/customers" class="text-sm font-bold text-navy-600 hover:underline">← Customers</a>
<div class="mt-3 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div><h1 class="text-3xl font-extrabold text-slate-900">{{ $customer->name }}</h1><p class="text-base text-slate-500 mt-2">{{ $customer->phone }} · customer since {{ $customer->created_at->format('M Y') }}</p></div>
    <div class="flex gap-3">
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-3 shadow-sm"><p class="text-xs font-semibold text-slate-500 uppercase">Visits</p><p class="text-2xl font-extrabold text-slate-900">{{ $orders->count() }}</p></div>
        @if($store->loyalty_earn_per > 0)<div class="bg-white border border-slate-200 rounded-2xl px-6 py-3 shadow-sm"><p class="text-xs font-semibold text-slate-500 uppercase">Loyalty points</p><p class="text-2xl font-extrabold text-slate-900">{{ number_format($customer->points) }}</p></div>@endif
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-3 shadow-sm"><p class="text-xs font-semibold text-slate-500 uppercase">Total spent</p><p class="text-2xl font-extrabold text-navy-700">{{ $cur }} {{ number_format($spent, 2) }}</p></div>
    </div>
</div>

@if(session('success'))<div class="theme-success-msg mb-6 px-5 py-4 rounded-2xl border font-semibold">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-6 px-5 py-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 font-semibold">{{ $errors->first() }}</div>@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Usually buys</h3></div>
            @forelse($usual as $u)
                <div class="flex items-center justify-between px-6 py-3.5 border-b border-slate-100"><span class="font-semibold text-slate-800">{{ $u['name'] }}</span><span class="text-sm text-slate-500">{{ $u['times'] }} visit(s) · last {{ \Carbon\Carbon::parse($u['last'])->format('d M Y') }}</span></div>
            @empty<p class="px-6 py-8 text-slate-500 text-sm">No purchases yet.</p>@endforelse
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100"><h3 class="font-bold text-slate-900 text-lg">Purchase history</h3></div>
            @foreach($orders as $o)
                <div class="px-6 py-4 border-b border-slate-100">
                    <div class="flex items-center justify-between"><span><b class="text-navy-700">{{ $o->invoice_number }}</b> <span class="text-sm text-slate-500">· {{ $o->created_at->format('d M Y, h:i A') }}</span></span><span class="font-bold">{{ $cur }} {{ number_format($o->total_amount, 2) }}</span></div>
                    <p class="text-sm text-slate-500 mt-1">{{ collect($o->cart_items)->map(fn ($l) => $l['qty'] . '× ' . $l['name'])->implode(', ') }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <form method="POST" action="/customers/{{ $customer->id }}" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 h-fit space-y-3">
        @csrf @method('PUT')
        <h3 class="font-bold text-slate-900 text-lg">Profile & safety</h3>
        <input name="name" value="{{ old('name', $customer->name) }}" required class="{{ $inp }}">
        <input name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="Phone" class="{{ $inp }}">
        <input type="email" name="email" value="{{ old('email', $customer->email) }}" placeholder="Email" class="{{ $inp }}">
        <div><label class="block text-xs font-semibold text-slate-500 mb-1">Date of birth</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth?->toDateString()) }}" class="{{ $inp }}"></div>
        <div><label class="block text-xs font-semibold text-red-600 mb-1">Allergies (comma separated, e.g. penicillin, aspirin, NSAIDs)</label><textarea name="allergies" rows="2" class="{{ $inp }}">{{ old('allergies', $customer->allergies) }}</textarea></div>
        <div><label class="block text-xs font-semibold text-slate-500 mb-1">Notes</label><textarea name="notes" rows="3" class="{{ $inp }}">{{ old('notes', $customer->notes) }}</textarea></div>
        <button type="submit" class="w-full py-3 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold transition">Save</button>
    </form>
</div>
@endsection
