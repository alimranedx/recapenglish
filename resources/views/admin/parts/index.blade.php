@extends('layouts.admin')

@section('page_title', 'Book Parts & Sections')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900">Curriculum Parts</h2>
            <p class="text-xs text-slate-600 mt-0.5">Top-level pedagogical grouping for chapters and modules.</p>
        </div>
        <a href="{{ route('admin.parts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Part
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Order</th>
                    <th class="px-6 py-3.5">Part Title</th>
                    <th class="px-6 py-3.5">Description</th>
                    <th class="px-6 py-3.5">Chapters</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($parts as $part)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-600">{{ $part->order }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $part->title }}</td>
                        <td class="px-6 py-4 text-slate-600 truncate max-w-xs">{{ $part->description }}</td>
                        <td class="px-6 py-4 font-semibold text-indigo-600">{{ $part->chapters_count }} chapters</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $part->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $part->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.parts.edit', $part->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('admin.parts.destroy', $part->id) }}" method="POST" onsubmit="return confirm('Delete this part and all its chapters?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
