/* Counter speed-ups: barcode scan, held bills, returns, keyboard shortcuts.
   Relies on globals from sales-billing.js (cart, renderCart, setBillingType, csrfToken, ...). */

const $id = (x) => document.getElementById(x);
const jsonHeaders = { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' };
const money = (n) => CUR + ' ' + Number(n).toFixed(2);

function toast(msg, ok = true) {
    const t = document.createElement('div');
    t.textContent = msg;
    t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-[2000] px-5 py-3 rounded-xl text-white font-semibold shadow-2xl ' + (ok ? 'bg-slate-900' : 'bg-rose-600');
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2600);
}

/* ---------- Barcode / exact-name scan ---------- */
async function scanSubmit(code) {
    code = code.trim();
    if (!code) return;
    const res = await fetch('/pos/lookup?code=' + encodeURIComponent(code), { headers: { Accept: 'application/json' } });
    const data = await res.json().catch(() => ({}));
    if (!res.ok || !data.found) return toast(data.message || 'Not found', false);

    const existing = cart.find(i => i.id === data.id);
    const nextQty = (existing ? existing.qty : 0) + 1;
    if (nextQty > data.sellable) return toast(`${data.name}: only ${data.sellable} sellable unit(s) in stock.`, false);

    if (existing) existing.qty = nextQty; else cart.push({ id: data.id, name: data.name, price: data.price, qty: 1 });
    renderCart();
    toast(`Added ${data.name}`);
}

$id('scan_input')?.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    scanSubmit(e.target.value);
    e.target.value = '';
});

/* ---------- Held bills ---------- */
function currentBillPayload() {
    const v = (x) => $id(x)?.value || '';
    return {
        cart, mode: currentMode, discount_percent: v('discount_percent'),
        customer_name: v('customer_name'), customer_phone: v('customer_phone'),
    };
}

async function holdCurrentBill() {
    if (!cart.length) return toast('Cart is empty - nothing to hold.', false);
    const label = prompt('Label for this held bill (optional):', currentBillPayload().customer_name || '');
    if (label === null) return;
    const res = await fetch('/pos/hold', { method: 'POST', headers: jsonHeaders, body: JSON.stringify({ label, payload: currentBillPayload() }) });
    if (!res.ok) return toast('Could not hold bill.', false);
    clearCart();
    toast('Bill held. Recall it from "Held".');
    refreshHeldCount();
}

async function refreshHeldCount() {
    const res = await fetch('/pos/held', { headers: { Accept: 'application/json' } });
    if (!res.ok) return [];
    const list = await res.json();
    const badge = $id('held-count');
    if (badge) { badge.textContent = list.length; badge.classList.toggle('hidden', !list.length); }
    return list;
}

async function openHeldModal() {
    const list = await refreshHeldCount();
    const box = $id('held-list');
    box.replaceChildren();
    if (!list.length) {
        const p = document.createElement('p');
        p.className = 'py-8 text-center text-slate-500';
        p.textContent = 'No held bills.';
        box.appendChild(p);
    }
    list.forEach(h => {
        const row = document.createElement('div');
        row.className = 'flex items-center justify-between gap-3 py-3 border-b border-slate-100';
        const info = document.createElement('div');
        const t = document.createElement('p'); t.className = 'font-bold text-slate-900'; t.textContent = h.label;
        const s = document.createElement('p'); s.className = 'text-sm text-slate-500'; s.textContent = `${h.items} item(s) · ${h.held_at}${h.by ? ' · ' + h.by : ''}`;
        info.append(t, s);
        const btns = document.createElement('div'); btns.className = 'flex gap-2';
        const rec = document.createElement('button'); rec.type = 'button'; rec.textContent = 'Recall';
        rec.className = 'dropdown-btn px-4 py-2 bg-navy-600 hover:bg-navy-700 text-white text-sm font-bold rounded-xl';
        rec.onclick = () => resumeHeld(h.id);
        const del = document.createElement('button'); del.type = 'button'; del.textContent = 'Discard';
        del.className = 'dropdown-btn px-4 py-2 bg-rose-50 text-rose-700 text-sm font-bold rounded-xl';
        del.onclick = async () => { if (!confirm('Discard this held bill?')) return; await fetch('/pos/held/' + h.id, { method: 'DELETE', headers: jsonHeaders }); openHeldModal(); };
        btns.append(rec, del);
        row.append(info, btns);
        box.appendChild(row);
    });
    $id('held-modal').classList.remove('hidden');
}

async function resumeHeld(id) {
    if (cart.length && !confirm('Replace the current cart with the held bill?')) return;
    const res = await fetch(`/pos/held/${id}/resume`, { method: 'POST', headers: jsonHeaders });
    if (!res.ok) return toast('Could not recall bill.', false);
    const p = await res.json();
    cart = p.cart || [];
    renderCart();
    if (p.mode) setBillingType(p.mode);
    const set = (x, val) => { if ($id(x)) $id(x).value = val ?? ''; };
    set('discount_percent', p.discount_percent); set('customer_name', p.customer_name); set('customer_phone', p.customer_phone);
    calculateTotals();
    $id('held-modal').classList.add('hidden');
    refreshHeldCount();
    toast('Held bill recalled.');
}

/* ---------- Returns ---------- */
let returnOrder = null;

function openReturnModal(orderId) {
    returnOrder = (window.initialOrders || []).find(o => o.id === orderId);
    if (!returnOrder) return;
    const returned = returnOrder.returned_map || {};
    $id('return-title').textContent = `Return items - ${returnOrder.invoice_number}`;
    const box = $id('return-lines');
    box.replaceChildren();
    let any = false;
    (returnOrder.cart_items || []).forEach((line, i) => {
        const left = line.qty - (returned[i] || 0);
        if (left <= 0) return;
        any = true;
        const row = document.createElement('div');
        row.className = 'flex items-center justify-between gap-3 py-3 border-b border-slate-100';
        const info = document.createElement('div');
        const n = document.createElement('p'); n.className = 'font-bold text-slate-900'; n.textContent = line.name;
        const d = document.createElement('p'); d.className = 'text-sm text-slate-500'; d.textContent = `${money(line.price)} each · up to ${left} returnable`;
        info.append(n, d);
        const inp = document.createElement('input');
        inp.type = 'number'; inp.min = 0; inp.max = left; inp.value = 0; inp.dataset.index = i;
        inp.className = 'w-24 px-3 py-2 border border-slate-300 rounded-xl text-right';
        inp.oninput = updateRefundPreview;
        row.append(info, inp);
        box.appendChild(row);
    });
    if (!any) {
        const p = document.createElement('p'); p.className = 'py-8 text-center text-slate-500'; p.textContent = 'Everything on this invoice has already been returned.';
        box.appendChild(p);
    }
    $id('return-reason').value = '';
    updateRefundPreview();
    $id('return-modal').classList.remove('hidden');
}

function returnSelection() {
    return [...document.querySelectorAll('#return-lines input')]
        .map(i => ({ index: +i.dataset.index, qty: Math.min(+i.max, Math.max(0, parseInt(i.value) || 0)) }))
        .filter(r => r.qty > 0);
}

function updateRefundPreview() {
    if (!returnOrder) return;
    const ratio = returnOrder.subtotal > 0 ? returnOrder.total_amount / returnOrder.subtotal : 1;
    const sum = returnSelection().reduce((a, r) => a + returnOrder.cart_items[r.index].price * r.qty * ratio, 0);
    $id('return-refund').textContent = money(sum);
}

async function submitReturn() {
    const items = returnSelection();
    if (!items.length) return toast('Choose at least one item to return.', false);
    if (!confirm('Record this return and put the units back in stock?')) return;
    const res = await fetch(`/sales-billing/orders/${returnOrder.id}/return`, {
        method: 'POST', headers: jsonHeaders, body: JSON.stringify({ items, reason: $id('return-reason').value })
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) return toast(data.message || 'Return failed.', false);
    alert(`${data.message}\nRefund to customer: ${money(data.refund)}`);
    location.reload();
}

/* ---------- Keyboard shortcuts ---------- */
document.addEventListener('keydown', (e) => {
    if (e.key === 'F2') { e.preventDefault(); $id('scan_input')?.focus(); }
    else if (e.key === 'F4') { e.preventDefault(); if (cart.length) openInvoiceModal(); }
    else if (e.key === 'F8') { e.preventDefault(); holdCurrentBill(); }
    else if (e.key === 'F9') { e.preventDefault(); openHeldModal(); }
    else if (e.key === 'Escape') { ['held-modal', 'return-modal'].forEach(m => $id(m)?.classList.add('hidden')); }
});

refreshHeldCount();
$id('scan_input')?.focus();


/* ---------- Safety checks: interactions, allergies, controlled drugs, alternatives ---------- */
let safetyState = { warnings: [], alternatives: {} };
let safetyTimer = null;
window.rxInfo = null;

function scheduleSafetyCheck() { clearTimeout(safetyTimer); safetyTimer = setTimeout(runSafetyCheck, 250); }

async function runSafetyCheck() {
    const banner = $id('safety-banner');
    if (!banner) return;
    const items = cart.filter(i => Number.isFinite(+i.id)).map(i => ({ id: i.id, qty: i.qty }));
    if (!items.length) { safetyState = { warnings: [], alternatives: {} }; return renderSafety(); }
    try {
        const res = await fetch('/pos/check', { method: 'POST', headers: jsonHeaders, body: JSON.stringify({ phone: $id('customer_phone')?.value || '', items }) });
        if (!res.ok) return;
        const data = await res.json();
        safetyState = data;
        const nameInput = $id('customer_name');
        if (data.customer && nameInput && !nameInput.value.trim()) nameInput.value = data.customer.name;
        window.customerPoints = data.customer ? data.customer.points : 0;
        const row = $id('loyalty-row');
        if (row) {
            row.classList.toggle('hidden', !(LOYALTY_ON && window.customerPoints > 0));
            $id('points-available').textContent = window.customerPoints;
        }
        calculateTotals();
    } catch (e) { return; }
    renderSafety();
}

function renderSafety() {
    const banner = $id('safety-banner');
    banner.replaceChildren();
    const colors = { major: 'bg-red-50 border-red-300 text-red-800', moderate: 'bg-amber-50 border-amber-300 text-amber-800', info: 'bg-navy-50 border-navy-200 text-navy-800' };
    safetyState.warnings.forEach(w => {
        const box = document.createElement('div');
        box.className = 'px-4 py-3 rounded-xl border text-sm font-semibold ' + (colors[w.level] || colors.info);
        box.textContent = (w.level === 'major' ? '⚠ ' : '') + w.text;
        banner.appendChild(box);
    });
    Object.entries(safetyState.alternatives).forEach(([id, alts]) => {
        if (!alts.length) return;
        const box = document.createElement('div');
        box.className = 'px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm';
        const label = document.createElement('span'); label.className = 'font-semibold text-slate-600 mr-2'; label.textContent = 'Same medicine in stock:';
        box.appendChild(label);
        alts.forEach(a => {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'dropdown-btn mr-2 mt-1 px-3 py-1.5 rounded-lg bg-navy-50 text-navy-700 font-bold hover:bg-navy-100';
            b.textContent = `${a.name} · ${money(a.price)} (${a.sellable} left)`;
            b.onclick = () => swapToAlternative(+id, a);
            box.appendChild(b);
        });
        banner.appendChild(box);
    });
    banner.classList.toggle('hidden', !banner.children.length);
}

function swapToAlternative(oldId, alt) {
    const line = cart.find(i => +i.id === oldId);
    if (!line) return;
    const qty = Math.min(line.qty, alt.sellable);
    cart = cart.filter(i => +i.id !== oldId);
    const existing = cart.find(i => +i.id === alt.id);
    if (existing) existing.qty = Math.min(existing.qty + qty, alt.sellable); else cart.push({ id: alt.id, name: alt.name, price: alt.price, qty });
    renderCart();
    toast(`Swapped to ${alt.name}`);
}

// Re-check whenever the cart is redrawn or the phone changes.
const _renderCart = renderCart;
renderCart = function () { _renderCart.apply(this, arguments); scheduleSafetyCheck(); };
$id('customer_phone')?.addEventListener('input', scheduleSafetyCheck);

// Block-and-confirm before the invoice opens: serious warnings, and controlled-drug paperwork.
const _openInvoiceModal = openInvoiceModal;
openInvoiceModal = function () {
    const majors = safetyState.warnings.filter(w => w.level === 'major');
    if (majors.length && !confirm('SAFETY WARNING\n\n' + majors.map(w => '• ' + w.text).join('\n') + '\n\nContinue anyway?')) return;

    const controlled = cart.some(i => (window.stockDatabase || []).find(m => m.id === +i.id && m.is_controlled));
    window.rxInfo = null;
    if (controlled) {
        const prescriber = (prompt('Controlled drug in this bill.\nPrescriber name:') || '').trim();
        const number = prescriber ? (prompt('Prescription number:') || '').trim() : '';
        if (!prescriber || !number) return toast('Prescriber and prescription number are required for controlled drugs.', false);
        window.rxInfo = { prescriber, number };
    }
    return _openInvoiceModal.apply(this, arguments);
};
