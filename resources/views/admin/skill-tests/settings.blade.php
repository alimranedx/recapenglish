@extends('layouts.admin')

@section('page_title', 'Skill Test Exam Configuration & Settings')

@section('content')
<div class="space-y-8 max-w-5xl">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-1 rounded bg-indigo-100 text-indigo-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Exam Control Center</span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mt-1">Skill Test Rules & Configuration</h2>
            <p class="text-xs text-slate-600 mt-0.5">Customize exam duration, question count, topic selection modes (All vs Fixed Topics), and pass requirements.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.skill-tests.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>Submissions & Reports</span>
            </a>
            <a href="{{ route('skill-test.index') }}" target="_blank" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Preview Student Exam</span>
            </a>
        </div>
    </div>

    <!-- Active Settings Overview Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 block">Exam Duration</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $config->duration_minutes }} <span class="text-xs font-normal text-slate-600">Minutes</span></div>
            <span class="text-[11px] text-slate-600 mt-1 block">{{ $config->duration_minutes * 60 }} seconds countdown</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 block">Question Count</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $config->total_questions }} <span class="text-xs font-normal text-slate-600">MCQs</span></div>
            <span class="text-[11px] text-slate-600 mt-1 block">Randomized each attempt</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 block">Topic Scope</span>
            <div class="text-xl font-black {{ $config->topic_mode === 'fixed' ? 'text-amber-600' : 'text-emerald-600' }} mt-1">
                {{ $config->topic_mode === 'fixed' ? count($config->fixed_topic_ids ?? []) . ' Fixed Topics' : 'All Topics (100%)' }}
            </div>
            <span class="text-[11px] text-slate-600 mt-1 block">
                {{ $config->topic_mode === 'fixed' ? 'Limited to chosen subjects' : 'Guaranteed coverage across all' }}
            </span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 block">Pass Threshold</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $config->pass_percentage }}%</div>
            <span class="text-[11px] text-slate-600 mt-1 block">Requires {{ ceil(($config->total_questions * $config->pass_percentage) / 100) }} / {{ $config->total_questions }} correct</span>
        </div>
    </div>

    <!-- Main Settings Form -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-xs space-y-8">
        <form action="{{ route('admin.skill-test-settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Basic Parameters -->
            <div class="space-y-4">
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-2">
                    1. Test Identity & Timings
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Exam Title</label>
                        <input type="text" id="title" name="title" required value="{{ old('title', $config->title) }}"
                               class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @error('title')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pass_percentage" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pass Mark Percentage (%)</label>
                        <input type="number" id="pass_percentage" name="pass_percentage" min="20" max="100" required value="{{ old('pass_percentage', $config->pass_percentage) }}"
                               class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <span class="text-[11px] text-slate-600 mt-1 block">Standard competitive exams benchmark at 50% or 60%.</span>
                        @error('pass_percentage')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Exam Duration in Minutes -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="duration_minutes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Exam Time Duration (Minutes)
                        </label>
                        <span class="text-xs font-bold text-indigo-600" id="duration-preview">
                            {{ old('duration_minutes', $config->duration_minutes) }} Minutes
                        </span>
                    </div>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="number" id="duration_minutes" name="duration_minutes" min="5" max="240" required value="{{ old('duration_minutes', $config->duration_minutes) }}"
                               class="w-36 px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm font-black text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <span class="text-xs text-slate-600">Quick Presets:</span>
                        <div class="flex flex-wrap items-center gap-1.5">
                            @foreach([15, 25, 30, 45, 50, 60, 90] as $presetMins)
                                <button type="button" onclick="setDuration({{ $presetMins }})"
                                        class="px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
                                    {{ $presetMins }}m
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @error('duration_minutes')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Questions -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="total_questions" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Total Questions to Select per Attempt
                        </label>
                        <span class="text-xs font-bold text-indigo-600" id="questions-preview">
                            {{ old('total_questions', $config->total_questions) }} MCQs
                        </span>
                    </div>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="number" id="total_questions" name="total_questions" min="5" max="200" required value="{{ old('total_questions', $config->total_questions) }}"
                               class="w-36 px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm font-black text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <span class="text-xs text-slate-600">Quick Presets:</span>
                        <div class="flex flex-wrap items-center gap-1.5">
                            @foreach([20, 25, 30, 40, 50, 75, 100] as $presetQ)
                                <button type="button" onclick="setQuestions({{ $presetQ }})"
                                        class="px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
                                    {{ $presetQ }} MCQs
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @error('total_questions')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Topic Scope & Selection Rules -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-2">
                    2. Topic Selection & Coverage Scope
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Mode 1: All Topics -->
                    <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-start gap-3.5 {{ old('topic_mode', $config->topic_mode) === 'all' ? 'border-indigo-600 bg-indigo-50/40' : 'border-slate-200 hover:border-slate-300 bg-white' }}" id="mode-card-all">
                        <input type="radio" name="topic_mode" value="all" {{ old('topic_mode', $config->topic_mode) === 'all' ? 'checked' : '' }}
                               onchange="toggleTopicMode('all')" class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">All Topics (100% Guaranteed Coverage)</span>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                Questions are drawn dynamically across all <strong>{{ $topics->count() }} active topics</strong>. Guarantees that every single grammar topic is represented in every test attempt.
                            </p>
                        </div>
                    </label>

                    <!-- Mode 2: Fixed Topics -->
                    <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-start gap-3.5 {{ old('topic_mode', $config->topic_mode) === 'fixed' ? 'border-indigo-600 bg-indigo-50/40' : 'border-slate-200 hover:border-slate-300 bg-white' }}" id="mode-card-fixed">
                        <input type="radio" name="topic_mode" value="fixed" {{ old('topic_mode', $config->topic_mode) === 'fixed' ? 'checked' : '' }}
                               onchange="toggleTopicMode('fixed')" class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">Fixed / Selected Topics Only</span>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                Limit the exam pool to only specific topics you check below (e.g. Parts of Speech only, Tenses only, or Prepositions). Questions will be distributed evenly among your chosen topics.
                            </p>
                        </div>
                    </label>
                </div>

                <!-- Fixed Topic Checkbox Grid -->
                <div id="fixed-topics-section" class="{{ old('topic_mode', $config->topic_mode) === 'fixed' ? '' : 'hidden' }} space-y-3 pt-3">
                    <div class="flex items-center justify-between p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="font-bold text-amber-900">Choose which topics to include in the exam pool:</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="selectAllTopics(true)" class="text-[11px] font-bold text-indigo-600 hover:underline">Select All</button>
                            <span class="text-slate-400">•</span>
                            <button type="button" onclick="selectAllTopics(false)" class="text-[11px] font-bold text-slate-600 hover:underline">Deselect All</button>
                        </div>
                    </div>

                    @error('fixed_topic_ids')
                        <p class="text-xs text-rose-600 font-bold">{{ $message }}</p>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2.5 max-h-96 overflow-y-auto p-1">
                        @php
                            $activeFixedIds = old('fixed_topic_ids', $config->fixed_topic_ids ?? []);
                        @endphp
                        @foreach($topics as $topic)
                            <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-slate-50 transition cursor-pointer text-xs">
                                <input type="checkbox" name="fixed_topic_ids[]" value="{{ $topic->id }}"
                                       class="topic-checkbox mt-0.5 h-4 w-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500"
                                       {{ in_array($topic->id, $activeFixedIds) ? 'checked' : '' }}>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-900 block truncate" title="{{ $topic->title }}">
                                        {{ $topic->title }}
                                    </span>
                                    <span class="text-[10px] text-slate-600 block">
                                        {{ $topic->chapter->title ?? '' }} • <strong class="text-indigo-600">{{ $topic->exercises_count }} MCQs</strong>
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Section 3: Student Instructions -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-2">
                    3. Student Guidelines & Instructions
                </h3>

                <div>
                    <label for="instructions" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pre-Test Instructions (Optional)</label>
                    <textarea id="instructions" name="instructions" rows="3" placeholder="Provide instructions for students regarding negative marking, time limits, or syllabus references..."
                              class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed">{{ old('instructions', $config->instructions) }}</textarea>
                    <p class="text-[11px] text-slate-600 mt-1">Displayed on the test landing page before the student starts their session.</p>
                </div>
            </div>

            <!-- Form Submit Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.skill-tests.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                    Cancel & Return
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Exam Settings</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function setDuration(mins) {
        document.getElementById('duration_minutes').value = mins;
        document.getElementById('duration-preview').innerText = mins + ' Minutes';
    }

    function setQuestions(count) {
        document.getElementById('total_questions').value = count;
        document.getElementById('questions-preview').innerText = count + ' MCQs';
    }

    function toggleTopicMode(mode) {
        const fixedSection = document.getElementById('fixed-topics-section');
        const cardAll = document.getElementById('mode-card-all');
        const cardFixed = document.getElementById('mode-card-fixed');

        if (mode === 'fixed') {
            fixedSection.classList.remove('hidden');
            cardFixed.classList.add('border-indigo-600', 'bg-indigo-50/40');
            cardFixed.classList.remove('border-slate-200');
            cardAll.classList.remove('border-indigo-600', 'bg-indigo-50/40');
            cardAll.classList.add('border-slate-200');
        } else {
            fixedSection.classList.add('hidden');
            cardAll.classList.add('border-indigo-600', 'bg-indigo-50/40');
            cardAll.classList.remove('border-slate-200');
            cardFixed.classList.remove('border-indigo-600', 'bg-indigo-50/40');
            cardFixed.classList.add('border-slate-200');
        }
    }

    function selectAllTopics(checked) {
        const checkboxes = document.querySelectorAll('.topic-checkbox');
        checkboxes.forEach(cb => cb.checked = checked);
    }

    document.getElementById('duration_minutes').addEventListener('input', function() {
        document.getElementById('duration-preview').innerText = this.value + ' Minutes';
    });

    document.getElementById('total_questions').addEventListener('input', function() {
        document.getElementById('questions-preview').innerText = this.value + ' MCQs';
    });
</script>
@endsection
