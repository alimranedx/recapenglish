@extends('layouts.admin')

@section('page_title', 'Curriculum Topics Management')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900">Book Topics & Modules</h2>
            <p class="text-xs text-slate-600 mt-0.5">Edit existing lecture topics, organize sequences, or add newly written chapters.</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Filter by Chapter -->
            <form action="{{ route('admin.topics.index') }}" method="GET" class="inline">
                <select name="chapter_id" onchange="this.form.submit()" class="text-xs font-semibold px-3 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none">
                    <option value="">All Chapters</option>
                    @foreach($chapters as $ch)
                        <option value="{{ $ch->id }}" {{ $chapterId == $ch->id ? 'selected' : '' }}>
                            {{ $ch->title }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('admin.topics.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Topic
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Order</th>
                    <th class="px-6 py-3.5">Topic Title</th>
                    <th class="px-6 py-3.5">Chapter & Part</th>
                    <th class="px-6 py-3.5">Read Time</th>
                    <th class="px-6 py-3.5">MCQs</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($topics as $topic)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-600">{{ $topic->order }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $topic->title }}
                            @if($topic->mnemonics)
                                <span class="block text-[10px] text-amber-700 font-semibold truncate max-w-xs">
                                    ★ {{ $topic->mnemonics }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="block font-bold text-slate-800">{{ $topic->chapter->title }}</span>
                            <span class="text-[10px] text-slate-600">{{ $topic->chapter->part->title }}</span>
                        </td>
                        <td class="px-6 py-4">~{{ $topic->estimated_read_time }} mins</td>
                        <td class="px-6 py-4 font-semibold text-indigo-600">{{ $topic->exercises->count() }} questions</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $topic->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $topic->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.exercises.create', ['topic_id' => $topic->id]) }}" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Add Question</span>
                            </a>
                            <a href="{{ route('admin.topics.edit', $topic->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold hover:underline">
                                Edit Topic
                            </a>
                            <a href="{{ route('reader.topic', $topic->slug) }}" target="_blank" class="text-slate-600 hover:text-slate-800 font-semibold" title="Preview on Reader">
                                View
                            </a>
                            <form action="{{ route('admin.topics.destroy', $topic->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this topic?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-600">No topics found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-2">
        {{ $topics->links() }}
    </div>

</div>
@endsection
