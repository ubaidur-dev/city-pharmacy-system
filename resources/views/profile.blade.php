@extends('layouts.app')

@section('title', 'Admin Profile Settings - Family Health & Pharmacy Tracker')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Admin Profile Settings</h1>
        <p class="text-base text-slate-500 mt-2">Manage your personal credentials, public bio, and contact information.</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Profile Card (Dynamic First Letter & Image) -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8 text-center h-fit">
            <div class="relative w-40 h-40 mx-auto mb-6">
                <!-- Profile Image or Dynamic First Letter of Current User -->
                <div class="w-40 h-40 rounded-full bg-navy-600 text-white flex items-center justify-center font-extrabold text-5xl shadow-md overflow-hidden border-4 border-slate-100">
                    @if(!empty($admin->profile_image))
                        <img src="{{ asset('storage/' . $admin->profile_image) }}" alt="{{ $admin->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(trim($admin->name ?? 'A'), 0, 1)) }}
                    @endif
                </div>
                
                <!-- Pro Camera Upload Badge -->
                <label for="profile-image-input" class="absolute bottom-1 right-1 p-3 bg-white hover:bg-slate-50 text-navy-600 rounded-full shadow-md cursor-pointer transition transform hover:scale-105 border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </label>
            </div>

            <h3 class="text-2xl font-extrabold text-slate-900">{{ $admin->name }}</h3>
            <p class="text-base font-semibold text-navy-600 mt-1">{{ $admin->designation }}</p>
            <p class="text-sm font-medium text-slate-500 mt-2">{{ $admin->email }}</p>
            <div class="mt-5 pt-5 border-t border-slate-100 text-left">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Bio / About</p>
                <p class="text-sm text-slate-600 mt-1.5 leading-relaxed">{{ $admin->bio }}</p>
            </div>
        </div>

        <!-- Right Column: Detailed Profile Update Form -->
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-6 h-6 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <h3 class="font-bold text-slate-900 text-lg">Edit Profile & Professional Details</h3>
                </div>

                <form action="{{ url('/admin/profile/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name (Username)</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Professional Designation</label>
                            <input type="text" name="designation" value="{{ old('designation', $admin->designation) }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Office Address</label>
                            <input type="text" name="address" value="{{ old('address', $admin->address) }}" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Profile Image</label>
                            <input type="file" id="profile-image-input" name="profile_image" class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-navy-50 file:text-navy-700 hover:file:bg-navy-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Short Bio / About Me</label>
                        <textarea name="bio" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-navy-500 outline-none">{{ old('bio', $admin->bio) }}</textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-7 py-3 bg-navy-600 hover:bg-navy-700 text-white text-base font-bold rounded-xl shadow-md transition">Save Profile Changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Script to auto-submit form instantly when image is chosen via camera badge or file input -->
    <script>
        document.getElementById('profile-image-input').addEventListener('change', function() {
            if(this.files && this.files[0]) {
                this.closest('form').submit();
            }
        });
    </script>

@endsection