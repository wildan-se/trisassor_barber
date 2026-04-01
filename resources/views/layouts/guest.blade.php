<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TRISASSOR') }} - Welcome</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-white bg-barber-dark min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

    <div class="mb-8 mt-6">
        <a href="/" wire:navigate class="group flex items-center justify-center flex-col gap-2">
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-barber-gold group-hover:-rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/></svg>
                <span class="font-display font-bold text-3xl tracking-[0.15em] text-white">TRISASSOR</span>
            </div>
            <p class="text-barber-gold text-xs uppercase tracking-[0.3em] font-bold text-center mt-1 group-hover:text-white transition-colors duration-300">Barber House</p>
        </a>
    </div>

    <div class="w-full sm:max-w-md px-8 py-8 bg-zinc-900 shadow-2xl overflow-hidden sm:rounded-2xl border border-zinc-800">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
