@extends('layouts.admin')

@section('page_title', 'Edit Topic: ' . $topic->title)

@section('content')
<div class="space-y-8 max-w-5xl">

    <!-- Topic Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $topic->chapter->title }}</span>
                <h2 class="text-xl font-black text-slate-900 mt-0.5">Edit Topic Content</h2>
            </div>
            <a href="{{ route('reader.topic', $topic->slug) }}" target="_blank" class="px-3.5 py-1.5 border border-slate-200 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                <span>View on Reader</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        <form action="{{ route('admin.topics.update', $topic->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="chapter_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Chapter</label>
                    <select id="chapter_id" name="chapter_id" required
                            class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                        @foreach($chapters as $ch)
                            <option value="{{ $ch->id }}" {{ (old('chapter_id', $topic->chapter_id) == $ch->id) ? 'selected' : '' }}>
                                {{ $ch->part->title }} → {{ $ch->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Topic Title</label>
                    <input id="title" name="title" type="text" required value="{{ old('title', $topic->title) }}"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="mnemonics" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Exam Shortcut / Mnemonic (Optional)</label>
                <input id="mnemonics" name="mnemonics" type="text" value="{{ old('mnemonics', $topic->mnemonics) }}"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="estimated_read_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Estimated Read Time (Minutes)</label>
                    <input id="estimated_read_time" name="estimated_read_time" type="number" min="1" value="{{ old('estimated_read_time', $topic->estimated_read_time) }}" required
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label for="order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Display Order</label>
                    <input id="order" name="order" type="number" min="0" value="{{ old('order', $topic->order) }}" required
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Lesson Notes & Grammar Rules</label>
                <textarea id="content" name="content" rows="14" required
                          class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none font-sans leading-relaxed">{{ old('content', $topic->content) }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input id="is_published" name="is_published" type="checkbox" value="1" {{ old('is_published', $topic->is_published) ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                <label for="is_published" class="text-xs font-bold text-slate-700">Published (Visible to readers)</label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.topics.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                    Back to List
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Practice Questions / Exercises Management -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-black text-slate-900">Topic Practice Questions & MCQs</h3>
                <p class="text-xs text-slate-600 mt-0.5">Manage multiple-choice questions, answer keys, explanations, and exam references.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="text-xs font-bold bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full">
                    {{ $topic->exercises->count() }} Questions
                </span>
                <a href="{{ route('admin.exercises.create', ['topic_id' => $topic->id]) }}" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-xs shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Add MCQ</span>
                </a>
            </div>
        </div>

        <!-- Existing Questions List -->
        <div class="space-y-4">
            @forelse($topic->exercises as $ex)
                <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/50 space-y-2 text-xs">
                    <div class="flex items-start justify-between gap-3">
                        <div class="font-bold text-slate-900 text-sm">
                            {{ $loop->iteration }}. {{ $ex->question }}
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($ex->exam_reference)
                                <span class="text-[10px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded border border-sky-100">
                                    {{ $ex->exam_reference }}
                                </span>
                            @endif
                            <a href="{{ route('admin.exercises.edit', $ex->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold hover:underline text-xs">
                                Edit
                            </a>
                            <span class="text-slate-300">•</span>
                            <form action="{{ route('admin.topics.exercises.destroy', $ex->id) }}" method="POST" onsubmit="return confirm('Remove this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold hover:underline text-xs">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 font-semibold text-slate-700">
                        @foreach($ex->options as $opt)
                            <div class="p-1.5 bg-white rounded-lg border border-slate-200 truncate">
                                {{ $opt }}
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-1 flex items-center justify-between text-[11px] text-slate-600">
                        <span class="text-emerald-700 font-bold">
                            Correct Answer: ({{ strtoupper($ex->correct_answer) }})
                        </span>
                        @if($ex->explanation)
                            <span class="truncate max-w-md italic">
                                Note: {{ $ex->explanation }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-600 py-4 text-center italic">No practice questions added yet for this topic.</p>
            @endforelse
        </div>

        <!-- Add Question Form -->
        <div class="mt-8 pt-6 border-t border-slate-200">
            <h4 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-4">Add Practice Question</h4>
            
            <form action="{{ route('admin.topics.exercises.store', $topic->id) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="question" class="block text-xs font-bold text-slate-700 uppercase">Question Text</label>
                    <textarea id="question" name="question" rows="2" required placeholder="Enter question or fill in the blank..."
                              class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="option_a" class="block text-xs font-bold text-slate-700 uppercase">Option A</label>
                        <input id="option_a" name="option_a" type="text" required placeholder="Option A text"
                               class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="option_b" class="block text-xs font-bold text-slate-700 uppercase">Option B</label>
                        <input id="option_b" name="option_b" type="text" required placeholder="Option B text"
                               class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="option_c" class="block text-xs font-bold text-slate-700 uppercase">Option C (Optional)</label>
                        <input id="option_c" name="option_c" type="text" placeholder="Option C text"
                               class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="option_d" class="block text-xs font-bold text-slate-700 uppercase">Option D (Optional)</label>
                        <input id="option_d" name="option_d" type="text" placeholder="Option D text"
                               class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="correct_answer" class="block text-xs font-bold text-slate-700 uppercase">Correct Answer</label>
                        <select id="correct_answer" name="correct_answer" required
                                class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>

                    <div>
                        <label for="exam_reference" class="block text-xs font-bold text-slate-700 uppercase">Exam Reference (Optional)</label>
                        <input id="exam_reference" name="exam_reference" type="text" placeholder="e.g. 38th BCS, DU A-Unit 2018"
                               class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label for="explanation" class="block text-xs font-bold text-slate-700 uppercase">Explanation / Reason (Optional)</label>
                    <textarea id="explanation" name="explanation" rows="2" placeholder="Explain why this option is correct or reference the relevant rule..."
                              class="mt-1 block w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                        + Add Question to Topic
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
