<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $dir ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - City Pharmacy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', 'Noto Sans Arabic', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 flex">
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#0b1a40] via-[#12255a] to-[#1e3a8a] text-white p-14 flex-col justify-between">
        <div class="bg-white rounded-2xl px-5 py-3 w-fit shadow-lg">
            <img src="{{ asset('images/cityy_logo.png') }}" alt="City Pharmacy" class="h-12 w-auto">
        </div>
        <div>
            <h1 class="text-4xl font-extrabold leading-tight">{{ __('Run your pharmacy with total clarity.') }}</h1>
            <p class="mt-4 text-blue-100 text-lg max-w-md">{{ __("Billing, inventory, expiry tracking and insights, all in one fast, simple workspace.") }}</p>
        </div>
        <p class="text-sm text-blue-200/70">&copy; {{ date('Y') }} City Pharmacy</p>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <form method="POST" action="/locale" class="fixed top-4 end-4 z-10">@csrf
        <select name="locale" onchange="this.form.submit()" aria-label="{{ __('Language') }}" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-600">
            @foreach(config('locales') as $code => [$label])<option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $label }}</option>@endforeach
        </select></form>
        <form method="POST" action="/login" class="w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-sm p-8 sm:p-10">
            @csrf
            <img src="{{ asset('images/cityy_logo.png') }}" alt="City Pharmacy" class="h-10 w-auto mb-8 lg:hidden">
            <h2 class="text-2xl font-extrabold text-slate-900">{{ __("Welcome back") }}</h2>
            <p class="text-slate-500 mt-1 mb-8">{{ __("Sign in to continue to your pharmacy.") }}</p>

            <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __("Email") }}</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a] @error('email') border-red-400 @enderror">
            @error('email')<p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>@enderror

            <label class="block text-sm font-semibold text-slate-700 mt-5 mb-1.5">{{ __("Password") }}</label>
            <input type="password" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a8a]">

            <label class="flex items-center gap-2 mt-5 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300"> {{ __("Keep me signed in") }}
            </label>

            <button type="submit" class="mt-7 w-full py-3.5 rounded-xl bg-[#1e3a8a] hover:bg-[#172554] text-white font-bold shadow-md transition">{{ __("Sign in") }}</button>
        </form>
    </div>
</body>
</html>
