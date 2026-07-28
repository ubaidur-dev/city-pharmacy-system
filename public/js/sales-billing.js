let stockDatabase = [], cart = [], salesHistory = [], currentMode = 'customer', selectedPaymentMethod = 'cash', activeSelectedMed = null;

document.addEventListener('DOMContentLoaded', function() {
    if (window.stockDatabase && Array.isArray(window.stockDatabase)) {
        stockDatabase = window.stockDatabase.sort((a, b) => a.name.localeCompare(b.name));
    }
    populateMedicineList(stockDatabase);
    
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('med_dropdown_menu'), searchInput = document.getElementById('med_search_input');
        if (dropdown && searchInput && !dropdown.contains(e.target) && !searchInput.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});

function openMedDropdown() { const d = document.getElementById('med_dropdown_menu'); if (d) d.classList.remove('hidden'); }
function toggleMedDropdown() { const d = document.getElementById('med_dropdown_menu'); if (d) d.classList.toggle('hidden'); }

function populateMedicineList(items) {
    const container = document.getElementById('med_list_container');
    if (!container) return;
    if (items.length === 0) {
        container.innerHTML = `<div class="p-4 text-center"><p class="text-xs text-slate-500 font-bold">Item not in stock list.</p><button type="button" onclick="selectManualCustomItem()" class="mt-2 px-3 py-1.5 bg-emerald-50 text-emerald-800 text-xs font-black rounded-lg border border-emerald-200 hover:bg-emerald-100 transition">+ Add as Manual Custom Item</button></div>`;
        return;
    }
    let html = '';
    items.forEach(item => {
        const priceVal = parseFloat(item.price) || 0, stockVal = item.stock !== undefined ? item.stock : 0;
        html += `<div onclick="selectStockMedicine('${item.id}', '${item.name.replace(/'/g, "\\'")}', ${priceVal})" class="p-3.5 hover:bg-slate-50 cursor-pointer flex justify-between items-center transition"><div><p class="text-sm font-black text-slate-900">${item.name}</p><p class="text-xs font-bold text-slate-400">Stock Available: ${stockVal} units</p></div><span class="text-sm font-black text-emerald-700">Rs. ${priceVal.toFixed(2)}</span></div>`;
    });
    container.innerHTML = html;
}

function filterMedList() {
    const query = document.getElementById('med_search_input').value.toLowerCase().trim();
    openMedDropdown();
    populateMedicineList(stockDatabase.filter(m => m.name.toLowerCase().includes(query)));
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
    if (!customName) return alert('Please type a medicine name first.');
    document.getElementById('selected_price').value = '';
    document.getElementById('selected_price').focus();
    document.getElementById('med_dropdown_menu').classList.add('hidden');
    activeSelectedMed = { id: 'manual-' + Date.now(), name: customName, price: 0 };
}

function setBillingType(type) {
    currentMode = type;
    ['customer', 'corporate', 'clinic'].forEach(ch => {
        const btn = document.getElementById(`btn-${ch}`);
        if (!btn) return;
        const icon = btn.querySelector('svg');
        if (ch === type) {
            btn.className = "py-3.5 px-4 rounded-2xl border border-emerald-600 bg-emerald-50/80 text-emerald-950 font-black text-base sm:text-lg transition flex items-center justify-start space-x-2.5 shadow-xs hover:bg-emerald-100";
            if (icon) icon.setAttribute('class', 'w-7 h-7 text-emerald-700 shrink-0 stroke-[1.5]');
        } else {
            btn.className = "py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 font-extrabold text-base sm:text-lg transition flex items-center justify-start space-x-2.5 hover:border-slate-400 hover:bg-slate-100";
            if (icon) icon.setAttribute('class', 'w-7 h-7 text-slate-600 shrink-0 stroke-[1.5]');
        }
    });
    const label = document.getElementById('client-label'), nameInput = document.getElementById('client_name'), corpFields = document.getElementById('corporate-fields');
    if (type === 'customer') {
        if (label) label.innerText = "Patient / Customer Name";
        if (nameInput) nameInput.placeholder = "Enter customer full name...";
        if (corpFields) corpFields.classList.add('hidden');
    } else if (type === 'corporate') {
        if (label) label.innerText = "Company / Corporate Name";
        if (nameInput) nameInput.placeholder = "Enter corporate organization name...";
        if (corpFields) corpFields.classList.remove('hidden');
    } else if (type === 'clinic') {
        if (label) label.innerText = "Clinic / Ward Name";
        if (nameInput) nameInput.placeholder = "Enter ward or in-patient ID...";
        if (corpFields) corpFields.classList.add('hidden');
    }
}

function setPaymentMethod(method) {
    selectedPaymentMethod = method;
    ['cash', 'card', 'insurance', 'digital'].forEach(m => {
        const btn = document.getElementById('pay-' + m);
        if (btn) {
            const icon = btn.querySelector('svg');
            if (m === method) {
                btn.className = "py-3.5 px-4 rounded-2xl border border-emerald-600 bg-emerald-50 text-emerald-800 text-base sm:text-lg font-black flex items-center justify-center space-x-2.5 transition shadow-xs";
                if (icon) icon.setAttribute('class', 'w-7 h-7 text-emerald-600 shrink-0 stroke-[1.5]');
            } else {
                btn.className = "py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 text-base sm:text-lg font-extrabold flex items-center justify-center space-x-2.5 transition hover:border-slate-400 hover:bg-slate-100";
                if (icon) icon.setAttribute('class', 'w-7 h-7 text-slate-600 shrink-0 stroke-[1.5]');
            }
        }
    });
    const tenderBox = document.getElementById('cash-tender-box');
    if (tenderBox) { method === 'cash' ? tenderBox.classList.remove('hidden') : tenderBox.classList.add('hidden'); }
}

function quickCash(val) {
    const totals = calculateTotals(), cashInput = document.getElementById('cash_tendered');    
    if (cashInput) {
        cashInput.value = val === 'exact' ? totals.grandTotal.toFixed(2) : val;
        calculateTotals();
    }
}

function addMedicineItem() {
    const medNameInput = document.getElementById('med_search_input').value.trim();
    const priceInput = parseFloat(document.getElementById('selected_price').value);
    const qty = parseInt(document.getElementById('selected_qty').value);
    if (!medNameInput) return alert('Please select or enter medicine name.');
    if (isNaN(priceInput) || priceInput <= 0) return alert('Please enter a valid price greater than 0.');
    if (isNaN(qty) || qty < 1) return alert('Please enter valid quantity.');
    
    let medId = activeSelectedMed ? activeSelectedMed.id : 'manual-' + Date.now();
    let existing = cart.find(item => item.name.toLowerCase() === medNameInput.toLowerCase());
    if (existing) { existing.qty += qty; } 
    else { cart.push({ id: medId, name: medNameInput, price: priceInput, qty: qty }); }
    
    renderCart();
    document.getElementById('med_search_input').value = '';
    document.getElementById('selected_price').value = '';
    document.getElementById('selected_qty').value = '1';
    activeSelectedMed = null;
    populateMedicineList(stockDatabase);
}

function removeFromCart(index) { cart.splice(index, 1); renderCart(); }

function clearCart() {
    cart = []; renderCart();
    if (document.getElementById('client_name')) document.getElementById('client_name').value = '';
    if (document.getElementById('po_reference')) document.getElementById('po_reference').value = '';
    if (document.getElementById('client_phone')) document.getElementById('client_phone').value = '';
    if (document.getElementById('cash_tendered')) document.getElementById('cash_tendered').value = '';
    if (document.getElementById('change_due_display')) document.getElementById('change_due_display').innerText = 'Rs. 0.00';
}

function calculateTotals() {
    let subtotal = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
    let discount = subtotal * 0.058, tax = subtotal * 0.015;
    let grandTotal = Math.max(0, subtotal - discount + tax);
    
    if (document.getElementById('summary-subtotal')) document.getElementById('summary-subtotal').innerText = "Rs. " + subtotal.toFixed(2);
    if (document.getElementById('summary-discount')) document.getElementById('summary-discount').innerText = "-Rs. " + discount.toFixed(2);
    if (document.getElementById('summary-tax')) document.getElementById('summary-tax').innerText = "+Rs. " + tax.toFixed(2);
    if (document.getElementById('grand-total-display')) document.getElementById('grand-total-display').innerText = "Rs. " + grandTotal.toFixed(2);
    
    const cashElem = document.getElementById('cash_tendered');
    const tendered = cashElem ? (parseFloat(cashElem.value) || 0) : 0;
    const change = tendered - grandTotal;
    const display = document.getElementById('change_due_display');
    if (display) {
        display.innerText = change >= 0 ? "Rs. " + change.toFixed(2) : "-Rs. " + Math.abs(change).toFixed(2);
        display.className = change >= 0 ? "font-black text-emerald-700 text-xl" : "font-black text-rose-600 text-xl";
    }
    return { subtotal, discount, tax, grandTotal };
}

function renderCart() {
    const tbody = document.getElementById('cart-items-list'), itemCount = document.getElementById('cart-item-count');    
    if (itemCount) itemCount.innerText = cart.length + " item(s) added";
    if (!tbody) return;
    if (cart.length === 0) {
        tbody.innerHTML = `<tr id="empty-cart-row"><td colspan="5" class="py-12 text-center text-slate-400 font-medium text-base">Cart is empty. Choose or type a medicine above to start building the invoice.</td></tr>`;
        calculateTotals();
        return;
    }
    let html = '';
    cart.forEach((item, index) => {
        html += `<tr class="hover:bg-slate-50 transition"><td class="py-4 px-6 font-black text-slate-900">${item.name}</td><td class="py-4 px-6 font-bold text-slate-700">Rs. ${item.price.toFixed(2)}</td><td class="py-4 px-6 text-emerald-700 font-black">${item.qty} units</td><td class="py-4 px-6 font-black text-slate-900">Rs. ${(item.price * item.qty).toFixed(2)}</td><td class="py-4 px-6 text-center"><button type="button" onclick="removeFromCart(${index})" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-black rounded-xl transition border border-rose-100">Remove</button></td></tr>`;
    });
    tbody.innerHTML = html;
    calculateTotals();
}

function openInvoiceModal() {
    const clientNameElem = document.getElementById('client_name');
    const clientName = clientNameElem ? clientNameElem.value : '';
    if (!clientName.trim()) return alert('Please enter patient or customer name before processing invoice.');
    if (cart.length === 0) return alert('Cart is empty. Add medicine items first.');
    
    const confirmBtn = document.getElementById('btn-confirm-order');
    if (confirmBtn) confirmBtn.classList.remove('hidden');
    
    const modalTitle = document.getElementById('invoice-modal-title'), subtitle = document.getElementById('invoice-subtitle');
    if (modalTitle) modalTitle.innerText = "City Pharmacy Store";
    
    if (subtitle) {
        if (currentMode === 'corporate') { subtitle.innerText = "Hospital Partner Credit Voucher"; }
        else if (currentMode === 'clinic') { subtitle.innerText = "Clinical Patient Receipt"; }
        else { subtitle.innerText = "Verified Counter Cash Receipt"; }
    }
    
    if (document.getElementById('slip-client-name')) document.getElementById('slip-client-name').innerText = clientName;
    if (document.getElementById('slip-payment-method')) document.getElementById('slip-payment-method').innerText = "Gateway: " + selectedPaymentMethod.toUpperCase();
    
    const now = new Date();
    if (document.getElementById('slip-invoice-id')) document.getElementById('slip-invoice-id').innerText = "#INV-2026-" + String(salesHistory.length + 1).padStart(3, '0');
    if (document.getElementById('slip-date')) document.getElementById('slip-date').innerText = `${now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}, ${now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })}`;
    
    let slipHtml = '';
    cart.forEach(item => {
        slipHtml += `<tr><td class="py-3 px-4 font-black text-slate-900">${item.name}</td><td class="py-3 px-4 font-bold">${item.qty}</td><td class="py-3 px-4">Rs. ${item.price.toFixed(2)}</td><td class="py-3 px-4 text-right font-black text-slate-900">Rs. ${(item.price * item.qty).toFixed(2)}</td></tr>`;
    });
    if (document.getElementById('slip-items-list')) document.getElementById('slip-items-list').innerHTML = slipHtml;
    
    let totals = calculateTotals();
    if (document.getElementById('slip-subtotal')) document.getElementById('slip-subtotal').innerText = "Rs. " + totals.subtotal.toFixed(2);
    if (document.getElementById('slip-discount')) document.getElementById('slip-discount').innerText = "-Rs. " + totals.discount.toFixed(2);
    if (document.getElementById('slip-tax')) document.getElementById('slip-tax').innerText = "+Rs. " + totals.tax.toFixed(2);
    if (document.getElementById('slip-grand-total')) document.getElementById('slip-grand-total').innerText = "Rs. " + totals.grandTotal.toFixed(2);
    
    const modal = document.getElementById('invoice-modal');
    if (modal) modal.classList.remove('hidden');
}

function closeInvoiceModal() { 
    const modal = document.getElementById('invoice-modal');
    if (modal) modal.classList.add('hidden'); 
}

function confirmPaymentAndSave() {
    const clientNameElem = document.getElementById('client_name');
    const clientName = clientNameElem ? clientNameElem.value : 'Guest';
    const now = new Date();
    const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    const invId = "INV-2026-" + String(salesHistory.length + 1).padStart(3, '0');
    const totals = calculateTotals();
    
    salesHistory.unshift({
        id: invId, 
        clientName: clientName, 
        channel: currentMode, 
        method: selectedPaymentMethod,
        dateTime: `${dateStr}, ${timeStr}`, 
        items: [...cart], 
        itemsCount: cart.reduce((acc, curr) => acc + curr.qty, 0), 
        totals: totals
    });
    
    updateKPICards(); 
    renderHistoryTable();
    alert('Payment processed successfully! Invoice #' + invId + ' saved to Payment History.');
    closeInvoiceModal(); 
    clearCart();
}

function updateKPICards() {
    let totalRev = 0, cashColl = 0, digitalColl = 0;
    salesHistory.forEach(tx => {
        totalRev += tx.totals.grandTotal;
        if (tx.method === 'cash') cashColl += tx.totals.grandTotal;
        else digitalColl += tx.totals.grandTotal;
    });
    if (document.getElementById('kpi-total-revenue')) document.getElementById('kpi-total-revenue').innerText = "Rs. " + totalRev.toFixed(2);
    if (document.getElementById('kpi-cash-collected')) document.getElementById('kpi-cash-collected').innerText = "Rs. " + cashColl.toFixed(2);
    if (document.getElementById('kpi-digital-collected')) document.getElementById('kpi-digital-collected').innerText = "Rs. " + digitalColl.toFixed(2);
    if (document.getElementById('kpi-invoice-count')) document.getElementById('kpi-invoice-count').innerText = salesHistory.length;
}

function renderHistoryTable() {
    const tbody = document.getElementById('sales-ledger-body'), countBadge = document.getElementById('ledger-count-badge');
    if (countBadge) countBadge.innerText = salesHistory.length + " Verified Payment Transactions Recorded Today";
    if (!tbody) return;
    if (salesHistory.length === 0) {
        tbody.innerHTML = `<tr id="empty-ledger-row"><td colspan="7" class="py-12 text-center text-slate-400 font-medium text-base">No verified payment records logged today. Completed sales will automatically appear here.</td></tr>`;
        return;
    }
    let html = '';
    salesHistory.forEach((tx) => {
        let channelBadge = tx.channel === 'corporate' ? `<span class="px-3 py-1 bg-purple-50 text-purple-800 text-xs font-bold rounded-lg border border-purple-200 shadow-2xs">Corporate B2B</span>` : (tx.channel === 'clinic' ? `<span class="px-3 py-1 bg-blue-50 text-blue-800 text-xs font-bold rounded-lg border border-blue-200 shadow-2xs">Clinic Ward</span>` : `<span class="px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-lg border border-emerald-200 shadow-2xs">Retail Patient</span>`);
        html += `<tr class="hover:bg-slate-50/60 transition ledger-row" data-search="${tx.id} ${tx.clientName.toLowerCase()}"><td class="py-5 px-6 font-black text-slate-900 text-base">#${tx.id}</td><td class="py-5 px-6 font-bold text-slate-500 text-sm">${tx.dateTime}</td><td class="py-5 px-6 font-bold text-slate-900 text-base">${tx.clientName}</td><td class="py-5 px-6">${channelBadge} <span class="text-xs text-slate-400 font-bold uppercase ml-1">(${tx.method})</span></td><td class="py-5 px-6 font-extrabold text-emerald-700">${tx.itemsCount} units</td><td class="py-5 px-6 font-black text-slate-900 text-lg">Rs. ${tx.totals.grandTotal.toFixed(2)}</td><td class="py-5 px-6 text-center"><div class="flex items-center justify-center space-x-2"><button type="button" onclick="viewHistoricalInvoice('${tx.id}')" title="View & Print Slip" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-xs rounded-xl transition border border-slate-200 flex items-center space-x-1"><svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg><span>Slip</span></button><button type="button" onclick="deleteHistoryTransaction(event, '${tx.id}')" title="Delete Voucher" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition border border-rose-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div></td></tr>`;
    });
    tbody.innerHTML = html;
}

function filterLedgerTable() {
    const searchInput = document.getElementById('ledger-search');
    if (!searchInput) return;
    const query = searchInput.value.toLowerCase().trim();
    document.querySelectorAll('.ledger-row').forEach(row => {
        row.style.display = row.getAttribute('data-search').includes(query) ? '' : 'none';
    });
}

function viewHistoricalInvoice(txId) {
    const tx = salesHistory.find(t => t.id === txId);
    if (!tx) return;
    
    const confirmBtn = document.getElementById('btn-confirm-order');
    if (confirmBtn) confirmBtn.classList.add('hidden');
    
    const modalTitle = document.getElementById('invoice-modal-title'), subtitle = document.getElementById('invoice-subtitle');
    if (modalTitle) modalTitle.innerText = "City Pharmacy Store";
    if (subtitle) {
        if (tx.channel === 'corporate') { subtitle.innerText = "Hospital Partner Credit Voucher"; }
        else if (tx.channel === 'clinic') { subtitle.innerText = "Clinical Patient Receipt"; }
        else { subtitle.innerText = "Verified Counter Cash Receipt"; }
    }
    
    if (document.getElementById('slip-client-name')) document.getElementById('slip-client-name').innerText = tx.clientName;
    if (document.getElementById('slip-payment-method')) document.getElementById('slip-payment-method').innerText = "Gateway: " + tx.method.toUpperCase();
    if (document.getElementById('slip-invoice-id')) document.getElementById('slip-invoice-id').innerText = "#" + tx.id;
    if (document.getElementById('slip-date')) document.getElementById('slip-date').innerText = tx.dateTime;
    
    let slipHtml = '';
    tx.items.forEach(item => {
        slipHtml += `<tr><td class="py-3 px-4 font-black text-slate-900">${item.name}</td><td class="py-3 px-4 font-bold">${item.qty}</td><td class="py-3 px-4">Rs. ${item.price.toFixed(2)}</td><td class="py-3 px-4 text-right font-black text-slate-900">Rs. ${(item.price * item.qty).toFixed(2)}</td></tr>`;
    });
    if (document.getElementById('slip-items-list')) document.getElementById('slip-items-list').innerHTML = slipHtml;
    
    if (document.getElementById('slip-subtotal')) document.getElementById('slip-subtotal').innerText = "Rs. " + tx.totals.subtotal.toFixed(2);
    if (document.getElementById('slip-discount')) document.getElementById('slip-discount').innerText = "-Rs. " + tx.totals.discount.toFixed(2);
    if (document.getElementById('slip-tax')) document.getElementById('slip-tax').innerText = "+Rs. " + tx.totals.tax.toFixed(2);
    if (document.getElementById('slip-grand-total')) document.getElementById('slip-grand-total').innerText = "Rs. " + tx.totals.grandTotal.toFixed(2);
    
    const modal = document.getElementById('invoice-modal');
    if (modal) modal.classList.remove('hidden');
}

function deleteHistoryTransaction(event, txId) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const cleanId = String(txId).replace(/^#/, '').trim();
    if (confirm('Are you sure you want to delete transaction record #' + cleanId + '?')) {
        salesHistory = salesHistory.filter(t => String(t.id).replace(/^#/, '').trim() !== cleanId);
        updateKPICards(); 
        renderHistoryTable();
        filterLedgerTable();
    }
}

// Fixed 1-Page Clean Thermal Receipt Printer
function printCurrentSlip() {
    const elem = document.getElementById('printable-slip-area');
    const headerLogo = document.querySelector('#invoice-modal img') ? document.querySelector('#invoice-modal img').src : '';
    const headerSubtitle = document.getElementById('invoice-subtitle') ? document.getElementById('invoice-subtitle').innerText : 'Verified Counter Cash Receipt';
    if (!elem) return alert('Slip content not found.');

    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0px';
    iframe.style.height = '0px';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Receipt Slip</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                @page { size: auto; margin: 5mm; }
                body { font-family: ui-sans-serif, system-ui, sans-serif; background: white; color: black; margin: 0; padding: 0; }
            </style>
        </head>
        <body class="p-2">
            <div class="max-w-md mx-auto p-4 border border-slate-300 rounded-2xl bg-white space-y-4">
                <div class="border-b border-slate-200 pb-3">
                    ${headerLogo ? `<img src="${headerLogo}" class="h-10 w-auto object-contain mb-1">` : ''}
                    <p class="text-xs text-slate-500 font-bold text-left">${headerSubtitle}</p>
                </div>
                ${elem.innerHTML}
            </div>
        </body>
        </html>
    `);
    doc.close();

    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        setTimeout(() => document.body.removeChild(iframe), 1000);
    }, 400);
}

function printPaymentHistory() {
    if (salesHistory.length === 0) return alert('No payment history records to print.');
    
    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0px';
    iframe.style.height = '0px';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment History - City Pharmacy</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                @page { size: A4 landscape; margin: 10mm; }
                body { font-family: ui-sans-serif, system-ui, sans-serif; background: white; padding: 15px; }
            </style>
        </head>
        <body>
            <div class="mb-4 text-center border-b pb-3">
                <h1 class="text-2xl font-black text-slate-900">City Pharmacy Store</h1>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1">Today's Verified Payment Transactions Ledger</p>
            </div>
            <table class="w-full text-left text-xs border-collapse border border-slate-200">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 font-extrabold border-b">
                        <th class="p-3 border-r">Invoice ID</th>
                        <th class="p-3 border-r">Date & Time</th>
                        <th class="p-3 border-r">Patient / Client Name</th>
                        <th class="p-3 border-r">Channel & Payment Method</th>
                        <th class="p-3 border-r">Qty</th>
                        <th class="p-3">Grand Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    ${salesHistory.map(tx => `
                        <tr>
                            <td class="p-3 font-black border-r">#${tx.id}</td>
                            <td class="p-3 border-r">${tx.dateTime}</td>
                            <td class="p-3 font-bold border-r">${tx.clientName}</td>
                            <td class="p-3 border-r">${tx.channel.toUpperCase()} (${tx.method.toUpperCase()})</td>
                            <td class="p-3 border-r font-bold">${tx.itemsCount} units</td>
                            <td class="p-3 font-black text-emerald-700">Rs. ${tx.totals.grandTotal.toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </body>
        </html>
    `);
    doc.close();

    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        setTimeout(() => document.body.removeChild(iframe), 1000);
    }, 400);
}

function downloadPaymentHistoryCSV() {
    if (salesHistory.length === 0) return alert('No payment records to export.');
    let csvContent = "data:text/csv;charset=utf-8,Invoice ID,Date Time,Patient Entity,Channel,Method,Items Count,Grand Total\n";    
    salesHistory.forEach(tx => {
        csvContent += `"${tx.id}","${tx.dateTime}","${tx.clientName}","${tx.channel}","${tx.method}","${tx.itemsCount}","${tx.totals.grandTotal.toFixed(2)}"\n`;
    });
    const link = document.createElement("a");
    link.setAttribute("href", encodeURI(csvContent));
    link.setAttribute("download", `Payment_History_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link); 
    link.click(); 
    document.body.removeChild(link);
}