@extends('layouts.app')

@section('title', 'Taking Skill Test (50 Questions) - RecapEnglish')

@section('content')
<div class="bg-slate-100/70 min-h-screen pb-24">

    <!-- Sticky Exam Control Bar -->
    <div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-slate-200/90 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
            
            <!-- Left: Info -->
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <div>
                    <h1 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $config->title ?? 'Master Skill Test' }}</h1>
                    <span class="text-[11px] text-slate-500 hidden sm:inline">Randomized from {{ count($exercises->pluck('topic_id')->unique()) }} topics</span>
                </div>
            </div>

            <!-- Middle: Progress & Stats -->
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Progress</span>
                    <span class="text-xs font-black text-slate-800">
                        <span id="answered-counter-top">0</span> / {{ count($exercises) }} Answered
                    </span>
                </div>

                <!-- Timer Indicator -->
                <div id="exam-timer-box" class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-900 text-white font-mono text-sm font-black tracking-wider shadow-inner">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="timer-display">{{ sprintf('%02d:%02d', floor($remainingSeconds / 60), $remainingSeconds % 60) }}</span>
                </div>
            </div>

            <!-- Right: Submit Action Button -->
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="toggleMobileNavigator()" 
                        class="lg:hidden inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    <span>Grid</span>
                </button>

                <button type="button" 
                        onclick="openSubmitModal()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-emerald-600/20 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Submit</span>
                </button>
            </div>

        </div>

        <!-- Thin Interactive Progress Bar -->
        <div class="w-full bg-slate-200 h-1">
            <div id="test-progress-bar" class="h-1 bg-gradient-to-r from-indigo-500 to-emerald-500 transition-all duration-200" style="width: 0%;"></div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <form id="skill-test-form" action="{{ route('skill-test.submit', $attempt->uuid) }}" method="POST">
            @csrf
            <input type="hidden" name="duration_seconds" id="duration_seconds" value="{{ $attempt->duration_seconds ?? 0 }}">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left Column: Questions List (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    @php 
                        $optLetters = ['a', 'b', 'c', 'd', 'e']; 
                        $preAnswers = $attempt->user_answers ?? [];
                    @endphp

                    @foreach($exercises as $index => $exercise)
                        @php
                            $selectedLetter = strtolower(trim($preAnswers[$exercise->id] ?? ''));
                            $isAnswered = !empty($selectedLetter);
                        @endphp
                        <div class="bg-white rounded-3xl border {{ $isAnswered ? 'border-indigo-300' : 'border-slate-200/90' }} p-5 sm:p-7 shadow-xs space-y-5 transition scroll-mt-32 question-card" 
                             id="question-card-{{ $exercise->id }}"
                             data-question-id="{{ $exercise->id }}"
                             data-index="{{ $index + 1 }}">

                            <!-- Question Header -->
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="w-8 h-8 rounded-xl bg-slate-900 text-white font-black text-xs sm:text-sm flex items-center justify-center shrink-0">
                                        {{ $index + 1 }}
                                    </span>

                                    @if($exercise->topic)
                                        <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-100/80 px-2.5 py-1 rounded-lg">
                                            {{ $exercise->topic->title }}
                                        </span>
                                    @endif
                                </div>

                                @if($exercise->exam_reference)
                                    <span class="text-[11px] font-extrabold text-sky-800 bg-sky-50 px-3 py-1 rounded-full shrink-0 border border-sky-100 shadow-2xs">
                                        {{ $exercise->exam_reference }}
                                    </span>
                                @endif
                            </div>

                            <!-- Question Stem -->
                            <div class="text-sm sm:text-base font-bold text-slate-900 leading-relaxed">
                                {{ $exercise->question }}
                            </div>

                            <!-- MCQ Options -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                @foreach($exercise->options as $optIndex => $opt)
                                    @php
                                        if (preg_match('/^([a-e])[\)\.]\s*(.*)/i', $opt, $matches)) {
                                            $letter = strtolower($matches[1]);
                                            $optText = $matches[2];
                                        } else {
                                            $letter = $optLetters[$optIndex] ?? 'a';
                                            $optText = $opt;
                                        }
                                        $isSelected = ($letter === $selectedLetter);
                                    @endphp
                                    <label for="ans_{{ $exercise->id }}_{{ $letter }}" 
                                           id="label_{{ $exercise->id }}_{{ $letter }}"
                                           class="option-container flex items-center gap-3 p-3.5 rounded-2xl border transition cursor-pointer select-none {{ $isSelected ? 'bg-indigo-50 border-indigo-500 ring-2 ring-indigo-500/20' : 'border-slate-200 bg-slate-50/50 hover:bg-indigo-50/50 hover:border-indigo-300' }}">
                                        <input type="radio" 
                                               name="answers[{{ $exercise->id }}]" 
                                               id="ans_{{ $exercise->id }}_{{ $letter }}" 
                                               value="{{ $letter }}" 
                                               class="hidden"
                                               {{ $isSelected ? 'checked' : '' }}
                                               onchange="selectOption({{ $exercise->id }}, '{{ $letter }}', {{ $index + 1 }})">
                                        
                                        <span id="badge_{{ $exercise->id }}_{{ $letter }}" class="w-7 h-7 rounded-lg font-black text-xs flex items-center justify-center shrink-0 uppercase transition {{ $isSelected ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border border-slate-300 text-slate-700' }}">
                                            {{ $letter }}
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 leading-snug flex-1">
                                            {{ $optText }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Footer of Question: Clear Option -->
                            <div class="pt-1 flex items-center justify-between text-xs text-slate-400">
                                <span class="font-medium text-[11px] text-slate-400">Select one option</span>
                                <button type="button" 
                                        onclick="clearQuestionSelection({{ $exercise->id }}, {{ $index + 1 }})" 
                                        id="clear-btn-{{ $exercise->id }}"
                                        class="text-[11px] font-bold text-slate-400 hover:text-rose-600 transition {{ $isAnswered ? '' : 'hidden' }}">
                                    Clear Selection (উত্তর বাতিল)
                                </button>
                            </div>

                        </div>
                    @endforeach

                    <!-- Bottom Submit Card -->
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 text-center space-y-4">
                        <h3 class="text-base font-extrabold text-slate-900">Finished answering the questions?</h3>
                        <p class="text-xs text-slate-500">Make sure to review your answers using the question navigator before submitting.</p>
                        <button type="button" 
                                onclick="openSubmitModal()" 
                                class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-emerald-600/25 transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Finish & Submit Test (পরীক্ষা শেষ ও জমা দিন)</span>
                        </button>
                    </div>

                </div>

                <!-- Right Column: Sticky Question Navigator (4 of 12) -->
                <div class="lg:col-span-4 sticky top-36 space-y-5" id="navigator-desktop-container">
                    
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-5">
                        
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Question Navigator</h3>
                            <span class="text-xs font-bold text-slate-500">50 Questions</span>
                        </div>

                        <!-- Mini Stats Legend -->
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <div>
                                    <span class="text-[10px] text-emerald-800 uppercase font-bold block">Answered</span>
                                    <span class="font-black text-emerald-900 text-sm" id="nav-stat-answered">0</span>
                                </div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase font-bold block">Unanswered</span>
                                    <span class="font-black text-slate-800 text-sm" id="nav-stat-unanswered">{{ count($exercises) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 50 Jump Buttons Grid -->
                        <div class="grid grid-cols-5 sm:grid-cols-5 gap-2 pt-1 max-h-[360px] overflow-y-auto pr-1">
                            @foreach($exercises as $index => $exercise)
                                @php
                                    $isPreAnswered = !empty($preAnswers[$exercise->id]);
                                @endphp
                                <button type="button" 
                                        onclick="jumpToQuestion({{ $exercise->id }})" 
                                        id="nav-btn-{{ $exercise->id }}"
                                        title="Jump to Question {{ $index + 1 }}"
                                        class="h-9 rounded-xl border font-extrabold text-xs transition flex items-center justify-center cursor-pointer shadow-2xs {{ $isPreAnswered ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200 bg-white hover:bg-slate-100 text-slate-700' }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Submit Test Button in Navigator -->
                        <div class="pt-2">
                            <button type="button" 
                                    onclick="openSubmitModal()" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md transition cursor-pointer">
                                <span>Submit Test</span>
                                <span>→</span>
                            </button>
                        </div>

                    </div>

                    <!-- Instructions Card -->
                    <div class="bg-indigo-50/60 rounded-2xl border border-indigo-100 p-4 text-xs space-y-2 text-indigo-900">
                        <div class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span>Exam Tips:</span>
                        </div>
                        <p class="leading-relaxed text-[11px] text-indigo-800">
                            Your selections are automatically saved in real time. If your browser reloads, your progress will be preserved. Click any number in the grid to jump directly to that question.
                        </p>
                    </div>

                </div>

            </div>
        </form>
    </div>

</div>

<!-- Submission Confirmation Modal -->
<div id="submit-confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200 transform transition-all">
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>

        <div class="text-center space-y-2">
            <h3 class="text-xl font-black text-slate-900">Ready to Submit?</h3>
            <p class="text-xs text-slate-600 font-bangla">আপনি কি পরীক্ষা সমাপ্ত করতে প্রস্তুত?</p>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-slate-500 font-medium">Total Questions:</span>
                <span class="font-black text-slate-800">{{ count($exercises) }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-emerald-700 font-medium">Answered (উত্তর দিয়েছেন):</span>
                <span class="font-black text-emerald-700" id="modal-answered-count">0</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-rose-600 font-medium">Unanswered (বাকি আছে):</span>
                <span class="font-black text-rose-600" id="modal-unanswered-count">{{ count($exercises) }}</span>
            </div>
        </div>

        <div id="unanswered-warning" class="text-xs text-amber-700 bg-amber-50 p-3 rounded-xl border border-amber-200 hidden leading-relaxed">
            ⚠️ You still have unanswered questions. Unanswered questions will be counted as 0 points.
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="button" 
                    onclick="closeSubmitModal()" 
                    class="flex-1 py-3 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition cursor-pointer">
                Review More
            </button>
            <button type="button" 
                    onclick="confirmAndSubmit()" 
                    class="flex-1 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-md shadow-emerald-600/20 cursor-pointer">
                Yes, Submit Now
            </button>
        </div>
    </div>
</div>

<script>
    const testUuid = "{{ $attempt->uuid }}";
    const totalQuestions = {{ count($exercises) }};
    const saveProgressUrl = "{{ route('skill-test.save-progress', $attempt->uuid) }}";
    const csrfToken = "{{ csrf_token() }}";

    // Initial answers from server or localStorage
    let answeredMap = @json($attempt->user_answers ?? (object)[]);
    if (Array.isArray(answeredMap)) {
        answeredMap = {};
    }

    // Try reading fallback from localStorage
    try {
        const localSaved = localStorage.getItem('skill_test_' + testUuid);
        if (localSaved) {
            const parsed = JSON.parse(localSaved);
            if (parsed && typeof parsed === 'object') {
                answeredMap = Object.assign({}, parsed, answeredMap);
            }
        }
    } catch(e) {}

    let secondsRemaining = {{ $remainingSeconds }};
    let timeElapsed = {{ $attempt->duration_seconds ?? 0 }};

    // Timer Countdown
    const timerDisplay = document.getElementById('timer-display');
    const timerInterval = setInterval(function() {
        if (secondsRemaining <= 0) {
            clearInterval(timerInterval);
            document.getElementById('skill-test-form').submit();
            return;
        }
        secondsRemaining--;
        timeElapsed++;
        document.getElementById('duration_seconds').value = timeElapsed;

        const minutes = Math.floor(secondsRemaining / 60);
        const seconds = secondsRemaining % 60;
        timerDisplay.innerText = 
            (minutes < 10 ? '0' : '') + minutes + ':' + 
            (seconds < 10 ? '0' : '') + seconds;

        // Warning state when under 5 minutes
        if (secondsRemaining <= 300) {
            const timerBox = document.getElementById('exam-timer-box');
            if (timerBox) {
                timerBox.classList.remove('bg-slate-900');
                timerBox.classList.add('bg-rose-600', 'animate-pulse');
            }
        }
    }, 1000);

    // Auto-save debounce timer
    let autoSaveTimeout = null;
    function queueAutoSave(exerciseId, letter) {
        try {
            localStorage.setItem('skill_test_' + testUuid, JSON.stringify(answeredMap));
        } catch(e) {}

        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            fetch(saveProgressUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    answers: answeredMap,
                    duration_seconds: timeElapsed
                })
            }).catch(function(err) {
                console.log('Progress auto-saved locally');
            });
        }, 500);
    }

    // Option Selection Handler
    function selectOption(exerciseId, letter, index) {
        answeredMap[exerciseId] = letter;

        // Reset styling for all options of this exercise
        const letters = ['a', 'b', 'c', 'd', 'e'];
        letters.forEach(l => {
            const container = document.getElementById('label_' + exerciseId + '_' + l);
            const badge = document.getElementById('badge_' + exerciseId + '_' + l);
            if (container && badge) {
                if (l === letter) {
                    container.classList.remove('bg-slate-50/50', 'border-slate-200');
                    container.classList.add('bg-indigo-50', 'border-indigo-500', 'ring-2', 'ring-indigo-500/20');
                    badge.classList.remove('bg-white', 'text-slate-700', 'border-slate-300');
                    badge.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
                } else {
                    container.classList.remove('bg-indigo-50', 'border-indigo-500', 'ring-2', 'ring-indigo-500/20');
                    container.classList.add('bg-slate-50/50', 'border-slate-200');
                    badge.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                    badge.classList.add('bg-white', 'text-slate-700', 'border-slate-300');
                }
            }
        });

        // Show clear button
        const clearBtn = document.getElementById('clear-btn-' + exerciseId);
        if (clearBtn) clearBtn.classList.remove('hidden');

        // Update card border
        const card = document.getElementById('question-card-' + exerciseId);
        if (card) {
            card.classList.remove('border-slate-200/90');
            card.classList.add('border-indigo-300');
        }

        // Update navigator button
        const navBtn = document.getElementById('nav-btn-' + exerciseId);
        if (navBtn) {
            navBtn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
            navBtn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
        }

        updateStats();
        queueAutoSave(exerciseId, letter);
    }

    // Clear Option Selection Handler
    function clearQuestionSelection(exerciseId, index) {
        delete answeredMap[exerciseId];

        // Uncheck radio inputs
        const radios = document.getElementsByName('answers[' + exerciseId + ']');
        radios.forEach(r => r.checked = false);

        // Reset styling
        const letters = ['a', 'b', 'c', 'd', 'e'];
        letters.forEach(l => {
            const container = document.getElementById('label_' + exerciseId + '_' + l);
            const badge = document.getElementById('badge_' + exerciseId + '_' + l);
            if (container && badge) {
                container.classList.remove('bg-indigo-50', 'border-indigo-500', 'ring-2', 'ring-indigo-500/20');
                container.classList.add('bg-slate-50/50', 'border-slate-200');
                badge.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                badge.classList.add('bg-white', 'text-slate-700', 'border-slate-300');
            }
        });

        // Hide clear button
        const clearBtn = document.getElementById('clear-btn-' + exerciseId);
        if (clearBtn) clearBtn.classList.add('hidden');

        // Reset card border
        const card = document.getElementById('question-card-' + exerciseId);
        if (card) {
            card.classList.remove('border-indigo-300');
            card.classList.add('border-slate-200/90');
        }

        // Reset nav button
        const navBtn = document.getElementById('nav-btn-' + exerciseId);
        if (navBtn) {
            navBtn.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
            navBtn.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
        }

        updateStats();
        queueAutoSave(exerciseId, null);
    }

    // Update Progress & Navigator Counters
    function updateStats() {
        const answeredCount = Object.keys(answeredMap).length;
        const unansweredCount = totalQuestions - answeredCount;
        const percent = Math.round((answeredCount / totalQuestions) * 100);

        document.getElementById('answered-counter-top').innerText = answeredCount;
        document.getElementById('nav-stat-answered').innerText = answeredCount;
        document.getElementById('nav-stat-unanswered').innerText = unansweredCount;
        document.getElementById('test-progress-bar').style.width = percent + '%';
    }

    // Smooth Jump to Question
    function jumpToQuestion(exerciseId) {
        const target = document.getElementById('question-card-' + exerciseId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            target.classList.add('ring-4', 'ring-indigo-400/40');
            setTimeout(() => {
                target.classList.remove('ring-4', 'ring-indigo-400/40');
            }, 1000);
        }
    }

    function toggleMobileNavigator() {
        const nav = document.getElementById('navigator-desktop-container');
        if (nav) {
            nav.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Modal Control
    function openSubmitModal() {
        const answeredCount = Object.keys(answeredMap).length;
        const unansweredCount = totalQuestions - answeredCount;

        document.getElementById('modal-answered-count').innerText = answeredCount;
        document.getElementById('modal-unanswered-count').innerText = unansweredCount;

        const warningEl = document.getElementById('unanswered-warning');
        if (unansweredCount > 0) {
            warningEl.classList.remove('hidden');
        } else {
            warningEl.classList.add('hidden');
        }

        document.getElementById('submit-confirm-modal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submit-confirm-modal').classList.add('hidden');
    }

    function confirmAndSubmit() {
        clearInterval(timerInterval);
        try {
            localStorage.removeItem('skill_test_' + testUuid);
        } catch(e) {}
        document.getElementById('skill-test-form').submit();
    }

    // Run on load to set initial state
    updateStats();
</script>
@endsection
