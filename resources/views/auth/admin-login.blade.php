<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In - RecapEnglish Console</title>

    <!-- Fonts: English (Plus Jakarta Sans) & Bangla (Hind Siliguri, Noto Sans Bengali) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Noto+Sans+Bengali:wght@400;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Vite Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="min-h-full flex flex-col justify-center py-10 px-4 sm:px-6 lg:px-8 bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">

    <!-- Subtle Ambient Background Glow -->
    <div class="absolute inset-0 -z-10 pointer-events-none flex justify-center overflow-hidden">
        <div class="w-[800px] h-[500px] bg-gradient-to-tr from-indigo-900/30 via-sky-900/20 to-purple-900/30 blur-3xl opacity-70 rounded-full -translate-y-1/3"></div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo & Badge -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-sky-400 text-white shadow-xl shadow-indigo-600/30 border border-indigo-400/30 ring-4 ring-indigo-500/10 mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-indigo-500/15 text-indigo-300 border border-indigo-500/30">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Admin Console Portal
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                RecapEnglish Management
            </h1>
            <p class="mt-1.5 text-xs sm:text-sm text-slate-400">
                Staff sign-in for Super Admins & Academic Editors
            </p>
        </div>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-900/90 backdrop-blur-md py-7 px-5 sm:px-8 shadow-2xl shadow-black/60 rounded-3xl border border-slate-800/90">

            <!-- Flash Error Notification -->
            @if(session('error'))
                <div class="mb-5 p-3.5 bg-rose-500/15 border border-rose-500/30 text-rose-300 rounded-xl text-xs sm:text-sm flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Flash Success Notification -->
            @if(session('success'))
                <div class="mb-5 p-3.5 bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 rounded-xl text-xs sm:text-sm flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Warning if logged in as a learner -->
            @auth
                @if(!auth()->user()->isAdmin())
                    <div class="mb-5 p-3.5 bg-amber-500/15 border border-amber-500/30 text-amber-200 rounded-xl text-xs flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-bold">Learner Account Active</p>
                            <p class="text-amber-300/80 mt-0.5">
                                You are signed in as <strong>{{ auth()->user()->email }}</strong>. Please sign in below with administrative credentials, or <a href="{{ route('reader.home') }}" class="underline hover:text-white font-semibold">return to Reader</a>.
                            </p>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Credentials Form -->
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="admin_email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                        Admin Email
                    </label>
                    <div class="mt-1 relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                            </svg>
                        </div>
                        <input id="admin_email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               placeholder="admin@recapenglish.com"
                               class="block w-full pl-9 pr-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="admin_password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                        Admin Password
                    </label>
                    <div class="mt-1 relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="admin_password" name="password" type="password" autocomplete="current-password" required
                               placeholder="••••••••••••"
                               class="block w-full pl-9 pr-10 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300 cursor-pointer"
                                aria-label="Toggle password visibility">
                            <svg id="pwd-icon-show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="pwd-icon-hide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900">
                        <span class="ml-2 text-xs font-semibold text-slate-400">Remember session</span>
                    </label>
                    <span class="text-[11px] font-medium text-slate-500">256-bit encrypted</span>
                </div>

                <div>
                    <button type="submit"
                            class="w-full mt-2 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-600/30 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Authenticate &amp; Enter Console</span>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-slate-800">
                <div class="flex items-center justify-between mb-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Quick Demo Accounts (Click to Fill):
                    </p>
                    <span class="text-[10px] text-indigo-400 font-semibold">Demo Mode</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-center">
                    <button type="button" 
                            onclick="fillAdmin('superadmin@recapenglish.com', 'password123')" 
                            class="p-2.5 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 hover:border-amber-500/50 text-amber-200 text-xs font-bold rounded-xl transition cursor-pointer text-left flex items-center justify-between group">
                        <div>
                            <span class="block text-amber-300 font-extrabold">Super Admin</span>
                            <span class="block text-[10px] text-amber-400/80 font-normal">Full root privileges</span>
                        </div>
                        <svg class="w-4 h-4 text-amber-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    
                    <button type="button" 
                            onclick="fillAdmin('admin@recapenglish.com', 'password123')" 
                            class="p-2.5 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 hover:border-indigo-500/50 text-indigo-200 text-xs font-bold rounded-xl transition cursor-pointer text-left flex items-center justify-between group">
                        <div>
                            <span class="block text-indigo-300 font-extrabold">Editor Admin</span>
                            <span class="block text-[10px] text-indigo-400/80 font-normal">Content &amp; MCQs management</span>
                        </div>
                        <svg class="w-4 h-4 text-indigo-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Portal Navigation Links -->
            <div class="mt-6 pt-4 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Learner Sign In Portal</span>
                </a>
                <a href="{{ route('reader.home') }}" class="text-slate-400 hover:text-white transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back to Reader Home</span>
                </a>
            </div>

        </div>

        <p class="mt-6 text-center text-[11px] text-slate-500">
            &copy; {{ date('Y') }} RecapEnglish Grammar Master Book. Protected System.
        </p>
    </div>

    <script>
        function fillAdmin(email, password) {
            const emailInput = document.getElementById('admin_email');
            const pwdInput = document.getElementById('admin_password');
            if (emailInput && pwdInput) {
                emailInput.value = email;
                pwdInput.value = password;
                pwdInput.focus();
            }
        }

        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('admin_password');
            const showIcon = document.getElementById('pwd-icon-show');
            const hideIcon = document.getElementById('pwd-icon-hide');
            if (pwdInput && showIcon && hideIcon) {
                if (pwdInput.type === 'password') {
                    pwdInput.type = 'text';
                    showIcon.classList.add('hidden');
                    hideIcon.classList.remove('hidden');
                } else {
                    pwdInput.type = 'password';
                    showIcon.classList.remove('hidden');
                    hideIcon.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>
