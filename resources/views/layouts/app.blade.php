<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RecapEnglish - Grammar Master Book')</title>
    
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
        [x-cloak] { display: none !important; }
    </style>
<body class="bg-slate-50 text-slate-800 min-h-full flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Top Reading Progress Bar -->
    <div id="reading-progress-bar" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-indigo-500 via-sky-400 to-emerald-400 z-50 transition-all duration-75" style="width: 0%"></div>

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2 sm:gap-4">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('reader.home') }}" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-sky-400 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-lg sm:text-xl tracking-tight bg-gradient-to-r from-indigo-700 via-indigo-600 to-sky-600 bg-clip-text text-transparent">RecapEnglish</span>
                        <span class="block text-[9px] sm:text-[10px] font-semibold tracking-wider uppercase text-slate-500 leading-none mt-0.5">Grammar Master Book</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Search Bar -->
            <div class="hidden md:flex flex-1 max-w-md mx-4">
                <form action="{{ route('reader.search') }}" method="GET" class="w-full relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" placeholder="Search rules, topics, mnemonics..." value="{{ request('q') }}"
                           class="w-full pl-9 pr-4 py-2 bg-slate-100/80 hover:bg-slate-100 focus:bg-white text-sm rounded-xl border border-transparent focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </form>
            </div>

            <!-- Navigation Actions -->
            <div class="flex items-center gap-1.5 sm:gap-3">
                <!-- Mobile Search Toggle Button -->
                <button type="button" 
                        onclick="toggleMobileSearch()" 
                        class="md:hidden p-2 text-slate-600 hover:text-indigo-600 hover:bg-slate-100 rounded-xl transition cursor-pointer"
                        aria-label="Toggle search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <a href="{{ route('reader.home') }}" class="hidden lg:inline-flex text-sm font-semibold text-slate-600 hover:text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
                    Table of Contents
                </a>

                <a href="{{ route('skill-test.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-slate-700 hover:text-indigo-600 px-2.5 sm:px-3 py-1.5 rounded-lg hover:bg-slate-50 transition {{ request()->routeIs('skill-test.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Skill Test</span>
                    <span class="px-1.5 py-0.5 text-[10px] font-extrabold uppercase rounded-full bg-emerald-100 text-emerald-800">50 MCQs</span>
                </a>

                @auth
                    <a href="{{ route('reader.bookmarks') }}" class="hidden xl:inline-flex text-sm font-semibold text-slate-600 hover:text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                        Saved Topics
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden lg:inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin
                        </a>
                    @endif

                    <!-- User Menu Dropdown (Desktop) -->
                    <div class="relative hidden sm:block" id="userMenuContainer">
                        <button type="button" 
                                onclick="toggleUserDropdown(event)" 
                                id="userMenuBtn"
                                class="flex items-center gap-2 text-sm font-medium text-slate-700 hover:text-slate-900 focus:outline-none p-1 rounded-xl hover:bg-slate-50 transition cursor-pointer">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full border border-slate-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="hidden md:inline-block max-w-[100px] truncate text-xs font-bold">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="userDropdownMenu" class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-[11px] text-slate-500">Signed in as</p>
                                <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                                <span class="inline-block mt-1 px-1.5 py-0.5 text-[9px] font-extrabold rounded bg-indigo-50 text-indigo-700 uppercase">{{ auth()->user()->role }}</span>
                            </div>
                            @if(auth()->user()->last_topic_id && auth()->user()->lastTopic)
                                <a href="{{ route('reader.topic', auth()->user()->lastTopic->slug) }}" class="block px-4 py-2 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">
                                    Resume Reading →
                                </a>
                            @endif
                            <a href="{{ route('reader.bookmarks') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                Saved Topics & Bookmarks
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">
                                    Admin Dashboard
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium cursor-pointer">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-xs sm:text-sm font-semibold text-slate-700 hover:text-indigo-600 px-2.5 sm:px-3 py-1.5 transition">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-3.5 py-1.5 rounded-xl shadow-xs transition">
                        Register
                    </a>
                @endauth

                <!-- Mobile Menu Hamburger Button -->
                <button type="button" 
                        onclick="toggleMobileDrawer()" 
                        class="lg:hidden p-2 text-slate-700 hover:text-indigo-600 hover:bg-slate-100 rounded-xl transition cursor-pointer"
                        aria-label="Open Navigation Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>

        <!-- Mobile Expandable Search Row -->
        <div id="mobile-search-bar" class="hidden md:hidden px-4 pb-3 pt-1 border-t border-slate-100 bg-white">
            <form action="{{ route('reader.search') }}" method="GET" class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="q" placeholder="Search rules, topics, mnemonics..." value="{{ request('q') }}"
                       class="w-full pl-9 pr-4 py-2 bg-slate-100 focus:bg-white text-xs rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition">
            </form>
        </div>
    </header>

    <!-- Mobile Navigation Drawer / Slide-Over Modal -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
        <!-- Backdrop -->
        <div onclick="toggleMobileDrawer()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
        
        <!-- Drawer Content -->
        <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-2xl flex flex-col z-10 transition-transform duration-300 transform translate-x-0">
            <!-- Drawer Header -->
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-black text-xs shadow-xs">
                        RE
                    </div>
                    <div>
                        <span class="font-extrabold text-sm text-slate-900 block leading-tight">RecapEnglish</span>
                        <span class="text-[10px] text-slate-500 block">Grammar Master</span>
                    </div>
                </div>
                <button type="button" 
                        onclick="toggleMobileDrawer()" 
                        class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition cursor-pointer"
                        aria-label="Close menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Drawer Links -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                
                <!-- Quick Search -->
                <form action="{{ route('reader.search') }}" method="GET" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" placeholder="Search rules..." value="{{ request('q') }}"
                           class="w-full pl-9 pr-3 py-2 bg-slate-100 text-xs rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-100">
                </form>

                <div class="space-y-1 pt-2">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-1">Navigation</div>
                    
                    <a href="{{ route('reader.home') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('reader.home') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Table of Contents (Curriculum)</span>
                    </a>

                    <a href="{{ route('skill-test.index') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('skill-test.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>50-MCQ Skill Test</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[9px] font-black uppercase rounded bg-emerald-100 text-emerald-800">Exam</span>
                    </a>

                    @auth
                        <a href="{{ route('reader.bookmarks') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('reader.bookmarks') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            <span>Saved Topics & Revision</span>
                        </a>

                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-800 border border-indigo-100">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Admin Panel Console</span>
                            </a>
                        @endif
                    @endauth
                </div>

            </div>

            <!-- Drawer Footer (User / Auth status) -->
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                @auth
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full border border-slate-200">
                            @else
                                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        @if(auth()->user()->last_topic_id && auth()->user()->lastTopic)
                            <a href="{{ route('reader.topic', auth()->user()->lastTopic->slug) }}" 
                               class="block w-full text-center py-2 px-3 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition">
                                Resume Reading →
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full py-2 px-3 bg-white hover:bg-rose-50 text-rose-600 font-bold text-xs rounded-xl border border-slate-200 transition cursor-pointer">
                                Sign Out
                            </button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" 
                           class="py-2.5 text-center text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 rounded-xl border border-slate-200 transition">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="py-2.5 text-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Global Toast / Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        @if(session('warning'))
            <div class="p-3.5 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600">
            <p>© {{ date('Y') }} RecapEnglish — English Grammar Mastery Book for Competitive Exams.</p>
            <p>Designed for BCS, University Admission & Job Examinations.</p>
        </div>
    </footer>

    <!-- Floating Scroll-to-Top Button -->
    <button id="scroll-to-top" 
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            aria-label="Scroll to top"
            class="fixed bottom-6 right-6 w-11 h-11 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/30 flex items-center justify-center transition-all duration-200 opacity-0 pointer-events-none z-40 cursor-pointer hover:scale-105 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    <script>
        // Reading progress and scroll to top
        window.addEventListener('scroll', function() {
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const progress = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
            const bar = document.getElementById('reading-progress-bar');
            if (bar) bar.style.width = progress + '%';

            const topBtn = document.getElementById('scroll-to-top');
            if (topBtn) {
                if (scrollTop > 350) {
                    topBtn.classList.remove('opacity-0', 'pointer-events-none');
                    topBtn.classList.add('opacity-100');
                } else {
                    topBtn.classList.add('opacity-0', 'pointer-events-none');
                    topBtn.classList.remove('opacity-100');
                }
            }
        });

        // Mobile Search Toggle
        function toggleMobileSearch() {
            const searchBar = document.getElementById('mobile-search-bar');
            if (searchBar) {
                searchBar.classList.toggle('hidden');
                if (!searchBar.classList.contains('hidden')) {
                    const input = searchBar.querySelector('input');
                    if (input) input.focus();
                }
            }
        }

        // Mobile Navigation Drawer Toggle
        function toggleMobileDrawer() {
            const drawer = document.getElementById('mobile-drawer');
            if (drawer) {
                const isHidden = drawer.classList.contains('hidden');
                if (isHidden) {
                    drawer.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    drawer.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        }

        // Desktop / Touch User Dropdown Toggle
        function toggleUserDropdown(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('userDropdownMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Close user dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.getElementById('userMenuContainer');
            const menu = document.getElementById('userDropdownMenu');
            if (container && menu && !container.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
