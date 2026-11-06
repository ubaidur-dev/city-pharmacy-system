<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $dir ?? 'ltr' }}" class="{{ ($globalTheme->interface_mode ?? $theme->interface_mode ?? 'light') == 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'City Pharmacy Store')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { navy: {
                50:'#eff4fb',100:'#dbe6f5',200:'#b9cdea',300:'#8eaad9',400:'#5f80c4',
                500:'#2f55a8',600:'#1e3a8a',700:'#172e6e',800:'#12255a',900:'#0b1a40'
            } } } }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @php
        $currentTheme = $globalTheme ?? $theme ?? null;
        $selectedColor = $currentTheme->primary_color ?? 'navy';
        
        $colorMap = [
            'navy' => ['bg' => '#1e3a8a', 'hover' => '#172554', 'light_bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1e3a8a'],
            'indigo'  => ['bg' => '#4f46e5', 'hover' => '#4338ca', 'light_bg' => '#eef2ff', 'border' => '#c7d2fe', 'text' => '#3730a3'],
            'blue'    => ['bg' => '#2563eb', 'hover' => '#1d4ed8', 'light_bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1e40af'],
            'amber'   => ['bg' => '#f59e0b', 'hover' => '#d97706', 'light_bg' => '#fffbeb', 'border' => '#fde68a', 'text' => '#92400e'],
            'purple'  => ['bg' => '#7c3aed', 'hover' => '#6d28d9', 'light_bg' => '#faf5ff', 'border' => '#e9d5ff', 'text' => '#5b21b6'],
            'rose'    => ['bg' => '#e11d48', 'hover' => '#be123c', 'light_bg' => '#fff1f2', 'border' => '#fecdd3', 'text' => '#9f1239'],
        ];
        $activeColor = $colorMap[$selectedColor] ?? $colorMap['navy'];
    @endphp

    <script>
        const CUR = @json($cur), TAX_RATE = @json((float) $store->tax_rate),
              LOYALTY_ON = @json($store->loyalty_earn_per > 0), POINT_VALUE = @json((float) $store->loyalty_point_value);
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', 'Noto Sans Arabic', sans-serif; }
        html[dir=rtl] .text-left { text-align: right; }
        html[dir=rtl] .text-right { text-align: left; }

        :root {
            --primary-bg: {{ $activeColor['bg'] }};
            --primary-hover: {{ $activeColor['hover'] }};
            --alert-bg: {{ $activeColor['light_bg'] }};
            --alert-border: {{ $activeColor['border'] }};
            --alert-text: {{ $activeColor['text'] }};
        }

        /* 1. Global Buttons & Primary Highlights across all pages */
        .bg-navy-600, button[type="submit"]:not(.dropdown-btn) {
            background-color: var(--primary-bg) !important;
        }
        .hover\:bg-navy-700:hover, button[type="submit"]:not(.dropdown-btn):hover {
            background-color: var(--primary-hover) !important;
        }
        .text-navy-600, svg.text-navy-600 {
            color: var(--primary-bg) !important;
        }
        .border-navy-600, .border-navy-500 {
            border-color: var(--primary-bg) !important;
        }

        /* 2. Dynamic Alert Message Box */
        .theme-success-msg {
            background-color: var(--alert-bg) !important;
            border-color: var(--alert-border) !important;
            color: var(--alert-text) !important;
        }

        /* 3. Global Dark Mode Rules */
        html.dark body {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }
        html.dark .bg-white {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark .text-slate-900, html.dark .text-slate-800 {
            color: #f8fafc !important;
        }
        html.dark .text-slate-500 {
            color: #94a3b8 !important;
        }
        html.dark input, html.dark select {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        html.dark .bg-slate-50, html.dark thead { background-color: #172033 !important; }
        html.dark .border-slate-100, html.dark .border-slate-200, html.dark .border-slate-300, html.dark .divide-slate-100 > * { border-color: #334155 !important; }
        html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-800 { color: #e2e8f0 !important; }
        html.dark .hover\:bg-slate-50:hover { background-color: #243049 !important; }
        html.dark .bg-navy-50, html.dark .bg-navy-100 { background-color: #1e2d52 !important; }
        html.dark .text-navy-600, html.dark .text-navy-700 { color: #93b4f5 !important; }
        html.dark select { background-color: #0f172a !important; color: #f8fafc !important; border-color: #334155 !important; }
        /* The navy logo turns white on dark backgrounds so it stays readable. */
        html.dark img[alt="City Pharmacy Logo"], html.dark img[alt="City Pharmacy"] { filter: brightness(0) invert(1); mix-blend-mode: normal !important; }
        @media print {
            header, footer, .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            main { max-width: 100% !important; padding: 0 !important; }
        }
        html.dark .bg-slate-50\/50 {
            background-color: rgba(15, 23, 42, 0.5) !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen text-base transition-colors duration-300">

    @include('layouts.header')
    @include('layouts.palette')

    <main class="flex-grow max-w-[95%] w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>