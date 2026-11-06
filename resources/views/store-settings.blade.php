@extends('layouts.app')

@section('title', 'Store Settings')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-3xl font-extrabold text-slate-900">Store Settings</h1>
    <p class="text-base text-slate-500 mt-2 mb-8">These apply across billing, reports and alerts.</p>

    @if(session('success'))
        <div class="theme-success-msg mb-6 px-5 py-4 rounded-2xl border font-semibold">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 font-semibold">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/admin/store" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Store name</label>
            <input name="store_name" value="{{ old('store_name', $settings->store_name) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Currency symbol</label>
                <input name="currency" value="{{ old('currency', $settings->currency) }}" maxlength="10" required placeholder="$, €, £, Rs., AED…" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sales tax / VAT (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="tax_rate" value="{{ old('tax_rate', $settings->tax_rate) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Low-stock threshold (units)</label>
                <input type="number" min="0" name="low_stock_threshold" value="{{ old('low_stock_threshold', $settings->low_stock_threshold) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Warn before expiry (days)</label>
                <input type="number" min="1" max="730" name="expiry_warning_days" value="{{ old('expiry_warning_days', $settings->expiry_warning_days) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
            </div>
        </div>
        <div class="pt-4 border-t border-slate-100">
            <h3 class="font-bold text-slate-900">Loyalty points</h3>
            <p class="text-sm text-slate-500 mb-3">Customers with a phone number earn points on every sale. Set "spend per point" to 0 to turn loyalty off.</p>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Spend per 1 point ({{ $cur }})</label>
                    <input type="number" step="0.01" min="0" name="loyalty_earn_per" value="{{ old('loyalty_earn_per', $settings->loyalty_earn_per) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Value of 1 point when redeemed ({{ $cur }})</label>
                    <input type="number" step="0.0001" min="0" name="loyalty_point_value" value="{{ old('loyalty_point_value', $settings->loyalty_point_value) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500 outline-none">
                </div>
            </div>
        </div>
        <button type="submit" class="px-8 py-3.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md transition">Save settings</button>
    </form>
</div>
@endsection
