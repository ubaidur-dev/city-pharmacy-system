@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div><h1 class="text-3xl font-extrabold text-slate-900">Activity Log</h1><p class="text-base text-slate-500 mt-2">Who did what: sales, returns, price and stock changes, payments and settings.</p></div>
    <form method="GET"><select name="action" onchange="this.form.submit()" class="px-4 py-3 border border-slate-300 rounded-xl bg-white"><option value="">All activity</option>@foreach($actions as $a)<option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>@endforeach</select></form>
</div>
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-x-auto">
<table class="w-full text-left text-sm">
    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider"><tr><th class="px-6 py-3">When</th><th class="px-6 py-3">User</th><th class="px-6 py-3">Action</th><th class="px-6 py-3">Details</th></tr></thead>
    <tbody class="divide-y divide-slate-100">
    @forelse($logs as $l)
        <tr><td class="px-6 py-3.5 whitespace-nowrap text-slate-500">{{ $l->created_at->format('d M Y H:i') }}</td><td class="px-6 py-3.5 font-semibold">{{ $l->user?->name ?? 'System' }}</td><td class="px-6 py-3.5"><span class="px-3 py-1 rounded-full text-xs font-bold bg-navy-50 text-navy-700">{{ $l->action }}</span></td><td class="px-6 py-3.5 text-slate-700">{{ $l->summary }}</td></tr>
    @empty
        <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Nothing logged yet.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="px-6 py-4">{{ $logs->links() }}</div>
</div>
@endsection
