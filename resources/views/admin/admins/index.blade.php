@extends('layouts.admin')

@section('page_title', 'Manage Administrators (Super Admin Only)')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900">Administrator Accounts</h2>
            <p class="text-xs text-slate-600 mt-0.5">Only Super Admins can provision new administrator accounts. No public registration exists for admin roles.</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create New Admin
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Name</th>
                    <th class="px-6 py-3.5">Email</th>
                    <th class="px-6 py-3.5">Role</th>
                    <th class="px-6 py-3.5">Provisioned</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($admins as $admin)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <span>{{ $admin->name }}</span>
                            @if($admin->id === auth()->id())
                                <span class="text-[9px] bg-slate-100 text-slate-600 font-bold px-1.5 py-0.5 rounded">You</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono">{{ $admin->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $admin->isSuperAdmin() ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-indigo-100 text-indigo-800 border border-indigo-200' }}">
                                {{ $admin->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $admin->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($admin->id !== auth()->id())
                                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this admin account?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold hover:underline">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-600 italic">Protected</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-2">
        {{ $admins->links() }}
    </div>

</div>
@endsection
