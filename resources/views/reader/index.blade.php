@extends('layouts.app')

@section('title', 'RecapEnglish - Comprehensive Grammar Master Book')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    <!-- Hero / Resume Last Topic Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white p-8 sm:p-12 shadow-2xl border border-indigo-700/30">
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 max-w-3xl space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-200 text-xs font-bold tracking-wide uppercase">
                <span>📘 Interactive Grammar Book</span>
                <span class="w-1 h-1 rounded-full bg-indigo-300"></span>
                <span>BCS & Admission Ready</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Master English Grammar with Intuitive Rules & Mnemonics
            </h1>
            
            <p class="text-indigo-100/90 text-sm sm:text-base leading-relaxed">
                A structured, book-style learning path synthesized from high-yield lecture notes. Includes bilingual explanations, memory tricks (like <code class="bg-indigo-950/60 px-1.5 py-0.5 rounded text-amber-300 font-mono text-xs font-bold">BARSHA-NOVA</code> & <code class="bg-indigo-950/60 px-1.5 py-0.5 rounded text-sky-300 font-mono text-xs font-bold">RUPA IS MODERN</code>), and authentic competitive exam questions.
            </p>

            <!-- Resume Last Topic Card / User Status -->
            @auth
                <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    @if($lastTopic)
                        <a href="{{ route('reader.topic', $lastTopic->slug) }}" class="inline-flex items-center justify-center gap-3 px-6 py-3.5 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-extrabold rounded-2xl shadow-lg shadow-amber-500/25 transition transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Resume Reading: {{ Str::limit($lastTopic->title, 28) }}</span>
                        </a>
                    @else
                        @if($parts->first() && $parts->first()->chapters->first() && $parts->first()->chapters->first()->topics->first())
                            <a href="{{ route('reader.topic', $parts->first()->chapters->first()->topics->first()->slug) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-500 hover:bg-indigo-400 text-white font-extrabold rounded-2xl shadow-lg shadow-indigo-500/30 transition">
                                Start Reading Chapter 1 →
                            </a>
                        @endif
                    @endif

                    <div class="flex items-center gap-3 px-4 py-2.5 bg-white/10 backdrop-blur rounded-2xl border border-white/10 text-xs">
                        <div class="text-right">
                            <span class="text-indigo-200 block text-[10px] uppercase font-bold tracking-wider">Your Progress</span>
                            <span class="font-extrabold text-white text-sm">{{ count($completedTopicIds) }} / {{ $totalTopicsCount }} Topics</span>
                        </div>
                        <div class="w-12 h-12 relative flex items-center justify-center">
                            <div class="w-10 h-10 rounded-full border-2 border-indigo-400/40 flex items-center justify-center font-bold text-amber-300 text-xs">
                                {{ $userProgressPercent }}%
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="pt-4 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="px-6 py-3.5 bg-indigo-500 hover:bg-indigo-400 text-white font-extrabold rounded-2xl shadow-lg transition">
                        Create Free Learner Account
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl backdrop-blur transition">
                        Log in to Track Progress
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Curriculum Table of Contents -->
    <div class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Curriculum Syllabus & Chapters</h2>
                <p class="text-xs text-slate-600">Explore all 4 parts and 15 comprehensive chapters.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-600">
                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $totalTopicsCount }} Core Topics
                </span>
                <span>•</span>
                <span class="font-semibold text-slate-700">225+ Practice Questions</span>
            </div>
        </div>

        <div class="space-y-8">
            @foreach($parts as $part)
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs hover:shadow-md transition">
                    
                    <!-- Part Header -->
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <span class="text-[11px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">
                                    Section {{ $loop->iteration }}
                                </span>
                                <h3 class="text-xl font-black text-slate-900 mt-2 tracking-tight">{{ $part->title }}</h3>
                                @if($part->description)
                                    <p class="text-xs text-slate-600 mt-1">{{ $part->description }}</p>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full shrink-0">
                                {{ $part->chapters->count() }} Chapters
                            </span>
                        </div>
                    </div>

                    <!-- Chapters Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($part->chapters as $chapter)
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 hover:border-indigo-200 p-5 flex flex-col justify-between transition group">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                                            Chapter {{ $chapter->order }}
                                        </span>
                                        @if($chapter->topics->count() > 0)
                                            @php
                                                $chTopic = $chapter->topics->first();
                                                $isChCompleted = in_array($chTopic->id, $completedTopicIds);
                                            @endphp
                                            @if($isChCompleted)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Completed
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <h4 class="font-extrabold text-sm text-slate-900 group-hover:text-indigo-600 transition leading-snug">
                                        {{ $chapter->title }}
                                    </h4>

                                    @if($chapter->summary)
                                        <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                                            {{ $chapter->summary }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                    <span class="text-slate-600 font-medium">
                                        {{ $chapter->topics->count() }} topic • {{ $chapter->topics->sum(fn($t) => $t->exercises->count()) }} MCQs
                                    </span>

                                    @if($chapter->topics->count() > 0)
                                        <a href="{{ route('reader.topic', $chapter->topics->first()->slug) }}" class="font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                            Read Chapter →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
