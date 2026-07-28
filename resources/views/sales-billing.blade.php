@extends('layouts.app')

@section('title', 'Pharmacy Billing & POS - City Pharmacy Store')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pharmacy Billing & POS Terminal</h1>
            <p class="text-base text-slate-500 mt-2 font-medium">Create patient invoices, manage corporate hospital credit slips, and track counter sales in real-time.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white border border-slate-200 rounded-2xl p-7 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-extrabold text-slate-400 uppercase tracking-wider">Total Revenue</p>
            <h3 id="kpi-total-revenue" class="text-3xl font-black text-slate-900 mt-1.5">Rs. 0.00</h3>
        </div>
        <div class="text-emerald-600 shrink-0 flex items-center justify-center">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-7 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-extrabold text-slate-400 uppercase tracking-wider">Cash Collected</p>
            <h3 id="kpi-cash-collected" class="text-3xl font-black text-slate-900 mt-1.5">Rs. 0.00</h3>
        </div>
        <div class="text-blue-600 shrink-0 flex items-center justify-center">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-7 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-extrabold text-slate-400 uppercase tracking-wider">Digital / Card</p>
            <h3 id="kpi-digital-collected" class="text-3xl font-black text-slate-900 mt-1.5">Rs. 0.00</h3>
        </div>
        <div class="text-purple-600 shrink-0 flex items-center justify-center">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-7 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-extrabold text-slate-400 uppercase tracking-wider">Today Invoices</p>
            <h3 id="kpi-invoice-count" class="text-3xl font-black text-slate-900 mt-1.5">0</h3>
        </div>
        <div class="text-amber-600 shrink-0 flex items-center justify-center">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
    </div>
</div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        <div class="lg:col-span-7 space-y-6">
           <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    <div class="flex items-center space-x-3 mb-5 border-b border-slate-100 pb-4">
        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <h3 class="text-xl font-extrabold text-slate-900">1. Select Billing Channel</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <button type="button" id="btn-customer" onclick="setBillingType('customer')" class="py-3.5 px-4 rounded-2xl border border-emerald-600 bg-emerald-50/80 text-emerald-950 font-black text-base sm:text-lg transition flex items-center justify-start space-x-2.5 shadow-xs hover:bg-emerald-100">
            <svg class="w-7 h-7 text-emerald-700 shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Retail Patient</span>
        </button>
        <button type="button" id="btn-corporate" onclick="setBillingType('corporate')" class="py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 font-extrabold text-base sm:text-lg transition flex items-center justify-start space-x-2.5 hover:border-slate-400 hover:bg-slate-100">
            <svg class="w-7 h-7 text-slate-600 shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>Corporate B2B</span>
        </button>
        <button type="button" id="btn-clinic" onclick="setBillingType('clinic')" class="py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 font-extrabold text-base sm:text-lg transition flex items-center justify-start space-x-2.5 hover:border-slate-400 hover:bg-slate-100">
            <svg class="w-7 h-7 text-slate-600 shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Clinic Ward</span>
        </button>
    </div>
    <div class="mt-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label id="client-label" class="block text-sm font-extrabold text-slate-800 mb-2">Patient / Customer Name</label>
                <input type="text" id="client_name" placeholder="Enter customer full name..." class="w-full px-4 py-3.5 bg-white border border-slate-300 rounded-xl text-base text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm placeholder:font-normal">
            </div>
            <div>
                <label class="block text-sm font-extrabold text-slate-800 mb-2">Patient Phone Number</label>
                <input type="text" id="client_phone" placeholder="e.g. 0300-1234567..." class="w-full px-4 py-3.5 bg-white border border-slate-300 rounded-xl text-base text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm placeholder:font-normal">
            </div>
        </div>
        <div id="corporate-fields" class="hidden">
            <label class="block text-sm font-extrabold text-slate-800 mb-2">Purchase Order / Reference No.</label>
            <input type="text" id="po_reference" placeholder="e.g. PO-9982-HOSP" class="w-full px-4 py-3.5 bg-white border border-slate-300 rounded-xl text-base text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm placeholder:font-normal">
        </div>
    </div>
</div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center space-x-3 mb-5 border-b border-slate-100 pb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-xl font-extrabold text-slate-900">2. Add Medicines To Cart</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 relative">
                    <div class="sm:col-span-7 relative">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Search Medicine Item</label>
                        <div class="relative">
                            <input type="text" id="med_search_input" onfocus="openMedDropdown()" oninput="filterMedList()" placeholder="Type to search stock (e.g. Panadol)..." class="w-full px-4 py-3.5 bg-white border border-slate-300 rounded-xl text-base text-slate-900 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm placeholder:font-normal">
                            <button type="button" onclick="toggleMedDropdown()" class="absolute right-3 top-4 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                        <div id="med_dropdown_menu" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 max-h-64 overflow-y-auto divide-y divide-slate-100">
                            <div id="med_list_container"></div>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Price (Rs.)</label>
                        <input type="number" id="selected_price" placeholder="0.00" class="w-full px-4 py-3.5 bg-white border border-slate-300 rounded-xl text-base text-slate-900 font-extrabold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Qty</label>
                        <input type="number" id="selected_qty" value="1" min="1" class="w-full px-4 py-3.5 bg-white border border-slate-300 rounded-xl text-base text-slate-900 font-extrabold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="button" onclick="addMedicineItem()" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-base rounded-xl transition shadow-md flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-white stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Item To Order</span>
                    </button>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-extrabold text-slate-900 text-lg">Active Order Cart</h3>
                    <span id="cart-item-count" class="inline-flex items-center px-4 py-1.5 rounded-xl text-sm font-black bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">0 item(s) added</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 text-xs font-extrabold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-4 px-6">Item</th>
                                <th class="py-4 px-6">Unit Price</th>
                                <th class="py-4 px-6">Qty</th>
                                <th class="py-4 px-6">Total</th>
                                <th class="py-4 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items-list" class="divide-y divide-slate-100 text-base text-slate-800 font-semibold">
                            <tr id="empty-cart-row">
                                <td colspan="5" class="py-12 text-center text-slate-400 font-medium text-base">Cart is empty. Choose or type a medicine above to start building the invoice.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <h3 class="text-xl font-extrabold text-slate-900">3. Order Summary & Payment</h3>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-3">Payment Channel</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" id="pay-cash" onclick="setPaymentMethod('cash')" class="py-3.5 px-4 rounded-2xl border border-emerald-600 bg-emerald-50 text-emerald-800 text-base sm:text-lg font-black flex items-center justify-center space-x-2.5 transition shadow-xs">
                            <svg class="w-7 h-7 text-emerald-600 shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Cash Payment</span>
                        </button>
                        <button type="button" id="pay-card" onclick="setPaymentMethod('card')" class="py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 text-base sm:text-lg font-extrabold flex items-center justify-center space-x-2.5 transition hover:border-slate-400 hover:bg-slate-100">
                            <svg class="w-7 h-7 text-slate-600 shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>Debit / Card</span>
                        </button>
                        <button type="button" id="pay-insurance" onclick="setPaymentMethod('insurance')" class="py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 text-base sm:text-lg font-extrabold flex items-center justify-center space-x-2.5 transition hover:border-slate-400 hover:bg-slate-100">
                            <svg class="w-7 h-7 text-slate-600 shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Insurance</span>
                        </button>

                        <button type="button" id="pay-digital" onclick="setPaymentMethod('digital')" class="py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 text-base sm:text-lg font-extrabold flex items-center justify-center space-x-2.5 transition hover:border-slate-400 hover:bg-slate-100">
                            <svg class="w-7 h-7 text-slate-600 shrink-0 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>Online Wallet</span>
                        </button>
                    </div>
                </div>

                <div id="cash-tender-box" class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3.5">
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-wider">Cash Received From Patient</label>
                    <input type="number" id="cash_tendered" oninput="calculateTotals()" placeholder="Enter amount received..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-lg text-slate-900 font-extrabold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">                    
                    <div class="flex items-center gap-2 pt-1 flex-wrap">
                        <button type="button" onclick="quickCash(500)" class="px-3.5 py-1.5 bg-white border border-slate-300 text-slate-800 text-xs font-black rounded-lg hover:bg-slate-100 transition shadow-2xs">Rs. 500</button>
                        <button type="button" onclick="quickCash(1000)" class="px-3.5 py-1.5 bg-white border border-slate-300 text-slate-800 text-xs font-black rounded-lg hover:bg-slate-100 transition shadow-2xs">Rs. 1000</button>
                        <button type="button" onclick="quickCash(5000)" class="px-3.5 py-1.5 bg-white border border-slate-300 text-slate-800 text-xs font-black rounded-lg hover:bg-slate-100 transition shadow-2xs">Rs. 5000</button>
                        <button type="button" onclick="quickCash('exact')" class="px-3.5 py-1.5 bg-emerald-100 border border-emerald-300 text-emerald-900 text-xs font-black rounded-lg hover:bg-emerald-200 transition shadow-2xs">Exact Amount</button>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                        <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Change Due:</span>
                        <span id="change_due_display" class="font-black text-emerald-700 text-xl">Rs. 0.00</span>
                    </div>
                </div>

                <div class="space-y-3 text-base bg-slate-50 p-5 rounded-2xl border border-slate-200 font-bold">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal:</span>
                        <span id="summary-subtotal" class="font-extrabold text-slate-900">Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-rose-600">
                        <span>Discount (5.8%):</span>
                        <span id="summary-discount" class="font-extrabold">-Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-700">
                        <span>Govt Tax (1.5%):</span>
                        <span id="summary-tax" class="font-extrabold">+Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-900 font-black text-xl pt-3 border-t border-slate-200">
                        <span>Grand Total:</span>
                        <span id="grand-total-display" class="text-emerald-700 font-black">Rs. 0.00</span>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-5 pt-3">
                    <button type="button" onclick="clearCart()" class="px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white text-base font-extrabold rounded-xl transition shadow-sm hover:shadow flex items-center justify-center space-x-2.5 shrink-0">
                        <svg class="w-5 h-5 text-white stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Cancel</span>
                    </button>
                    <button type="button" onclick="openInvoiceModal()" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white text-base font-extrabold rounded-xl transition shadow-sm shadow-emerald-600/20 hover:shadow flex items-center justify-center space-x-2.5 shrink-0">
                        <svg class="w-5 h-5 text-white stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Confirm</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-12">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payment History</h2>
                <p id="ledger-count-badge" class="text-sm font-bold text-slate-500 mt-1">1 Verified Payment Recorded Today</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="ledger-search" onkeyup="filterLedgerTable()" placeholder="Search invoice or patient..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-xs font-bold placeholder:font-normal">
                </div>
                
                <button type="button" onclick="printPaymentHistory()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-extrabold rounded-xl border border-slate-300 shadow-2xs transition flex items-center justify-center space-x-2 shrink-0">
                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print</span>
                </button>
                <button type="button" onclick="downloadPaymentHistoryCSV()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold rounded-xl shadow-xs transition flex items-center justify-center space-x-2 shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Download</span>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="payment-history-table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 text-xs font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-6">Invoice ID</th>
                        <th class="py-4 px-6">Date & Time</th>
                        <th class="py-4 px-6">Patient / Entity</th>
                        <th class="py-4 px-6">Channel</th>
                        <th class="py-4 px-6">Items</th>
                        <th class="py-4 px-6">Total Paid</th>
                        <th class="py-4 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="sales-ledger-body" class="divide-y divide-slate-100 text-base text-slate-800 font-semibold">
                    <tr id="empty-ledger-row">
                        <td colspan="7" class="py-12 text-center text-slate-400 font-medium text-base">No verified payment records logged today. Completed sales will automatically appear here.</td>
                    </tr>
                    {{-- Note for JS: Table delete button dynamically rendered in JS uses class: "p-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition" --}}
                </tbody>
            </table>
        </div>
    </div>

<div id="invoice-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-5" style="font-family: 'Inter', sans-serif;">            
        <div class="border-b border-slate-200 pb-3 space-y-2">
            <div class="flex items-center justify-between">
                <img src="/images/cityy_logo.png" alt="City Pharmacy Logo" class="h-11 w-auto object-contain">
            </div>
            
            <div class="flex justify-start items-center pt-1">
                <p id="invoice-subtitle" class="text-sm text-slate-600 font-bold tracking-tight text-left">Verified Counter Cash Receipt</p>
                <span id="invoice-modal-title" class="hidden"></span>
            </div>
        </div>

        <div id="printable-slip-area" class="space-y-4">
            <div class="flex justify-between text-sm text-slate-800 bg-slate-50/80 p-4 rounded-xl border border-slate-200 font-bold">
                <div>
                    <p class="text-slate-400 uppercase tracking-wider text-[11px]">PATIENT / CLIENT:</p>
                    <p id="slip-client-name" class="text-slate-900 text-base font-black mt-0.5">--</p>
                    <p id="slip-payment-method" class="text-emerald-700 font-black mt-1 text-xs">Gateway: CASH</p>
                </div>
                <div class="text-right">
                    <p class="text-slate-400 uppercase tracking-wider text-[11px]">INVOICE NO:</p>
                    <p id="slip-invoice-id" class="text-slate-900 font-black mt-0.5 text-sm">#INV-2026-001</p>
                    <p id="slip-date" class="text-slate-500 font-bold mt-1 text-xs">--</p>
                </div>
            </div>

            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700 font-extrabold border-b border-slate-200 text-xs">
                            <th class="py-3 px-4">Item</th>
                            <th class="py-3 px-2 text-center">Qty</th>
                            <th class="py-3 px-2">Price</th>
                            <th class="py-3 px-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="slip-items-list" class="divide-y divide-slate-100 font-semibold text-slate-800">
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-1">
                <div class="w-full sm:w-80 space-y-2 text-sm bg-slate-50/80 p-4 rounded-xl border border-slate-200 font-bold">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal:</span>
                        <span id="slip-subtotal" class="font-extrabold text-slate-900">Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-rose-600">
                        <span>Discount:</span>
                        <span id="slip-discount" class="font-extrabold">-Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Govt Tax (1.5%):</span>
                        <span id="slip-tax" class="font-extrabold">+Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-900 font-black text-base pt-2 border-t border-slate-200">
                        <span>Grand Total:</span>
                        <span id="slip-grand-total" class="text-emerald-700 font-black text-lg">Rs. 0.00</span>
                    </div>
                </div>
            </div>

            <div class="text-center pt-3 border-t border-dashed border-slate-200 text-xs text-slate-400 font-bold space-y-0.5">
                <p class="text-slate-800 font-extrabold text-sm">Thank you for trusting City Pharmacy!</p>
                <p class="text-xs text-slate-400">Official Patient Receipt • Please retain for your records</p>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-200 flex items-center justify-between gap-4">
            <span class="text-xs text-slate-400 font-bold">Ready for print</span>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="printCurrentSlip()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black rounded-xl transition border border-slate-300">
                    Print Slip
                </button>
                <button type="button" onclick="confirmPaymentAndSave()" id="btn-confirm-order" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition shadow-md">
                    Confirm & Save
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.stockDatabase = @json($medicines ?? []);
</script>
<script src="{{ asset('js/sales-billing.js') }}"></script>
@endpush