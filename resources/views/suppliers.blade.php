@extends('layouts.app')

@section('title', 'Suppliers & Vendors - City Pharmacy Store')

@section('content')

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Suppliers & Vendors Directory</h1>
            <p class="text-base text-slate-500 mt-2">Manage partner pharmaceutical companies, distributors, and quick stock view.</p>
        </div>

        <div class="relative w-full md:w-[420px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="supplierSearchInput" onkeyup="filterSuppliers()" placeholder="Search supplier or company..." class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4"></path>
                </svg>
                <h3 class="font-bold text-slate-900 text-lg">Registered Supplying Companies</h3>
            </div>
            <span class="text-base bg-slate-100 text-slate-800 font-bold px-4 py-1.5 rounded-xl border border-slate-200">Total: {{ count($suppliers) }} Vendors</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-8 w-20 text-center">#</th>
                        <th class="py-4 px-8">Company / Distributor Name</th>
                        <th class="py-4 px-8">Status</th>
                        <th class="py-4 px-8 text-center">Vendor Inventory</th>
                    </tr>
                </thead>
                <tbody id="supplierTableBody" class="divide-y divide-slate-100 text-base text-slate-700">
                    @forelse($suppliers as $index => $supplier)
                    <tr class="supplier-row hover:bg-slate-50/60 transition">
                        <td class="py-5 px-8 text-center font-bold text-slate-500 text-base">{{ $index + 1 }}</td>
                        <td class="py-5 px-8 font-bold text-slate-900 text-base">{{ $supplier->company }}</td>
                        
                        <td class="py-5 px-8">
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Verified</span>
                            </span>
                        </td>

                        <td class="py-5 px-8 text-center">
                            <button onclick="openModal('modal-{{ $index }}')" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>View Stock</span>
                            </button>

                            <div id="modal-{{ $index }}" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
                                <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full p-8 text-left relative max-h-[90vh] flex flex-col border border-slate-100">
                                    
                                    <div id="print-area-{{ $index }}" class="flex flex-col flex-grow overflow-hidden">
                                        
                                        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
                                            <div class="flex items-center space-x-3">
                                                <img src="/images/cityy_logo.png" alt="City Pharmacy Logo" class="h-10 sm:h-12 w-auto object-contain mix-blend-multiply">
                                                <div>
                                                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">City Pharmacy Store</h2>
                                                    <p class="text-xs font-semibold text-slate-500">Official Vendor Inventory & Stock Statement</p>
                                                </div>
                                            </div>
                                            <button onclick="closeModal('modal-{{ $index }}')" class="no-print-element w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-lg transition shadow-sm">
                                                ✕
                                            </button>
                                        </div>

                                        <div class="mb-6">
                                            <h3 class="text-xl text-slate-700 font-medium">
                                                Medicines Supplied by: <span class="font-extrabold text-slate-900 border-b-2 border-emerald-500 pb-0.5">{{ $supplier->company }}</span>
                                            </h3>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">
                                                Verified Distributor Itemized Summary
                                            </p>
                                        </div>

                                        <div class="overflow-y-auto flex-grow pr-1">
                                            @php
                                                $companyMedicines = \App\Models\Medicine::where('company', $supplier->company)->get();
                                            @endphp

                                            @if($companyMedicines->count() > 0)
                                                <table class="w-full text-left border-collapse text-base">
                                                    <thead>
                                                        <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-y border-slate-200">
                                                            <th class="py-4 px-5 whitespace-nowrap">MEDICINE</th>
                                                            <th class="py-4 px-5 whitespace-nowrap">CATEGORY</th>
                                                            <th class="py-4 px-5 whitespace-nowrap">PRICE</th>
                                                            <th class="py-4 px-5 whitespace-nowrap">QUANTITY</th>
                                                            <th class="py-4 px-5 whitespace-nowrap">EXPIRY</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 text-slate-700">
                                                        @foreach($companyMedicines as $med)
                                                        <tr class="hover:bg-slate-50/50 transition">
                                                            <td class="py-4 px-5 font-bold text-slate-900 whitespace-nowrap text-base">{{ $med->name }}</td>
                                                            
                                                            <td class="py-4 px-5 whitespace-nowrap">
                                                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">
                                                                    {{ $med->category }}
                                                                </span>
                                                            </td>

                                                            <td class="py-4 px-5 font-extrabold text-slate-900 whitespace-nowrap text-base">Rs. {{ number_format($med->price, 2) }}</td>
                                                            <td class="py-4 px-5 font-extrabold text-emerald-600 whitespace-nowrap text-base">{{ $med->stock }} units</td>
                                                            <td class="py-4 px-5 font-medium text-slate-600 whitespace-nowrap text-base">{{ $med->expiry_date }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="text-center py-12">
                                                    <p class="text-slate-400 font-medium text-base">No medicines registered under this company yet.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-slate-100 mt-6 flex items-center justify-between">
                                        <button onclick="printSupplierStock('print-area-{{ $index }}')" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-base rounded-xl transition shadow-sm flex items-center space-x-2 border border-slate-300">
                                            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            <span>PRINT</span>
                                        </button>

                                        <button onclick="printSupplierStock('print-area-{{ $index }}')" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base rounded-xl shadow-md transition flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span>DOWNLOAD PDF</span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400 font-medium text-base">No suppliers registered yet. Add medicines with company names in Inventory.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function filterSuppliers() {
        const query = document.getElementById('supplierSearchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.supplier-row');

        rows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            if (rowText.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function printSupplierStock(areaId) {
        const printElement = document.getElementById(areaId).cloneNode(true);
        
        const closeBtn = printElement.querySelector('.no-print-element');
        if (closeBtn) closeBtn.remove();

        const printWindow = window.open('', '', 'height=750,width=950');

        printWindow.document.write('<html><head><title>Supplier Stock Statement - City Pharmacy</title>');
        printWindow.document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">');
        printWindow.document.write(`
            <style>
                body { 
                    font-family: "Inter", sans-serif; 
                    padding: 35px; 
                    background-color: #ffffff; 
                    -webkit-print-color-adjust: exact !important; 
                    print-color-adjust: exact !important; 
                }
                @page { margin: 15mm; }
            </style>
        `);
        printWindow.document.write('</head><body>');
        printWindow.document.write(printElement.innerHTML);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 700);
    }
</script>
@endpush