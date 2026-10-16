@extends('layouts.admin')

@section('page_title', 'Create Administrator Account')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-8 shadow-xs space-y-6">

    <div>
        <h2 class="text-lg font-black text-slate-900">Provision New Administrator</h2>
        <p class="text-xs text-slate-600 mt-1">Super Admins can grant administrative and editorial rights to new staff members.</p>
    </div>

    <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Full Name</label>
            <input id="name" name="name" type="text" required value="{{ old('name') }}"
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            @error('name')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            @error('email')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Administrator Role</label>
            <select id="role" name="role" required
                    class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin / Academic Editor (Can manage chapters, topics & questions)</option>
                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Full system control + can create other admins)</option>
            </select>
            @error('role')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Initial Password</label>
            <input id="password" name="password" type="password" required
                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            @error('password')
                <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 border border-slate-300 text-xs font-bold text-slate-700 rounded-xl hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Create Administrator
            </button>
        </div>
    </form>

</div>
@endsection
