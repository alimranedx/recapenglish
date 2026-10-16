@extends('layouts.admin')

@section('page_title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Header Banner / Academic Console Greeting -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-5 sm:p-6 lg:p-8 shadow-lg border border-slate-800 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="px-2.5 py-0.5 text-[11px] font-black uppercase tracking-wider rounded-md bg-indigo-600 text-white shadow-xs">
                        {{ auth()->user()->isSuperAdmin() ? 'Super Admin Console' : 'Academic Editor' }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        RecapEnglish Learning & Testing Engine Active
                    </span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight">
                    Welcome back, {{ auth()->user()->name }}
                </h1>
                <p class="text-xs lg:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Monitor curriculum coverage, analyze student performance across the 50-MCQ random skill test engine, and track grammar topic difficulty diagnostics.
                </p>
            </div>

            <!-- Quick Action Hub -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('admin.exercises.create') }}" class="px-3.5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    New MCQ
                </a>
                <a href="{{ route('admin.topics.create') }}" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    New Topic
                </a>
                <a href="{{ route('admin.skill-tests.index') }}" class="px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Skill Test Reports
                </a>
                <a href="{{ route('admin.skill-test-settings.edit') }}" class="px-3.5 py-2.5 bg-indigo-900/60 hover:bg-indigo-800 text-indigo-200 border border-indigo-700/60 font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5" title="Configure exam duration, questions count, and topic rules">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Exam Settings
                </a>
                <a href="{{ route('reader.home') }}" target="_blank" class="px-3 py-2.5 bg-slate-900/80 hover:bg-slate-800 text-slate-300 border border-slate-800 font-semibold text-xs rounded-xl transition flex items-center gap-1" title="Open student reader website">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Reader
                </a>
            </div>
        </div>
    </div>

    <!-- Primary KPI Metric Cards (5 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
        
        <!-- Card 1: Curriculum Topics -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:border-indigo-200 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Grammar Topics</span>
                <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ $stats['total_topics'] }}</div>
            <div class="mt-1 flex items-center gap-1.5 text-xs text-slate-600 font-medium">
                <span class="text-emerald-600 font-bold">100% Active</span>
                <span>• {{ $stats['total_chapters'] }} Ch / {{ $stats['total_parts'] }} Parts</span>
            </div>
        </div>

        <!-- Card 2: Question Pool Bank -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:border-indigo-200 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">MCQ Bank Pool</span>
                <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-indigo-600">{{ number_format($stats['total_exercises']) }}</div>
            <div class="mt-1 text-xs text-slate-600 font-medium">
                <span>Avg ~{{ round($stats['total_exercises'] / max($stats['total_topics'], 1)) }} MCQs / topic</span>
            </div>
        </div>

        <!-- Card 3: Registered Students -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:border-indigo-200 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Registered Students</span>
                <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ number_format($stats['total_students']) }}</div>
            <div class="mt-1 text-xs text-emerald-600 font-semibold">
                {{ $stats['active_students_7d'] }} active this week
            </div>
        </div>

        <!-- Card 4: Skill Tests Completed -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:border-indigo-200 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">50-MCQ Skill Tests</span>
                <span class="p-1.5 rounded-lg bg-purple-50 text-purple-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-purple-700">{{ number_format($stats['total_skill_tests']) }}</div>
            <div class="mt-1 text-xs text-slate-600 font-medium">
                Avg Score: <strong class="text-slate-800">{{ $stats['avg_test_score'] }}/50</strong> ({{ $stats['avg_test_percentage'] }}%)
            </div>
        </div>

        <!-- Card 5: High Mastery & Speed -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:border-indigo-200 transition col-span-1 sm:col-span-2 md:col-span-1 lg:col-span-1">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">High Scores (≥80%)</span>
                <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-black text-amber-600">{{ $stats['high_performers_count'] }}</div>
            <div class="mt-1 text-xs text-slate-600 font-medium">
                Avg time: <strong class="text-slate-800">{{ $stats['avg_duration_minutes'] }} mins</strong> / 50 MCQs
            </div>
        </div>

    </div>

    <!-- Diagnostic Hub: Grammar Topic Difficulty & Performance Matrix -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs p-5 sm:p-6 lg:p-7 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-5">
            <div>
                <div class="flex items-center gap-2">
                    <span class="p-1 rounded bg-indigo-100 text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    <h2 class="text-base font-extrabold text-slate-900">Grammar Topic Difficulty & Diagnostic Analysis</h2>
                </div>
                <p class="text-xs text-slate-600 mt-1">
                    Aggregated student accuracy from 50-MCQ random skill tests. Topics with low student accuracy (<span class="text-rose-600 font-bold">&lt; 55%</span>) highlight grammar hurdles where students frequently fail.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.exercises.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    Browse All MCQs →
                </a>
            </div>
        </div>

        @if($stats['total_skill_tests'] > 0 && $hardestTopics->isNotEmpty())
            <!-- Split Grid: Hardest Topics vs Strongest Topics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Hardest Grammar Topics (Need Attention) -->
                <div class="bg-rose-50/40 rounded-2xl border border-rose-200/80 p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <h3 class="font-extrabold text-xs uppercase tracking-wider text-rose-900">
                                Most Challenging Topics (Lowest Student Accuracy)
                            </h3>
                        </div>
                        <span class="text-[10px] font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded-md">Action Required</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($hardestTopics as $topic)
                            <div class="bg-white rounded-xl p-3.5 border border-rose-100 shadow-2xs flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-900 truncate">{{ $topic->title }}</h4>
                                        <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-black bg-rose-100 text-rose-800">
                                            {{ $topic->accuracy }}% accuracy
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-[11px] text-slate-600 mt-1">
                                        <span>Correct: <strong class="text-emerald-600">{{ $topic->correct }}</strong></span>
                                        <span>Wrong: <strong class="text-rose-600">{{ $topic->wrong }}</strong></span>
                                        <span>Tested: <strong>{{ $topic->total_asked }}x</strong></span>
                                        <span>Pool: <strong>{{ $topic->exercises_count }} MCQs</strong></span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('admin.topics.edit', $topic->id) }}" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg text-[11px] transition inline-block">
                                        Review Topic →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Strongest Grammar Topics (High Mastery) -->
                <div class="bg-emerald-50/40 rounded-2xl border border-emerald-200/80 p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="font-extrabold text-xs uppercase tracking-wider text-emerald-900">
                                High Student Mastery Topics (Strongest Scores)
                            </h3>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">High Proficiency</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($strongestTopics as $topic)
                            <div class="bg-white rounded-xl p-3.5 border border-emerald-100 shadow-2xs flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-900 truncate">{{ $topic->title }}</h4>
                                        <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800">
                                            {{ $topic->accuracy }}% accuracy
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-[11px] text-slate-600 mt-1">
                                        <span>Correct: <strong class="text-emerald-600">{{ $topic->correct }}</strong></span>
                                        <span>Wrong: <strong class="text-slate-600">{{ $topic->wrong }}</strong></span>
                                        <span>Tested: <strong>{{ $topic->total_asked }}x</strong></span>
                                        <span>Pool: <strong>{{ $topic->exercises_count }} MCQs</strong></span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('admin.topics.edit', $topic->id) }}" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-lg text-[11px] transition inline-block">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @else
            <!-- Notice when test data is still being accumulated -->
            <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-5 flex items-start gap-4 text-xs text-indigo-900">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="font-black text-indigo-950 text-sm">Real-time Grammar Diagnostic Matrix Ready</h4>
                    <p class="text-indigo-800/80 mt-1 leading-relaxed">
                        As students submit the 50-MCQ skill tests, this dashboard aggregates student right vs wrong answer rates topic-by-topic. You'll see which English grammar rules (Prepositions, Tenses, Modifiers, etc.) students struggle with most so you can enhance content.
                    </p>
                </div>
            </div>
        @endif

        <!-- Question Pool Depth Audit -->
        <div class="border-t border-slate-100 pt-5">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-700">Question Pool Inventory</h3>
                    <p class="text-[11px] text-slate-600">Topics with lowest exercise pool size (recommend adding more MCQs to ensure high test variety)</p>
                </div>
                <a href="{{ route('admin.exercises.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    + Add New MCQ
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
                @foreach($questionPoolAudit as $topic)
                    <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl space-y-1">
                        <span class="text-[10px] font-bold text-slate-600 uppercase block truncate">{{ $topic->chapter }}</span>
                        <h4 class="font-bold text-slate-900 truncate" title="{{ $topic->title }}">{{ $topic->title }}</h4>
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-[11px] font-black {{ $topic->exercises_count < 40 ? 'text-amber-600' : 'text-slate-700' }}">
                                {{ $topic->exercises_count }} MCQs
                            </span>
                            <a href="{{ route('admin.topics.edit', $topic->id) }}" class="text-[10px] font-bold text-indigo-600 hover:underline">
                                Edit →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Two-Column Activity Streams: Recent 50-MCQ Tests & Recent Learner Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Recent 50-MCQ Skill Tests (2 columns width) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <h3 class="font-extrabold text-sm text-slate-900">Recent 50-MCQ Skill Tests</h3>
                </div>
                <a href="{{ route('admin.skill-tests.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    View All {{ $stats['total_skill_tests'] }} Submissions →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-3">Student</th>
                            <th class="py-2.5 px-3">Score</th>
                            <th class="py-2.5 px-3">Percentage</th>
                            <th class="py-2.5 px-3">Breakdown</th>
                            <th class="py-2.5 px-3">Submitted</th>
                            <th class="py-2.5 px-3 text-right">Review</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($recentSkillTests as $test)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-3 font-bold text-slate-900">
                                    {{ $test->user ? $test->user->name : 'Guest Learner' }}
                                    @if($test->user)
                                        <span class="block text-[10px] text-slate-600 font-mono font-normal">{{ $test->user->email }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 font-black text-slate-900 text-sm">
                                    {{ $test->score }} <span class="text-slate-600 text-xs font-normal">/ 50</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $test->percentage >= 80 ? 'bg-emerald-100 text-emerald-800' : ($test->percentage >= 60 ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ number_format($test->percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-[11px] whitespace-nowrap">
                                    <span class="text-emerald-600 font-bold">✓ {{ $test->correct_count }}</span>
                                    <span class="text-rose-600 font-bold ml-1.5">✗ {{ $test->incorrect_count }}</span>
                                    <span class="text-slate-600 font-bold ml-1.5">⚪ {{ $test->unanswered_count }}</span>
                                </td>
                                <td class="py-3 px-3 text-slate-600 text-[11px] whitespace-nowrap">
                                    {{ $test->completed_at ? $test->completed_at->diffForHumans() : $test->created_at->diffForHumans() }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('skill-test.result', $test->uuid) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition inline-flex items-center gap-1">
                                        Inspect
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-600 italic">No skill tests submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Recent Student Signups & Reading Activity (1 column width) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="font-extrabold text-sm text-slate-900">Recent Learners</h3>
                </div>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    All Learners →
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentStudents as $student)
                    <div class="py-3 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                @if($student->avatar)
                                    <img src="{{ $student->avatar }}" class="w-7 h-7 rounded-full border border-slate-200 shrink-0" alt="">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-black flex items-center justify-center text-xs shrink-0">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="truncate">
                                    <p class="font-bold text-slate-900 truncate">{{ $student->name }}</p>
                                    <p class="text-[10px] text-slate-600 truncate font-mono">{{ $student->email }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-600 shrink-0">
                                {{ $student->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>

                        <!-- Progress / Tests stats badge -->
                        <div class="flex items-center justify-between text-[11px] bg-slate-50 rounded-lg px-2.5 py-1.5 border border-slate-100">
                            <span class="text-slate-600">
                                Reads: <strong class="text-slate-800">{{ $student->completed_reads_count }}</strong>
                            </span>
                            @if($student->skillTestAttempts->isNotEmpty())
                                <span class="text-emerald-700 font-bold">
                                    Best Test: {{ $student->skillTestAttempts->max('score') }}/50
                                </span>
                            @else
                                <span class="text-slate-600 italic">No tests yet</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-600 py-4 text-center">No registered students yet.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
