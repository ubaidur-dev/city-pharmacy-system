@extends('layouts.app')

@section('title', 'Pharmacy Billing & POS - City Pharmacy Store')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Pharmacy Billing & POS Terminal</h1>
        <p class="text-base text-slate-500 mt-2">Create patient invoices, manage corporate hospital credit slips, and track counter sales in real-time.</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Revenue</p>
                <h3 id="kpi-total-revenue" class="text-3xl font-extrabold text-slate-900 mt-1.5">
                    {{ $cur }} {{ number_format($orders->sum('total_amount'), 2) }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-navy-50 text-navy-600 shrink-0 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Cash Collected</p>
                <h3 id="kpi-cash-collected" class="text-3xl font-extrabold text-slate-900 mt-1.5">
                    {{ $cur }} {{ number_format($orders->where('payment_method', 'cash')->sum('total_amount'), 2) }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 shrink-0 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Digital / Card</p>
                <h3 id="kpi-digital-collected" class="text-3xl font-extrabold text-slate-900 mt-1.5">
                    {{ $cur }} {{ number_format($orders->whereIn('payment_method', ['card', 'digital', 'insurance'])->sum('total_amount'), 2) }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 shrink-0 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Today Invoices</p>
                <h3 id="kpi-invoice-count" class="text-3xl font-extrabold text-slate-900 mt-1.5">
                    {{ $orders->where('created_at', '>=', now()->startOfDay())->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 shrink-0 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
                <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <div class="flex items-center space-x-3 mb-5 border-b border-slate-100 pb-4">
                    <div class="p-2 bg-navy-50 text-navy-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">1. Select Billing Channel</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <button type="button" id="btn-customer" onclick="setBillingType('customer')" class="py-3 px-4 rounded-xl border border-slate-300 bg-slate-50 text-slate-700 font-semibold text-base transition flex items-center justify-start space-x-2.5 hover:border-navy-300 hover:bg-navy-50/40 shadow-sm">
                        <svg class="w-5 h-5 text-slate-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Retail Patient</span>
                    </button>
                    <button type="button" id="btn-corporate" onclick="setBillingType('corporate')" class="py-3 px-4 rounded-xl border border-slate-300 bg-slate-50 text-slate-700 font-semibold text-base transition flex items-center justify-start space-x-2.5 hover:border-navy-300 hover:bg-navy-50/40 shadow-sm">
                        <svg class="w-5 h-5 text-slate-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Corporate B2B</span>
                    </button>
                    <button type="button" id="btn-clinic" onclick="setBillingType('clinic')" class="py-3 px-4 rounded-xl border border-slate-300 bg-slate-50 text-slate-700 font-semibold text-base transition flex items-center justify-start space-x-2.5 hover:border-navy-300 hover:bg-navy-50/40 shadow-sm">
                        <svg class="w-5 h-5 text-slate-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Clinic Ward</span>
                    </button>
                </div>
                
                <div class="mt-6 space-y-4">
                    <div id="customer-fields" class="billing-fields hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label id="client-label" class="block text-sm font-semibold text-slate-700 mb-2">Patient / Customer Name</label>
                                <input type="text" id="customer_name" placeholder="Enter customer full name..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Patient Phone Number</label>
                                <input type="text" id="customer_phone" placeholder="e.g. 0300-1234567..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                        </div>
                    </div>

                    <div id="corporate-fields" class="billing-fields hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Company / Corporate Name</label>
                                <input type="text" id="corporate_name" placeholder="Enter company name..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Corporate Contact Person</label>
                                <input type="text" id="corporate_contact" placeholder="Enter contact person name..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Corporate Phone</label>
                                <input type="text" id="corporate_phone" placeholder="e.g. 021-1234567..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Corporate Reference / PO</label>
                                <input type="text" id="corporate_reference" placeholder="Optional: PO / billing ref..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                        </div>
                    </div>

                    <div id="clinic-fields" class="billing-fields hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Clinic / Ward Name</label>
                                <input type="text" id="clinic_name" placeholder="Enter clinic or ward name..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Clinic Contact Person</label>
                                <input type="text" id="clinic_contact" placeholder="Enter doctor / manager name..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Clinic Phone</label>
                                <input type="text" id="clinic_phone" placeholder="e.g. 0300-1234567..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Ward / Department</label>
                                <input type="text" id="clinic_reference" placeholder="Enter ward or department..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <div class="flex items-center space-x-3 mb-5 border-b border-slate-100 pb-4">
                    <div class="p-2 bg-navy-50 text-navy-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">2. Add Medicines To Cart</h3>
                </div>
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Scan barcode <span class="text-slate-400 font-medium">(or type exact name + Enter)</span></label>
                    <input type="text" id="scan_input" autocomplete="off" placeholder="Ready to scan…  F2 to focus" class="w-full px-4 py-3.5 bg-navy-50/60 border-2 border-navy-200 rounded-xl text-lg font-semibold text-slate-900 focus:outline-none focus:border-navy-500 transition placeholder-slate-400">
                    <p class="mt-2 text-xs text-slate-500"><b>F2</b> scan · <b>F4</b> confirm order · <b>F8</b> hold bill · <b>F9</b> held bills</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 relative">
                    <div class="sm:col-span-7 relative">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Search Medicine Item</label>
                        <div class="relative">
                            <input type="text" id="med_search_input" onfocus="openMedDropdown()" oninput="filterMedList()" placeholder="Type to search stock..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                        </div>
                        <div id="med_dropdown_menu" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 max-h-64 overflow-y-auto divide-y divide-slate-100">
                            <div id="med_list_container">
                                @foreach($medicines as $med)
                                    <div data-name="{{ strtolower($med->name) }}" onclick="selectStockMedicine({{ $med->id }}, '{{ addslashes($med->name) }}', {{ $med->price }}, {{ $med->stock ?? 0 }})" class="p-3 hover:bg-navy-50 cursor-pointer flex justify-between items-center transition">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $med->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $med->category }} | Stock: <span class="font-semibold {{ ($med->stock ?? 0) < 10 ? 'text-rose-600' : 'text-navy-600' }}">{{ $med->stock ?? 0 }}</span></p>
                                        </div>
                                        <span class="font-extrabold text-navy-700 text-sm">{{ $cur }} {{ number_format($med->price, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Price ({{ $cur }})</label>
                        <input type="number" id="selected_price" placeholder="0.00" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Qty</label>
                        <input type="number" id="selected_qty" value="1" min="1" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="button" onclick="addMedicineItem()" class="w-full sm:w-auto px-7 py-3 bg-navy-600 hover:bg-navy-700 text-white font-bold text-base rounded-xl transition shadow-md flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-white stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Item To Order</span>
                    </button>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg">Active Order Cart</h3>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="holdCurrentBill()" class="dropdown-btn px-3.5 py-1.5 rounded-lg text-sm font-bold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition">Hold</button>
                        <button type="button" onclick="openHeldModal()" class="dropdown-btn px-3.5 py-1.5 rounded-lg text-sm font-bold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition">Held <span id="held-count" class="hidden ml-1 px-1.5 rounded-full bg-navy-600 text-white text-xs">0</span></button>
                        <span id="cart-item-count" class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-bold bg-navy-50 text-navy-800 border border-navy-200">0 items</span>
                    </div>
                </div>
                <div id="safety-banner" class="hidden px-6 py-4 space-y-2 border-b border-slate-100"></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-4 px-4">S.No</th>
                                <th class="py-4 px-8">Item</th>
                                <th class="py-4 px-8">Unit Price</th>
                                <th class="py-4 px-8">Qty</th>
                                <th class="py-4 px-8">Total</th>
                                <th class="py-4 px-8 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items-list" class="divide-y divide-slate-100 text-base text-slate-700">
                            <tr id="empty-cart-row">
                                <td colspan="6" class="py-12 text-center text-slate-400 font-medium text-base">Cart is empty. Choose or type a medicine above to start building the invoice.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: SUMMARY & PAYMENT --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">
                
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-navy-50 text-navy-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">3. Order Summary & Payment</h3>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Payment Channel</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" id="pay-cash" onclick="setPaymentMethod('cash')" class="py-3 px-3 rounded-xl border border-slate-300 bg-slate-50 text-slate-700 text-sm font-bold flex items-center justify-center space-x-2 transition hover:border-navy-300 hover:bg-navy-50/40 shadow-sm">
                            <svg class="w-5 h-5 text-slate-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="10" rx="2"/>
                                <circle cx="12" cy="12" r="2"/>
                                <path d="M7 12h.01M17 12h.01" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Cash Payment</span>
                        </button>
                        <button type="button" id="pay-card" onclick="setPaymentMethod('card')" class="py-3 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm font-semibold flex items-center justify-center space-x-2 transition hover:bg-slate-100 shadow-sm">
                            <svg class="w-5 h-5 text-sky-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Debit / Card</span>
                        </button>
                        <button type="button" id="pay-insurance" onclick="setPaymentMethod('insurance')" class="py-3 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm font-semibold flex items-center justify-center space-x-2 transition hover:bg-slate-100 shadow-sm">
                            <svg class="w-5 h-5 text-violet-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 4v5c0 5-4 9-7 11-3-2-7-6-7-11V6l7-4z"/></svg>
                            <span>Insurance</span>
                        </button>
                        <button type="button" id="pay-digital" onclick="setPaymentMethod('digital')" class="py-3 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm font-semibold flex items-center justify-center space-x-2 transition hover:bg-slate-100 shadow-sm">
                            <svg class="w-5 h-5 text-purple-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6"/><path d="M7 8h10" stroke-linecap="round" stroke-linejoin="round"/><circle cx="18" cy="16" r="1"/></svg>
                            <span>Online Wallet</span>
                        </button>
                    </div>
                </div>
                <div id="cash-tender-box" class="bg-slate-50 p-5 rounded-xl border border-slate-200 space-y-3 hidden">
                    <label class="block text-sm font-semibold text-slate-700">Cash Received From Patient</label>
                    <input type="number" id="cash_tendered" oninput="calculateTotals()" placeholder="Enter amount received..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">                    
                    <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                        <span class="text-sm font-bold text-slate-600 uppercase tracking-wider">Change Due:</span>
                        <span id="change_due_display" class="font-extrabold text-navy-700 text-xl">{{ $cur }} 0.00</span>
                    </div>
                </div>

                <!-- Card details are collected externally via the POS terminal; no manual card inputs here -->

                <div id="insurance-payment-fields" class="hidden bg-slate-50 p-5 rounded-xl border border-slate-200 space-y-3">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Insurance Provider</label>
                            <input type="text" id="insurance_provider" placeholder="e.g. EFU / Adamjee / Jubilee" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Policy / Claim Number</label>
                            <input type="text" id="insurance_policy_number" placeholder="Enter policy or claim number..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Claim Reference</label>
                            <input type="text" id="insurance_claim_no" placeholder="Optional claim reference..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                        </div>
                    </div>
                </div>

                <!-- Online wallet details handled outside the cashier flow; no manual wallet inputs here -->
                <div class="space-y-3 text-base bg-slate-50 p-5 rounded-xl border border-slate-200 font-semibold text-slate-700">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span id="summary-subtotal" class="font-bold text-slate-900">{{ $cur }} 0.00</span>
                    </div>
                    <div class="flex justify-between text-rose-600">
                        <span class="flex items-center gap-2">Discount
                            <input type="number" id="discount_percent" min="0" max="100" step="0.1" value="0" oninput="calculateTotals()" class="w-20 px-2 py-1 border border-slate-300 rounded-lg text-sm text-slate-800 text-right"> %
                        </span>
                        <span id="summary-discount" class="font-bold">-{{ $cur }} 0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax ({{ rtrim(rtrim(number_format($store->tax_rate, 2), '0'), '.') }}%):</span>
                        <span id="summary-tax" class="font-bold">+{{ $cur }} 0.00</span>
                    </div>
                    <div id="loyalty-row" class="hidden flex justify-between text-navy-700">
                        <span class="flex items-center gap-2">Redeem points <span class="text-xs text-slate-500">(<span id="points-available">0</span> available)</span>
                            <input type="number" id="redeem_points" min="0" value="0" oninput="calculateTotals()" class="w-20 px-2 py-1 border border-slate-300 rounded-lg text-sm text-slate-800 text-right">
                        </span>
                        <span id="summary-redeem" class="font-bold">-{{ $cur }} 0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-900 font-extrabold text-lg pt-3 border-t border-slate-200">
                        <span>Grand Total:</span>
                        <span id="grand-total-display" class="text-navy-700 font-extrabold">{{ $cur }} 0.00</span>
                    </div>
                </div>

                    <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" onclick="clearCart()" class="w-1/2 py-3.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-base font-bold rounded-xl transition flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Cancel</span>
                    </button>
                    <button type="button" onclick="openInvoiceModal()" class="w-1/2 py-3.5 bg-navy-600 hover:bg-navy-700 text-white text-base font-bold rounded-xl transition shadow-md shadow-navy-600/20 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-white shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Confirm Order</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-12">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Payment History Ledger</h2>
                <p id="ledger-count-badge" class="text-sm font-semibold text-slate-500 mt-1">{{ $orders->count() }} Verified Payment(s) Recorded</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="ledger-search" onkeyup="filterLedgerTable()" placeholder="Search invoice or patient..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-navy-500 transition shadow-sm placeholder-slate-400">
                </div>      
                <button type="button" onclick="printPaymentHistory()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-bold rounded-xl border border-slate-300 shadow-sm transition flex items-center justify-center space-x-1.5 shrink-0">
                <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print</span>
            </button>
            <button type="button" onclick="downloadPaymentHistoryCSV()" class="px-5 py-2.5 bg-navy-600 hover:bg-navy-700 text-white text-sm font-bold rounded-xl shadow-sm transition flex items-center justify-center space-x-1.5 shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Export CSV</span>
            </button>
            </div>
        </div>       
        <div class="overflow-x-auto">
    <table id="payment-history-table" class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                <th class="py-4 px-8">Invoice ID</th>
                <th class="py-4 px-8">Date & Time</th>
                <th class="py-4 px-8">Patient / Entity</th>
                <th class="py-4 px-8">Phone Number</th>
                <th class="py-4 px-8">Channel</th>
                <th class="py-4 px-8">Items</th>
                <th class="py-4 px-8">Total Paid</th>
                <th class="py-4 px-8 text-center">Action</th>
            </tr>
        </thead>
        <tbody id="sales-ledger-body" class="divide-y divide-slate-100 text-base text-slate-700">
            @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition" 
                    id="order-row-{{ $order->id }}"
                    data-invoice="{{ $order->invoice_number }}"
                    data-date="{{ $order->created_at->format('d M Y, h:i A') }}"
                    data-patient="{{ $order->patient_name }}"
                    data-phone="{{ $order->phone ?? 'No phone' }}"
                    data-channel="{{ $order->channel ?? 'Customer' }}"
                    data-items="{{ $order->total_items }}"
                    data-subtotal="{{ $order->subtotal ?? 0 }}"
                    data-discount="{{ $order->discount ?? 0 }}"
                    data-tax="{{ $order->tax ?? 0 }}"
                    data-total="{{ $order->total_amount }}">
                    
                    <td class="py-4 px-8 font-black text-navy-700">{{ $order->invoice_number }}</td>
                    <td class="py-4 px-8 font-semibold text-slate-600 text-sm">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td class="py-4 px-8 font-black text-slate-900">{{ $order->patient_name }}</td>
                    <td class="py-4 px-8 font-semibold text-slate-600">{{ $order->phone ?? 'No phone' }}</td>
                    <td class="py-4 px-8 font-bold text-slate-700 uppercase text-xs">{{ $order->channel ?? 'Customer' }}</td>
                    <td class="py-4 px-8 font-bold text-slate-800">{{ $order->total_items }} unit(s)</td>
                    <td class="py-4 px-8 font-black text-slate-900">{{ $cur }} {{ number_format($order->total_amount, 2) }}</td>
                    <td class="py-4 px-8 text-center">
                        <button type="button" onclick="reprintOrderInvoice({{ $order->id }})" class="px-3.5 py-2 bg-navy-50 hover:bg-navy-100 text-navy-700 text-xs font-black rounded-xl transition inline-flex items-center justify-center space-x-1.5 border border-navy-200 shadow-xs">
                            <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print</span>
                        </button>
                        <button type="button" onclick="openReturnModal({{ $order->id }})" class="dropdown-btn ml-2 px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-black rounded-xl transition">Return</button>
                        @if($order->returns->count())
                            <p class="mt-1.5 text-[11px] font-bold text-rose-600">Refunded {{ $cur }} {{ number_format($order->returns->sum('refund_amount'), 2) }}</p>
                        @endif
                    </td>
                </tr>
            @empty
                <tr id="empty-ledger-row">
                    <td colspan="8" class="py-12 text-center text-slate-400 font-medium text-base">No verified payment records logged today. Completed sales will automatically appear here.</td>
                </tr>
            @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div id="invoice-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-5" style="font-family: 'Inter', sans-serif;">            
            <div class="border-b border-slate-200 pb-3 space-y-2">
                <div class="flex items-center justify-between">
                    <img src="{{ asset('images/cityy_logo.png') }}" alt="City Pharmacy Logo" class="h-10 w-auto object-contain">
                </div>        
                <div class="flex justify-start items-center pt-1">
                    <p id="invoice-subtitle" class="text-xs text-slate-500 font-bold tracking-tight text-left">Verified Counter Cash Receipt</p>
                    <span id="invoice-modal-title" class="hidden"></span>
                </div>
            </div>
            <div id="printable-slip-area" class="space-y-4">
                <div class="flex justify-between text-xs text-slate-800 bg-slate-50 p-3.5 rounded-xl border border-slate-200 font-bold">
                    <div>
                        <p class="text-slate-400 uppercase tracking-wider text-[10px]">PATIENT / CLIENT:</p>
                        <p id="slip-client-name" class="text-slate-900 text-sm font-black mt-0.5">--</p>
                        <p id="slip-client-phone" class="text-slate-600 font-semibold mt-0.5">Phone: --</p>
                        <p id="slip-payment-method" class="text-navy-700 font-black mt-1 text-[11px]">Gateway: CASH</p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-400 uppercase tracking-wider text-[10px]">INVOICE NO:</p>
                        <p id="slip-invoice-id" class="text-slate-900 font-black mt-0.5 text-xs">#INV-2026-001</p>
                        <p id="slip-date" class="text-slate-500 font-bold mt-1 text-[11px]">--</p>
                    </div>
                </div>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-extrabold border-b border-slate-200 text-[11px] uppercase tracking-wider">
                                <th class="py-2.5 px-3">Item</th>
                                <th class="py-2.5 px-2 text-center">Qty</th>
                                <th class="py-2.5 px-2">Price</th>
                                <th class="py-2.5 px-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="slip-items-list" class="divide-y divide-slate-100 font-semibold text-slate-800">
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end pt-1">
                    <div class="w-full sm:w-72 space-y-1.5 text-xs bg-slate-50 p-3.5 rounded-xl border border-slate-200 font-bold">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span id="slip-subtotal" class="font-extrabold text-slate-900">{{ $cur }} 0.00</span>
                        </div>
                        <div class="flex justify-between text-rose-600">
                            <span>Discount:</span>
                            <span id="slip-discount" class="font-extrabold">-{{ $cur }} 0.00</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Tax ({{ rtrim(rtrim(number_format($store->tax_rate, 2), '0'), '.') }}%):</span>
                            <span id="slip-tax" class="font-extrabold">+{{ $cur }} 0.00</span>
                        </div>
                        <div class="flex justify-between text-slate-900 font-black text-sm pt-2 border-t border-slate-200">
                            <span>Grand Total:</span>
                            <span id="slip-grand-total" class="text-navy-700 font-black text-base">{{ $cur }} 0.00</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-center pt-3 border-t border-dashed border-slate-200 text-xs text-slate-400 font-bold space-y-0.5">
                    <p class="text-slate-800 font-extrabold text-xs">Thank you for trusting City Pharmacy!</p>
                    <p class="text-[11px] text-slate-400">Official Patient Receipt • Please retain for your records</p>
                </div>
            </div>
            
            <div class="pt-3 border-t border-slate-200 flex items-center justify-between gap-4">
                <span class="text-sm text-slate-400 font-semibold">Ready for print</span>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="printCurrentSlip()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-bold rounded-xl transition border border-slate-300 shadow-sm">
                        Print Slip
                    </button>
                    <button type="button" onclick="confirmPaymentAndSave()" id="btn-confirm-order" class="px-6 py-2.5 bg-navy-600 hover:bg-navy-700 text-white text-sm font-bold rounded-xl transition shadow-md flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-white shrink-0 stroke-[2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Confirm & Save</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    

    <div id="held-modal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-extrabold text-slate-900 text-xl">Held bills</h3>
                <button type="button" onclick="document.getElementById('held-modal').classList.add('hidden')" class="dropdown-btn text-slate-400 hover:text-slate-700 text-2xl leading-none bg-transparent">&times;</button>
            </div>
            <div id="held-list" class="max-h-[50vh] overflow-y-auto"></div>
        </div>
    </div>

    <div id="return-modal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-8">
            <div class="flex items-center justify-between mb-4">
                <h3 id="return-title" class="font-extrabold text-slate-900 text-xl">Return items</h3>
                <button type="button" onclick="document.getElementById('return-modal').classList.add('hidden')" class="dropdown-btn text-slate-400 hover:text-slate-700 text-2xl leading-none bg-transparent">&times;</button>
            </div>
            <div id="return-lines" class="max-h-[40vh] overflow-y-auto"></div>
            <label class="block text-sm font-semibold text-slate-700 mt-5 mb-1.5">Reason</label>
            <select id="return-reason" class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white">
                <option value="">Select a reason…</option>
                <option>Wrong item dispensed</option><option>Prescription changed</option><option>Damaged / defective</option><option>Customer changed mind</option><option>Other</option>
            </select>
            <div class="mt-5 flex items-center justify-between">
                <div><p class="text-xs font-semibold text-slate-500 uppercase">Refund</p><p id="return-refund" class="text-2xl font-extrabold text-navy-700">{{ $cur }} 0.00</p></div>
                <button type="button" onclick="submitReturn()" class="dropdown-btn px-7 py-3.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white font-bold shadow-md">Confirm return</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    window.stockDatabase = @json($medicines ?? []);
    window.initialOrders = @json($orders ?? []);
</script>
<script src="{{ asset('js/sales-billing.js') }}"></script>
<script src="{{ asset('js/pos-extras.js') }}"></script>
@endpush