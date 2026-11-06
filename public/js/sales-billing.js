const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
let cart = [], currentMode = '', selectedPaymentMethod = '', activeSelectedMed = null;

const formatPhoneNumber = (p) => { if (!p) return ''; let c = ('' + p).replace(/\D/g, ''); return c.length === 11 ? c.replace(/(\d{4})(\d{7})/, '$1-$2') : p; };

function getSelectedBillingInfo() {
    if (!currentMode) {
        return { ok: false, message: 'Please select a billing channel first.' };
    }

    if (currentMode === 'customer') {
        const name = document.getElementById('customer_name')?.value.trim() || '';
        const phone = document.getElementById('customer_phone')?.value.trim() || '';
        if (!name || !phone) {
            return { ok: false, message: 'Patient name and phone number are required.' };
        }
        return { ok: true, name, phone, contact: '', reference: '' };
    }

    if (currentMode === 'corporate') {
        const name = document.getElementById('corporate_name')?.value.trim() || '';
        const phone = document.getElementById('corporate_phone')?.value.trim() || '';
        const contact = document.getElementById('corporate_contact')?.value.trim() || '';
        const reference = document.getElementById('corporate_reference')?.value.trim() || '';
        if (!name || !phone || !contact) {
            return { ok: false, message: 'Company name, corporate contact person, and phone are required.' };
        }
        return { ok: true, name, phone, contact, reference };
    }

    const name = document.getElementById('clinic_name')?.value.trim() || '';
    const phone = document.getElementById('clinic_phone')?.value.trim() || '';
    const contact = document.getElementById('clinic_contact')?.value.trim() || '';
    const reference = document.getElementById('clinic_reference')?.value.trim() || '';
    if (!name || !phone || !contact) {
        return { ok: false, message: 'Clinic name, contact person, and phone are required.' };
    }

    return { ok: true, name, phone, contact, reference };
}

function getSelectedPaymentInfo() {
    if (!selectedPaymentMethod) {
        return { ok: false, message: 'Please select a payment method first.' };
    }

    if (selectedPaymentMethod === 'cash') {
        const tendered = parseFloat(document.getElementById('cash_tendered')?.value || '0');
        if (!document.getElementById('cash_tendered') || isNaN(tendered) || tendered <= 0) {
            return { ok: false, message: 'Please enter the amount received from the patient.' };
        }
        return { ok: true, paid_amount: tendered, cash_received: tendered, reference: '', details: { cash_received: tendered } };
    }

    if (selectedPaymentMethod === 'card') {
        // Card payments are processed externally (POS terminal). Cashier only selects method.
        return { ok: true, paid_amount: 0, cash_received: 0, reference: '', details: [] };
    }

    if (selectedPaymentMethod === 'insurance') {
        const provider = document.getElementById('insurance_provider')?.value.trim() || '';
        const policyNo = document.getElementById('insurance_policy_number')?.value.trim() || '';
        if (!provider || !policyNo) {
            return { ok: false, message: 'Insurance provider and policy number are required.' };
        }
        return {
            ok: true,
            paid_amount: 0,
            cash_received: 0,
            reference: policyNo,
            details: {
                insurance_provider: provider,
                insurance_policy_number: policyNo,
                insurance_claim_no: document.getElementById('insurance_claim_no')?.value.trim() || ''
            }
        };
    }

    // Digital wallet payments are processed externally; cashier selects the method only.
    if (selectedPaymentMethod === 'digital') {
        return { ok: true, paid_amount: 0, cash_received: 0, reference: '', details: [] };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const pInp = document.getElementById('customer_phone');
    if (pInp) pInp.addEventListener('input', (e) => { let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,7})/); e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2]; });
    document.addEventListener('click', (e) => {
        const d = document.getElementById('med_dropdown_menu'), s = document.getElementById('med_search_input');
        if (d && s && !d.contains(e.target) && !s.contains(e.target)) d.classList.add('hidden');
    });
    setBillingType('');
    setPaymentMethod('');
});

const openMedDropdown = () => {
    const dropdown = document.getElementById('med_dropdown_menu');
    if (!dropdown) return;
    dropdown.classList.remove('hidden');
    filterMedList();
};

function filterMedList() {
    const query = document.getElementById('med_search_input')?.value.trim().toLowerCase() || '';
    const items = document.querySelectorAll('#med_list_container > div');
    let visibleCount = 0;

    items.forEach(item => {
        const name = item.dataset.name || item.innerText.toLowerCase();
        const show = query === '' || name.includes(query);
        item.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    const dropdown = document.getElementById('med_dropdown_menu');
    if (!dropdown) return;

    if (visibleCount === 0) {
        if (!document.getElementById('med_no_results')) {
            const noMatch = document.createElement('div');
            noMatch.id = 'med_no_results';
            noMatch.className = 'p-4 text-sm text-slate-500 bg-slate-50';
            noMatch.innerText = 'No medicines match your search. Please try a different name.';
            dropdown.appendChild(noMatch);
        }
    } else {
        const existing = document.getElementById('med_no_results');
        if (existing) existing.remove();
    }
}

function selectStockMedicine(id, name, price) {
    document.getElementById('med_search_input').value = name;
    document.getElementById('selected_price').value = price.toFixed(2);
    document.getElementById('med_dropdown_menu').classList.add('hidden');
    activeSelectedMed = { id, name, price };
}

function setBillingType(type) {
    currentMode = type;
    ['customer', 'corporate', 'clinic'].forEach(ch => {
        const btn = document.getElementById(`btn-${ch}`); if (!btn) return;
        const active = ch === type;
        btn.className = active
            ? 'py-3.5 px-4 rounded-2xl border border-navy-500/80 bg-gradient-to-r from-navy-700/90 via-navy-600/90 to-navy-500/90 text-white font-black text-lg transition flex items-center justify-start space-x-2.5 shadow-md shadow-slate-900/15 ring-1 ring-navy-300/40 backdrop-blur-sm [&_svg]:w-6 [&_svg]:h-6 [&_svg]:stroke-[2.5] [&_svg]:text-white'
            : 'py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-700 font-bold text-base transition flex items-center justify-start space-x-2.5 hover:border-navy-300 hover:bg-navy-50/40';
    });

    ['customer-fields', 'corporate-fields', 'clinic-fields'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('hidden', `${id}` !== `${type}-fields`);
    });
}

function setPaymentMethod(method) {
    selectedPaymentMethod = method;
    ['cash', 'card', 'insurance', 'digital'].forEach(m => {
        const btn = document.getElementById('pay-' + m); if (!btn) return;
        const active = m === method;
        btn.className = active
            ? 'py-3.5 px-4 rounded-2xl border border-navy-500/80 bg-gradient-to-br from-navy-700/90 via-navy-600/90 to-navy-500/90 text-white text-lg font-black flex items-center justify-center space-x-2.5 transition shadow-md shadow-slate-900/15 scale-[1.01] backdrop-blur-sm [&_svg]:w-6 [&_svg]:h-6 [&_svg]:stroke-[2.5] [&_svg]:text-white'
            : 'py-3.5 px-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-700 text-base font-bold flex items-center justify-center space-x-2.5 transition hover:border-navy-300 hover:bg-navy-50/40 hover:text-navy-800';
    });

    ['cash-tender-box', 'card-payment-fields', 'insurance-payment-fields', 'digital-payment-fields'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            const shouldShow = (method === 'cash' && id === 'cash-tender-box') || (method === 'card' && id === 'card-payment-fields') || (method === 'insurance' && id === 'insurance-payment-fields') || (method === 'digital' && id === 'digital-payment-fields');
            el.classList.toggle('hidden', !shouldShow);
        }
    });
}

function addMedicineItem() {
    const name = document.getElementById('med_search_input').value.trim();
    const price = parseFloat(document.getElementById('selected_price').value);
    const qty = parseInt(document.getElementById('selected_qty').value);
    if (!name) return alert('Please select medicine.');
    if (isNaN(price) || price <= 0) return alert('Enter valid price.');
    if (isNaN(qty) || qty < 1) return alert('Enter valid quantity.');
    let id = activeSelectedMed ? activeSelectedMed.id : 'manual-' + Date.now();
    let existing = cart.find(i => i.name.toLowerCase() === name.toLowerCase());
    if (existing) existing.qty += qty; else cart.push({ id, name, price, qty });
    renderCart();
    document.getElementById('med_search_input').value = ''; document.getElementById('selected_price').value = ''; document.getElementById('selected_qty').value = '1'; activeSelectedMed = null;
}

const removeFromCart = (i) => { cart.splice(i, 1); renderCart(); };

function clearCart() {
    cart = []; renderCart();
    ['discount_percent', 'redeem_points', 'customer_name', 'customer_phone', 'corporate_name', 'corporate_phone', 'corporate_contact', 'corporate_reference', 'clinic_name', 'clinic_phone', 'clinic_contact', 'clinic_reference', 'cash_tendered', 'insurance_provider', 'insurance_policy_number', 'insurance_claim_no'].forEach(id => { if (document.getElementById(id)) document.getElementById(id).value = ''; });
    if (document.getElementById('change_due_display')) document.getElementById('change_due_display').innerText = CUR + " 0.00";
    setBillingType('');
    setPaymentMethod('');
}

function calculateTotals() {
    let subtotal = cart.reduce((acc, i) => acc + (i.price * i.qty), 0);
    const discountPct = Math.min(100, Math.max(0, parseFloat(document.getElementById('discount_percent')?.value) || 0));
    let discount = subtotal * discountPct / 100, tax = (subtotal - discount) * TAX_RATE / 100, grandTotal = Math.max(0, subtotal - discount + tax);
    const redeemPts = LOYALTY_ON ? Math.min(Math.max(0, parseInt(document.getElementById('redeem_points')?.value) || 0), window.customerPoints || 0, POINT_VALUE > 0 ? Math.floor(grandTotal / POINT_VALUE) : 0) : 0;
    grandTotal = Math.max(0, grandTotal - redeemPts * POINT_VALUE);
    if (document.getElementById('summary-redeem')) document.getElementById('summary-redeem').innerText = "-" + CUR + " " + (redeemPts * POINT_VALUE).toFixed(2);
    if (document.getElementById('summary-subtotal')) document.getElementById('summary-subtotal').innerText = CUR + " " + subtotal.toFixed(2);
    if (document.getElementById('summary-discount')) document.getElementById('summary-discount').innerText = "-" + CUR + " " + discount.toFixed(2);
    if (document.getElementById('summary-tax')) document.getElementById('summary-tax').innerText = "+" + CUR + " " + tax.toFixed(2);
    if (document.getElementById('grand-total-display')) document.getElementById('grand-total-display').innerText = CUR + " " + grandTotal.toFixed(2);
    const cashTenderedInput = document.getElementById('cash_tendered'), changeDueDisplay = document.getElementById('change_due_display');
    if (cashTenderedInput && changeDueDisplay) {
        let tendered = parseFloat(cashTenderedInput.value) || 0, change = tendered - grandTotal;
        changeDueDisplay.innerText = CUR + " " + (change >= 0 ? change.toFixed(2) : "0.00");
    }
    return { subtotal, discount, tax, grandTotal };
}

function renderCart() {
    const tbody = document.getElementById('cart-items-list'), count = document.getElementById('cart-item-count');
    if (count) count.innerText = cart.length + " item(s) added"; if (!tbody) return;
    if (!cart.length) {
        tbody.innerHTML = `<tr id="empty-cart-row"><td colspan="5" class="py-12 text-center text-slate-400 font-medium text-base">Cart is empty. Select a medicine above.</td></tr>`;
        calculateTotals(); return;
    }
    tbody.innerHTML = cart.map((item, index) => `
        <tr class="hover:bg-slate-50 transition">
            <td class="py-4 px-4 font-black text-slate-700">${index + 1}</td>
            <td class="py-4 px-6 font-black text-slate-900">${item.name}</td>
            <td class="py-4 px-6 font-bold text-slate-700">${CUR} ${item.price.toFixed(2)}</td>
            <td class="py-4 px-6 text-navy-700 font-black">${item.qty} units</td>
            <td class="py-4 px-6 font-black text-slate-900">${CUR} ${(item.price * item.qty).toFixed(2)}</td>
            <td class="py-4 px-6 text-center">
                <button type="button" onclick="removeFromCart(${index})" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-black rounded-xl transition border border-rose-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-9 0h14M10 11v6m4-6v6" />
                    </svg>
                    <span>Remove</span>
                </button>
            </td>
        </tr>
    `).join('');
    calculateTotals();
}

function openInvoiceModal() {
    const billingInfo = getSelectedBillingInfo();
    const paymentInfo = getSelectedPaymentInfo();
    if (!billingInfo.ok) return alert(billingInfo.message);
    if (!paymentInfo.ok) return alert(paymentInfo.message);
    if (!cart.length) return alert('Cart is empty. Please add items first.');

    document.getElementById('slip-client-name').innerText = billingInfo.name;
    document.getElementById('slip-client-phone').innerText = 'Phone: ' + billingInfo.phone;
    document.getElementById('slip-payment-method').innerText = 'Gateway: ' + selectedPaymentMethod.toUpperCase();
    document.getElementById('slip-date').innerText = new Date().toLocaleString();
    document.getElementById('slip-invoice-id').innerText = '#INV-' + Math.floor(100000 + Math.random() * 900000);
    const slipItemsList = document.getElementById('slip-items-list');
    if (slipItemsList) {
        slipItemsList.innerHTML = cart.map(item => `
            <tr class="border-b border-slate-100"><td class="py-2.5 px-3 font-bold text-slate-900">${item.name}</td><td class="py-2.5 px-2 text-center">${item.qty}</td><td class="py-2.5 px-2">${CUR} ${item.price.toFixed(2)}</td><td class="py-2.5 px-3 text-right font-extrabold text-slate-900">${CUR} ${(item.price * item.qty).toFixed(2)}</td></tr>
        `).join('');
    }
    document.getElementById('slip-subtotal').innerText = document.getElementById('summary-subtotal').innerText;
    document.getElementById('slip-discount').innerText = document.getElementById('summary-discount').innerText;
    document.getElementById('slip-tax').innerText = document.getElementById('summary-tax').innerText;
    document.getElementById('slip-grand-total').innerText = document.getElementById('grand-total-display').innerText;
    document.getElementById('invoice-modal')?.classList.remove('hidden');
}

const closeInvoiceModal = () => document.getElementById('invoice-modal')?.classList.add('hidden');

function printCurrentSlip() {
    const printContent = document.getElementById('printable-slip-area').innerHTML, originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent; window.print(); document.body.innerHTML = originalContent; location.reload();
}

function confirmPaymentAndSave() {
    const billingInfo = getSelectedBillingInfo();
    const paymentInfo = getSelectedPaymentInfo();
    if (!billingInfo.ok) return alert(billingInfo.message);
    if (!paymentInfo.ok) return alert(paymentInfo.message);

    const totals = calculateTotals();
    const payload = {
        patient_name: billingInfo.name,
        phone: billingInfo.phone,
        channel: currentMode,
        billing_type: currentMode,
        billing_contact: billingInfo.contact || '',
        billing_reference: billingInfo.reference || '',
        billing_details: {
            name: billingInfo.name,
            phone: billingInfo.phone,
            contact: billingInfo.contact || '',
            reference: billingInfo.reference || ''
        },
        payment_method: selectedPaymentMethod,
        payment_reference: paymentInfo.reference || '',
        payment_details: paymentInfo.details || {},
        cash_received: paymentInfo.cash_received || 0,
        paid_amount: paymentInfo.paid_amount || totals.grandTotal,
        total_items: cart.reduce((acc, item) => acc + item.qty, 0),
        redeem_points: parseInt(document.getElementById('redeem_points')?.value) || 0,
        discount_percent: parseFloat(document.getElementById('discount_percent')?.value) || 0,
        subtotal: totals.subtotal,
        discount: totals.discount,
        tax: totals.tax,
        total_amount: totals.grandTotal,
        rx_prescriber: window.rxInfo?.prescriber || '',
        rx_number: window.rxInfo?.number || '',
        cart_items: cart
    };

    fetch('/sales-billing/generate', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            throw new Error(data.message || 'DB Error');
        }
        return data;
    })
    .then(data => { alert(data.message || 'Order saved to Database successfully!'); closeInvoiceModal(); clearCart(); location.reload(); })
    .catch(err => { console.error(err); alert(err.message || 'Failed to save order in DB.'); });
}

function reprintOrderInvoice(orderId) {
    const ordersList = window.initialOrders || [], targetOrder = ordersList.find(o => o.id === orderId);
    if (!targetOrder) return alert('Invoice record not found!');
    document.getElementById('slip-client-name').innerText = targetOrder.patient_name || 'Walk-in Patient';
    document.getElementById('slip-client-phone').innerText = 'Phone: ' + (targetOrder.phone || 'No phone provided');
    document.getElementById('slip-payment-method').innerText = 'Gateway: ' + (targetOrder.payment_method ? targetOrder.payment_method.toUpperCase() : 'CASH');
    document.getElementById('slip-date').innerText = targetOrder.created_at ? new Date(targetOrder.created_at).toLocaleString() : new Date().toLocaleString();
    document.getElementById('slip-invoice-id').innerText = targetOrder.invoice_number || ('#INV-' + targetOrder.id);
    const slipItemsList = document.getElementById('slip-items-list');
    if (slipItemsList) {
        let items = [];
        try { items = typeof targetOrder.cart_items === 'string' ? JSON.parse(targetOrder.cart_items) : (targetOrder.cart_items || []); } catch (e) { items = []; }
        if (items.length > 0) {
            slipItemsList.innerHTML = items.map(item => `
                <tr class="border-b border-slate-100"><td class="py-2.5 px-3 font-bold text-slate-900">${item.name}</td><td class="py-2.5 px-2 text-center">${item.qty}</td><td class="py-2.5 px-2">${CUR} ${parseFloat(item.price).toFixed(2)}</td><td class="py-2.5 px-3 text-right font-extrabold text-slate-900">${CUR} ${(item.price * item.qty).toFixed(2)}</td></tr>
            `).join('');
        } else {
            slipItemsList.innerHTML = `<tr><td colspan="4" class="py-3 text-center text-slate-500 font-semibold">Standard Counter Sale Unit</td></tr>`;
        }
    }
    let subtotalAmt = parseFloat(targetOrder.subtotal) || 0;
    let discountAmt = parseFloat(targetOrder.discount) || 0;
    let taxAmt = parseFloat(targetOrder.tax) || 0;
    let totalAmt = parseFloat(targetOrder.total_amount) || 0;

    document.getElementById('slip-subtotal').innerText = CUR + " " + subtotalAmt.toFixed(2);
    document.getElementById('slip-discount').innerText = "-" + CUR + " " + discountAmt.toFixed(2);
    document.getElementById('slip-tax').innerText = "+" + CUR + " " + taxAmt.toFixed(2);
    document.getElementById('slip-grand-total').innerText = CUR + " " + totalAmt.toFixed(2);

    const confirmBtn = document.getElementById('btn-confirm-order');
    if (confirmBtn) {
        confirmBtn.innerText = "Close Slip";
        confirmBtn.onclick = function() { closeInvoiceModal(); location.reload(); };
    }
    document.getElementById('invoice-modal')?.classList.remove('hidden');
}

// 3. Export Payment History as CSV (Dynamic Function)
function downloadPaymentHistoryCSV() {
    let csv = [];
    let rows = document.querySelectorAll("#paymentHistorySection tr, table tr"); 
    if (!rows || rows.length === 0) {
        alert("No records found to export.");
        return;
    }
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        csv.push(row.join(","));
    }
    let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    let downloadLink = document.createElement("a");
    downloadLink.download = "City_Pharmacy_Payment_History.csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}


// 4. Professional Custom-Ordered Print Payment History Ledger
function printPaymentHistory() {
    let rows = document.querySelectorAll('#payment-history-table tbody tr, table tbody tr');
    
    // Filter valid rows using dataset attributes from Blade
    let validRows = Array.from(rows).filter(row => row.dataset && row.dataset.invoice);
    
    if (validRows.length === 0) {
        alert("No payment history found to print.");
        return;
    }

    let printWindow = window.open('', '', 'height=750,width=1150');
    printWindow.document.write('<html><head><title></title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: A4 landscape; margin: 10mm; }');
    printWindow.document.write('body { font-family: "Inter", system-ui, -apple-system, sans-serif; color: #1e293b; background: #fff; margin: 0; padding: 0; }');
    
    printWindow.document.write('.print-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #1e3a8a; padding-bottom: 12px; margin-bottom: 20px; }');
    printWindow.document.write('.brand-area { display: flex; align-items: center; gap: 12px; }');
    printWindow.document.write('.brand-logo { height: 42px; width: auto; object-fit: contain; }');
    printWindow.document.write('.brand-title p { color: #696e74; margin: 2px 0 0; font-size: 20px; font-weight: bold; margin-top: 140px; margin-left:-150px; text-transform: uppercase; letter-spacing: 0.5px; }');
    printWindow.document.write('.meta-info { text-align: right; font-size: 11px; color: #64748b; font-weight: 600; }');
    
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 5px; }');
    printWindow.document.write('th, td { border: 1px solid #cbd5e1; padding: 7px 9px; text-align: left; font-size: 11px; }');
    printWindow.document.write('th { background-color: #f1f5f9; color: #334155; font-weight: 800; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; }');
    printWindow.document.write('td { color: #334155; font-weight: 600; }');
    
    printWindow.document.write('</style></head><body>');
    
    printWindow.document.write('<div class="print-header">');
    printWindow.document.write('<div class="brand-area">');
    printWindow.document.write('<img src="/images/cityy_logo.png" alt="City Pharmacy Logo" class="brand-logo">');
    printWindow.document.write('<div class="brand-title"><p>Official Payment History</div>');
    printWindow.document.write('</div>');
    printWindow.document.write('<div class="meta-info">Generated On: ' + new Date().toLocaleString() + '</div>');
    printWindow.document.write('</div>');
    
    let tableHTML = '<table><thead><tr>';
    tableHTML += '<th style="width: 35px; text-align: center;">#</th>';
    tableHTML += '<th>Invoice ID</th>';
    tableHTML += '<th>Date & Time</th>';
    tableHTML += '<th>Patient / Entity</th>';
    tableHTML += '<th>Phone Number</th>';
    tableHTML += '<th>Channel</th>';
    tableHTML += '<th style="text-align: center;">Items</th>';
    tableHTML += '<th style="text-align: right;">Subtotal</th>';
    tableHTML += '<th style="text-align: right;">Discount</th>';
    tableHTML += '<th style="text-align: right;">Tax</th>';
    tableHTML += '<th style="text-align: right;">Total Paid</th>';
    tableHTML += '</tr></thead><tbody>';
    
    let count = 1;
    validRows.forEach(row => {
        let d = row.dataset;
        if(d && d.invoice) {
            tableHTML += `<tr>`;
            tableHTML += `<td style="text-align: center; font-weight: 800;">${count++}</td>`;
            tableHTML += `<td style="font-weight: 900; color: #1e3a8a;">${d.invoice}</td>`;
            tableHTML += `<td>${d.date}</td>`;
            tableHTML += `<td style="font-weight: 800; color: #0f172a;">${d.patient}</td>`;
            tableHTML += `<td>${d.phone}</td>`;
            tableHTML += `<td style="text-transform: uppercase; font-size: 10px;">${d.channel}</td>`;
            tableHTML += `<td style="text-align: center; font-weight: 700;">${d.items}</td>`;
            tableHTML += `<td style="text-align: right;">${CUR} ${parseFloat(d.subtotal || 0).toFixed(2)}</td>`;
            tableHTML += `<td style="text-align: right; color: #e11d48;">- ${CUR} ${parseFloat(d.discount || 0).toFixed(2)}</td>`;
            tableHTML += `<td style="text-align: right; color: #0284c7;">+ ${CUR} ${parseFloat(d.tax || 0).toFixed(2)}</td>`;
            tableHTML += `<td style="text-align: right; font-weight: 900; color: #1e3a8a;">${CUR} ${parseFloat(d.total || 0).toFixed(2)}</td>`;
            tableHTML += `</tr>`;
        }
    });
    
    tableHTML += '</tbody></table>';
    printWindow.document.write(tableHTML);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}