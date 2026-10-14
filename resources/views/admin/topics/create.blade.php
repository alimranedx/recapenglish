@extends('layouts.admin')

@section('page_title', 'Add New Topic')

@section('content')
<div class="max-w-4xl bg-white rounded-2xl border border-slate-200/80 p-8 shadow-xs space-y-6">

    <div>
        <h2 class="text-lg font-black text-slate-900">Add New Grammar Topic</h2>
        <p class="text-xs text-slate-600 mt-1">Create a new lecture topic or modular lesson within an existing chapter.</p>
    </div>

    <form action="{{ route('admin.topics.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="chapter_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Chapter</label>
                <select id="chapter_id" name="chapter_id" required
                        class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                    @foreach($chapters as $ch)
                        <option value="{{ $ch->id }}" {{ old('chapter_id') == $ch->id ? 'selected' : '' }}>
                            {{ $ch->part->title }} → {{ $ch->title }}
                        </option>
                    @endforeach
                </select>
                @error('chapter_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Topic Title</label>
                <input id="title" name="title" type="text" required value="{{ old('title') }}" placeholder="e.g. 1.1 Proper Noun & Personified Noun"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                @error('title')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="mnemonics" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Exam Shortcut / Mnemonic (Optional)</label>
            <input id="mnemonics" name="mnemonics" type="text" value="{{ old('mnemonics') }}" placeholder="e.g. BARSHA-NOVA or RUPA IS MODERN"
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="estimated_read_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Estimated Read Time (Minutes)</label>
                <input id="estimated_read_time" name="estimated_read_time" type="number" min="1" value="{{ old('estimated_read_time', 5) }}" required
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div>
                <label for="order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Display Order</label>
                <input id="order" name="order" type="number" min="0" value="{{ old('order', 1) }}" required
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
        </div>

        <div>
            <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Topic Content & Lesson Notes</label>
            <textarea id="content" name="content" rows="12" required
                      placeholder="Write your grammar rules, explanations, exceptions, and examples here..."
                      class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none font-sans leading-relaxed">{{ old('content') }}</textarea>
            @error('content')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="is_published" name="is_published" type="checkbox" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
            <label for="is_published" class="text-xs font-bold text-slate-700">Publish immediately for learners</label>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('admin.topics.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Create Topic & Proceed to MCQs →
            </button>
        </div>
    </form>

</div>
@endsection
