<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'KAS-RT') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-900" x-data="{ sidebarOpen: false }">

    {{-- Overlay & Mobile Sidebar (Sama seperti sebelumnya) --}}
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/80 lg:hidden" x-cloak></div>

    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-slate-950 lg:hidden" x-cloak>
        @include('layouts.sidebar')
    </div>

    {{-- DESKTOP SIDEBAR (FIXED & NO SCROLL PADA WRAPPER) --}}
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
        {{-- Pastikan h-full dan overflow-hidden agar pembungkus tidak ikut scroll --}}
        <div class="flex flex-col h-full overflow-hidden border-r border-slate-800 bg-slate-950">
            @include('layouts.sidebar')
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="flex flex-col h-screen lg:pl-64">
        @include('layouts.navigation')
        <main class="flex-1 overflow-y-auto bg-slate-50">
            <div class="py-8 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>