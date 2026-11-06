@extends('layouts.app')

@section('title', 'Theme & Color Settings - City Pharmacy Store')

@section('content')

    <!-- Simple Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Theme & Appearance</h1>
        <p class="text-base text-slate-500 mt-2">Customize your workspace visual colors and display mode easily.</p>
    </div>

    <!-- Success Message (Dynamic Theme Alert Box) -->
    @if(session('success'))
        <div class="mb-6 p-4 theme-success-msg border rounded-2xl font-bold text-base shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Validation Error Messages -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-bold text-base shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/admin/theme/update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- Box 1: Primary Brand Theme -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
                <div class="flex items-center space-x-3 mb-4">
                    <!-- Standard Pro Color Palette Icon -->
                    <svg class="w-6 h-6 text-navy-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 12c0 4.142-3.358 7.5-7.5 7.5-1.5 0-2.25-.75-2.25-1.5 0-.414.168-.788.44-1.06.272-.272.44-.646.44-1.06 0-.828-.672-1.5-1.5-1.5H7.5A4.5 4.5 0 013 9.878C3 5.526 7.03 2 12 2s9.5 3.526 9.5 7.878z"></path>
                        <circle cx="7.5" cy="9.5" r="1" fill="currentColor"></circle>
                        <circle cx="10.5" cy="6.5" r="1" fill="currentColor"></circle>
                        <circle cx="14.5" cy="6.5" r="1" fill="currentColor"></circle>
                        <circle cx="17.5" cy="9.5" r="1" fill="currentColor"></circle>
                    </svg>
                    <h3 class="font-bold text-slate-900 text-lg">Primary Brand Theme</h3>
                </div>
                <p class="text-sm text-slate-500 mb-6">Choose a color highlight for buttons and selected menu items.</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <!-- 1. Navy -->
                    <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-navy-500 transition bg-slate-50/50">
                        <input type="radio" name="primary_color" value="navy" {{ ($theme->primary_color ?? 'navy') == 'navy' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-center space-x-3">
                            <span class="w-5 h-5 rounded-full bg-navy-600 flex items-center justify-center"></span>
                            <span class="font-bold text-slate-800 text-sm">Dark Blue</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-navy-600 rounded-2xl pointer-events-none opacity-0 peer-checked:opacity-100 transition"></div>
                    </label>

                    <!-- 2. Indigo -->
                    <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-500 transition bg-slate-50/50">
                        <input type="radio" name="primary_color" value="indigo" {{ ($theme->primary_color ?? '') == 'indigo' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-center space-x-3">
                            <span class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center"></span>
                            <span class="font-bold text-slate-800 text-sm">Indigo Purple</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-indigo-600 rounded-2xl pointer-events-none opacity-0 peer-checked:opacity-100 transition"></div>
                    </label>

                    <!-- 3. Royal Blue -->
                    <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-blue-500 transition bg-slate-50/50">
                        <input type="radio" name="primary_color" value="blue" {{ ($theme->primary_color ?? '') == 'blue' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-center space-x-3">
                            <span class="w-5 h-5 rounded-full bg-blue-600 flex items-center justify-center"></span>
                            <span class="font-bold text-slate-800 text-sm">Royal Blue</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-blue-600 rounded-2xl pointer-events-none opacity-0 peer-checked:opacity-100 transition"></div>
                    </label>

                    <!-- 4. Amber Gold -->
                    <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-amber-500 transition bg-slate-50/50">
                        <input type="radio" name="primary_color" value="amber" {{ ($theme->primary_color ?? '') == 'amber' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-center space-x-3">
                            <span class="w-5 h-5 rounded-full bg-amber-500 flex items-center justify-center"></span>
                            <span class="font-bold text-slate-800 text-sm">Amber Gold</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-amber-500 rounded-2xl pointer-events-none opacity-0 peer-checked:opacity-100 transition"></div>
                    </label>

                    <!-- 5. Purple -->
                    <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-purple-500 transition bg-slate-50/50">
                        <input type="radio" name="primary_color" value="purple" {{ ($theme->primary_color ?? '') == 'purple' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-center space-x-3">
                            <span class="w-5 h-5 rounded-full bg-purple-600 flex items-center justify-center"></span>
                            <span class="font-bold text-slate-800 text-sm">Deep Purple</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-purple-600 rounded-2xl pointer-events-none opacity-0 peer-checked:opacity-100 transition"></div>
                    </label>

                    <!-- 6. Rose Red -->
                    <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-rose-500 transition bg-slate-50/50">
                        <input type="radio" name="primary_color" value="rose" {{ ($theme->primary_color ?? '') == 'rose' ? 'checked' : '' }} class="sr-only peer">
                        <div class="flex items-center space-x-3">
                            <span class="w-5 h-5 rounded-full bg-rose-600 flex items-center justify-center"></span>
                            <span class="font-bold text-slate-800 text-sm">Rose Red</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-rose-600 rounded-2xl pointer-events-none opacity-0 peer-checked:opacity-100 transition"></div>
                    </label>
                </div>
            </div>

            <!-- Box 2: Interface Display Mode -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
                <div class="flex items-center space-x-3 mb-4">
                    <svg class="w-6 h-6 text-navy-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <h3 class="font-bold text-slate-900 text-lg">Display Mode</h3>
                </div>
                <p class="text-sm text-slate-500 mb-6">Select how light or dark you want your screen to appear.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Screen View Style</label>
                        <select name="interface_mode" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none bg-white font-medium text-slate-800 cursor-pointer">
                            <option value="light" {{ ($theme->interface_mode ?? 'light') == 'light' ? 'selected' : '' }}>Light Mode (Clean & Bright)</option>
                            <option value="dark" {{ ($theme->interface_mode ?? '') == 'dark' ? 'selected' : '' }}>Dark Mode (Night View)</option>
                            <option value="system" {{ ($theme->interface_mode ?? '') == 'system' ? 'selected' : '' }}>System Default (Auto Match)</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <!-- Save Button Card -->
        <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3.5 bg-navy-600 hover:bg-navy-700 text-white text-base font-bold rounded-2xl shadow-md transition flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Save Theme Settings</span>
            </button>
        </div>

    </form>

    <script src="{{ asset('js/theme.js') }}"></script>

@endsection