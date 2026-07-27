@extends('layouts.app')

@section('title', 'Medical Inventory - City Pharmacy Store')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Medical Inventory Management</h1>
        <p class="text-base text-slate-500 mt-2">Manage, monitor, and search pharmacy stock efficiently with global real-time filtering.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">
        <div class="flex items-center space-x-3 mb-5">
            <svg class="w-6 h-6 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="font-bold text-slate-900 text-lg">Filter & Search Inventory</h3>
        </div>
        
        <div class="flex flex-col lg:flex-row items-center gap-4 w-full">
            <div class="relative flex-1 w-full min-w-0">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="liveSearchInput" onkeyup="filterInventoryTable()" placeholder="Search medicine name..." class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
            </div>

            <div class="flex-1 w-full min-w-0">
                <input type="text" id="liveCategoryInput" onkeyup="filterInventoryTable()" placeholder="Filter by category..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
            </div>

            <div class="flex-1 w-full min-w-0">
                <input type="text" id="liveCompanyInput" onkeyup="filterInventoryTable()" placeholder="Filter by company..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
            </div>

            <div class="flex items-center gap-3 w-full lg:w-auto flex-shrink-0">
                <button onclick="filterInventoryTable()" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-base font-semibold rounded-xl transition shadow-sm flex items-center justify-center space-x-2 flex-shrink-0 whitespace-nowrap">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Search</span>
                </button>
                <a href="/medicines" class="px-5 py-3 bg-rose-600 hover:bg-rose-700 text-white text-base font-semibold rounded-xl transition shadow-sm flex items-center justify-center space-x-2 flex-shrink-0 whitespace-nowrap" title="Clear Filters">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Reset</span>
                </a>
            </div>
        </div>
    </div>

\    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8 mb-8">
        <div class="flex items-center space-x-3 mb-6">
            <svg class="w-6 h-6 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="font-bold text-slate-900 text-lg">Add New Medicine</h3>
        </div>

        <form action="/medicines/store" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Medicine Name</label>
                <input type="text" name="name" placeholder="e.g. Panadol Extra" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder-slate-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                <input type="text" name="category" placeholder="e.g. Analgesic" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder-slate-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Company</label>
                <input type="text" name="company" placeholder="e.g. GSK" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder-slate-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Price (Rs.)</label>
                <input type="number" step="0.01" name="price" placeholder="0.00" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder-slate-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Stock Quantity</label>
                <input type="number" name="stock" placeholder="0" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 placeholder-slate-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Expiry Date</label>
                <input type="date" name="expiry_date" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-700">
            </div>
            <div class="sm:col-span-2 md:col-span-3 flex justify-end mt-2">
                <button type="submit" class="px-7 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-base font-bold rounded-xl shadow-md transition flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save Medicine</span>
                </button>
            </div>
        </form>
    </div>

\    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="font-bold text-slate-900 text-lg">Available Medicines List</h3>
            </div>
            <span class="text-base bg-slate-100 text-slate-800 font-bold px-4 py-1.5 rounded-xl border border-slate-200">Total: {{ $medicines->total() ?? count($medicines) }} Items</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 text-sm font-extrabold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-8 w-16 text-center">#</th>
                        <th class="py-4 px-8">Medicine</th>
                        <th class="py-4 px-8">Category</th>
                        <th class="py-4 px-8">Company</th>
                        <th class="py-4 px-8">Price</th>
                        <th class="py-4 px-8">Stock</th>
                        <th class="py-4 px-8">Expiry</th>
                        <th class="py-4 px-8 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-base text-slate-700" id="inventoryTableBody">
                    @forelse($medicines as $index => $medicine)
                    <tr id="medicine-row-{{ $medicine->id }}" class="inventory-row hover:bg-slate-50/60 transition duration-200">
                        <td class="py-5 px-8 text-center font-bold text-slate-500 text-base">
                            {{ ($medicines->currentPage() - 1) * $medicines->perPage() + $index + 1 }}
                        </td>
                        <td class="med-name py-5 px-8 font-bold text-slate-900 text-base">{{ $medicine->name }}</td>
                        
                        <td class="py-5 px-8">
                            <span class="med-cat inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">
                                {{ $medicine->category }}
                            </span>
                        </td>

                        <td class="med-comp py-5 px-8 font-semibold text-slate-700 text-base">{{ $medicine->company }}</td>
                        <td class="med-price py-5 px-8 font-extrabold text-slate-900 text-base">Rs. {{ number_format($medicine->price, 2) }}</td>
                        <td class="py-5 px-8 text-base"><span class="med-stock text-emerald-600 font-extrabold">{{ $medicine->stock }} units</span></td>
                        <td class="med-expiry py-5 px-8 font-medium text-slate-600 text-base">{{ $medicine->expiry_date }}</td>
                        <td class="py-5 px-8 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="document.getElementById('edit-modal-{{ $medicine->id }}').classList.remove('hidden')" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span>Edit</span>
                                </button>

                                <form action="/medicines/delete/{{ $medicine->id }}" method="POST" onsubmit="handleDelete(event, {{ $medicine->id }});">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>

\                            <div id="edit-modal-{{ $medicine->id }}" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
                                <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-8 text-left border border-slate-100">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="font-extrabold text-slate-900 text-xl">Edit Medicine: {{ $medicine->name }}</h3>
                                        <button type="button" onclick="document.getElementById('edit-modal-{{ $medicine->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>
                                    </div>

                                    <form action="/medicines/update/{{ $medicine->id }}" method="POST" onsubmit="handleUpdate(event, {{ $medicine->id }});" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2 text-left">Medicine Name</label>
                                            <input type="text" name="name" value="{{ $medicine->name }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2 text-left">Category</label>
                                            <input type="text" name="category" value="{{ $medicine->category }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2 text-left">Company</label>
                                            <input type="text" name="company" value="{{ $medicine->company }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2 text-left">Price (Rs.)</label>
                                            <input type="number" step="0.01" name="price" value="{{ $medicine->price }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2 text-left">Stock Quantity</label>
                                            <input type="number" name="stock" value="{{ $medicine->stock }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2 text-left">Expiry Date</label>
                                            <input type="date" name="expiry_date" value="{{ $medicine->expiry_date }}" required class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base focus:ring-2 focus:ring-emerald-500 outline-none">
                                        </div>
                                        <div class="sm:col-span-2 flex justify-end space-x-3 mt-6">
                                            <button type="button" onclick="document.getElementById('edit-modal-{{ $medicine->id }}').classList.add('hidden')" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 text-base font-bold rounded-xl transition">Cancel</button>
                                            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-base font-bold rounded-xl shadow-md transition">Update Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400 font-medium text-base">No medicines found matching your criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($medicines, 'hasPages') && $medicines->hasPages())
        <div class="px-8 py-6 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center space-x-2">
                @if ($medicines->onFirstPage())
                    <button disabled class="px-4 py-2 bg-slate-200 text-slate-400 text-sm font-bold rounded-xl cursor-not-allowed">← Previous</button>
                @else
                    <a href="{{ $medicines->previousPageUrl() }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition shadow-sm">← Previous</a>
                @endif
            </div>

            <div class="hidden sm:flex items-center space-x-1.5">
                @foreach ($medicines->getUrlRange(1, $medicines->lastPage()) as $page => $url)
                    @if ($page == $medicines->currentPage())
                        <span class="px-3.5 py-2 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3.5 py-2 bg-slate-200 hover:bg-emerald-600 text-slate-700 hover:text-white text-sm font-bold rounded-xl transition">{{ $page }}</a>
                    @endif
                @endforeach
            </div>

            <div class="flex items-center space-x-2">
                @if ($medicines->hasMorePages())
                    <a href="{{ $medicines->nextPageUrl() }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition shadow-sm">Next →</a>
                @else
                    <button disabled class="px-4 py-2 bg-slate-200 text-slate-400 text-sm font-bold rounded-xl cursor-not-allowed">Next →</button>
                @endif
            </div>
        </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    function filterInventoryTable() {
        const nameQuery = document.getElementById('liveSearchInput').value.toLowerCase().trim();
        const catQuery = document.getElementById('liveCategoryInput').value.toLowerCase().trim();
        const compQuery = document.getElementById('liveCompanyInput').value.toLowerCase().trim();

        const rows = document.querySelectorAll('.inventory-row');

        rows.forEach(row => {
            const nameText = row.querySelector('.med-name') ? row.querySelector('.med-name').innerText.toLowerCase() : '';
            const catText = row.querySelector('.med-cat') ? row.querySelector('.med-cat').innerText.toLowerCase() : '';
            const compText = row.querySelector('.med-comp') ? row.querySelector('.med-comp').innerText.toLowerCase() : '';

            const matchesName = nameText.includes(nameQuery);
            const matchesCat = catText.includes(catQuery);
            const matchesComp = compText.includes(compQuery);

            if (matchesName && matchesCat && matchesComp) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    async function handleDelete(event, id) {
        event.preventDefault();
        if (!confirm('Are you sure you want to delete this medicine?')) return;

        const form = event.target;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            if (response.ok || response.redirected) {
                const row = document.getElementById(`medicine-row-${id}`);
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.97)';
                    setTimeout(() => row.remove(), 300);
                }
            } else {
                form.submit();
            }
        } catch (error) {
            form.submit();
        }
    }

    async function handleUpdate(event, id) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            if (response.ok || response.redirected) {
                const modal = document.getElementById(`edit-modal-${id}`);
                if (modal) modal.classList.add('hidden');

                const row = document.getElementById(`medicine-row-${id}`);
                if (row) {
                    row.querySelector('.med-name').innerText = formData.get('name');
                    row.querySelector('.med-cat').innerText = formData.get('category');
                    row.querySelector('.med-comp').innerText = formData.get('company');
                    
                    const priceVal = parseFloat(formData.get('price')).toFixed(2);
                    row.querySelector('.med-price').innerText = `Rs. ${priceVal}`;
                    
                    row.querySelector('.med-stock').innerText = `${formData.get('stock')} units`;
                    row.querySelector('.med-expiry').innerText = formData.get('expiry_date');

                    row.classList.add('bg-emerald-50');
                    setTimeout(() => row.classList.remove('bg-emerald-50'), 1500);
                }
            } else {
                form.submit();
            }
        } catch (error) {
            form.submit();
        }
    }
</script>
@endpush