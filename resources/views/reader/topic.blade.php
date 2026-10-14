@extends('layouts.app')

@section('title', $topic->title . ' - RecapEnglish')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Left Table of Contents Sidebar / Drawer -->
        <aside class="w-full lg:w-80 shrink-0 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs lg:sticky lg:top-20 max-h-[calc(100vh-6rem)] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                <span class="text-xs font-black uppercase tracking-wider text-slate-600">Table of Contents</span>
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Book View</span>
            </div>

            <div class="space-y-4">
                @foreach($parts as $part)
                    <div>
                        <div class="text-[11px] font-black uppercase text-indigo-700 tracking-wider mb-1.5 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                            {{ $part->title }}
                        </div>
                        <div class="pl-2.5 space-y-2 border-l border-slate-100">
                            @foreach($part->chapters as $chapter)
                                <div class="space-y-1">
                                    <div class="text-xs font-bold text-slate-700">
                                        {{ $chapter->title }}
                                    </div>
                                    @foreach($chapter->topics as $chTopic)
                                        @php
                                            $isCurrent = $chTopic->id === $topic->id;
                                            $isDone = in_array($chTopic->id, $completedTopicIds);
                                        @endphp
                                        <a href="{{ route('reader.topic', $chTopic->slug) }}" 
                                           class="flex items-center justify-between text-xs py-1.5 px-2 rounded-lg transition {{ $isCurrent ? 'bg-indigo-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                                            <span class="truncate pr-2">{{ $chTopic->title }}</span>
                                            @if($isDone)
                                                <svg class="w-3.5 h-3.5 {{ $isCurrent ? 'text-white' : 'text-emerald-500' }} shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        <!-- Main Reading Pane -->
        <main class="flex-1 w-full min-w-0 space-y-6">
            
            <!-- Breadcrumbs & Action Header -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-4">
                
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <nav class="flex items-center gap-1.5 text-xs text-slate-600 truncate">
                        <a href="{{ route('reader.home') }}" class="hover:text-indigo-600 font-semibold">Book</a>
                        <span>/</span>
                        <span class="truncate">{{ $topic->chapter->part->title }}</span>
                        <span>/</span>
                        <span class="font-bold text-slate-800 truncate">{{ $topic->chapter->title }}</span>
                    </nav>

                    <!-- Interactive Action Buttons -->
                    <div class="flex items-center gap-2">
                        @auth
                            <!-- Bookmark button -->
                            <form action="{{ route('reader.toggle-bookmark', $topic->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl border {{ $isBookmarked ? 'bg-amber-50 border-amber-300 text-amber-800' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100' }} transition">
                                    <svg class="w-4 h-4 {{ $isBookmarked ? 'text-amber-500 fill-current' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                    <span>{{ $isBookmarked ? 'Saved' : 'Save Topic' }}</span>
                                </button>
                            </form>

                            <!-- Mark as Completed button -->
                            <form action="{{ route('reader.toggle-complete', $topic->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold px-3.5 py-1.5 rounded-xl border {{ $isCompleted ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-slate-900 border-slate-900 text-white hover:bg-indigo-600' }} transition">
                                    <svg class="w-4 h-4 {{ $isCompleted ? 'text-emerald-600' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $isCompleted ? 'Completed' : 'Mark Completed' }}</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                Log in to save topic progress
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Topic Title & Meta Info -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $topic->title }}
                    </h1>
                    
                    <div class="flex items-center gap-4 mt-2 text-xs text-slate-600">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            ~{{ $topic->estimated_read_time }} min read
                        </span>
                        <span>•</span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            {{ $topic->exercises->count() }} Practice MCQs
                        </span>
                        @auth
                            <span>•</span>
                            <span class="text-emerald-600 font-semibold">
                                ✓ Progress auto-saved
                            </span>
                        @endauth
                    </div>
                </div>

            </div>

            <!-- Mnemonics Box (If topic has special mnemonic e.g. BARSHA-NOVA or RUPA IS MODERN) -->
            @if($topic->mnemonics)
                <div class="rounded-2xl bg-gradient-to-r from-amber-500/10 via-amber-400/5 to-transparent border-l-4 border-amber-500 p-5 shadow-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-black text-sm shrink-0">
                            ★
                        </div>
                        <div>
                            <h3 class="font-extrabold text-sm text-amber-900 uppercase tracking-wider">Exam Shortcut / Mnemonic</h3>
                            <p class="text-xs sm:text-sm font-semibold text-amber-950 mt-1 leading-relaxed">
                                {{ $topic->mnemonics }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lecture Text & Grammar Rules Body -->
            <article class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-10 shadow-xs prose prose-slate max-w-none">
                <div class="markdown-content text-slate-800 leading-relaxed text-sm sm:text-base font-normal space-y-4">
                    {!! Str::markdown($topic->content) !!}
                </div>
            </article>

            <!-- Interactive Practice MCQs / Exam Questions -->
            @if($topic->exercises->count() > 0)
                <section class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">Interactive Drill</span>
                            <h2 class="text-xl font-black text-slate-900 mt-1">Practice Questions & Past Exam MCQs</h2>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" 
                                    onclick="toggleAllAnswers()" 
                                    id="toggle-all-btn"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 transition cursor-pointer shadow-2xs">
                                <svg id="toggle-all-icon" class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span id="toggle-all-label">Show All Answers (সব উত্তর দেখুন)</span>
                            </button>
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">
                                {{ $topic->exercises->count() }} Questions
                            </span>
                            <span class="text-xs font-extrabold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full flex items-center gap-1.5">
                                <span>Score:</span>
                                <span id="quiz-score-val">0</span> / <span>{{ $topic->exercises->count() }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @php $optLetters = ['a', 'b', 'c', 'd']; @endphp
                        @foreach($topic->exercises as $index => $exercise)
                            @php
                                $correctKey = strtolower(trim($exercise->correct_answer));
                                $correctOptText = '';
                                foreach($exercise->options as $oIdx => $oVal) {
                                    if (preg_match('/^([a-d])[\)\.]\s*(.*)/i', $oVal, $m)) {
                                        if (strtolower($m[1]) === $correctKey) {
                                            $correctOptText = $m[2];
                                            break;
                                        }
                                    } elseif (($optLetters[$oIdx] ?? '') === $correctKey) {
                                        $correctOptText = $oVal;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="p-5 sm:p-6 rounded-2xl border border-slate-200/80 bg-slate-50/50 space-y-4 transition quiz-card" id="quiz-{{ $exercise->id }}" data-answered="0" data-correct="0" data-showing-answer="0" data-correct-letter="{{ $correctKey }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-xs">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="font-bold text-sm sm:text-base text-slate-900 leading-snug">
                                            {{ $exercise->question }}
                                        </div>
                                    </div>
                                    @if($exercise->exam_reference)
                                        <span class="text-[11px] font-extrabold text-sky-800 bg-sky-50 px-3 py-1 rounded-full shrink-0 border border-sky-100 shadow-2xs">
                                            {{ $exercise->exam_reference }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Options List (Interactive) -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    @foreach($exercise->options as $optIndex => $opt)
                                        @php
                                            if (preg_match('/^([a-d])[\)\.]\s*(.*)/i', $opt, $matches)) {
                                                $letter = strtolower($matches[1]);
                                                $optText = $matches[2];
                                            } else {
                                                $letter = $optLetters[$optIndex] ?? 'a';
                                                $optText = $opt;
                                            }
                                        @endphp
                                        <button type="button" 
                                                onclick="checkAnswer({{ $exercise->id }}, '{{ $letter }}', '{{ $correctKey }}')"
                                                id="opt-{{ $exercise->id }}-{{ $letter }}"
                                                class="option-btn text-left px-4 py-3.5 rounded-xl border border-slate-200 bg-white hover:bg-indigo-50/60 hover:border-indigo-300 text-xs sm:text-sm font-semibold text-slate-800 transition shadow-2xs flex items-center gap-3 cursor-pointer">
                                            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center shrink-0 uppercase border border-slate-200/80">
                                                {{ $letter }}
                                            </span>
                                            <span class="flex-1 leading-snug">{{ $optText }}</span>
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Question Actions: Show/Hide Answer Toggle & Reset -->
                                <div class="flex items-center justify-between pt-3 border-t border-slate-200/60">
                                    <button type="button" 
                                            onclick="toggleAnswer({{ $exercise->id }}, '{{ $correctKey }}')" 
                                            id="toggle-btn-{{ $exercise->id }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-indigo-200 bg-indigo-50/70 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition cursor-pointer shadow-2xs">
                                        <svg id="eye-icon-{{ $exercise->id }}" class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span id="toggle-label-{{ $exercise->id }}">Show Answer (উত্তর দেখুন)</span>
                                    </button>

                                    <button type="button" 
                                            onclick="resetQuestion({{ $exercise->id }})" 
                                            id="reset-btn-{{ $exercise->id }}"
                                            class="hidden text-xs font-bold text-slate-500 hover:text-indigo-600 underline cursor-pointer items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span>পুনরায় চেষ্টা করুন (Try Again)</span>
                                    </button>
                                </div>

                                <!-- Explanation & Answer Box (Toggled on button click or upon answering) -->
                                <div id="expl-{{ $exercise->id }}" class="hidden pt-3 border-t border-slate-200/80 text-xs text-slate-700 space-y-3">
                                    <!-- Correct Answer Callout -->
                                    <div class="flex items-center gap-2.5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl">
                                        <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center shrink-0">
                                            ✓
                                        </span>
                                        <div class="text-xs text-emerald-950 font-bold">
                                            <span>সঠিক উত্তর (Correct Answer): </span>
                                            <span class="uppercase font-black text-emerald-800 text-sm">Option ({{ strtoupper($correctKey) }})</span>
                                            @if($correctOptText)
                                                <span class="text-emerald-900 font-semibold">— {{ $correctOptText }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status Message from Interactive Attempt -->
                                    <div class="font-bold flex items-center gap-1.5" id="status-{{ $exercise->id }}"></div>

                                    @if($exercise->explanation)
                                        <div class="bg-indigo-50/90 p-4 rounded-xl border border-indigo-100 text-indigo-950 font-medium leading-relaxed">
                                            <span class="font-extrabold text-indigo-900 block mb-1">💡 ব্যাখ্যা ও নিয়ম (Explanation):</span>
                                            {{ $exercise->explanation }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Previous & Next Topic Navigation Footer -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between gap-4">
                @if($previousTopic)
                    <a href="{{ route('reader.topic', $previousTopic->slug) }}" class="flex items-center gap-2 text-xs font-bold text-slate-700 hover:text-indigo-600 transition p-2 rounded-xl hover:bg-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <div class="text-left">
                            <span class="text-[10px] text-slate-600 block uppercase">Previous Topic</span>
                            <span class="truncate max-w-[140px] sm:max-w-xs block">{{ $previousTopic->title }}</span>
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextTopic)
                    <a href="{{ route('reader.topic', $nextTopic->slug) }}" class="flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition p-2 rounded-xl hover:bg-indigo-50 text-right">
                        <div>
                            <span class="text-[10px] text-indigo-400 block uppercase">Next Topic</span>
                            <span class="truncate max-w-[140px] sm:max-w-xs block">{{ $nextTopic->title }}</span>
                        </div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <a href="{{ route('reader.home') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                        ✓ Completed All Topics! Return to Contents
                    </a>
                @endif
            </div>

        </main>

    </div>

</div>

@push('scripts')
<script>
    let currentScore = 0;
    let allAnswersVisible = false;

    // Toggle single answer show/hide
    function toggleAnswer(exerciseId, correctLetter) {
        correctLetter = correctLetter.toLowerCase().trim();
        const container = document.getElementById('quiz-' + exerciseId);
        const explBox = document.getElementById('expl-' + exerciseId);
        const toggleLabel = document.getElementById('toggle-label-' + exerciseId);
        const eyeIcon = document.getElementById('eye-icon-' + exerciseId);
        const correctBtn = document.getElementById(`opt-${exerciseId}-${correctLetter}`);

        const isCurrentlyShown = container.getAttribute('data-showing-answer') === '1';

        if (isCurrentlyShown) {
            // Hide answer
            explBox.classList.add('hidden');
            container.setAttribute('data-showing-answer', '0');
            toggleLabel.textContent = 'Show Answer (উত্তর দেখুন)';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';

            // Only remove highlight if user hasn't attempted yet
            if (container.getAttribute('data-answered') !== '1' && correctBtn) {
                correctBtn.classList.remove('bg-emerald-100', 'text-emerald-950', 'border-emerald-500', 'font-bold');
            }
        } else {
            // Show answer
            explBox.classList.remove('hidden');
            container.setAttribute('data-showing-answer', '1');
            toggleLabel.textContent = 'Hide Answer (উত্তর লুকান)';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';

            // Highlight the correct option
            if (correctBtn && container.getAttribute('data-answered') !== '1') {
                correctBtn.classList.add('bg-emerald-100', 'text-emerald-950', 'border-emerald-500', 'font-bold');
            }
        }
    }

    // Toggle all answers show/hide across the whole topic
    function toggleAllAnswers() {
        allAnswersVisible = !allAnswersVisible;
        const allCards = document.querySelectorAll('.quiz-card');
        const toggleAllLabel = document.getElementById('toggle-all-label');
        const toggleAllIcon = document.getElementById('toggle-all-icon');

        if (allAnswersVisible) {
            toggleAllLabel.textContent = 'Hide All Answers (সব উত্তর লুকান)';
            toggleAllIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
            
            allCards.forEach(card => {
                const exId = card.id.replace('quiz-', '');
                const correctLetter = card.getAttribute('data-correct-letter');
                if (card.getAttribute('data-showing-answer') !== '1') {
                    toggleAnswer(exId, correctLetter);
                }
            });
        } else {
            toggleAllLabel.textContent = 'Show All Answers (সব উত্তর দেখুন)';
            toggleAllIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';

            allCards.forEach(card => {
                const exId = card.id.replace('quiz-', '');
                const correctLetter = card.getAttribute('data-correct-letter');
                if (card.getAttribute('data-showing-answer') === '1') {
                    toggleAnswer(exId, correctLetter);
                }
            });
        }
    }

    // Interactive answering
    function checkAnswer(exerciseId, selectedLetter, correctLetter) {
        selectedLetter = selectedLetter.toLowerCase().trim();
        correctLetter = correctLetter.toLowerCase().trim();

        const container = document.getElementById('quiz-' + exerciseId);
        if (container.getAttribute('data-answered') === '1') {
            return;
        }
        container.setAttribute('data-answered', '1');

        // Disable options
        const buttons = container.querySelectorAll('.option-btn');
        buttons.forEach(btn => btn.disabled = true);

        const selectedBtn = document.getElementById(`opt-${exerciseId}-${selectedLetter}`);
        const correctBtn = document.getElementById(`opt-${exerciseId}-${correctLetter}`);
        const explBox = document.getElementById('expl-' + exerciseId);
        const statusSpan = document.getElementById('status-' + exerciseId);
        const resetBtn = document.getElementById('reset-btn-' + exerciseId);
        const toggleLabel = document.getElementById('toggle-label-' + exerciseId);

        if (selectedLetter === correctLetter) {
            container.setAttribute('data-correct', '1');
            currentScore++;
            updateScoreDisplay();

            if (selectedBtn) {
                selectedBtn.classList.remove('bg-white', 'border-slate-200');
                selectedBtn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-700', 'shadow-sm');
            }
            statusSpan.innerHTML = '<span class="text-emerald-700 font-extrabold text-sm flex items-center gap-1.5"><svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> আপনার উত্তরটি সঠিক হয়েছে (Correct Answer)!</span>';
        } else {
            container.setAttribute('data-correct', '0');
            if (selectedBtn) {
                selectedBtn.classList.remove('bg-white', 'border-slate-200');
                selectedBtn.classList.add('bg-rose-500', 'text-white', 'border-rose-600', 'shadow-sm');
            }
            if (correctBtn) {
                correctBtn.classList.remove('bg-white', 'border-slate-200');
                correctBtn.classList.add('bg-emerald-100', 'text-emerald-950', 'border-emerald-500', 'font-bold');
            }
            statusSpan.innerHTML = `<span class="text-rose-600 font-extrabold text-sm flex items-center gap-1.5"><svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg> ভুল উত্তর নির্বাচন করেছেন। সঠিক উত্তর হলো (${correctLetter.toUpperCase()})</span>`;
        }

        // Show answer & explanation
        explBox.classList.remove('hidden');
        container.setAttribute('data-showing-answer', '1');
        if (toggleLabel) toggleLabel.textContent = 'Hide Answer (উত্তর লুকান)';
        if (resetBtn) {
            resetBtn.classList.remove('hidden');
            resetBtn.classList.add('inline-flex');
        }
    }

    // Reset single question to retry
    function resetQuestion(exerciseId) {
        const container = document.getElementById('quiz-' + exerciseId);
        const wasCorrect = container.getAttribute('data-correct') === '1';

        if (wasCorrect) {
            currentScore = Math.max(0, currentScore - 1);
            updateScoreDisplay();
        }

        container.setAttribute('data-answered', '0');
        container.setAttribute('data-correct', '0');
        container.setAttribute('data-showing-answer', '0');

        const buttons = container.querySelectorAll('.option-btn');
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.className = 'option-btn text-left px-4 py-3.5 rounded-xl border border-slate-200 bg-white hover:bg-indigo-50/60 hover:border-indigo-300 text-xs sm:text-sm font-semibold text-slate-800 transition shadow-2xs flex items-center gap-3 cursor-pointer';
        });

        const explBox = document.getElementById('expl-' + exerciseId);
        explBox.classList.add('hidden');

        const statusSpan = document.getElementById('status-' + exerciseId);
        if (statusSpan) statusSpan.innerHTML = '';

        const resetBtn = document.getElementById('reset-btn-' + exerciseId);
        if (resetBtn) {
            resetBtn.classList.add('hidden');
            resetBtn.classList.remove('inline-flex');
        }

        const toggleLabel = document.getElementById('toggle-label-' + exerciseId);
        if (toggleLabel) toggleLabel.textContent = 'Show Answer (উত্তর দেখুন)';
    }

    function updateScoreDisplay() {
        const scoreElem = document.getElementById('quiz-score-val');
        if (scoreElem) {
            scoreElem.textContent = currentScore;
        }
    }
</script>
@endpush
@endsection
