<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — TRISASSOR</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="relative min-h-screen">

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/60 z-20 lg:hidden" x-transition></div>

    {{-- Sidebar --}}
    <aside class="fixed top-0 left-0 h-full w-64 bg-zinc-900 border-r border-zinc-800 z-30 transition-transform duration-300"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Logo --}}
        <div class="p-6 border-b border-zinc-800">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 group">
                <svg class="w-6 h-6 text-barber-gold group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7l3-1m0 0l3 1m-3-1v14m5-14l3 1m0 0l3-1m-3 1v14"/>
                </svg>
                <div>
                    <span class="font-display text-lg font-bold tracking-widest text-white uppercase">TRISASSOR</span>
                    <p class="text-[10px] text-barber-gold uppercase tracking-widest font-bold">Admin Panel</p>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="p-4 space-y-1">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard',       'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', 'label' => 'Dashboard'],
                    ['route' => 'admin.bookings.index',  'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Semua Booking'],
                    ['route' => 'admin.barbers.index',   'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Barber'],
                    ['route' => 'admin.services.index',  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Layanan'],
                    ['route' => 'admin.schedules.index', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Jadwal'],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" wire:navigate
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200
                          {{ request()->routeIs($item['route'] . '*')
                             ? 'bg-barber-gold/10 text-barber-gold border border-barber-gold/20'
                             : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Bottom: user & logout --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-zinc-800">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-8 h-8 rounded-full bg-barber-gold flex items-center justify-center text-barber-dark font-bold text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-zinc-500">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-zinc-400 hover:text-red-400 hover:bg-red-500/10 transition-all font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">

        {{-- Top Bar --}}
        <header class="bg-zinc-900/80 backdrop-blur border-b border-zinc-800 sticky top-0 z-10 px-4 md:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-zinc-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-white">@yield('title', 'Dashboard')</h1>
                        <p class="text-xs text-zinc-500">@yield('subtitle', 'Kelola operasional barbershop')</p>
                    </div>
                </div>
                <a href="{{ route('home') }}" target="_blank"
                   class="hidden md:flex items-center gap-2 text-xs text-zinc-500 hover:text-barber-gold transition-colors font-semibold uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Website
                </a>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="m-6 bg-green-500/10 border border-green-500/30 text-green-400 px-5 py-3 rounded-xl text-sm font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                 class="m-6 bg-red-500/10 border border-red-500/30 text-red-400 px-5 py-3 rounded-xl text-sm font-semibold">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-4 md:p-8">
            @yield('content')
        </main>
    </div>

    </div>
    @stack('scripts')
    @livewireScripts
</body>
</html>
