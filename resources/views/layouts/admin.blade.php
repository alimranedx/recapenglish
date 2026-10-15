<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - RecapEnglish')</title>
    
    <!-- Fonts: English (Plus Jakarta Sans) & Bangla (Hind Siliguri, Noto Sans Bengali) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Vite Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Hind Siliguri', 'Noto Sans Bengali', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
        }
        .font-bangla { 
            font-family: 'Hind Siliguri', 'Noto Sans Bengali', sans-serif; 
        }
        .font-english { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-full flex antialiased">

    <!-- Admin Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 border-r border-slate-800">
        
        <!-- Logo -->
        <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-800 bg-slate-950/40">
            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-black text-sm">
                RE
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="font-extrabold text-white text-base tracking-tight block">RecapEnglish</a>
                <span class="text-[10px] uppercase font-semibold text-indigo-400 tracking-wider">
                    @if(auth()->user()->isSuperAdmin())
                        Super Admin Console
                    @else
                        Admin Management
                    @endif
                </span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-600">Overview</div>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="pt-6 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-600">Curriculum Content</div>

            <a href="{{ route('admin.topics.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.topics.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Topics
            </a>

            <a href="{{ route('admin.exercises.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.exercises.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Questions & MCQs
            </a>

            <a href="{{ route('admin.chapters.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.chapters.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Chapters
            </a>

            <a href="{{ route('admin.parts.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.parts.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Book Parts
            </a>

            <div class="pt-6 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-600">Students & Analytics</div>

            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.students.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Registered Students
            </a>

            <a href="{{ route('admin.skill-tests.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.skill-tests.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Skill Test Reports
            </a>

            @if(auth()->user()->isSuperAdmin())
                <div class="pt-6 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-amber-500">Super Admin Only</div>

                <a href="{{ route('admin.admins.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.admins.*') ? 'bg-amber-600 text-white' : 'hover:bg-slate-800 text-amber-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Manage Admins
                </a>
            @endif
        </nav>

        <!-- Current User / Return to Reader -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-600 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reader.home') }}" class="flex-1 text-center py-1.5 px-2 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 rounded-lg transition">
                    View Book
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 bg-slate-800 hover:bg-rose-900/40 text-slate-600 hover:text-rose-400 rounded-lg transition" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        
        <!-- Admin Header -->
        <header class="h-16 bg-white border-b border-slate-200 px-8 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ auth()->user()->isSuperAdmin() ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-indigo-100 text-indigo-800 border border-indigo-200' }}">
                    {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}
                </span>
                <a href="{{ route('reader.home') }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                    Open Reader Site
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-8 mt-6">
            @if(session('success'))
                <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Admin Content Body -->
        <main class="p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>
