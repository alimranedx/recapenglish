@extends('layouts.admin')

@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">

    <!-- Super Admin Banner / Role Alert -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-2xl p-6 shadow-md border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded bg-indigo-500 text-white">
                    {{ auth()->user()->isSuperAdmin() ? 'Super Admin System' : 'Academic Editor Console' }}
                </span>
                <span class="text-xs text-slate-400">RecapEnglish Content & Learner Engine</span>
            </div>
            <h2 class="text-xl font-black mt-1">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">Manage existing grammar topics, add new curriculum modules, and monitor learners.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.topics.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Topic
            </a>
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.admins.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Create Admin
                </a>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Total Topics</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_topics'] }}</div>
            <span class="text-xs text-slate-600 mt-1 block">across {{ $stats['total_chapters'] }} chapters</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Practice MCQs</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $stats['total_exercises'] }}</div>
            <span class="text-xs text-slate-600 mt-1 block">with answer keys & references</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Registered Students</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_students'] }}</div>
            <span class="text-xs text-emerald-600 font-semibold mt-1 block">{{ $stats['completed_reads'] }} reads recorded</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Staff & Admins</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['total_admins'] + 1 }}</div>
            <span class="text-xs text-slate-600 mt-1 block">1 Super Admin, {{ $stats['total_admins'] }} Editors</span>
        </div>
    </div>

    <!-- Two-Column Section: Recent Students & Recent Topics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Students Activity -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-sm text-slate-900">Recent Learner Activity</h3>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    View All Students →
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentStudents as $student)
                    <div class="py-3 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs uppercase">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $student->name }}</p>
                                <p class="text-slate-600 text-[11px]">{{ $student->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($student->lastTopic)
                                <span class="font-semibold text-slate-700 block truncate max-w-[150px]">
                                    {{ $student->lastTopic->title }}
                                </span>
                                <span class="text-[10px] text-slate-600">
                                    {{ $student->last_read_at ? $student->last_read_at->diffForHumans() : 'Recently' }}
                                </span>
                            @else
                                <span class="text-slate-600 italic">No topic read yet</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-600 py-4 text-center">No students registered yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Curriculum Quick Topics -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-sm text-slate-900">Curriculum Topics</h3>
                <a href="{{ route('admin.topics.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Manage All Topics →
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($recentTopics as $topic)
                    <div class="py-3 flex items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-indigo-600 block">
                                {{ $topic->chapter->title }}
                            </span>
                            <span class="font-bold text-slate-800">
                                {{ $topic->title }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $topic->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $topic->is_published ? 'Published' : 'Draft' }}
                            </span>
                            <a href="{{ route('admin.topics.edit', $topic->id) }}" class="text-indigo-600 font-bold hover:underline">
                                Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
