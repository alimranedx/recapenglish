@extends('layouts.app')

@section('title', 'Skill Test Performance Analysis - RecapEnglish')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    <!-- Top Scoreboard Card -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-indigo-900 text-white p-6 sm:p-10 shadow-2xl border border-indigo-800/40">
        <div class="absolute -right-10 -bottom-10 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            
            <!-- Left Info -->
            <div class="space-y-4 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-200 text-xs font-bold tracking-wide uppercase">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Test Completed & Evaluated</span>
                    <span class="w-1 h-1 rounded-full bg-indigo-300"></span>
                    <span>All Topics Assessed</span>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight">
                    Your Skill Test Score & Performance Report
                </h1>

                <p class="text-indigo-100/80 text-xs sm:text-sm leading-relaxed">
                    Test taken on {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y - h:i A') : $attempt->created_at->format('M d, Y') }}. 
                    Examine all 50 questions below — view correct answers for questions you answered wrong as well as questions you didn't touch (skipped).
                </p>

                <!-- Evaluation Tier Badge & Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    @php
                        $pct = $attempt->percentage;
                        if ($pct >= 80) {
                            $badgeBg = 'bg-emerald-500/20 text-emerald-300 border-emerald-400/40';
                            $badgeText = '🏆 Outstanding! BCS & Admission Ready (উচ্চমান দক্ষতা)';
                        } elseif ($pct >= 65) {
                            $badgeBg = 'bg-sky-500/20 text-sky-300 border-sky-400/40';
                            $badgeText = '🎯 Strong Performance (ভালো প্রস্তুতি)';
                        } elseif ($pct >= 50) {
                            $badgeBg = 'bg-amber-500/20 text-amber-300 border-amber-400/40';
                            $badgeText = '📖 Moderate - Target Weak Topics (মাঝারি প্রস্তুতি)';
                        } else {
                            $badgeBg = 'bg-rose-500/20 text-rose-300 border-rose-400/40';
                            $badgeText = '⚠️ Needs Revision (প্রাথমিক দুর্বলতা)';
                        }
                    @endphp
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border text-xs sm:text-sm font-bold {{ $badgeBg }}">
                        <span>{{ $badgeText }}</span>
                    </div>

                    @if($attempt->incorrect_count > 0)
                        <button type="button" 
                                onclick="scrollToReview('incorrect')" 
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs sm:text-sm font-extrabold shadow-md transition transform hover:scale-105 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Show {{ $attempt->incorrect_count }} Wrong Answers (ভুল উত্তরগুলো) ↓</span>
                        </button>
                    @endif

                    @if($attempt->unanswered_count > 0)
                        <button type="button" 
                                onclick="scrollToReview('unanswered')" 
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs sm:text-sm font-black shadow-md transition transform hover:scale-105 cursor-pointer">
                            <span>⚪ Show {{ $attempt->unanswered_count }} Untouched Questions & Answers (না ছোঁয়া প্রশ্ন ও উত্তর) ↓</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Right: Circular Score / Big Gauge -->
            <div class="flex flex-col sm:flex-row items-center gap-6 bg-white/10 backdrop-blur rounded-3xl p-6 border border-white/10 shrink-0">
                <div class="text-center">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-200 block mb-1">Total Score</span>
                    <div class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                        {{ $attempt->score }} <span class="text-xl sm:text-2xl font-bold text-indigo-300">/ {{ $attempt->total_questions }}</span>
                    </div>
                    <div class="text-sm font-extrabold text-amber-300 mt-1">
                        {{ number_format($attempt->percentage, 1) }}% Score
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex flex-col gap-2 w-full sm:w-auto">
                    <form action="{{ route('skill-test.start') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-emerald-500/25 transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Retake (New 50 Questions)</span>
                        </button>
                    </form>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="window.print()" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition cursor-pointer print:hidden">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print / PDF</span>
                        </button>
                        <button type="button" onclick="shareScore()" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition cursor-pointer print:hidden">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            <span id="share-btn-text">Share</span>
                        </button>
                    </div>

                    <a href="{{ route('reader.home') }}" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition print:hidden">
                        Back to Syllabus
                    </a>
                </div>
            </div>

        </div>

        <!-- Metric Cards (Clickable Quick Filters) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 pt-8 border-t border-white/10 mt-8">
            <button type="button" 
                    onclick="scrollToReview('correct')" 
                    class="text-left bg-white/5 hover:bg-white/15 rounded-2xl p-4 border border-white/10 transition cursor-pointer group">
                <div class="flex items-center justify-between text-emerald-400 mb-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Correct Answers</span>
                    <svg class="w-4 h-4 group-hover:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="text-2xl font-black text-white">{{ $attempt->correct_count }}</div>
                <span class="text-[10px] text-emerald-300 font-bold block mt-1">Click to view correct →</span>
            </button>

            <button type="button" 
                    onclick="scrollToReview('incorrect')" 
                    class="text-left bg-rose-500/20 hover:bg-rose-500/30 rounded-2xl p-4 border border-rose-400/40 transition cursor-pointer group">
                <div class="flex items-center justify-between text-rose-300 mb-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Incorrect</span>
                    <svg class="w-4 h-4 group-hover:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div class="text-2xl font-black text-white">{{ $attempt->incorrect_count }}</div>
                <span class="text-[10px] text-rose-200 font-bold block mt-1">Click to view wrong answers →</span>
            </button>

            <button type="button" 
                    onclick="scrollToReview('unanswered')" 
                    class="text-left bg-amber-500/20 hover:bg-amber-500/30 rounded-2xl p-4 border border-amber-400/40 transition cursor-pointer group">
                <div class="flex items-center justify-between text-amber-300 mb-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Untouched (না ছোঁয়া)</span>
                    <svg class="w-4 h-4 group-hover:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                </div>
                <div class="text-2xl font-black text-white">{{ $attempt->unanswered_count }}</div>
                <span class="text-[10px] text-amber-200 font-bold block mt-1">Click to view untouched & answers →</span>
            </button>

            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="flex items-center justify-between text-sky-400 mb-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Time Taken</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-2xl font-black text-white">
                    @if($attempt->duration_seconds)
                        {{ floor($attempt->duration_seconds / 60) }}m {{ $attempt->duration_seconds % 60 }}s
                    @else
                        -
                    @endif
                </div>
                <span class="text-[10px] text-sky-300 font-bold">Duration</span>
            </div>
        </div>

    </div>

    <!-- PRIMARY SECTION: Question-by-Question Solutions with Given Answer vs Correct Answer -->
    <div id="questions-review-section" class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 space-y-6 shadow-xs scroll-mt-24">
        
        <!-- Header & Filter Tabs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span class="text-[11px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">All Questions Review</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                    Questions, Given Answers & Correct Solutions
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    View answers for all questions: correct choices, questions answered wrong, and questions not touched (skipped).
                </p>
            </div>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 shrink-0 overflow-x-auto text-xs font-bold p-1 bg-slate-100/80 rounded-2xl">
                <button type="button" 
                        onclick="filterReview('all')" 
                        id="rev-filter-all" 
                        class="rev-tab px-3.5 py-2 rounded-xl bg-indigo-600 text-white shadow-xs font-extrabold transition cursor-pointer">
                    All ({{ count($exercises) }})
                </button>

                @if($attempt->incorrect_count > 0)
                    <button type="button" 
                            onclick="filterReview('incorrect')" 
                            id="rev-filter-incorrect" 
                            class="rev-tab px-3.5 py-2 rounded-xl text-slate-700 hover:bg-white font-extrabold transition cursor-pointer flex items-center gap-1.5">
                        <span class="text-rose-600">✗ Wrong ({{ $attempt->incorrect_count }})</span>
                    </button>
                @endif

                @if($attempt->unanswered_count > 0)
                    <button type="button" 
                            onclick="filterReview('unanswered')" 
                            id="rev-filter-unanswered" 
                            class="rev-tab px-3.5 py-2 rounded-xl text-slate-700 hover:bg-white font-extrabold transition cursor-pointer flex items-center gap-1.5">
                        <span class="text-amber-700">⚪ Untouched / Skipped ({{ $attempt->unanswered_count }})</span>
                    </button>
                @endif

                <button type="button" 
                        onclick="filterReview('correct')" 
                        id="rev-filter-correct" 
                        class="rev-tab px-3.5 py-2 rounded-xl text-slate-700 hover:bg-white font-extrabold transition cursor-pointer flex items-center gap-1.5">
                    <span class="text-emerald-700">✓ Correct ({{ $attempt->correct_count }})</span>
                </button>
            </div>
        </div>

        <!-- Filter Count Alert -->
        <div id="filter-active-message" class="text-xs font-bold text-slate-700 p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <span id="filter-active-text">Showing all {{ count($exercises) }} questions with their correct solutions</span>
            <span class="text-[11px] text-slate-400 font-normal">Scroll to examine any question</span>
        </div>

        <!-- Questions List Review -->
        <div class="space-y-6" id="review-cards-list">
            @php 
                $optLetters = ['a', 'b', 'c', 'd', 'e'];
                $userAnswers = $attempt->user_answers ?? [];
            @endphp

            @foreach($exercises as $index => $exercise)
                @php
                    $userAns = isset($userAnswers[$exercise->id]) ? strtolower(trim($userAnswers[$exercise->id])) : null;
                    if ($userAns === '') $userAns = null;
                    $correctAns = strtolower(trim($exercise->correct_answer));
                    
                    if (empty($userAns)) {
                        $reviewStatus = 'unanswered';
                    } elseif ($userAns === $correctAns) {
                        $reviewStatus = 'correct';
                    } else {
                        $reviewStatus = 'incorrect';
                    }

                    // Extract exact label texts for user selection and correct answer
                    $userOptText = '';
                    $correctOptText = '';
                    foreach ($exercise->options as $optIdx => $optStr) {
                        if (preg_match('/^([a-e])[\)\.]\s*(.*)/i', $optStr, $m)) {
                            $curLetter = strtolower($m[1]);
                            $curText = $m[2];
                        } else {
                            $curLetter = $optLetters[$optIdx] ?? 'a';
                            $curText = $optStr;
                        }

                        if ($curLetter === $userAns) {
                            $userOptText = $curText;
                        }
                        if ($curLetter === $correctAns) {
                            $correctOptText = $curText;
                        }
                    }
                @endphp

                <div class="p-5 sm:p-7 rounded-3xl border transition review-card {{ $reviewStatus === 'correct' ? 'border-emerald-300 bg-emerald-50/15' : ($reviewStatus === 'incorrect' ? 'border-rose-300 bg-rose-50/20' : 'border-amber-200/90 bg-amber-50/10') }}" 
                     id="rev-card-{{ $exercise->id }}"
                     data-status="{{ $reviewStatus }}">

                    <!-- Card Header -->
                    <div class="flex items-start justify-between gap-3 border-b border-slate-200/80 pb-3 mb-4">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="w-8 h-8 rounded-xl font-black text-xs sm:text-sm flex items-center justify-center shrink-0 {{ $reviewStatus === 'correct' ? 'bg-emerald-600 text-white' : ($reviewStatus === 'incorrect' ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white') }}">
                                {{ $index + 1 }}
                            </span>

                            @if($reviewStatus === 'correct')
                                <span class="text-xs font-black text-emerald-800 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-xl flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Correct Answer (সঠিক উত্তর)</span>
                                </span>
                            @elseif($reviewStatus === 'incorrect')
                                <span class="text-xs font-black text-rose-800 bg-rose-100 border border-rose-300 px-3 py-1 rounded-xl flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Wrong Answer (ভুল উত্তর দিয়েছেন)</span>
                                </span>
                            @else
                                <span class="text-xs font-black text-amber-900 bg-amber-100 border border-amber-300 px-3 py-1 rounded-xl flex items-center gap-1.5">
                                    <span>⚪</span> <span>Untouched Question (আপনি স্পর্শ করেননি — সঠিক উত্তর নিচে দেখুন)</span>
                                </span>
                            @endif

                            @if($exercise->topic)
                                <a href="{{ route('reader.topic', $exercise->topic->slug) }}" target="_blank" class="text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-2.5 py-1 rounded-lg transition">
                                    {{ $exercise->topic->title }}
                                </a>
                            @endif
                        </div>

                        @if($exercise->exam_reference)
                            <span class="text-[11px] font-extrabold text-sky-800 bg-sky-50 px-3 py-1 rounded-full shrink-0 border border-sky-100 shadow-2xs">
                                {{ $exercise->exam_reference }}
                            </span>
                        @endif
                    </div>

                    <!-- Question Stem -->
                    <div class="text-base sm:text-lg font-bold text-slate-900 leading-snug mb-5">
                        {{ $exercise->question }}
                    </div>

                    <!-- HIGH-CONTRAST ANSWER COMPARISON BANNER -->
                    <div class="mb-5 p-4 rounded-2xl border {{ $reviewStatus === 'correct' ? 'bg-emerald-50 border-emerald-200' : ($reviewStatus === 'incorrect' ? 'bg-rose-50 border-rose-200' : 'bg-amber-50/70 border-amber-200') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            
                            <!-- Student's Given Answer -->
                            <div class="p-3.5 rounded-xl bg-white border {{ $reviewStatus === 'correct' ? 'border-emerald-300' : ($reviewStatus === 'incorrect' ? 'border-rose-400 ring-2 ring-rose-400/20' : 'border-amber-300') }} flex items-start gap-3">
                                <span class="w-7 h-7 rounded-lg font-black text-xs flex items-center justify-center shrink-0 uppercase {{ $reviewStatus === 'correct' ? 'bg-emerald-600 text-white' : ($reviewStatus === 'incorrect' ? 'bg-rose-600 text-white' : 'bg-amber-100 text-amber-800 border border-amber-300') }}">
                                    {{ $userAns ? strtoupper($userAns) : '⚪' }}
                                </span>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase tracking-wider block {{ $reviewStatus === 'correct' ? 'text-emerald-700' : ($reviewStatus === 'incorrect' ? 'text-rose-700' : 'text-amber-800') }}">
                                        Your Given Answer (আপনার দেয়া উত্তর):
                                    </span>
                                    @if($userAns)
                                        <p class="font-extrabold text-sm {{ $reviewStatus === 'correct' ? 'text-emerald-950' : 'text-rose-950' }} mt-0.5 leading-snug">
                                            ({{ strtoupper($userAns) }}) {{ $userOptText }}
                                            @if($reviewStatus === 'incorrect')
                                                <span class="text-xs font-black text-rose-600 ml-1">✗ (Incorrect)</span>
                                            @else
                                                <span class="text-xs font-black text-emerald-600 ml-1">✓ (Correct)</span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="font-extrabold text-xs text-amber-900 mt-0.5">
                                            Not Touched / Skipped (স্পর্শ করেননি)
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- The Actual Correct Answer -->
                            <div class="p-3.5 rounded-xl bg-white border border-emerald-400 ring-2 ring-emerald-500/20 flex items-start gap-3">
                                <span class="w-7 h-7 rounded-lg font-black text-xs flex items-center justify-center shrink-0 uppercase bg-emerald-600 text-white">
                                    {{ strtoupper($correctAns) }}
                                </span>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">
                                        Correct Answer (সঠিক উত্তর):
                                    </span>
                                    <p class="font-extrabold text-sm text-emerald-950 mt-0.5 leading-snug">
                                        ({{ strtoupper($correctAns) }}) {{ $correctOptText }}
                                        <span class="text-xs font-black text-emerald-600 ml-1">✓ (Right Choice)</span>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- All 4 Options Breakdown -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                        @foreach($exercise->options as $optIndex => $opt)
                            @php
                                if (preg_match('/^([a-e])[\)\.]\s*(.*)/i', $opt, $matches)) {
                                    $letter = strtolower($matches[1]);
                                    $optText = $matches[2];
                                } else {
                                    $letter = $optLetters[$optIndex] ?? 'a';
                                    $optText = $opt;
                                }

                                $isThisCorrect = ($letter === $correctAns);
                                $isThisUserChoice = ($letter === $userAns);
                            @endphp

                            <div class="flex items-center gap-3 p-3.5 rounded-2xl border text-xs sm:text-sm font-semibold transition
                                @if($isThisCorrect)
                                    bg-emerald-50 border-emerald-500 text-emerald-950 font-bold ring-2 ring-emerald-500/20 shadow-xs
                                @elseif($isThisUserChoice && !$isThisCorrect)
                                    bg-rose-50 border-rose-400 text-rose-950 font-bold ring-2 ring-rose-400/20 shadow-xs
                                @else
                                    bg-white border-slate-200 text-slate-700
                                @endif">
                                
                                <span class="w-7 h-7 rounded-lg font-black text-xs flex items-center justify-center shrink-0 uppercase
                                    @if($isThisCorrect)
                                        bg-emerald-600 text-white
                                    @elseif($isThisUserChoice && !$isThisCorrect)
                                        bg-rose-600 text-white
                                    @else
                                        bg-slate-100 text-slate-700 border border-slate-200
                                    @endif">
                                    {{ $letter }}
                                </span>

                                <span class="flex-1 leading-snug">{{ $optText }}</span>

                                @if($isThisCorrect)
                                    <span class="text-[11px] font-black uppercase text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md shrink-0 flex items-center gap-1">
                                        <span>✓</span> <span>Correct Answer</span>
                                    </span>
                                @elseif($isThisUserChoice && !$isThisCorrect)
                                    <span class="text-[11px] font-black uppercase text-rose-700 bg-rose-100 px-2 py-0.5 rounded-md shrink-0 flex items-center gap-1">
                                        <span>✗</span> <span>Your Choice</span>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Detailed Explanation & Notes -->
                    @if($exercise->explanation)
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 text-xs sm:text-sm text-slate-700 space-y-2 font-bangla leading-relaxed shadow-2xs">
                            <span class="font-bold text-slate-900 font-sans uppercase text-[11px] tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                <span>ব্যাখ্যা ও সঠিক উত্তরের যুক্তি (Explanation):</span>
                            </span>
                            <div class="whitespace-pre-line text-slate-800 pt-1 border-t border-slate-100">
                                {{ $exercise->explanation }}
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

    </div>

    <!-- Section 2: Topic-By-Topic Performance Breakdown -->
    @if(!empty($attempt->topic_breakdown))
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 space-y-6 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">Diagnostic Analytics</span>
                    <h2 class="text-xl font-black text-slate-900 mt-1">Topic-by-Topic Mastery Matrix</h2>
                    <p class="text-xs text-slate-500">Every topic was tested. Click "Review Topic Rules" to study any topics where you made mistakes.</p>
                </div>
                <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1.5 rounded-xl self-start sm:self-auto">
                    {{ count($attempt->topic_breakdown) }} Topics Assessed
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($attempt->topic_breakdown as $topicStat)
                    @php
                        $tPct = $topicStat['percentage'] ?? 0;
                        if ($tPct >= 80) {
                            $statusColor = 'text-emerald-700 bg-emerald-50 border-emerald-200';
                            $statusLabel = 'Mastered (দক্ষতা সম্পন্ন)';
                            $barColor = 'bg-emerald-500';
                        } elseif ($tPct >= 50) {
                            $statusColor = 'text-amber-700 bg-amber-50 border-amber-200';
                            $statusLabel = 'Satisfactory (মাঝারি)';
                            $barColor = 'bg-amber-500';
                        } else {
                            $statusColor = 'text-rose-700 bg-rose-50 border-rose-200';
                            $statusLabel = 'Needs Revision (রিভিশন প্রয়োজন)';
                            $barColor = 'bg-rose-500';
                        }
                    @endphp
                    <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 flex flex-col justify-between gap-3">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-1" title="{{ $topicStat['title'] }}">
                                    {{ $topicStat['title'] }}
                                </h3>
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md border shrink-0 {{ $statusColor }}">
                                    {{ $topicStat['correct'] }} / {{ $topicStat['total'] }}
                                </span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2">
                                <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ $tPct }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1.5">
                                <span>{{ $statusLabel }}</span>
                                <span class="font-extrabold text-slate-700">{{ $tPct }}%</span>
                            </div>
                        </div>

                        <!-- Revise Link -->
                        @if(!empty($topicStat['slug']))
                            <div class="pt-2 border-t border-slate-200/60 flex justify-end">
                                <a href="{{ route('reader.topic', $topicStat['slug']) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-extrabold text-indigo-600 hover:text-indigo-800 transition">
                                    <span>Review Topic Rules</span>
                                    <span>→</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
    const totalCount = {{ count($exercises) }};
    const incorrectCount = {{ $attempt->incorrect_count }};
    const correctCount = {{ $attempt->correct_count }};
    const unansweredCount = {{ $attempt->unanswered_count }};

    function filterReview(status) {
        // Toggle tab styles
        const tabs = ['all', 'incorrect', 'unanswered', 'correct'];
        tabs.forEach(t => {
            const btn = document.getElementById('rev-filter-' + t);
            if (btn) {
                if (t === status) {
                    if (t === 'incorrect') {
                        btn.className = 'rev-tab px-3.5 py-2 rounded-xl bg-rose-600 text-white shadow-xs font-extrabold transition cursor-pointer flex items-center gap-1.5';
                    } else if (t === 'unanswered') {
                        btn.className = 'rev-tab px-3.5 py-2 rounded-xl bg-amber-500 text-slate-950 shadow-xs font-black transition cursor-pointer flex items-center gap-1.5';
                    } else if (t === 'correct') {
                        btn.className = 'rev-tab px-3.5 py-2 rounded-xl bg-emerald-600 text-white shadow-xs font-extrabold transition cursor-pointer flex items-center gap-1.5';
                    } else {
                        btn.className = 'rev-tab px-3.5 py-2 rounded-xl bg-indigo-600 text-white shadow-xs font-extrabold transition cursor-pointer';
                    }
                } else {
                    btn.className = 'rev-tab px-3.5 py-2 rounded-xl text-slate-700 hover:bg-white font-bold transition cursor-pointer flex items-center gap-1.5';
                }
            }
        });

        // Filter question cards
        const cards = document.querySelectorAll('.review-card');
        let visibleCount = 0;
        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'all' || cardStatus === status) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update active message text
        const msgEl = document.getElementById('filter-active-text');
        if (msgEl) {
            if (status === 'incorrect') {
                msgEl.innerText = `Showing all ${visibleCount} questions you answered incorrectly (showing your choice vs correct answer)`;
            } else if (status === 'unanswered') {
                msgEl.innerText = `Showing all ${visibleCount} questions you didn't touch (showing the correct answers and explanations for each)`;
            } else if (status === 'correct') {
                msgEl.innerText = `Showing all ${visibleCount} questions you answered correctly`;
            } else {
                msgEl.innerText = `Showing all ${visibleCount} questions (both answered and untouched) with correct solutions`;
            }
        }
    }

    function scrollToReview(filter) {
        if (filter) {
            filterReview(filter);
        }
        const el = document.getElementById('questions-review-section');
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function shareScore() {
        const text = "I scored {{ $attempt->score }}/{{ $attempt->total_questions }} ({{ number_format($attempt->percentage, 1) }}%) on the RecapEnglish 50-Question Master Skill Test! Test your English grammar skills here:";
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({
                title: 'RecapEnglish Skill Test Score',
                text: text,
                url: url
            }).catch(() => {});
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(text + " " + url).then(() => {
                const btnText = document.getElementById('share-btn-text');
                if (btnText) {
                    btnText.innerText = 'Copied!';
                    setTimeout(() => { btnText.innerText = 'Share'; }, 2000);
                }
            });
        }
    }

    // Default to 'all' so every question and its correct answer is immediately visible
    document.addEventListener('DOMContentLoaded', function() {
        filterReview('all');
    });
</script>
@endsection
