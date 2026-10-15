@extends('layouts.admin')

@section('page_title', 'Learners & Reading Analytics')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900">Registered Students</h2>
            <p class="text-xs text-slate-600 mt-0.5">Track user reading activity, last saved topics, and curriculum completion rates.</p>
        </div>
        <div>
            <form action="{{ route('admin.students.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" placeholder="Search by name or email..." value="{{ $search }}"
                       class="px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none w-64 bg-white">
                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                    Search
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Student</th>
                    <th class="px-6 py-3.5">Auth Method</th>
                    <th class="px-6 py-3.5">Last Read Topic</th>
                    <th class="px-6 py-3.5">Completed Topics</th>
                    <th class="px-6 py-3.5">Skill Tests</th>
                    <th class="px-6 py-3.5">Last Active</th>
                    <th class="px-6 py-3.5">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($students as $st)
                    @php
                        $completedCount = $st->topicReads->where('is_completed', true)->count();
                        $pct = $totalTopics > 0 ? round(($completedCount / $totalTopics) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                            @if($st->avatar)
                                <img src="{{ $st->avatar }}" class="w-7 h-7 rounded-full border border-slate-200" alt="">
                            @else
                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($st->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span class="block">{{ $st->name }}</span>
                                <span class="text-[11px] font-mono text-slate-600 font-normal">{{ $st->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($st->google_id)
                                <span class="inline-flex items-center gap-1 font-semibold text-slate-700 text-[11px]">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Google
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 font-semibold text-slate-700 text-[11px]">
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span> Email
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($st->lastTopic)
                                <span class="font-bold text-slate-800 block truncate max-w-xs">
                                    {{ $st->lastTopic->title }}
                                </span>
                                <span class="text-[10px] text-slate-600">
                                    {{ $st->lastTopic->chapter->title }}
                                </span>
                            @else
                                <span class="italic text-slate-600">No topic read yet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $completedCount }} / {{ $totalTopics }}</span>
                                <span class="text-[10px] text-emerald-600 font-bold">({{ $pct }}%)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $testsCount = $st->skillTestAttempts->count();
                                $bestScore = $testsCount > 0 ? $st->skillTestAttempts->max('score') : null;
                                $bestPct = $testsCount > 0 ? $st->skillTestAttempts->max('percentage') : null;
                            @endphp
                            @if($testsCount > 0)
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-extrabold text-slate-900">{{ $testsCount }} {{ Str::plural('test', $testsCount) }}</span>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-black {{ $bestPct >= 80 ? 'bg-emerald-100 text-emerald-800' : ($bestPct >= 50 ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                                            Best: {{ $bestScore }}/50 ({{ round($bestPct) }}%)
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.skill-tests.index', ['search' => $st->email]) }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 hover:underline block">
                                        View Submissions →
                                    </a>
                                </div>
                            @else
                                <span class="italic text-slate-600 text-[11px]">No tests yet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $st->last_read_at ? $st->last_read_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $st->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-600">No students registered matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-2">
        {{ $students->links() }}
    </div>

</div>
@endsection
