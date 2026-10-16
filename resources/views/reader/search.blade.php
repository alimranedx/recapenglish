@extends('layouts.app')

@section('title', 'Search Results: ' . $query . ' - RecapEnglish')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">
            Search Results for <span class="text-indigo-600">"{{ $query }}"</span>
        </h1>
        <p class="text-xs text-slate-600 mt-1">Found {{ count($results) }} matching topics and rules.</p>
    </div>

    @if(count($results) > 0 || (isset($matchingExercises) && count($matchingExercises) > 0))
        @if(count($results) > 0)
            <div class="space-y-4">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-500">Topics & Chapters ({{ count($results) }})</h2>
                @foreach($results as $topic)
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:border-indigo-300 transition">
                        <div class="flex items-center gap-2 text-[10px] font-bold text-indigo-600 uppercase mb-1">
                            <span>{{ $topic->chapter->part->title }}</span>
                            <span>•</span>
                            <span>{{ $topic->chapter->title }}</span>
                        </div>

                        <h2 class="text-base font-extrabold text-slate-900">
                            <a href="{{ route('reader.topic', $topic->slug) }}" class="hover:text-indigo-600">
                                {{ $topic->title }}
                            </a>
                        </h2>

                        @if($topic->mnemonics)
                            <div class="mt-2 text-xs font-semibold text-amber-800 bg-amber-50 p-2 rounded-lg border border-amber-200">
                                <strong>Mnemonic:</strong> {{ $topic->mnemonics }}
                            </div>
                        @endif

                        <p class="text-xs text-slate-600 mt-2 line-clamp-3">
                            {{ Str::limit(strip_tags($topic->content), 260) }}
                        </p>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap text-xs">
                            <span class="text-slate-600">~{{ $topic->estimated_read_time }} min read • {{ $topic->exercises->count() }} MCQs</span>
                            <a href="{{ route('reader.topic', $topic->slug) }}" class="font-bold text-indigo-600 hover:text-indigo-800 shrink-0">
                                Read Topic →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(isset($matchingExercises) && count($matchingExercises) > 0)
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-500">Practice Exam Questions ({{ count($matchingExercises) }})</h2>
                @foreach($matchingExercises as $ex)
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:border-indigo-300 transition space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">
                                {{ $ex->topic->chapter->title ?? 'Chapter' }} • {{ $ex->topic->title ?? 'Topic' }}
                            </span>
                            @if($ex->exam_reference)
                                <span class="text-[10px] font-extrabold text-sky-800 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                                    {{ $ex->exam_reference }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-slate-900">{{ $ex->question }}</p>
                        @if($ex->explanation)
                            <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <strong class="text-indigo-900">ব্যাখ্যা:</strong> {{ Str::limit($ex->explanation, 180) }}
                            </p>
                        @endif
                        <div class="pt-2 flex items-center justify-between gap-2 flex-wrap text-xs">
                            <span class="text-emerald-700 font-bold">উত্তর: {{ strtoupper($ex->correct_answer) }}</span>
                            <a href="{{ route('reader.topic', $ex->topic->slug) }}#quiz-{{ $ex->id }}" class="font-bold text-indigo-600 hover:text-indigo-800 shrink-0">
                                Practice in Topic →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
            <h3 class="font-bold text-slate-800 text-sm">No topics or questions found matching your query</h3>
            <p class="text-xs text-slate-600 mt-1">Try searching for keywords like "Noun", "BARSHA", "Voice", "Subjunctive", or "Tense".</p>
            <div class="mt-4">
                <a href="{{ route('reader.home') }}" class="inline-flex px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-xl shadow-xs">
                    Return to Table of Contents
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
