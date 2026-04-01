@extends('layouts.app')

@section('title', 'Premium Barbershop Experience | TRISASSOR')
@section('description', 'Booking barbershop premium TRISASSOR secara online. Barber profesional, layanan terbaik, pengalaman berkelas.')

@section('content')

{{-- ═══════════════════════════════════════════════════════╗
     HERO SECTION
╚═══════════════════════════════════════════════════════════ --}}
<section class="relative h-screen w-full flex items-center justify-center overflow-hidden">
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
        <source src="https://assets.mixkit.co/videos/preview/mixkit-man-getting-a-haircut-from-a-barber-high-angle-41249-large.mp4" type="video/mp4">
    </video>
    <div class="absolute inset-0 z-10" style="background: linear-gradient(to bottom, rgba(10,10,10,0.6) 0%, rgba(10,10,10,0.85) 70%, rgba(10,10,10,1) 100%)"></div>
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 h-full flex flex-col justify-center items-center text-center mt-10 md:mt-0">
        <div class="flex items-center gap-3 opacity-0 animate-fade-in-up">
            <div class="w-8 sm:w-12 h-px bg-barber-gold"></div>
            <span class="text-barber-gold uppercase tracking-widest font-bold text-xs sm:text-sm">EST. 2017 | Premium Grooming</span>
            <div class="w-8 sm:w-12 h-px bg-barber-gold"></div>
        </div>
        <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold uppercase tracking-tighter text-white mt-4 mb-4 leading-tight opacity-0 animate-fade-in-up" style="animation-delay:0.2s">
            Bukan Sekadar <br> <span class="text-barber-gold">Potong Rambut</span>
        </h1>
        <p class="max-w-2xl text-xs sm:text-sm md:text-xl text-white/80 mb-8 md:mb-12 leading-relaxed opacity-0 animate-fade-in-up px-2" style="animation-delay:0.4s">
            Kembalikan kepercayaan dirimu dengan sentuhan presisi dari kapster profesional kami. Suasana klasik, hasil modern, kepuasan mutlak.
        </p>
        <div class="w-full sm:w-auto opacity-0 animate-fade-in-up px-4 sm:px-0" style="animation-delay:0.6s">
            @if(auth()->check() && auth()->user()->isAdmin())
            <a wire:navigate href="{{ route('admin.dashboard') }}" class="group flex items-center justify-center gap-3 bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 sm:py-4 px-8 sm:px-10 rounded-full transition-all duration-300 transform hover:-translate-y-1 shadow-2xl w-full sm:w-auto">
                <span class="uppercase tracking-wider text-xs sm:text-sm">Dashboard Admin</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            @else
            <a wire:navigate href="{{ route('booking.create') }}" class="group flex items-center justify-center gap-3 bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 sm:py-4 px-8 sm:px-10 rounded-full transition-all duration-300 transform hover:-translate-y-1 shadow-2xl w-full sm:w-auto">
                <span class="uppercase tracking-wider text-xs sm:text-sm">Reservasi Sekarang</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            @endif
        </div>
    </div>
    <div class="absolute bottom-6 md:bottom-10 left-1/2 transform -translate-x-1/2 z-20 flex flex-col items-center gap-2 text-white/50">
        <span class="text-[10px] md:text-xs uppercase tracking-widest">Scroll</span>
        <div class="w-5 h-8 md:w-6 md:h-10 border-2 border-white/30 rounded-full flex justify-center p-1">
            <div class="w-1 h-1.5 md:h-2 bg-barber-gold rounded-full animate-bounce"></div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════╗
     ABOUT SECTION
╚═══════════════════════════════════════════════════════════ --}}
<section id="about" class="py-24 bg-barber-dark relative z-10">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-end mb-20">
            <div class="w-full lg:w-3/5 reveal-up">
                <p class="text-barber-gold tracking-[0.2em] text-xs md:text-sm font-bold mb-4 uppercase">Tradisi &amp; Presisi</p>
                <h2 class="font-display text-4xl sm:text-5xl md:text-7xl font-bold text-white leading-[1.1] uppercase tracking-tighter">
                    Mendefinisikan Ulang <br> <span class="text-zinc-500">Standar Maskulin.</span>
                </h2>
            </div>
            <div class="w-full lg:w-2/5 reveal-up" style="transition-delay:0.2s">
                <p class="text-zinc-400 text-xs sm:text-sm md:text-lg leading-relaxed border-l-2 border-barber-gold pl-6">
                    Bukan sekadar rutinitas merapikan rambut. Kami membangun ruang di mana teknik klasik bertemu dengan gaya hidup modern. Setiap potongan adalah mahakarya, setiap kunjungan adalah pengalaman berkelas.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-24">
            @foreach([
                ['icon' => 'M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5', 'title' => 'Kapster Master', 'desc' => 'Seniman rambut dengan pengalaman lebih dari satu dekade di industri grooming pria.'],
                ['icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'title' => 'Produk Elit', 'desc' => 'Hanya mengaplikasikan pomade, tonik, dan aftershave dari brand premium internasional.'],
                ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Atmosfer Klasik', 'desc' => 'Interior maskulin yang dirancang khusus untuk memberikan relaksasi maksimal saat dicukur.'],
            ] as $card)
            <div class="group bg-zinc-900/50 border border-zinc-800 p-6 hover:bg-zinc-900 hover:border-barber-gold hover:-translate-y-2 transition-all duration-300 cursor-pointer reveal-up">
                <div class="w-12 h-12 mb-6 text-zinc-500 group-hover:text-barber-gold transition-colors duration-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 font-display tracking-wide group-hover:text-barber-gold transition-colors">{{ $card['title'] }}</h3>
                <p class="text-xs md:text-sm text-zinc-500 group-hover:text-zinc-400 transition-colors">{{ $card['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Stats --}}
        <div id="stats-container" class="border-y border-zinc-800 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-y-10 md:gap-y-0 text-center">
                <div class="px-4 border-r border-zinc-800">
                    <div class="font-display text-4xl md:text-5xl font-bold text-white mb-2"><span class="counter" data-target="2017">0</span></div>
                    <p class="text-[10px] md:text-xs uppercase tracking-[0.15em] text-zinc-500 font-bold">Berdiri Sejak</p>
                </div>
                <div class="px-4 md:border-r md:border-zinc-800">
                    <div class="relative inline-block font-display text-4xl md:text-5xl font-bold text-white mb-2">
                        <span class="counter" data-target="2300">0</span>
                        <span class="text-barber-gold absolute -right-6 md:-right-8 top-0 text-3xl md:text-4xl">+</span>
                    </div>
                    <p class="text-[10px] md:text-xs uppercase tracking-[0.15em] text-zinc-500 font-bold">Pelanggan Puas</p>
                </div>
                <div class="px-4 border-r border-zinc-800">
                    <div class="font-display text-4xl md:text-5xl font-bold text-white mb-2"><span class="counter" data-target="15">0</span></div>
                    <p class="text-[10px] md:text-xs uppercase tracking-[0.15em] text-zinc-500 font-bold">Kapster Profesional</p>
                </div>
                <div class="px-4">
                    <div class="flex items-center justify-center gap-2 font-display text-4xl md:text-5xl font-bold text-white mb-2">
                        <span>4.5</span>
                        <svg class="w-6 h-6 text-barber-gold mb-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-[10px] md:text-xs uppercase tracking-[0.15em] text-zinc-500 font-bold">Rating Ulasan</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════╗
     SERVICES SECTION (DB-driven)
╚═══════════════════════════════════════════════════════════ --}}
<section id="services" class="py-24 bg-zinc-950 relative z-10">
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative">
        <div class="mb-16 reveal-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-px bg-barber-gold"></div>
                <span class="text-barber-gold uppercase tracking-widest font-bold text-xs sm:text-sm">Signature Services</span>
            </div>
            <h2 class="font-display text-4xl md:text-6xl font-bold text-white uppercase tracking-tighter">
                Layanan <span class="text-zinc-600">Grooming</span>
            </h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 relative">
            <div class="w-full lg:w-3/5 flex flex-col z-20">
                @foreach($services as $service)
                <div class="service-item group py-8 border-b border-zinc-800 cursor-pointer {{ $service->is_featured ? 'relative pl-6 border-l-4 border-l-barber-gold bg-gradient-to-r from-zinc-900/50 to-transparent' : '' }}"
                     data-image="{{ $service->image_url }}">
                    @if($service->is_featured)
                        <div class="absolute -top-3 left-6 bg-barber-gold text-barber-dark text-[10px] font-bold uppercase tracking-widest py-1 px-3 rounded-sm flex items-center gap-1">
                            ★ Pilihan Favorit
                        </div>
                    @endif
                    <div class="flex justify-between items-end mb-2 {{ $service->is_featured ? 'mt-2' : '' }}">
                        <h3 class="font-display text-2xl md:text-3xl lg:text-4xl {{ $service->is_featured ? 'text-white' : 'text-zinc-400' }} group-hover:text-white transition-colors duration-500 uppercase tracking-tight">
                            {{ $service->name }}
                        </h3>
                        <span class="text-barber-gold font-bold text-lg md:text-xl lg:text-2xl group-hover:scale-110 transition-transform duration-500">
                            {{ $service->formatted_price }}
                        </span>
                    </div>
                    <p class="text-zinc-500 text-xs md:text-base group-hover:text-zinc-300 transition-colors duration-500 max-w-md">
                        {{ $service->description }}
                    </p>
                    <div class="mt-3 flex items-center gap-2 text-xs text-zinc-600 group-hover:text-zinc-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $service->duration_minutes }} menit
                    </div>
                </div>
                @endforeach
            </div>
            <div class="hidden lg:block w-2/5 relative z-10">
                <div class="sticky top-32 flex items-start gap-4 h-[500px]">
                    <div class="flex-1 h-full rounded-lg overflow-hidden shadow-2xl border border-zinc-800 relative z-10">
                        <img id="service-preview-img"
                             src="{{ $services->where('is_featured', true)->first()?->image_url ?? $services->first()?->image_url }}"
                             alt="Service Preview"
                             class="w-full h-full object-cover transition-all duration-700 ease-in-out"
                             style="opacity:1">
                        <div class="absolute inset-0 bg-barber-dark/10"></div>
                    </div>
                    <div class="pt-2">
                        <span class="inline-block [writing-mode:vertical-rl] text-[10px] md:text-xs uppercase tracking-[0.4em] text-zinc-600 font-bold">
                            Trisassor Exclusive Services
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-12 text-center reveal-up">
            @if(auth()->check() && auth()->user()->isAdmin())
            <a wire:navigate href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-3 bg-barber-gold hover:bg-white text-barber-dark font-bold py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl text-sm uppercase tracking-widest">
                Dashboard Admin
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @else
            <a wire:navigate href="{{ route('booking.create') }}"
               class="inline-flex items-center gap-3 bg-barber-gold hover:bg-white text-barber-dark font-bold py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl text-sm uppercase tracking-widest">
                Reservasi Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @endif
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════╗
     GALLERY + LOCATION SECTION (same as original index.html)
╚═══════════════════════════════════════════════════════════ --}}
<section id="gallery" class="py-24 bg-barber-dark relative z-10">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div class="reveal-up">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-px bg-barber-gold"></div>
                    <span class="text-barber-gold uppercase tracking-widest font-bold text-xs sm:text-sm">The Lookbook</span>
                </div>
                <h2 class="font-display text-4xl md:text-6xl font-bold text-white uppercase tracking-tighter">
                    Galeri <span class="text-zinc-600">Pelanggan</span>
                </h2>
            </div>
            <a href="https://www.instagram.com/trisassorbarberhouse/" target="_blank"
               class="group inline-flex items-center gap-2 text-zinc-400 hover:text-barber-gold transition-colors font-bold uppercase tracking-wider text-xs sm:text-sm pb-1 border-b border-zinc-800 hover:border-barber-gold reveal-up">
                Lihat lebih banyak di Instagram
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 auto-rows-[200px] md:auto-rows-[280px] gap-4 md:gap-6">
            @foreach([
                ['src' => 'https://images.unsplash.com/photo-1593702275687-f8b402bf1fb5?q=80&w=2000', 'alt' => 'Classic Pompadour', 'title' => 'Classic Pompadour', 'by' => 'BY CAPTAIN. MIKE', 'class' => 'col-span-2 row-span-2'],
                ['src' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=1000', 'alt' => 'Textured Crop', 'title' => 'Textured Crop', 'by' => '', 'class' => 'col-span-1 row-span-1'],
                ['src' => 'https://images.unsplash.com/photo-1512496015851-a1dc8f411831?q=80&w=1000', 'alt' => 'Skin Fade', 'title' => 'Skin Fade & Beard', 'by' => '', 'class' => 'col-span-1 row-span-2'],
                ['src' => 'https://images.unsplash.com/photo-1605497788044-5a32c7078486?q=80&w=1000', 'alt' => 'Slick Back', 'title' => 'Slick Back', 'by' => '', 'class' => 'col-span-1 row-span-1'],
                ['src' => 'https://images.unsplash.com/photo-1532710093739-9470ac1d4e0b?q=80&w=2000', 'alt' => 'Buzz Cut', 'title' => 'Modern Buzz Cut', 'by' => '', 'class' => 'col-span-2 row-span-1'],
            ] as $photo)
            <div class="{{ $photo['class'] }} group relative overflow-hidden rounded-xl bg-zinc-900 reveal-up">
                <img src="{{ $photo['src'] }}" alt="{{ $photo['alt'] }}"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-barber-dark via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                    <h3 class="font-display text-xl text-white uppercase tracking-wider translate-y-4 group-hover:translate-y-0 transition-transform duration-500">{{ $photo['title'] }}</h3>
                    @if($photo['by'])<p class="text-barber-gold text-xs font-bold tracking-widest translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">{{ $photo['by'] }}</p>@endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Location Section --}}
<section id="location" class="py-24 bg-zinc-950 relative z-10">
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        <div class="mb-16 reveal-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-px bg-barber-gold"></div>
                <span class="text-barber-gold uppercase tracking-widest font-bold text-xs sm:text-sm">Headquarters</span>
            </div>
            <h2 class="font-display text-4xl md:text-6xl font-bold text-white uppercase tracking-tighter">
                Satu Titik, <span class="text-zinc-600">Pengalaman Maksimal.</span>
            </h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-12 items-start">
            <div class="w-full lg:w-1/2 flex flex-col gap-10 reveal-up lg:sticky lg:top-32">
                <div class="relative pl-8 border-l border-zinc-800 hover:border-barber-gold transition-colors duration-500">
                    <div class="absolute top-0 -left-[17px] w-8 h-8 bg-zinc-950 border border-zinc-800 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-barber-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-white font-bold uppercase tracking-widest text-sm mb-3">Lokasi Barbershop</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed max-w-sm">Jl. Sudirman No. 45, Kompleks Ruko Premium Blok A1, Jakarta Selatan, 12190.</p>
                </div>
                <div class="relative pl-8 border-l border-zinc-800 hover:border-barber-gold transition-colors duration-500">
                    <div class="absolute top-0 -left-[17px] w-8 h-8 bg-zinc-950 border border-zinc-800 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-barber-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-white font-bold uppercase tracking-widest text-sm mb-3">Jam Operasional</h3>
                    <ul class="text-zinc-400 text-sm space-y-2 max-w-sm">
                        <li class="flex justify-between border-b border-zinc-800/50 pb-1"><span>Senin - Jumat</span><span class="text-white font-bold">10:00 - 21:00</span></li>
                        <li class="flex justify-between border-b border-zinc-800/50 pb-1"><span>Sabtu - Minggu</span><span class="text-white font-bold">09:00 - 22:00</span></li>
                        <li class="flex justify-between pb-1 text-zinc-600"><span>Hari Libur Nasional</span><span>Tutup</span></li>
                    </ul>
                </div>
                <a href="https://wa.me/6281234567890" target="_blank"
                   class="ml-4 inline-flex items-center gap-2 bg-white text-zinc-950 hover:bg-barber-gold font-bold py-3 px-6 rounded-full transition-colors duration-300 text-sm uppercase tracking-wider shadow-lg max-w-xs">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hubungi via WhatsApp
                </a>
            </div>
            <div class="w-full lg:w-1/2 reveal-up">
                <div class="relative w-full h-[500px] lg:h-[750px] rounded-2xl overflow-hidden shadow-2xl border border-zinc-800 bg-zinc-900">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15862.446769070886!2d106.6806149871582!3d-6.314631899999986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e547f559d05b%3A0x21bd48da108dddb5!2sTrisassor%20Barber%20and%20shop!5e0!3m2!1sid!2sid!4v1774317798603!5m2!1sid!2sid"
                            allowfullscreen loading="lazy"
                            class="w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-700"
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Reveal on scroll
const revealEls = document.querySelectorAll('.reveal-up');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } });
}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
revealEls.forEach(el => observer.observe(el));

// Counter animation
const counters = document.querySelectorAll('.counter');
const statsContainer = document.getElementById('stats-container');
let hasCounted = false;
if (statsContainer) {
    new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && !hasCounted) {
            hasCounted = true;
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let current = target > 1900 && target < 2100 ? 1900 : 0;
                const inc = target / (7000 / 16);
                const update = () => {
                    current += inc;
                    if (current < target) { counter.innerText = Math.ceil(current); requestAnimationFrame(update); }
                    else counter.innerText = target;
                };
                update();
            });
        }
    }, { threshold: 0.5 }).observe(statsContainer);
}

// Service image preview on hover
const serviceItems = document.querySelectorAll('.service-item');
const previewImg = document.getElementById('service-preview-img');
serviceItems.forEach(item => {
    item.addEventListener('mouseenter', function() {
        const newSrc = this.getAttribute('data-image');
        if (previewImg && previewImg.src !== newSrc) {
            previewImg.style.opacity = '0';
            setTimeout(() => { previewImg.src = newSrc; previewImg.style.opacity = '1'; }, 300);
        }
    });
});
</script>
@endpush
