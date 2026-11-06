<header x-data="{ menu: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Left: Logo -->
        <a href="/dashboard" class="flex items-center">
            <img src="{{ asset('images/cityy_logo.png') }}" alt="City Pharmacy Logo" class="h-12 sm:h-14 w-auto object-contain mix-blend-multiply">
        </a>

        <!-- Center: Navigation Links -->
        <nav class="hidden lg:flex items-center space-x-7 text-[15px] font-semibold text-slate-600">
            <a href="/dashboard" class="{{ request()->is('dashboard*') ? 'text-navy-600 font-bold' : 'hover:text-navy-600' }} transition ">{{ __("Dashboard") }}</a>
            <a href="/medicines" class="{{ request()->is('medicines*') ? 'text-navy-600 font-bold' : 'hover:text-navy-600' }} transition ">{{ __("Inventory") }}</a>
            <a href="/customers" class="{{ request()->is('customers*') ? 'text-navy-600 font-bold' : 'hover:text-navy-600' }} transition ">{{ __("Customers") }}</a>
            @if(auth()->user()->role === 'owner')<a href="/purchasing" class="{{ request()->is('purchasing*') ? 'text-navy-600 font-bold' : 'hover:text-navy-600' }} transition ">{{ __("Purchasing") }}</a>@endif
            <a href="/stock-alerts" class="{{ request()->is('stock-alerts*') ? 'text-navy-600 font-bold' : 'hover:text-navy-600' }} transition ">{{ __("Stock Alerts") }}</a>
            <a href="/sales-billing" class="{{ request()->is('sales-billing*') ? 'text-navy-600 font-bold' : 'hover:text-navy-600' }} transition ">{{ __("Sales & Billing") }}</a>
            @if(auth()->user()->role === 'owner')<a href="/reports" class="{{ request()->is('reports*') ? 'text-navy-600 font-bold' : 'hover:text-navy-600' }} transition ">{{ __("Reports") }}</a>@endif
        </nav>

        <!-- Right: Dynamic Profile Dropdown -->
        <div class="flex items-center space-x-4 relative">
            <button type="button" @click="menu = !menu" class="dropdown-btn lg:hidden p-2.5 rounded-xl border border-slate-200 bg-white text-slate-600" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <form method="POST" action="/locale" class="hidden sm:block">
                @csrf
                <select name="locale" onchange="this.form.submit()" aria-label="{{ __('Language') }}" class="dropdown-btn px-2 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-600 cursor-pointer">
                    @foreach(config('locales') as $code => [$label])<option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $label }}</option>@endforeach
                </select>
            </form>
            <button type="button" onclick="window.dispatchEvent(new Event('open-palette'))" class="hidden lg:flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-sm text-slate-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                {{ __("Quick search") }} <kbd class="text-xs font-semibold border border-slate-300 rounded px-1.5 py-0.5 bg-white">Ctrl K</kbd>
            </button>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex items-center space-x-3 focus:outline-none hover:bg-slate-50 p-2 rounded-2xl transition">
                    
                    <!-- Text on the left side of the icon -->
                    <div class="hidden sm:block text-right">
                        <span class="block text-base font-semibold text-slate-700 leading-tight">{{ auth()->user()->name }}</span>
                        <span class="block text-xs font-medium text-navy-600">{{ auth()->user()->role === 'owner' ? __('Owner') : __('Cashier') }}</span>
                    </div>

                    <!-- Profile Image/Icon with Dynamic First Letter -->
                    <div class="w-11 h-11 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-lg border border-slate-300 shadow-sm overflow-hidden">
                        @php($avatar = \App\Models\AdminSetting::value('profile_image'))
                        @if(!empty($avatar))
                            <img src="{{ asset('storage/' . $avatar) }}" alt="Admin" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(trim(auth()->user()->name), 0, 1)) }}
                        @endif
                    </div>
                    
                    <!-- Small Arrow Icon with smooth rotation -->
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Dropdown Box -->
                <div x-show="open" 
                     @click.away="open = false" 
                     x-cloak
                     style="display: none;"
                     class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-slate-100 py-3 z-[999] text-left">
                    <div class="px-5 py-3 border-b border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ __("Signed in as") }}</p>
                        <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-2">
                        @if(auth()->user()->role === 'owner')
                        <a href="/admin/profile" class="flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>{{ __("Profile Settings") }}</span>
                        </a>
                        @endif
                        @if(auth()->user()->role === 'owner')
                        <a href="/admin/security" class="flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>{{ __("Security") }}</span>
                        </a>
                        @endif
                        @if(auth()->user()->role === 'owner')
                        <a href="/compliance/controlled" class="flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"><span class="w-4 text-center text-slate-500">℞</span><span>{{ __("Controlled Drug Register") }}</span></a>
                        <a href="/admin/audit" class="flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"><span class="w-4 text-center text-slate-500">≡</span><span>{{ __("Activity Log") }}</span></a>
                        <a href="/suppliers" class="flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"><span class="w-4 text-center text-slate-500">⌂</span><span>{{ __("Company Stock View") }}</span></a>
                        <a href="/admin/store" class="flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l1-5h16l1 5M3 9v11h18V9M3 9h18M9 20v-6h6v6"/></svg>
                            <span>{{ __("Store Settings") }}</span>
                        </a>
                        @endif
                        @if(auth()->user()->role === 'owner')<a href="/admin/theme-preferences" class="flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            <span>{{ __("Theme & Color") }}</span>
                        </a>@endif
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-btn w-full flex items-center space-x-3 px-5 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 transition text-left border-0 outline-none focus:outline-none bg-transparent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>{{ __("Sign Out") }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <nav x-show="menu" x-cloak style="display:none" class="lg:hidden border-t border-slate-200 bg-white px-4 py-3 grid grid-cols-2 gap-2 text-base font-semibold text-slate-700">
        <a href="/dashboard" class="px-4 py-3 rounded-xl {{ request()->is('dashboard*') ? 'bg-navy-50 text-navy-700 font-bold' : 'hover:bg-slate-50' }}">{{ __("Dashboard") }}</a>
        <a href="/medicines" class="px-4 py-3 rounded-xl {{ request()->is('medicines*') ? 'bg-navy-50 text-navy-700 font-bold' : 'hover:bg-slate-50' }}">{{ __("Inventory") }}</a>
        <a href="/customers" class="px-4 py-3 rounded-xl {{ request()->is('customers*') ? 'bg-navy-50 text-navy-700 font-bold' : 'hover:bg-slate-50' }}">{{ __("Customers") }}</a>
        @if(auth()->user()->role === 'owner')<a href="/purchasing" class="px-4 py-3 rounded-xl {{ request()->is('purchasing*') ? 'bg-navy-50 text-navy-700 font-bold' : 'hover:bg-slate-50' }}">{{ __("Purchasing") }}</a>@endif
        <a href="/stock-alerts" class="px-4 py-3 rounded-xl {{ request()->is('stock-alerts*') ? 'bg-navy-50 text-navy-700 font-bold' : 'hover:bg-slate-50' }}">{{ __("Stock Alerts") }}</a>
        <a href="/sales-billing" class="px-4 py-3 rounded-xl {{ request()->is('sales-billing*') ? 'bg-navy-50 text-navy-700 font-bold' : 'hover:bg-slate-50' }}">{{ __("Sales & Billing") }}</a>
        @if(auth()->user()->role === 'owner')<a href="/reports" class="px-4 py-3 rounded-xl {{ request()->is('reports*') ? 'bg-navy-50 text-navy-700 font-bold' : 'hover:bg-slate-50' }}">{{ __("Reports") }}</a>@endif
    </nav>
</header>