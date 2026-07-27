<header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <a href="/medicines" class="flex items-center">
            <img src="{{ asset('images/cityy_logo.png') }}" alt="City Pharmacy Logo" class="h-12 sm:h-14 w-auto object-contain mix-blend-multiply">
        </a>

        <nav class="hidden md:flex items-center space-x-10 text-base font-semibold text-slate-600">
            <a href="/medicines" class="{{ request()->is('medicines*') ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600' }} transition">Inventory</a>
            <a href="/suppliers" class="{{ request()->is('suppliers*') ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600' }} transition">Suppliers</a>
            <a href="/stock-alerts" class="{{ request()->is('stock-alerts*') ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600' }} transition">Stock Alerts</a>
            <a href="/sales-billing" class="{{ request()->is('sales-billing*') ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600' }} transition">Sales & Billing</a>
        </nav>

        <div class="flex items-center space-x-4">
            <span class="hidden sm:inline-block text-base font-semibold text-slate-700">Ubaid Ur Rehman</span>
            <div class="w-11 h-11 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-700 border border-slate-300 text-lg">U</div>
        </div>
    </div>
</header>