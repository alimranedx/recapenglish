@extends('layouts.app')

@section('title', '50-Question Comprehensive English Skill Test - RecapEnglish')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-950 via-indigo-900 to-slate-900 text-white p-8 sm:p-12 shadow-2xl border border-indigo-700/40">
        <div class="absolute -right-12 -bottom-12 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -top-12 w-80 h-80 bg-sky-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl space-y-5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/25 border border-indigo-400/40 text-indigo-200 text-xs font-bold tracking-wide uppercase">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span>Full Syllabus Skill Assessment</span>
                <span class="w-1 h-1 rounded-full bg-indigo-300"></span>
                <span>Randomized Pool</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                {{ $config->title ?? '50-Question Master Skill Test' }}
                <span class="block text-xl sm:text-2xl font-bold text-indigo-200/90 font-bangla mt-2">
                    {{ $config->topic_mode === 'fixed' ? 'নির্বাচিত গুরুত্বপূর্ণ টপিকসমূহে আপনার সার্বিক প্রস্তুতি যাচাই করুন' : 'সকল টপিক থেকে বিভিন্ন প্রশ্নে আপনার সার্বিক ইংরেজি গ্রামার দক্ষতা যাচাই করুন' }}
                </span>
            </h1>

            <p class="text-indigo-100/90 text-sm sm:text-base leading-relaxed">
                Take an authentic exam-style skill test. Every single test is dynamically generated with 
                <strong class="text-amber-300">{{ $config->total_questions ?? 50 }} questions selected randomly</strong> 
                {{ $config->topic_mode === 'fixed' ? 'from ' . $totalTopicsCount . ' designated key topics' : 'across every topic in the book — guarantee that no topic is omitted!' }} 
                Immediate scoring, topic-wise diagnostic analytics, and detailed bilingual solutions.
            </p>

            @if(!empty($config->instructions))
                <div class="p-4 rounded-2xl bg-white/10 border border-white/15 text-xs text-indigo-100 font-medium leading-relaxed backdrop-blur">
                    <strong class="text-amber-300 block mb-1 uppercase tracking-wider text-[11px]">Notice & Guidelines:</strong>
                    {{ $config->instructions }}
                </div>
            @endif

            <!-- Test Meta Highlights -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10">
                    <span class="text-[11px] font-semibold text-indigo-200 uppercase block">Questions</span>
                    <span class="text-xl font-extrabold text-white">{{ $config->total_questions ?? 50 }} MCQs</span>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10">
                    <span class="text-[11px] font-semibold text-indigo-200 uppercase block">Topic Scope</span>
                    <span class="text-xl font-extrabold text-emerald-300">
                        {{ $config->topic_mode === 'fixed' ? $totalTopicsCount . ' Fixed Topics' : '100% Guaranteed' }}
                    </span>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10">
                    <span class="text-[11px] font-semibold text-indigo-200 uppercase block">Pass Benchmark</span>
                    <span class="text-xl font-extrabold text-amber-300">{{ $config->pass_percentage ?? 60 }}%</span>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10">
                    <span class="text-[11px] font-semibold text-indigo-200 uppercase block">Exam Duration</span>
                    <span class="text-xl font-extrabold text-sky-300">{{ $config->duration_minutes ?? 50 }} Minutes</span>
                </div>
            </div>

            <!-- Start Action Button -->
            <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <form action="{{ route('skill-test.start') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-black text-base rounded-2xl shadow-xl shadow-emerald-500/25 transition transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Start {{ $config->total_questions ?? 50 }}-Question Skill Test (পরীক্ষা শুরু করুন)</span>
                    </button>
                </form>

                <a href="{{ route('reader.home') }}" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm backdrop-blur transition">
                    Review Curriculum First
                </a>
            </div>
        </div>
    </div>

    <!-- Feature Pillars -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-3 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h3 class="font-extrabold text-base text-slate-900">Never Misses Any Topic</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                The smart question allocator mandates that every topic in our 15 core chapters provides questions into your 50-question test session.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-3 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <h3 class="font-extrabold text-base text-slate-900">Fresh Questions Each Time</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Questions are randomly picked from a comprehensive database of {{ number_format($totalQuestionsInPool) }}+ BCS and university admission questions. Each attempt gives a distinct paper.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-3 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="font-extrabold text-base text-slate-900">Topic-by-Topic Score Matrix</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Receive immediate diagnostic insights showing which grammatical areas you've mastered and which topics require immediate revision with direct study links.
            </p>
        </div>
    </div>

    <!-- Topics Grid (Guarantee Proof) -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 space-y-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <span class="text-[11px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">Coverage Guarantee</span>
                <h2 class="text-xl font-black text-slate-900 mt-1">All {{ $totalTopicsCount }} Syllabus Topics Included</h2>
                <p class="text-xs text-slate-500">Every single topic below contributes random questions to every 50-question test session.</p>
            </div>
            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1.5 rounded-xl self-start sm:self-auto">
                Total Pool: {{ number_format($totalQuestionsInPool) }} Questions
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
            @foreach($topics as $index => $t)
                <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-indigo-50/40 hover:border-indigo-200 transition flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-slate-900 truncate" title="{{ $t->title }}">{{ $t->title }}</h4>
                            <span class="text-[11px] text-slate-500 font-bangla">প্রশ্ন ভাণ্ডার: {{ $t->exercises_count }} টি</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 shrink-0">
                        Active
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Attempts (if any) -->
    @if($recentAttempts->isNotEmpty())
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 space-y-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Your Recent Skill Test Scores</h2>
                    <p class="text-xs text-slate-500">Review your past test results and tracking progress.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Score</th>
                            <th class="py-3 px-4">Percentage</th>
                            <th class="py-3 px-4">Accuracy Breakdown</th>
                            <th class="py-3 px-4">Duration</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @foreach($recentAttempts as $attempt)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 font-normal text-slate-600">
                                    {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y h:i A') : $attempt->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-sm font-extrabold text-slate-900">{{ $attempt->score }}</span>
                                    <span class="text-slate-500">/ {{ $attempt->total_questions }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @php $pct = $attempt->percentage; @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold {{ $pct >= 80 ? 'bg-emerald-100 text-emerald-800' : ($pct >= 60 ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ number_format($pct, 1) }}%
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2 text-[11px]">
                                        <span class="text-emerald-600 font-bold">✓ {{ $attempt->correct_count }}</span>
                                        <span class="text-rose-600 font-bold">✗ {{ $attempt->incorrect_count }}</span>
                                        <span class="text-slate-400 font-bold">⚪ {{ $attempt->unanswered_count }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    @if($attempt->duration_seconds)
                                        {{ floor($attempt->duration_seconds / 60) }}m {{ $attempt->duration_seconds % 60 }}s
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('skill-test.result', $attempt->uuid) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                        <span>View Analysis</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
