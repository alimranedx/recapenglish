@extends('layouts.admin')

@section('page_title', 'Edit Practice Question & Answer Key')

@section('content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Question Management</span>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight mt-0.5">Edit Question & Answer Key</h2>
        </div>
        <a href="{{ request('return_url', route('admin.exercises.index')) }}" class="px-3.5 py-1.5 border border-slate-200 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
            ← Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-xs">
        <form action="{{ route('admin.exercises.update', $exercise->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="return_url" value="{{ request('return_url', old('return_url')) }}">

            <!-- Associated Topic -->
            <div>
                <label for="topic_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Associated Topic</label>
                <select name="topic_id" id="topic_id" required class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                    @foreach($topics as $t)
                        <option value="{{ $t->id }}" {{ old('topic_id', $exercise->topic_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->chapter->part->title ?? 'Part' }} → {{ $t->chapter->title ?? 'Chapter' }} → {{ $t->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Question Text -->
            <div>
                <label for="question" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Question Text</label>
                <textarea name="question" id="question" rows="3" required placeholder="Type the question or fill-in-the-blank sentence here..."
                          class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed">{{ old('question', $exercise->question) }}</textarea>
                <p class="text-[11px] text-slate-600 mt-1">Supports bilingual English & Bengali text.</p>
            </div>

            <!-- 4 Options -->
            <div class="space-y-3 pt-2 border-t border-slate-100">
                <h4 class="text-xs font-black uppercase text-slate-900 tracking-wider">Multiple Choice Options</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="option_a" class="block text-xs font-bold text-slate-700">Option A</label>
                        <div class="mt-1 flex items-center rounded-xl border border-slate-300 focus-within:ring-2 focus-within:ring-indigo-500 overflow-hidden">
                            <span class="px-3 py-2 bg-slate-100 text-slate-700 font-bold text-xs border-r border-slate-300">A</span>
                            <input type="text" name="option_a" id="option_a" required value="{{ old('option_a', $parsedOptions['a'] ?? '') }}"
                                   placeholder="Option A text" class="w-full px-3 py-2 text-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="option_b" class="block text-xs font-bold text-slate-700">Option B</label>
                        <div class="mt-1 flex items-center rounded-xl border border-slate-300 focus-within:ring-2 focus-within:ring-indigo-500 overflow-hidden">
                            <span class="px-3 py-2 bg-slate-100 text-slate-700 font-bold text-xs border-r border-slate-300">B</span>
                            <input type="text" name="option_b" id="option_b" required value="{{ old('option_b', $parsedOptions['b'] ?? '') }}"
                                   placeholder="Option B text" class="w-full px-3 py-2 text-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="option_c" class="block text-xs font-bold text-slate-700">Option C</label>
                        <div class="mt-1 flex items-center rounded-xl border border-slate-300 focus-within:ring-2 focus-within:ring-indigo-500 overflow-hidden">
                            <span class="px-3 py-2 bg-slate-100 text-slate-700 font-bold text-xs border-r border-slate-300">C</span>
                            <input type="text" name="option_c" id="option_c" required value="{{ old('option_c', $parsedOptions['c'] ?? '') }}"
                                   placeholder="Option C text" class="w-full px-3 py-2 text-sm focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="option_d" class="block text-xs font-bold text-slate-700">Option D</label>
                        <div class="mt-1 flex items-center rounded-xl border border-slate-300 focus-within:ring-2 focus-within:ring-indigo-500 overflow-hidden">
                            <span class="px-3 py-2 bg-slate-100 text-slate-700 font-bold text-xs border-r border-slate-300">D</span>
                            <input type="text" name="option_d" id="option_d" required value="{{ old('option_d', $parsedOptions['d'] ?? '') }}"
                                   placeholder="Option D text" class="w-full px-3 py-2 text-sm focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Answer Key & Exam Reference -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                <div>
                    <label for="correct_answer" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Correct Answer Key</label>
                    <select name="correct_answer" id="correct_answer" required class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm font-bold text-emerald-800 bg-emerald-50/50 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="a" {{ old('correct_answer', strtolower(trim($exercise->correct_answer))) == 'a' ? 'selected' : '' }}>Option A</option>
                        <option value="b" {{ old('correct_answer', strtolower(trim($exercise->correct_answer))) == 'b' ? 'selected' : '' }}>Option B</option>
                        <option value="c" {{ old('correct_answer', strtolower(trim($exercise->correct_answer))) == 'c' ? 'selected' : '' }}>Option C</option>
                        <option value="d" {{ old('correct_answer', strtolower(trim($exercise->correct_answer))) == 'd' ? 'selected' : '' }}>Option D</option>
                    </select>
                </div>

                <div>
                    <label for="exam_reference" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Competitive Exam Reference (Optional)</label>
                    <input type="text" name="exam_reference" id="exam_reference" value="{{ old('exam_reference', $exercise->exam_reference) }}"
                           placeholder="e.g. 38th BCS, Dhaka University A-Unit 2018"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <!-- Detailed Explanation -->
            <div class="pt-2 border-t border-slate-100">
                <label for="explanation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Grammar Rule & Answer Explanation</label>
                <textarea name="explanation" id="explanation" rows="3" placeholder="Provide a detailed explanation in Bengali and English explaining why this is the correct answer and which grammar rule applies..."
                          class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed">{{ old('explanation', $exercise->explanation) }}</textarea>
                <p class="text-[11px] text-slate-600 mt-1">Learners will see this explanation once they submit their answer.</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ request('return_url', route('admin.exercises.index')) }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
