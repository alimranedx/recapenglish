@extends('layouts.admin')

@section('page_title', '50-Question Skill Test Submissions & Reports')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900">Skill Test Submissions</h2>
            <p class="text-xs text-slate-600 mt-0.5">Monitor student exam attempts, scores, and individual diagnostic reports.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.skill-test-settings.edit') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>⚙️ Exam Settings & Duration</span>
            </a>
            <a href="{{ route('skill-test.index') }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Take Test</span>
            </a>
        </div>
    </div>

    <!-- Mini KPI Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Total Tests Taken</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_completed']) }}</div>
            <span class="text-xs text-slate-500 mt-1 block">50-MCQ completed papers</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Average Score</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $stats['avg_score'] }} <span class="text-sm font-semibold text-slate-400">/ 50</span></div>
            <span class="text-xs text-slate-500 mt-1 block">Overall learner average</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Average Accuracy</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['avg_percentage'] }}%</div>
            <span class="text-xs text-slate-500 mt-1 block">Across all curriculum topics</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">High Achievers (>=80%)</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['high_performers'] }}</div>
            <span class="text-xs text-emerald-600 font-semibold mt-1 block">BCS & Admission ready</span>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200/80 flex flex-col md:flex-row items-center justify-between gap-4 shadow-xs">
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
            <a href="{{ route('admin.skill-tests.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !$filter ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All Submissions
            </a>
            <a href="{{ route('admin.skill-tests.index', ['filter' => 'high']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $filter === 'high' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-emerald-700 hover:bg-slate-200' }}">
                🏆 High Scorers (80%+)
            </a>
            <a href="{{ route('admin.skill-tests.index', ['filter' => 'low']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $filter === 'low' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-rose-700 hover:bg-slate-200' }}">
                ⚠️ Needs Revision (<50%)
            </a>
        </div>

        <form action="{{ route('admin.skill-tests.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
            <input type="text" name="search" placeholder="Search by student name or email..." value="{{ $search }}"
                   class="px-3.5 py-1.5 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full md:w-64 bg-slate-50">
            <button type="submit" class="px-3.5 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shrink-0">
                Search
            </button>
        </form>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Learner</th>
                        <th class="px-6 py-3.5">Score</th>
                        <th class="px-6 py-3.5">Percentage</th>
                        <th class="px-6 py-3.5">Breakdown (Correct / Wrong / Skipped)</th>
                        <th class="px-6 py-3.5">Time Spent</th>
                        <th class="px-6 py-3.5">Date</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($attempts as $attempt)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">
                                    {{ $attempt->user ? $attempt->user->name : 'Guest Learner' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ $attempt->user ? $attempt->user->email : 'Session #'.substr($attempt->session_id ?? $attempt->uuid, 0, 8) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-black text-slate-900 text-sm">{{ $attempt->score }}</span>
                                <span class="text-slate-400">/ {{ $attempt->total_questions }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $attempt->percentage >= 80 ? 'bg-emerald-100 text-emerald-800' : ($attempt->percentage >= 60 ? 'bg-indigo-100 text-indigo-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ number_format($attempt->percentage, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold">
                                <span class="text-emerald-600">✓ {{ $attempt->correct_count }}</span>
                                <span class="text-rose-600 ml-2">✗ {{ $attempt->incorrect_count }}</span>
                                <span class="text-slate-400 ml-2">⚪ {{ $attempt->unanswered_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                @if($attempt->duration_seconds)
                                    {{ floor($attempt->duration_seconds / 60) }}m {{ $attempt->duration_seconds % 60 }}s
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y h:i A') : $attempt->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('skill-test.result', $attempt->uuid) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                        View Solutions →
                                    </a>
                                    <form action="{{ route('admin.skill-tests.destroy', $attempt->id) }}" method="POST" onsubmit="return confirm('Delete this test submission?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Delete record">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                No skill test submissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
