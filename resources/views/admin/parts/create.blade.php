@extends('layouts.admin')

@section('page_title', 'Create Book Part')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 p-8 shadow-xs space-y-6">

    <div>
        <h2 class="text-lg font-black text-slate-900">Add New Book Part</h2>
        <p class="text-xs text-slate-600 mt-1">Define a major pedagogical section of the grammar curriculum.</p>
    </div>

    <form action="{{ route('admin.parts.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Part Title</label>
            <input id="title" name="title" type="text" required value="{{ old('title') }}" placeholder="e.g. Part V: Advanced Composition & Idioms"
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label for="order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Display Order</label>
            <input id="order" name="order" type="number" min="0" value="{{ old('order', 5) }}" required
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <div>
            <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Description (Optional)</label>
            <textarea id="description" name="description" rows="3" placeholder="Overview of grammatical structures included in this part..."
                      class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
            <label for="is_active" class="text-xs font-bold text-slate-700">Active (Visible in book index)</label>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('admin.parts.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Create Part
            </button>
        </div>
    </form>

</div>
@endsection
