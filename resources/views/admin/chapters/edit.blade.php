@extends('layouts.admin')

@section('page_title', 'Edit Chapter: ' . $chapter->title)

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-8 shadow-xs space-y-6">

    <div>
        <h2 class="text-lg font-black text-slate-900">Edit Chapter</h2>
    </div>

    <form action="{{ route('admin.chapters.update', $chapter->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="part_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Book Part</label>
            <select id="part_id" name="part_id" required
                    class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                @foreach($parts as $part)
                    <option value="{{ $part->id }}" {{ old('part_id', $chapter->part_id) == $part->id ? 'selected' : '' }}>
                        {{ $part->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Chapter Title</label>
            <input id="title" name="title" type="text" required value="{{ old('title', $chapter->title) }}"
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label for="order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Display Order</label>
            <input id="order" name="order" type="number" min="0" value="{{ old('order', $chapter->order) }}" required
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label for="summary" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Chapter Summary (Optional)</label>
            <textarea id="summary" name="summary" rows="3"
                      class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('summary', $chapter->summary) }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input id="is_published" name="is_published" type="checkbox" value="1" {{ old('is_published', $chapter->is_published) ? 'checked' : '' }}
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
            <label for="is_published" class="text-xs font-bold text-slate-700">Published</label>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('admin.chapters.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Save Changes
            </button>
        </div>
    </form>

</div>
@endsection
