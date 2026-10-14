@extends('layouts.admin')

@section('page_title', 'Edit Part: ' . $part->title)

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 p-8 shadow-xs space-y-6">

    <div>
        <h2 class="text-lg font-black text-slate-900">Edit Book Part</h2>
    </div>

    <form action="{{ route('admin.parts.update', $part->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Part Title</label>
            <input id="title" name="title" type="text" required value="{{ old('title', $part->title) }}"
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label for="order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Display Order</label>
            <input id="order" name="order" type="number" min="0" value="{{ old('order', $part->order) }}" required
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Description (Optional)</label>
            <textarea id="description" name="description" rows="3"
                      class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('description', $part->description) }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $part->is_active) ? 'checked' : '' }}
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
            <label for="is_active" class="text-xs font-bold text-slate-700">Active</label>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('admin.parts.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Save Changes
            </button>
        </div>
    </form>

</div>
@endsection
