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
                <div class="text-slate-800 leading-relaxed text-sm sm:text-base space-y-4 whitespace-pre-line font-normal">
                    {{ $topic->content }}
                </div>
            </article>

            <!-- Interactive Practice MCQs / Exam Questions -->
            @if($topic->exercises->count() > 0)
                <section class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Interactive Drill</span>
                            <h2 class="text-xl font-black text-slate-900 mt-1">Practice Questions & Past Exam MCQs</h2>
                        </div>
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">
                            {{ $topic->exercises->count() }} Questions
                        </span>
                    </div>

                    <div class="space-y-6">
                        @foreach($topic->exercises as $index => $exercise)
                            <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-3" id="quiz-{{ $exercise->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-2.5">
                                        <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="font-bold text-sm text-slate-900 leading-snug">
                                            {{ $exercise->question }}
                                        </div>
                                    </div>
                                    @if($exercise->exam_reference)
                                        <span class="text-[10px] font-bold text-sky-700 bg-sky-50 px-2.5 py-0.5 rounded-full shrink-0 border border-sky-100">
                                            {{ $exercise->exam_reference }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Options List (Interactive) -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                                    @foreach($exercise->options as $opt)
                                        @php
                                            // Extract option letter (a, b, c, d)
                                            preg_match('/^([a-d])[\)\.]\s*(.*)/i', $opt, $matches);
                                            $letter = strtolower($matches[1] ?? 'a');
                                            $optText = $matches[2] ?? $opt;
                                        @endphp
                                        <button type="button" 
                                                onclick="checkAnswer({{ $exercise->id }}, '{{ $letter }}', '{{ strtolower($exercise->correct_answer) }}')"
                                                id="opt-{{ $exercise->id }}-{{ $letter }}"
                                                class="option-btn text-left px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-800 transition flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-700 font-bold text-[11px] flex items-center justify-center shrink-0 uppercase">
                                                {{ $letter }}
                                            </span>
                                            <span class="truncate">{{ $optText }}</span>
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Explanation Box (Revealed upon answering) -->
                                <div id="expl-{{ $exercise->id }}" class="hidden pt-3 border-t border-slate-200/80 text-xs text-slate-700 space-y-1">
                                    <div class="font-bold flex items-center gap-1.5" id="status-{{ $exercise->id }}"></div>
                                    @if($exercise->explanation)
                                        <div class="bg-indigo-50/70 p-3 rounded-xl border border-indigo-100 text-indigo-900 font-medium">
                                            <strong>Explanation:</strong> {{ $exercise->explanation }}
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
    function checkAnswer(exerciseId, selectedLetter, correctLetter) {
        // Disable further clicking for this question
        const container = document.getElementById('quiz-' + exerciseId);
        const buttons = container.querySelectorAll('.option-btn');
        buttons.forEach(btn => btn.disabled = true);

        const selectedBtn = document.getElementById(`opt-${exerciseId}-${selectedLetter}`);
        const correctBtn = document.getElementById(`opt-${exerciseId}-${correctLetter}`);
        const explBox = document.getElementById('expl-' + exerciseId);
        const statusSpan = document.getElementById('status-' + exerciseId);

        if (selectedLetter === correctLetter) {
            selectedBtn.classList.remove('bg-white', 'border-slate-200');
            selectedBtn.classList.add('bg-emerald-500', 'text-white', 'border-emerald-600');
            statusSpan.innerHTML = '<span class="text-emerald-600 font-extrabold">✓ Correct Answer!</span>';
        } else {
            selectedBtn.classList.remove('bg-white', 'border-slate-200');
            selectedBtn.classList.add('bg-rose-500', 'text-white', 'border-rose-600');
            if (correctBtn) {
                correctBtn.classList.remove('bg-white', 'border-slate-200');
                correctBtn.classList.add('bg-emerald-100', 'text-emerald-900', 'border-emerald-400', 'font-bold');
            }
            statusSpan.innerHTML = `<span class="text-rose-600 font-extrabold">✗ Incorrect. Correct answer is (${correctLetter.toUpperCase()})</span>`;
        }

        explBox.classList.remove('hidden');
    }
</script>
@endpush
@endsection
