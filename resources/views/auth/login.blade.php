<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KAS-RT Management</title>
    @vite(['resources/css/app.js', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 dark:bg-slate-950 min-h-screen flex items-center justify-center p-4 relative overflow-hidden antialiased">

    {{-- DEKORASI BACKGROUND BLUR (Efek Estetik Glow) --}}
    <div class="absolute -top-40 -right-40 size-[500px] bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-40 -left-40 size-[500px] bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full blur-[120px]"></div>

    {{-- KONTEN UTAMA CARD --}}
    <div class="w-full max-w-md relative z-10">
        
        {{-- LOGO & HEADER UTAMA --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center size-16 bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-3xl shadow-xl shadow-blue-500/20 mb-4 rotate-3 transform transition-transform hover:rotate-0 duration-300">
                <svg class="size-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v14.25M9 6.5h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5v3" />
                </svg>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 dark:text-white uppercase">
                KAS-RT <span class="text-blue-600">Management</span>
            </h1>
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Sistem Keuangan & Warga Digital</p>
        </div>

        {{-- BOX CARD LOGIN --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none rounded-[2.5rem] p-8 md:p-10 relative overflow-hidden">
            
            {{-- Flash Session Status --}}
            @if (session('status'))
                <div class="mb-4 bg-emerald-500 text-white p-4 rounded-2xl text-xs font-bold shadow-md">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }" class="space-y-5">
                @csrf

                {{-- INPUT EMAIL --}}
                <div class="space-y-1.5">
                    <label for="email" class="text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 tracking-widest ml-1">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="nama@email.com"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 text-slate-800 dark:text-white border border-slate-100 dark:border-slate-800 rounded-2xl py-3.5 pl-11 pr-4 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-inner transition-all">
                    </div>
                    @error('email')
                        <p class="text-[10px] font-black text-rose-500 ml-1 uppercase tracking-wider">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                {{-- INPUT PASSWORD --}}
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 tracking-widest">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black text-blue-500 hover:text-blue-600 uppercase tracking-widest transition-colors">Lupa?</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0V10.5m-2.851 0a2.003 2.003 0 00-1.5 1.95v7.25c0 1.1.9 2 2 2h13.5a2 2 0 002-2v-7.25a2 2 0 00-1.5-1.95m-12 0h12" />
                            </svg>
                        </div>
                        
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 text-slate-800 dark:text-white border border-slate-100 dark:border-slate-800 rounded-2xl py-3.5 pl-11 pr-12 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-inner transition-all">
                        
                        {{-- TOMBOL MATA (ALPINJS INTERACTIVE) --}}
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-500 transition-colors">
                            <svg x-show="!showPassword" class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="showPassword" style="display: none;" class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[10px] font-black text-rose-500 ml-1 uppercase tracking-wider">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                {{-- REMEMBER ME --}}
                <div class="flex items-center px-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="rounded-md bg-slate-50 border-slate-200 text-blue-600 shadow-sm focus:ring-blue-500 size-4 cursor-pointer">
                        <span class="ml-2 text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 tracking-wider">Ingat Sesi Saya</span>
                    </label>
                </div>

                {{-- BUTTON SUBMIT GRADIENT --}}
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span>Masuk Aplikasi</span>
                        <svg class="size-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>

            </form>
        </div>

        {{-- FOOTER HAK CIPTA --}}
        <p class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6">&copy; {{ date('Y') }} KAS-RT Management. All Rights Reserved.</p>
    </div>

</body>
</html>