@extends('layouts.app')

@section('title', 'Admin Security Settings - City Pharmacy Store')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Security Settings</h1>
        <p class="text-base text-slate-500 mt-2">Manage your account email address, password, and security credentials.</p>
    </div>

    <!-- DB Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-navy-50 border border-navy-200 text-navy-800 rounded-2xl font-bold text-base">
            {{ session('success') }}
        </div>
    @endif

    <!-- DB Validation Error Messages -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl font-bold text-base">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <!-- Section 1: Update Email Address (Balanced spacing & height) -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-6 h-6 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <h3 class="font-bold text-slate-900 text-lg">Update Email Address</h3>
            </div>

            <form action="{{ url('/admin/security/email/update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Current Email Address</label>
                    <input type="email" value="{{ $admin->email ?? 'ubaid@citypharmacy.com' }}" disabled class="w-full px-4 py-3 border border-slate-200 bg-slate-100 rounded-xl text-base text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Email Address</label>
                    <input type="email" name="email" required placeholder="Enter new email" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                </div>

                <!-- Spacer div to match the height profile of the right card perfectly -->
                <div class="pt-3">
                    <div class="flex justify-end pt-1">
                        <button type="submit" class="px-7 py-3 bg-navy-600 hover:bg-navy-700 text-white text-base font-bold rounded-xl shadow-md transition">Update Email</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section 2: Change Password -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-6 h-6 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <h3 class="font-bold text-slate-900 text-lg">Change Password</h3>
            </div>

            <form action="{{ url('/admin/security/password/update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" required placeholder="••••••••" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                    <input type="password" name="new_password" required placeholder="••••••••" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-7 py-3 bg-navy-600 hover:bg-navy-700 text-white text-base font-bold rounded-xl shadow-md transition">Update Password</button>
                </div>
            </form>
        </div>

    </div>

@endsection