@extends('layouts.app')

@section('title', 'Saved Topics - RecapEnglish')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                <span>Saved Topics & Bookmarks</span>
            </h1>
            <p class="text-xs text-slate-600 mt-1">Easily revisit challenging grammar concepts and review them before exams.</p>
        </div>
        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full self-start sm:self-auto">
            {{ $bookmarks->total() }} Saved
        </span>
    </div>

    @if($bookmarks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($bookmarks as $b)
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                {{ $b->topic->chapter->title }}
                            </span>
                            <span class="text-[10px] text-slate-600">
                                Saved {{ $b->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <h3 class="font-extrabold text-slate-900 group-hover:text-indigo-600 text-sm leading-snug">
                            {{ $b->topic->title }}
                        </h3>

                        @if($b->note)
                            <div class="mt-3 p-2.5 bg-amber-50 border border-amber-200/70 rounded-xl text-xs text-amber-900 italic">
                                "{{ $b->note }}"
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('reader.topic', $b->topic->slug) }}" class="text-xs font-extrabold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            Read Now →
                        </a>

                        <form action="{{ route('reader.toggle-bookmark', $b->topic->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-700">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $bookmarks->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-200/80 p-8">
            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">No saved topics yet</h3>
            <p class="text-xs text-slate-600 max-w-sm mx-auto mt-1">While reading any topic, click the "Save Topic" button to keep it in your revision list.</p>
            <div class="mt-4">
                <a href="{{ route('reader.home') }}" class="inline-flex px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-xl shadow-xs hover:bg-indigo-700 transition">
                    Explore Book Chapters
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
