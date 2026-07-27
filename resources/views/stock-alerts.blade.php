@extends('layouts.app')

@section('title', 'Stock Alerts & Expiry - City Pharmacy Store')

@section('content')

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Stock & Expiry Monitoring</h1>
            <p class="text-base text-slate-500 mt-2">Automated alerts for low stock levels and upcoming expiration dates to prevent losses.</p>
        </div>

        <div class="relative w-full md:w-[420px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="stockSearchInput" onkeyup="filterStockAlerts()" placeholder="Search alert by medicine or company..." class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center space-x-3 bg-amber-50/50">
            <svg class="w-7 h-7 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="font-bold text-slate-900 text-lg">Low Stock Medicines (Below 10 Units)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-8">Medicine</th>
                        <th class="py-4 px-8">Category</th>
                        <th class="py-4 px-8">Company</th>
                        <th class="py-4 px-8">Current Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-base text-slate-700">
                    @forelse($lowStockMedicines as $med)
                    <tr class="low-stock-row hover:bg-slate-50/60 transition">
                        <td class="py-5 px-8 font-bold text-slate-900 text-base">{{ $med->name }}</td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">
                                {{ $med->category }}
                            </span>
                        </td>
                        <td class="py-5 px-8 font-bold text-slate-800 text-base">{{ $med->company }}</td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-lg text-sm font-extrabold bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>{{ $med->stock }} units left</span>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400 font-medium text-base">Great! No medicines are running low on stock.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center space-x-3 bg-rose-50/50">
            <svg class="w-7 h-7 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="font-bold text-slate-900 text-lg">Expiring Soon (Next 3 Months)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-8">Medicine</th>
                        <th class="py-4 px-8">Category</th>
                        <th class="py-4 px-8">Company</th>
                        <th class="py-4 px-8">Expiry Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-base text-slate-700">
                    @forelse($expiringMedicines as $med)
                    <tr class="expiring-row hover:bg-slate-50/60 transition">
                        <td class="py-5 px-8 font-bold text-slate-900 text-base">{{ $med->name }}</td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">
                                {{ $med->category }}
                            </span>
                        </td>
                        <td class="py-5 px-8 font-bold text-slate-800 text-base">{{ $med->company }}</td>
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-lg text-sm font-extrabold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $med->expiry_date }}</span>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400 font-medium text-base">No medicines expiring soon in the next 3 months.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function filterStockAlerts() {
        const query = document.getElementById('stockSearchInput').value.toLowerCase().trim();
        
        document.querySelectorAll('.low-stock-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });

        document.querySelectorAll('.expiring-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }
</script>
@endpush