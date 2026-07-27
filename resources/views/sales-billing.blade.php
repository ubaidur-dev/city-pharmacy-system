@extends('layouts.app')

@section('title', 'Pharmacy Billing & POS - City Pharmacy Store')

@section('content')

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Pharmacy Billing & POS</h1>
            <p class="text-base text-slate-500 mt-2">Create patient invoices, manage corporate hospital credit slips, and track counter sales in real-time.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Revenue</p>
                <h3 id="kpi-total-revenue" class="text-2xl font-black text-slate-900 mt-1">Rs. 0.00</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Cash Collected</p>
                <h3 id="kpi-cash-collected" class="text-2xl font-black text-slate-900 mt-1">Rs. 0.00</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Digital / Card</p>
                <h3 id="kpi-digital-collected" class="text-2xl font-black text-slate-900 mt-1">Rs. 0.00</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Today Invoices</p>
                <h3 id="kpi-invoice-count" class="text-2xl font-black text-slate-900 mt-1">0</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">

        <div class="lg:col-span-7 space-y-6">

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Select Billing Channel</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <button type="button" id="btn-customer" onclick="setBillingType('customer')" class="p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/60 text-emerald-900 font-extrabold text-sm transition flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Retail Patient</span>
                    </button>
                    <button type="button" id="btn-corporate" onclick="setBillingType('corporate')" class="p-4 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 font-extrabold text-sm transition flex items-center space-x-3 hover:border-slate-300">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Corporate B2B</span>
                    </button>
                    <button type="button" id="btn-clinic" onclick="setBillingType('clinic')" class="p-4 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 font-extrabold text-sm transition flex items-center space-x-3 hover:border-slate-300">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Clinic Ward</span>
                    </button>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <label id="client-label" class="block text-sm font-extrabold text-slate-700 mb-2">Patient / Customer Name</label>
                        <input type="text" id="client_name" placeholder="Enter customer or patient full name..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm font-semibold">
                    </div>
                    <div id="corporate-fields" class="hidden">
                        <label class="block text-sm font-extrabold text-slate-700 mb-2">Purchase Order / Reference No.</label>
                        <input type="text" id="po_reference" placeholder="e.g. PO-9982-HOSP" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm font-semibold">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Add Medicines To Cart</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 relative">
                    <div class="sm:col-span-7 relative">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Search or Type Medicine</label>
                        <div class="relative">
                            <input type="text" id="med_search_input" onfocus="openMedDropdown()" oninput="filterMedList()" placeholder="Type to search stock (e.g. Panadol)..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
                            <button type="button" onclick="toggleMedDropdown()" class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>

                        <div id="med_dropdown_menu" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 max-h-64 overflow-y-auto divide-y divide-slate-100">
                            <div id="med_list_container"></div>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Price (Rs.)</label>
                        <input type="number" id="selected_price" placeholder="0.00" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Qty</label>
                        <input type="number" id="selected_qty" value="1" min="1" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="addMedicineItem()" class="w-full sm:w-auto px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl transition shadow-sm flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Item To Order</span>
                    </button>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg">Active Order Cart</h3>
                    <span id="cart-item-count" class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">0 item(s) added</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-4 px-5">Item</th>
                                <th class="py-4 px-5">Unit Price</th>
                                <th class="py-4 px-5">Qty</th>
                                <th class="py-4 px-5">Total</th>
                                <th class="py-4 px-5 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items-list" class="divide-y divide-slate-100 text-base text-slate-700">
                            <tr id="empty-cart-row">
                                <td colspan="5" class="py-12 text-center text-slate-400 font-semibold text-base">Cart is empty. Choose or type a medicine above to start building the invoice.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="lg:col-span-5 space-y-6">

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">Order Summary & Payment</h3>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Payment Channel</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" id="pay-cash" onclick="setPaymentMethod('cash')" class="py-3.5 px-3 rounded-2xl border-2 border-emerald-600 bg-emerald-50/80 text-emerald-900 text-xs font-black flex items-center justify-center space-x-2 transition">
                            <span>Cash Payment</span>
                        </button>
                        <button type="button" id="pay-card" onclick="setPaymentMethod('card')" class="py-3.5 px-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold flex items-center justify-center space-x-2 transition hover:bg-slate-100">
                            <span>Debit / Credit Card</span>
                        </button>
                        <button type="button" id="pay-insurance" onclick="setPaymentMethod('insurance')" class="py-3.5 px-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold flex items-center justify-center space-x-2 transition hover:bg-slate-100">
                            <span>Insurance Claim</span>
                        </button>
                        <button type="button" id="pay-digital" onclick="setPaymentMethod('digital')" class="py-3.5 px-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold flex items-center justify-center space-x-2 transition hover:bg-slate-100">
                            <span>Online / Wallet</span>
                        </button>
                    </div>
                </div>

                <div id="cash-tender-box" class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Cash Received From Patient</label>
                    <input type="number" id="cash_tendered" oninput="calculateTotals()" placeholder="Enter amount received..." class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-base text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                    
                    <div class="flex items-center gap-2 pt-1">
                        <button type="button" onclick="quickCash(500)" class="px-3 py-1 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-100 transition shadow-2xs">Rs. 500</button>
                        <button type="button" onclick="quickCash(1000)" class="px-3 py-1 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-100 transition shadow-2xs">Rs. 1000</button>
                        <button type="button" onclick="quickCash(5000)" class="px-3 py-1 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-100 transition shadow-2xs">Rs. 5000</button>
                        <button type="button" onclick="quickCash('exact')" class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-lg hover:bg-emerald-100 transition shadow-2xs">Exact</button>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                        <span class="text-xs font-bold text-slate-500">Change Due:</span>
                        <span id="change_due_display" class="font-black text-emerald-700 text-lg">Rs. 0.00</span>
                    </div>
                </div>

                <div class="space-y-2 text-sm bg-slate-50 p-4 rounded-xl border border-slate-200 font-bold">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal:</span>
                        <span id="summary-subtotal" class="font-black text-slate-900">Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-rose-600">
                        <span>Discount (5.8%):</span>
                        <span id="summary-discount" class="font-black">-Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-700">
                        <span>Govt Tax (1.5%):</span>
                        <span id="summary-tax" class="font-black">+Rs. 0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-900 font-black text-lg pt-2 border-t border-slate-200">
                        <span>Grand Total:</span>
                        <span id="grand-total-display" class="text-emerald-700">Rs. 0.00</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" onclick="clearCart()" class="py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black rounded-xl transition border border-slate-200">
                        Reset / Clear
                    </button>
                    <button type="button" onclick="openInvoiceModal()" class="py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition shadow-md">
                        Process Invoice
                    </button>
                </div>
            </div>

        </div>

    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-12">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payment History</h2>
                <p id="ledger-count-badge" class="text-sm font-bold text-slate-500 mt-1">0 Verified Payment Transactions Recorded Today</p>
                
                <div class="flex items-center gap-3 mt-3">
                    <button type="button" onclick="printPaymentHistory()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-800 text-xs font-extrabold rounded-xl border border-slate-200 shadow-2xs transition flex items-center space-x-2">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print History</span>
                    </button>
                    <button type="button" onclick="downloadPaymentHistoryCSV()" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-xl border border-emerald-200 shadow-2xs transition flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download Excel / CSV Report</span>
                    </button>
                </div>
            </div>

            <div class="relative w-full md:w-[320px]">
                <input type="text" id="ledger-search" onkeyup="filterLedgerTable()" placeholder="Search invoice or patient..." class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm font-semibold">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="payment-history-table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-5">Invoice ID</th>
                        <th class="py-4 px-5">Date & Time</th>
                        <th class="py-4 px-5">Patient / Entity</th>
                        <th class="py-4 px-5">Channel</th>
                        <th class="py-4 px-5">Items</th>
                        <th class="py-4 px-5">Total Paid</th>
                        <th class="py-4 px-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="sales-ledger-body" class="divide-y divide-slate-100 text-base text-slate-700">
                    <tr id="empty-ledger-row">
                        <td colspan="7" class="py-12 text-center text-slate-400 font-semibold text-base">No verified payment records logged today. Completed sales will automatically appear here.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="invoice-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-5">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 id="invoice-modal-title" class="text-xl font-black text-slate-900">CarePoint Pharmacy Retail Slip</h3>
                    <p id="invoice-subtitle" class="text-xs text-slate-500 font-bold">Verified Counter Cash Receipt</p>
                </div>
                <button type="button" onclick="closeInvoiceModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div id="printable-slip-area" class="space-y-4">
                <div class="flex justify-between text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-200 font-bold">
                    <div>
                        <p class="text-slate-400">PATIENT / CLIENT:</p>
                        <p id="slip-client-name" class="text-slate-900 text-sm font-black">--</p>
                        <p id="slip-payment-method" class="text-emerald-700 font-black mt-0.5">Gateway: CASH</p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-400">INVOICE NO:</p>
                        <p id="slip-invoice-id" class="text-slate-900 font-black">#INV-2026-001</p>
                        <p id="slip-date" class="text-slate-500 font-bold mt-0.5">--</p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-700 font-black border-b border-slate-200">
                                <th class="py-2.5 px-4">Item</th>
                                <th class="py-2.5 px-4">Qty</th>
                                <th class="py-2.5 px-4">Price</th>
                                <th class="py-2.5 px-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="slip-items-list" class="divide-y divide-slate-100 font-medium text-slate-800">
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end pt-2">
                    <div class="w-full sm:w-72 space-y-2 text-sm bg-slate-50 p-4 rounded-2xl border border-slate-200 font-bold">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span id="slip-subtotal" class="font-black text-slate-900">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between text-rose-600">
                            <span>Discount:</span>
                            <span id="slip-discount" class="font-black">-Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between text-slate-700">
                            <span>Govt Tax (1.5%):</span>
                            <span id="slip-tax" class="font-black">+Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between text-slate-900 font-black text-base pt-2 border-t border-slate-200">
                            <span>Grand Total:</span>
                            <span id="slip-grand-total" class="text-emerald-700">Rs. 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="text-center pt-4 border-t border-dashed border-slate-200 text-xs text-slate-400 font-bold space-y-1">
                    <p>CarePoint Pharmacy Management System — Verified Official Copy</p>
                    <p class="text-slate-500">Ubaid Ur Rehman — Software developer / Engineer</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-between gap-4">
                <span class="text-xs text-slate-400 font-bold">Ready for thermal printer</span>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black rounded-xl transition border border-slate-200">
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
    const stockDatabase = [
        { id: "1", name: "Panadol Extra 500mg", price: 35.00, stock: 150 },
        { id: "2", name: "Augmentin 625mg Tablet", price: 280.00, stock: 45 },
        { id: "3", name: "Brufen 400mg", price: 25.00, stock: 80 },
        { id: "4", name: "Risek 20mg Capsule", price: 120.00, stock: 60 },
        { id: "5", name: "Flygyl 400mg Tablet", price: 40.00, stock: 120 },
        { id: "6", name: "Arinac Forte Tablet", price: 65.00, stock: 90 },
        { id: "7", name: "Cac 1000 Effervescent", price: 310.00, stock: 35 },
        { id: "8", name: "Disprin 300mg", price: 15.00, stock: 300 },
        { id: "9", name: "Softin 10mg Tablet", price: 95.00, stock: 110 },
        { id: "10", name: "Surbex Z Multivitamin", price: 420.00, stock: 75 },
        { id: "11", name: "Leflox 500mg Tablet", price: 350.00, stock: 50 },
        { id: "12", name: "Ponstan 250mg Capsule", price: 30.00, stock: 210 },
        { id: "13", name: "Nuberol Forte Tablet", price: 110.00, stock: 85 },
        { id: "14", name: "Gravinate 50mg", price: 20.00, stock: 140 },
        { id: "15", name: "Klaricid 500mg Tablet", price: 580.00, stock: 25 }
    ];

    let cart = [];
    let salesHistory = [];
    let currentMode = 'customer';
    let selectedPaymentMethod = 'cash';
    let activeSelectedMed = null;

    document.addEventListener('DOMContentLoaded', function() {
        populateMedicineList(stockDatabase);

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('med_dropdown_menu');
            const searchInput = document.getElementById('med_search_input');
            if(!dropdown.contains(e.target) && !searchInput.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    function openMedDropdown() {
        document.getElementById('med_dropdown_menu').classList.remove('hidden');
    }

    function toggleMedDropdown() {
        document.getElementById('med_dropdown_menu').classList.toggle('hidden');
    }

    function populateMedicineList(items) {
        const container = document.getElementById('med_list_container');
        if(items.length === 0) {
            container.innerHTML = `
                <div class="p-4 text-center">
                    <p class="text-xs text-slate-500 font-bold">Item not in stock list.</p>
                    <button type="button" onclick="selectManualCustomItem()" class="mt-2 px-3 py-1.5 bg-emerald-50 text-emerald-800 text-xs font-black rounded-lg border border-emerald-200 hover:bg-emerald-100 transition">
                        + Add as Manual Custom Item
                    </button>
                </div>
            `;
            return;
        }

        let html = '';
        items.forEach(item => {
            html += `
                <div onclick="selectStockMedicine('${item.id}', '${item.name}', ${item.price})" class="p-3.5 hover:bg-slate-50 cursor-pointer flex justify-between items-center transition">
                    <div>
                        <p class="text-sm font-black text-slate-900">${item.name}</p>
                        <p class="text-xs font-bold text-slate-400">Stock Available: ${item.stock} units</p>
                    </div>
                    <span class="text-sm font-black text-emerald-700">Rs. ${item.price.toFixed(2)}</span>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    function filterMedList() {
        const query = document.getElementById('med_search_input').value.toLowerCase().trim();
        openMedDropdown();

        const filtered = stockDatabase.filter(m => m.name.toLowerCase().includes(query));
        populateMedicineList(filtered);

        activeSelectedMed = null;
    }

    function selectStockMedicine(id, name, price) {
        document.getElementById('med_search_input').value = name;
        document.getElementById('selected_price').value = price.toFixed(2);
        document.getElementById('med_dropdown_menu').classList.add('hidden');
        
        activeSelectedMed = { id, name, price };
    }

    function selectManualCustomItem() {
        const customName = document.getElementById('med_search_input').value.trim();
        if(!customName) {
            alert('Please type a medicine name first.');
            return;
        }
        document.getElementById('selected_price').value = '';
        document.getElementById('selected_price').focus();
        document.getElementById('med_dropdown_menu').classList.add('hidden');
        activeSelectedMed = { id: 'manual-' + Date.now(), name: customName, price: 0 };
    }

    function setBillingType(type) {
        currentMode = type;
        const btnCust = document.getElementById('btn-customer');
        const btnCorp = document.getElementById('btn-corporate');
        const btnClinic = document.getElementById('btn-clinic');
        const label = document.getElementById('client-label');
        const nameInput = document.getElementById('client_name');
        const corpFields = document.getElementById('corporate-fields');

        [btnCust, btnCorp, btnClinic].forEach(btn => {
            btn.className = "p-4 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 font-extrabold text-sm transition flex items-center space-x-3 hover:border-slate-300";
        });

        if(type === 'customer') {
            btnCust.className = "p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/60 text-emerald-900 font-extrabold text-sm transition flex items-center space-x-3";
            label.innerText = "Patient / Customer Name";
            nameInput.placeholder = "Enter customer or patient full name...";
            corpFields.classList.add('hidden');
        } else if(type === 'corporate') {
            btnCorp.className = "p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/60 text-emerald-900 font-extrabold text-sm transition flex items-center space-x-3";
            label.innerText = "Corporate Hospital Partner Name";
            nameInput.placeholder = "Enter corporate organization name...";
            corpFields.classList.remove('hidden');
        } else if(type === 'clinic') {
            btnClinic.className = "p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/60 text-emerald-900 font-extrabold text-sm transition flex items-center space-x-3";
            label.innerText = "Clinic / Ward Patient Reference";
            nameInput.placeholder = "Enter ward or in-patient ID...";
            corpFields.classList.add('hidden');
        }
    }

    function setPaymentMethod(method) {
        selectedPaymentMethod = method;
        const methods = ['cash', 'card', 'insurance', 'digital'];
        
        methods.forEach(m => {
            const btn = document.getElementById('pay-' + m);
            if(m === method) {
                btn.className = "py-3.5 px-3 rounded-2xl border-2 border-emerald-600 bg-emerald-50/80 text-emerald-900 text-xs font-black flex items-center justify-center space-x-2 transition";
            } else {
                btn.className = "py-3.5 px-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold flex items-center justify-center space-x-2 transition hover:bg-slate-100";
            }
        });

        const tenderBox = document.getElementById('cash-tender-box');
        if(method === 'cash') {
            tenderBox.classList.remove('hidden');
        } else {
            tenderBox.classList.add('hidden');
        }
    }

    function quickCash(val) {
        const totals = calculateTotals();
        if(val === 'exact') {
            document.getElementById('cash_tendered').value = totals.grandTotal.toFixed(2);
        } else {
            document.getElementById('cash_tendered').value = val;
        }
        calculateTotals();
    }

    function addMedicineItem() {
        const medNameInput = document.getElementById('med_search_input').value.trim();
        const priceInput = parseFloat(document.getElementById('selected_price').value);
        const qty = parseInt(document.getElementById('selected_qty').value);

        if(!medNameInput) {
            alert('Please select or enter medicine name.');
            return;
        }
        if(isNaN(priceInput) || priceInput <= 0) {
            alert('Please enter a valid price greater than 0.');
            return;
        }
        if(isNaN(qty) || qty < 1) {
            alert('Please enter valid quantity.');
            return;
        }

        let medId = activeSelectedMed ? activeSelectedMed.id : 'manual-' + Date.now();

        let existing = cart.find(item => item.name.toLowerCase() === medNameInput.toLowerCase());
        if(existing) {
            existing.qty += qty;
        } else {
            cart.push({ id: medId, name: medNameInput, price: priceInput, qty: qty });
        }

        renderCart();
        
        // Reset Inputs
        document.getElementById('med_search_input').value = '';
        document.getElementById('selected_price').value = '';
        document.getElementById('selected_qty').value = '1';
        activeSelectedMed = null;
        populateMedicineList(stockDatabase);
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
        document.getElementById('client_name').value = '';
        document.getElementById('po_reference').value = '';
        document.getElementById('cash_tendered').value = '';
        document.getElementById('change_due_display').innerText = 'Rs. 0.00';
    }

    function calculateTotals() {
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.price * item.qty;
        });

        let discount = subtotal * 0.058; 
        let tax = subtotal * 0.015;     
        let grandTotal = subtotal - discount + tax;
        if(grandTotal < 0) grandTotal = 0;

        document.getElementById('summary-subtotal').innerText = "Rs. " + subtotal.toFixed(2);
        document.getElementById('summary-discount').innerText = "-Rs. " + discount.toFixed(2);
        document.getElementById('summary-tax').innerText = "+Rs. " + tax.toFixed(2);
        document.getElementById('grand-total-display').innerText = "Rs. " + grandTotal.toFixed(2);

        const tendered = parseFloat(document.getElementById('cash_tendered').value) || 0;
        const change = tendered - grandTotal;
        const display = document.getElementById('change_due_display');

        if(change >= 0) {
            display.innerText = "Rs. " + change.toFixed(2);
            display.className = "font-black text-emerald-700 text-lg";
        } else {
            display.innerText = "-Rs. " + Math.abs(change).toFixed(2);
            display.className = "font-black text-rose-600 text-lg";
        }

        return { subtotal, discount, tax, grandTotal };
    }

    function renderCart() {
        const tbody = document.getElementById('cart-items-list');
        const itemCount = document.getElementById('cart-item-count');
        
        itemCount.innerText = cart.length + " item(s) added";

        if(cart.length === 0) {
            tbody.innerHTML = `<tr id="empty-cart-row"><td colspan="5" class="py-12 text-center text-slate-400 font-semibold text-base">Cart is empty. Choose or type a medicine above to start building the invoice.</td></tr>`;
            calculateTotals();
            return;
        }

        let html = '';
        cart.forEach((item, index) => {
            let subtotal = item.price * item.qty;
            html += `
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-5 font-black text-slate-900">${item.name}</td>
                    <td class="py-4 px-5 font-bold text-slate-700">Rs. ${item.price.toFixed(2)}</td>
                    <td class="py-4 px-5 text-emerald-700 font-black">${item.qty} units</td>
                    <td class="py-4 px-5 font-black text-slate-900">Rs. ${subtotal.toFixed(2)}</td>
                    <td class="py-4 px-5 text-center">
                        <button type="button" onclick="removeFromCart(${index})" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-black rounded-xl transition border border-rose-100">Remove</button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        calculateTotals();
    }

    function openInvoiceModal() {
        const clientName = document.getElementById('client_name').value;
        if(!clientName.trim()) {
            alert('Please enter patient or customer name before processing invoice.');
            return;
        }
        if(cart.length === 0) {
            alert('Cart is empty. Add medicine items first.');
            return;
        }

        document.getElementById('btn-confirm-order').classList.remove('hidden');

        const modalTitle = document.getElementById('invoice-modal-title');
        const subtitle = document.getElementById('invoice-subtitle');

        if(currentMode === 'corporate') {
            modalTitle.innerText = "CarePoint B2B Corporate Invoice";
            subtitle.innerText = "Hospital Partner Credit Voucher";
        } else if(currentMode === 'clinic') {
            modalTitle.innerText = "CarePoint Ward / In-Patient Voucher";
            subtitle.innerText = "Clinical Patient Receipt";
        } else {
            modalTitle.innerText = "CarePoint Pharmacy Retail Slip";
            subtitle.innerText = "Verified Counter Cash Receipt";
        }

        document.getElementById('slip-client-name').innerText = clientName;
        document.getElementById('slip-payment-method').innerText = "Gateway: " + selectedPaymentMethod.toUpperCase();
        
        const now = new Date();
        const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        
        document.getElementById('slip-invoice-id').innerText = "#INV-2026-" + String(salesHistory.length + 1).padStart(3, '0');
        document.getElementById('slip-date').innerText = `${dateStr}, ${timeStr}`;

        let slipTbody = document.getElementById('slip-items-list');
        let slipHtml = '';

        cart.forEach(item => {
            let subtotal = item.price * item.qty;
            slipHtml += `
                <tr>
                    <td class="py-3 px-4 font-black text-slate-900">${item.name}</td>
                    <td class="py-3 px-4 font-bold">${item.qty}</td>
                    <td class="py-3 px-4">Rs. ${item.price.toFixed(2)}</td>
                    <td class="py-3 px-4 text-right font-black text-slate-900">Rs. ${subtotal.toFixed(2)}</td>
                </tr>
            `;
        });

        slipTbody.innerHTML = slipHtml;

        let totals = calculateTotals();
        document.getElementById('slip-subtotal').innerText = "Rs. " + totals.subtotal.toFixed(2);
        document.getElementById('slip-discount').innerText = "-Rs. " + totals.discount.toFixed(2);
        document.getElementById('slip-tax').innerText = "+Rs. " + totals.tax.toFixed(2);
        document.getElementById('slip-grand-total').innerText = "Rs. " + totals.grandTotal.toFixed(2);

        document.getElementById('invoice-modal').classList.remove('hidden');
    }

    function closeInvoiceModal() {
        document.getElementById('invoice-modal').classList.add('hidden');
    }

    function confirmPaymentAndSave() {
        const clientName = document.getElementById('client_name').value;
        const now = new Date();
        const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        const invId = "INV-2026-" + String(salesHistory.length + 1).padStart(3, '0');

        const totals = calculateTotals();
        let totalItemsQty = cart.reduce((acc, curr) => acc + curr.qty, 0);

        const newTransaction = {
            id: invId,
            clientName: clientName,
            channel: currentMode,
            method: selectedPaymentMethod,
            dateTime: `${dateStr}, ${timeStr}`,
            items: [...cart],
            itemsCount: totalItemsQty,
            totals: totals
        };

        salesHistory.unshift(newTransaction);

        updateKPICards();
        renderHistoryTable();

        alert('Payment processed successfully! Invoice #' + invId + ' saved to Payment History.');
        closeInvoiceModal();
        clearCart();
    }

    function updateKPICards() {
        let totalRev = 0;
        let cashColl = 0;
        let digitalColl = 0;

        salesHistory.forEach(tx => {
            totalRev += tx.totals.grandTotal;
            if(tx.method === 'cash') cashColl += tx.totals.grandTotal;
            else digitalColl += tx.totals.grandTotal;
        });

        document.getElementById('kpi-total-revenue').innerText = "Rs. " + totalRev.toFixed(2);
        document.getElementById('kpi-cash-collected').innerText = "Rs. " + cashColl.toFixed(2);
        document.getElementById('kpi-digital-collected').innerText = "Rs. " + digitalColl.toFixed(2);
        document.getElementById('kpi-invoice-count').innerText = salesHistory.length;
    }

    function renderHistoryTable() {
        const tbody = document.getElementById('sales-ledger-body');
        const countBadge = document.getElementById('ledger-count-badge');

        countBadge.innerText = salesHistory.length + " Verified Payment Transactions Recorded Today";

        if(salesHistory.length === 0) {
            tbody.innerHTML = `<tr id="empty-ledger-row"><td colspan="7" class="py-12 text-center text-slate-400 font-semibold text-base">No verified payment records logged today. Completed sales will automatically appear here.</td></tr>`;
            return;
        }

        let html = '';
        salesHistory.forEach((tx) => {
            let channelBadge = '';
            if(tx.channel === 'corporate') {
                channelBadge = `<span class="px-3 py-1 bg-purple-50 text-purple-800 text-xs font-bold rounded-lg border border-purple-200 shadow-2xs">Corporate B2B</span>`;
            } else if(tx.channel === 'clinic') {
                channelBadge = `<span class="px-3 py-1 bg-blue-50 text-blue-800 text-xs font-bold rounded-lg border border-blue-200 shadow-2xs">Clinic Ward</span>`;
            } else {
                channelBadge = `<span class="px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-lg border border-emerald-200 shadow-2xs">Retail Patient</span>`;
            }

            html += `
                <tr class="hover:bg-slate-50/60 transition ledger-row" data-search="${tx.id} ${tx.clientName.toLowerCase()}">
                    <td class="py-5 px-5 font-black text-slate-900 text-base">#${tx.id}</td>
                    <td class="py-5 px-5 font-bold text-slate-500 text-sm">${tx.dateTime}</td>
                    <td class="py-5 px-5 font-bold text-slate-900 text-base">${tx.clientName}</td>
                    <td class="py-5 px-5">${channelBadge} <span class="text-xs text-slate-400 font-bold uppercase ml-1">(${tx.method})</span></td>
                    <td class="py-5 px-5 font-extrabold text-emerald-700">${tx.itemsCount} units</td>
                    <td class="py-5 px-5 font-black text-slate-900 text-lg">Rs. ${tx.totals.grandTotal.toFixed(2)}</td>
                    <td class="py-5 px-5 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <button type="button" onclick="viewHistoricalInvoice('${tx.id}')" title="View & Print Slip" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-xs rounded-xl transition border border-slate-200 flex items-center space-x-1">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Slip</span>
                            </button>
                            <button type="button" onclick="deleteHistoryTransaction('${tx.id}')" title="Delete Voucher" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function filterLedgerTable() {
        const query = document.getElementById('ledger-search').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.ledger-row');

        rows.forEach(row => {
            const data = row.getAttribute('data-search');
            if(data.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function viewHistoricalInvoice(txId) {
        const tx = salesHistory.find(t => t.id === txId);
        if(!tx) return;

        document.getElementById('btn-confirm-order').classList.add('hidden');

        document.getElementById('slip-client-name').innerText = tx.clientName;
        document.getElementById('slip-payment-method').innerText = "Gateway: " + tx.method.toUpperCase();
        document.getElementById('slip-invoice-id').innerText = "#" + tx.id;
        document.getElementById('slip-date').innerText = tx.dateTime;

        let slipTbody = document.getElementById('slip-items-list');
        let slipHtml = '';

        tx.items.forEach(item => {
            let subtotal = item.price * item.qty;
            slipHtml += `
                <tr>
                    <td class="py-3 px-4 font-black text-slate-900">${item.name}</td>
                    <td class="py-3 px-4 font-bold">${item.qty}</td>
                    <td class="py-3 px-4">Rs. ${item.price.toFixed(2)}</td>
                    <td class="py-3 px-4 text-right font-black text-slate-900">Rs. ${subtotal.toFixed(2)}</td>
                </tr>
            `;
        });

        slipTbody.innerHTML = slipHtml;

        document.getElementById('slip-subtotal').innerText = "Rs. " + tx.totals.subtotal.toFixed(2);
        document.getElementById('slip-discount').innerText = "-Rs. " + tx.totals.discount.toFixed(2);
        document.getElementById('slip-tax').innerText = "+Rs. " + tx.totals.tax.toFixed(2);
        document.getElementById('slip-grand-total').innerText = "Rs. " + tx.totals.grandTotal.toFixed(2);

        document.getElementById('invoice-modal').classList.remove('hidden');
    }

    function deleteHistoryTransaction(txId) {
        if(confirm('Are you sure you want to delete transaction record #' + txId + '?')) {
            salesHistory = salesHistory.filter(t => t.id !== txId);
            updateKPICards();
            renderHistoryTable();
        }
    }

    function printPaymentHistory() {
        if(salesHistory.length === 0) {
            alert('No payment history records to print.');
            return;
        }
        window.print();
    }

    function downloadPaymentHistoryCSV() {
        if(salesHistory.length === 0) {
            alert('No payment records to export.');
            return;
        }

        let csvContent = "data:text/csv;charset=utf-8,Invoice ID,Date Time,Patient Entity,Channel,Method,Items Count,Grand Total\n";
        
        salesHistory.forEach(tx => {
            csvContent += `"${tx.id}","${tx.dateTime}","${tx.clientName}","${tx.channel}","${tx.method}","${tx.itemsCount}","${tx.totals.grandTotal.toFixed(2)}"\n`;
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `Payment_History_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush