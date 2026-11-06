@php
    $fieldClass = 'w-full px-5 py-4 text-lg border-2 border-slate-300 rounded-2xl transition '
        . 'focus:outline-none focus:border-[#1e3a8a] focus:ring-4 focus:ring-[#1e3a8a]/15';
    $labelClass = 'block text-base font-bold text-slate-800 mb-2';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $dir ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Sign in') }} - City Pharmacy</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', 'Noto Sans Arabic', sans-serif; }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            -webkit-text-fill-color: #0f172a;
        }
    </style>
</head>
<body class="min-h-screen flex bg-slate-50">

    <aside class="hidden lg:flex lg:w-1/2 flex-col justify-between p-14 text-white bg-gradient-to-br from-[#0b1a40] via-[#12255a] to-[#1e3a8a]">
        <img src="{{ asset('images/cityy_logo.png') }}" alt="City Pharmacy" class="h-16 w-auto self-start" style="filter: brightness(0) invert(1);">

        <div>
            <h1 class="text-4xl font-extrabold leading-tight">{{ __('Run your pharmacy with total clarity.') }}</h1>
            <p class="mt-4 max-w-md text-lg text-blue-100">
                {{ __('Billing, inventory, expiry tracking and insights, all in one fast, simple workspace.') }}
            </p>
        </div>

        <p class="text-sm text-blue-200/70">&copy; {{ date('Y') }} City Pharmacy</p>
    </aside>

    <main class="flex-1 flex items-center justify-center p-6">
        <form method="POST" action="/locale" class="fixed top-4 end-4 z-10">
            @csrf
            <select name="locale" onchange="this.form.submit()" aria-label="{{ __('Language') }}"
                    class="px-3 py-2 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl">
                @foreach (config('locales') as $code => [$label])
                    <option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        <form method="POST" action="/login" class="w-full max-w-xl p-8 sm:p-14 bg-white border border-slate-200 rounded-3xl shadow-xl">
            @csrf

            <img src="{{ asset('images/cityy_logo.png') }}" alt="City Pharmacy" class="h-14 w-auto mb-10 lg:hidden">

            <h2 class="text-4xl font-extrabold tracking-tight text-slate-900">{{ __('Welcome back') }}</h2>
            <p class="mt-2 mb-10 text-lg text-slate-500">{{ __('Sign in to continue to your pharmacy.') }}</p>

            <label for="email" class="{{ $labelClass }}">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   autocomplete="username" required autofocus
                   class="{{ $fieldClass }} @error('email') border-red-400 @enderror">
            @error('email')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <label for="password" class="{{ $labelClass }} mt-6">{{ __('Password') }}</label>
            <input id="password" type="password" name="password"
                   autocomplete="current-password" required
                   class="{{ $fieldClass }}">

            <label class="flex items-center gap-3 mt-6 text-base text-slate-600">
                <input type="checkbox" name="remember" class="w-5 h-5 rounded border-slate-300 accent-[#1e3a8a]">
                {{ __('Keep me signed in') }}
            </label>

            <button type="submit"
                    class="w-full mt-9 py-4 text-lg font-bold text-white bg-[#1e3a8a] hover:bg-[#172554] rounded-2xl shadow-lg shadow-[#1e3a8a]/25 transition">
                {{ __('Sign in') }}
            </button>
        </form>
    </main>

</body>
</html>
