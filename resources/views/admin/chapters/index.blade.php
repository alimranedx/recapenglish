@extends('layouts.admin')

@section('page_title', 'Curriculum Chapters')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-black text-slate-900">Chapters</h2>
            <p class="text-xs text-slate-600 mt-0.5">Manage and organize book chapters across parts.</p>
        </div>
        <a href="{{ route('admin.chapters.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Chapter
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Order</th>
                    <th class="px-6 py-3.5">Chapter Title</th>
                    <th class="px-6 py-3.5">Book Part</th>
                    <th class="px-6 py-3.5">Topics Count</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($chapters as $ch)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-600">{{ $ch->order }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $ch->title }}</td>
                        <td class="px-6 py-4">{{ $ch->part->title }}</td>
                        <td class="px-6 py-4 font-semibold text-indigo-600">{{ $ch->topics->count() }} topics</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ch->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $ch->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.chapters.edit', $ch->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('admin.chapters.destroy', $ch->id) }}" method="POST" onsubmit="return confirm('Delete this chapter and all its topics?');" class="inline">
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

    <div class="pt-2">
        {{ $chapters->links() }}
    </div>

</div>
@endsection
