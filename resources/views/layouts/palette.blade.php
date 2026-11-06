{{-- Global quick search: Ctrl/Cmd+K or "/" anywhere. --}}
<div x-data="palette()" x-cloak
     @keydown.window.prevent.ctrl.k="toggle()" @keydown.window.prevent.meta.k="toggle()"
     @keydown.window="if ($event.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes($event.target.tagName)) { $event.preventDefault(); toggle(true) }"
     @open-palette.window="toggle(true)">
    <div x-show="open" class="fixed inset-0 z-[1000] flex items-start justify-center pt-[12vh] px-4" style="display:none">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden" @keydown.escape.window="open = false">
            <div class="flex items-center gap-3 px-6 border-b border-slate-100">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input x-ref="input" x-model="q" @input.debounce.150ms="search()"
                       @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="go()"
                       placeholder="Search medicines, brands, companies, or jump to a page…"
                       class="flex-1 py-5 text-lg bg-transparent outline-none text-slate-900 placeholder-slate-400">
                <kbd class="text-xs font-semibold text-slate-400 border border-slate-200 rounded-md px-2 py-1">ESC</kbd>
            </div>
            <ul class="max-h-[50vh] overflow-y-auto py-2">
                <template x-for="(r, i) in results" :key="r.url + i">
                    <li>
                        <a :href="r.url" @mouseenter="active = i" :class="active === i ? 'bg-navy-50' : ''" class="flex items-center justify-between px-6 py-3 transition">
                            <span class="min-w-0">
                                <span class="block font-bold text-slate-900 truncate" x-text="r.name"></span>
                                <span class="block text-sm text-slate-500 truncate" x-text="r.sub"></span>
                            </span>
                            <span class="text-sm font-bold text-navy-700 whitespace-nowrap ml-4" x-text="r.meta"></span>
                        </a>
                    </li>
                </template>
                <li x-show="!results.length" class="px-6 py-8 text-center text-slate-500">No matches.</li>
            </ul>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex gap-4">
                <span>↑↓ navigate</span><span>↵ open</span><span>Medicines open their stock &amp; batches</span>
            </div>
        </div>
    </div>
</div>

<script>
function palette() {
    const pages = [
        { name: 'Dashboard', sub: 'Overview', url: '/dashboard', meta: 'Page' },
        { name: 'Inventory', sub: 'All medicines', url: '/medicines', meta: 'Page' },
        { name: 'New Sale', sub: 'Sales & Billing', url: '/sales-billing', meta: 'Page' },
        { name: 'Stock Alerts', sub: 'Empty, low and expiring stock', url: '/stock-alerts', meta: 'Page' },
        { name: 'Suppliers', sub: 'Companies you buy from', url: '/suppliers', meta: 'Page' },
    ];
    return {
        open: false, q: '', results: pages, active: 0,
        toggle(force) {
            this.open = force ?? !this.open;
            if (this.open) { this.q = ''; this.results = pages; this.active = 0; this.$nextTick(() => this.$refs.input.focus()); }
        },
        move(d) { this.active = (this.active + d + this.results.length) % Math.max(1, this.results.length); },
        go() { const r = this.results[this.active]; if (r) window.location = r.url; },
        async search() {
            const q = this.q.trim();
            if (!q) { this.results = pages; return; }
            const matchedPages = pages.filter(p => p.name.toLowerCase().includes(q.toLowerCase()));
            try {
                const res = await fetch('/search?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' } });
                const meds = (await res.json()).map(m => ({
                    name: m.name, sub: m.company + ' · exp ' + m.expiry,
                    meta: m.stock + ' in stock · ' + CUR + ' ' + m.price.toFixed(2), url: m.url
                }));
                this.results = [...meds, ...matchedPages];
            } catch (e) { this.results = matchedPages; }
            this.active = 0;
        }
    };
}
</script>
