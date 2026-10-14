@extends('layouts.admin')

@section('page_title', 'Practice Questions & MCQs Management')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Practice Questions & MCQs</h2>
            <p class="text-xs text-slate-600 mt-1">Manage all competitive exam MCQs, answer keys, explanations, and topic associations.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exercises.create', ['topic_id' => $topicId]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add New Question</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <form action="{{ route('admin.exercises.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label for="chapter_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Filter by Chapter</label>
                <select name="chapter_id" id="chapter_id" onchange="this.form.submit()" class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">All Chapters ({{ $chapters->count() }})</option>
                    @foreach($chapters as $ch)
                        <option value="{{ $ch->id }}" {{ $chapterId == $ch->id ? 'selected' : '' }}>
                            {{ $ch->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="topic_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Filter by Topic</label>
                <select name="topic_id" id="topic_id" onchange="this.form.submit()" class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <option value="">All Topics ({{ $topics->count() }})</option>
                    @foreach($topics as $t)
                        <option value="{{ $t->id }}" {{ $topicId == $t->id ? 'selected' : '' }}>
                            {{ $t->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="q" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Search Questions / Rules</label>
                <div class="flex gap-2">
                    <input type="text" name="q" id="q" value="{{ $search }}" placeholder="Search question text or exam..." class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">Search</button>
                    @if($search || $topicId || $chapterId)
                        <a href="{{ route('admin.exercises.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center justify-center">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse($exercises as $ex)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-4 hover:border-indigo-200 transition">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div class="space-y-1.5 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $ex->topic->title ?? 'Topic' }}
                            </span>
                            @if($ex->exam_reference)
                                <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100">
                                    {{ $ex->exam_reference }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-slate-900 leading-snug pt-1">
                            {{ $loop->iteration + ($exercises->currentPage() - 1) * $exercises->perPage() }}. {{ $ex->question }}
                        </h3>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.exercises.edit', $ex->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 font-bold text-xs rounded-xl border border-slate-200 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit Question</span>
                        </a>

                        <form action="{{ route('admin.exercises.destroy', $ex->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Delete Question">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Options Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 text-xs">
                    @foreach($ex->options as $opt)
                        @php
                            preg_match('/^([a-d])[\)\.]\s*(.*)/i', $opt, $matches);
                            $optLetter = strtolower($matches[1] ?? '');
                            $isCorrect = ($optLetter === strtolower(trim($ex->correct_answer)));
                        @endphp
                        <div class="px-3 py-2 rounded-xl border {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }} flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[10px] {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ strtoupper($optLetter) }}
                            </span>
                            <span class="flex-1">{{ $opt }}</span>
                            @if($isCorrect)
                                <span class="text-[10px] uppercase font-extrabold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Correct</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Explanation Box -->
                @if($ex->explanation)
                    <div class="bg-indigo-50/70 border border-indigo-100/80 rounded-xl p-3 text-xs text-indigo-950 leading-relaxed font-medium">
                        <span class="font-bold text-indigo-900 block mb-0.5">💡 ব্যাখ্যা ও নিয়ম (Explanation):</span>
                        {{ $ex->explanation }}
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center shadow-xs">
                <p class="text-sm font-semibold text-slate-600">No practice questions found matching your filter.</p>
                <a href="{{ route('admin.exercises.create') }}" class="inline-block mt-3 text-xs font-bold text-indigo-600 hover:underline">
                    + Add your first question
                </a>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $exercises->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection
