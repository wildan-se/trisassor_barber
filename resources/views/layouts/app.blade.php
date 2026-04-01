<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TRISASSOR — Premium Barbershop')</title>
    <meta name="description" content="@yield('description', 'Booking barbershop premium online. Pilih barber favorit, layanan terbaik, slot waktu yang tepat.')">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">

    {{-- Alpine.js removed, handled by Livewire 3 --}}

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @stack('head')
</head>
<body class="bg-barber-dark text-barber-light font-sans antialiased overflow-x-hidden">

    {{-- Navbar & Mobile Menu Wrapper --}}
    <div x-data="{ open: false, scrolled: false }" 
         x-init="scrolled = window.scrollY > 20"
         @scroll.window="scrolled = window.scrollY > 20"
         class="relative z-50">

        <nav id="navbar" 
             class="fixed left-0 right-0 transition-all duration-500 mx-auto z-50 flex justify-between items-center"
             :class="scrolled ? 'top-4 w-[95%] max-w-6xl bg-barber-dark/90 backdrop-blur-md shadow-2xl rounded-2xl border border-zinc-800/80 py-3 px-6 md:px-8' : 'top-0 w-full bg-transparent py-5 px-6 md:px-12'">

            {{-- Logo --}}
            <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 group z-50">
                <svg class="w-6 h-6 md:w-8 md:h-8 text-barber-gold group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7l3-1m0 0l3 1m-3-1v14m5-14l3 1m0 0l3-1m-3 1v14"/>
                </svg>
                <span class="font-display text-xl md:text-2xl font-bold tracking-widest text-white mt-1 uppercase">trisassor</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-8 text-xs uppercase tracking-widest font-semibold text-gray-300">
                <a href="{{ route('home') }}#about"    class="hover:text-barber-gold transition-colors duration-300">Tentang</a>
                <a href="{{ route('home') }}#services" class="hover:text-barber-gold transition-colors duration-300">Layanan</a>
                <a href="{{ route('home') }}#gallery"  class="hover:text-barber-gold transition-colors duration-300">Galeri</a>
                <a href="{{ route('home') }}#location" class="hover:text-barber-gold transition-colors duration-300">Lokasi</a>
            </div>

            {{-- CTA + Auth --}}
            <div class="hidden md:flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" wire:navigate class="text-xs uppercase tracking-widest text-gray-400 hover:text-barber-gold transition-colors font-semibold">Masuk</a>
                @endguest

                @auth
                    <div class="flex items-center gap-3 mr-4">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full border border-barber-gold aspect-square object-cover shadow-sm">
                        @else
                            <div class="w-8 h-8 rounded-full bg-zinc-800 border-2 border-barber-gold flex items-center justify-center text-xs text-barber-gold font-bold shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-xs tracking-widest text-white font-semibold hidden lg:block">Hai, {{ strtok(auth()->user()->name, " ") }}</span>
                    </div>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="bg-barber-gold hover:bg-white text-barber-dark font-bold text-xs uppercase tracking-widest py-2.5 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Admin Pnl
                        </a>
                    @else
                        <a href="{{ route('booking.index') }}" wire:navigate class="bg-barber-gold hover:bg-white text-barber-dark font-bold text-xs uppercase tracking-widest py-2.5 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Booking Saya
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex items-center ml-2">
                        @csrf
                        <button type="submit" title="Keluar" class="text-zinc-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-zinc-800 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                @endauth

                @guest
                <a href="{{ route('booking.create') }}" wire:navigate class="bg-barber-gold hover:bg-white text-barber-dark font-bold text-xs uppercase tracking-widest py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Reservasi
                </a>
                @endguest
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 relative w-8 h-8">
                <svg x-show="!open" class="w-7 h-7 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-7 h-7 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </nav>

        {{-- Mobile Menu Overlay (Outside of backdrop-filter parent to cover full screen) --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="display: none;"
             class="fixed inset-0 bg-barber-dark/98 backdrop-blur-xl z-60 flex flex-col items-center justify-center md:hidden">
            
            {{-- Close Button --}}
            <button @click="open = false" class="absolute top-6 right-6 text-zinc-400 hover:text-white p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <ul class="flex flex-col items-center gap-8 w-full text-lg uppercase tracking-wider font-display text-white text-center">
                <li><a @click="open=false" href="{{ route('home') }}#about"    class="hover:text-barber-gold transition-colors">Tentang</a></li>
                <li><a @click="open=false" href="{{ route('home') }}#services" class="hover:text-barber-gold transition-colors">Layanan</a></li>
                <li><a @click="open=false" href="{{ route('home') }}#gallery"  class="hover:text-barber-gold transition-colors">Galeri</a></li>
                <li><a @click="open=false" href="{{ route('home') }}#location" class="hover:text-barber-gold transition-colors">Lokasi</a></li>
                @auth
                    <li class="flex flex-col items-center gap-2 mb-4 border-b border-zinc-800 pb-6 w-full px-8">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-16 h-16 rounded-full border-2 border-barber-gold aspect-square object-cover shadow-lg">
                        @else
                            <div class="w-16 h-16 rounded-full bg-zinc-800/80 border-2 border-barber-gold shadow-lg flex items-center justify-center text-2xl text-barber-gold font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-sm tracking-widest text-white font-semibold mt-2">HALO, {{ strtoupper(auth()->user()->name) }}</span>
                    </li>

                    @if(auth()->user()->isAdmin())
                        <li class="w-full px-8 mt-2">
                            <a @click="open=false" href="{{ route('admin.dashboard') }}" wire:navigate class="bg-barber-gold hover:bg-white text-barber-dark font-bold text-sm uppercase tracking-wider py-4 px-8 rounded-full transition-all w-full flex justify-center shadow-lg">
                                Admin Panel
                            </a>
                        </li>
                    @else
                        <li class="w-full px-8 mt-2">
                            <a @click="open=false" href="{{ route('booking.index') }}" wire:navigate class="bg-barber-gold hover:bg-white text-barber-dark font-bold text-sm uppercase tracking-wider py-4 px-8 rounded-full transition-all w-full flex justify-center shadow-lg">
                                Booking Saya
                            </a>
                        </li>
                    @endif
                    <li class="w-full px-8 mt-4">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex items-center justify-center gap-2 text-zinc-400 hover:text-red-500 transition-colors uppercase tracking-wider w-full py-4 rounded-xl border border-zinc-800 hover:border-red-500/50 hover:bg-red-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                                Keluar
                            </button>
                        </form>
                    </li>
                @endauth
                @guest
                    <li><a @click="open=false" href="{{ route('login') }}" wire:navigate class="hover:text-barber-gold transition-colors">Masuk</a></li>
                    <li><a @click="open=false" href="{{ route('register') }}" wire:navigate class="hover:text-barber-gold transition-colors">Daftar</a></li>
                @endguest
            </ul>
            
            @guest
            <div class="mt-10 w-full flex justify-center px-8">
                <a href="{{ route('booking.create') }}" wire:navigate class="bg-barber-gold hover:bg-white text-barber-dark font-bold text-xs uppercase tracking-wider py-4 px-8 rounded-full transition-all w-full max-w-xs text-center shadow-lg">
                    Reservasi Sekarang
                </a>
            </div>
            @endguest
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-20 right-4 z-[100] bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-xl text-sm font-semibold shadow-2xl backdrop-blur">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error') || $errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-20 right-4 z-[100] bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-4 rounded-xl text-sm font-semibold shadow-2xl backdrop-blur max-w-sm">
            {{ session('error') ?? $errors->first() }}
        </div>
    @endif

    {{-- Main Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="bg-black pt-20 pb-10 relative overflow-hidden border-t border-zinc-900 z-10">
        <div class="absolute bottom-0 left-0 w-full overflow-hidden flex justify-center pointer-events-none z-0 opacity-[0.04] select-none">
            <span class="font-display text-[7.2vw] font-bold text-white whitespace-nowrap leading-none uppercase tracking-widest">
                TRISASSOR BARBER HOUSE
            </span>
        </div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12 mb-20">
                <div class="md:w-1/2">
                    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-2 mb-6 group">
                        <svg class="w-8 h-8 text-barber-gold group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7l3-1m0 0l3 1m-3-1v14m5-14l3 1m0 0l3-1m-3 1v14"/>
                        </svg>
                        <span class="font-display text-3xl font-bold tracking-widest text-white mt-1">TRISASSOR</span>
                    </a>
                    <p class="text-zinc-500 max-w-sm text-sm leading-relaxed">
                        Mendefinisikan ulang standar maskulin. Kami tidak sekadar memotong rambut, kami memahat karakter untuk para pria modern.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-8 md:gap-24 w-full md:w-auto">
                    <div>
                        <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Jelajahi</h4>
                        <ul class="space-y-4 text-zinc-500 text-sm font-semibold tracking-wider uppercase">
                            <li><a href="{{ route('home') }}#about"    class="hover:text-barber-gold transition-colors">Tentang</a></li>
                            <li><a href="{{ route('home') }}#services" class="hover:text-barber-gold transition-colors">Layanan</a></li>
                            <li><a href="{{ route('home') }}#gallery"  class="hover:text-barber-gold transition-colors">Galeri</a></li>
                            <li><a href="{{ route('booking.create') }}" wire:navigate class="hover:text-barber-gold transition-colors">Reservasi</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Sosial</h4>
                        <ul class="space-y-4 text-zinc-500 text-sm font-semibold tracking-wider uppercase">
                            <li><a href="https://www.instagram.com/trisassorbarberhouse/" target="_blank" class="hover:text-barber-gold transition-colors">Instagram</a></li>
                            <li><a href="https://www.tiktok.com/@trisassor.barberhouse" target="_blank" class="hover:text-barber-gold transition-colors">TikTok</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-zinc-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center">
                <p class="text-zinc-600 text-xs tracking-[0.2em] uppercase font-bold">
                    © {{ date('Y') }} TRISASSOR BARBER HOUSE. All rights reserved.
                </p>
                <p class="text-zinc-600 text-xs tracking-widest uppercase font-bold">
                    Designed with <span class="text-barber-gold">♥</span> Precision
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    @livewireScripts
</body>
</html>
