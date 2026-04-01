@extends('layouts.app')
@section('title', 'Riwayat Booking — TRISASSOR')

@section('content')
<div class="min-h-screen bg-barber-dark pt-24 pb-16" x-data="bookingIndexPoller()" x-init="startPolling('{{ request()->fullUrl() }}')">
    <div class="max-w-4xl mx-auto px-4 md:px-8">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="font-display text-3xl md:text-4xl font-bold text-white uppercase tracking-tight">Booking Saya</h1>
                <p class="text-zinc-500 text-sm mt-1">Riwayat seluruh reservasi kamu di TRISASSOR</p>
            </div>
            <a wire:navigate href="{{ route('booking.create') }}"
               class="inline-flex items-center gap-2 bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-6 rounded-full transition-all text-xs uppercase tracking-widest shadow-lg">
                + Booking Baru
            </a>
        </div>

        <div id="booking-list-container">
            @if($bookings->isEmpty())
                <div class="text-center py-24 bg-zinc-900 border border-zinc-800 rounded-2xl">
                    <div class="w-16 h-16 rounded-full bg-zinc-800 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl text-white uppercase mb-2">Belum Ada Booking</h3>
                    <p class="text-zinc-500 text-sm mb-6">Kamu belum pernah booking. Yuk reservasi sekarang!</p>
                    <a wire:navigate href="{{ route('booking.create') }}" class="bg-barber-gold text-barber-dark font-bold py-3 px-8 rounded-full text-sm uppercase tracking-wider hover:bg-white transition-all">
                        Reservasi Sekarang
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                    <a wire:navigate href="{{ route('booking.show', $booking) }}"
                       class="block bg-zinc-900 border border-zinc-800 rounded-xl p-5 hover:border-zinc-600 transition-all duration-200 group relative overflow-hidden">
                       
                        {{-- Polling Update Flash Animation --}}
                        <div class="absolute inset-0 bg-barber-gold/5 opacity-0 transition-opacity duration-1000 poller-flash" data-id="{{ $booking->id }}" data-status="{{ $booking->status }}"></div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                            <div class="flex items-center gap-4">
                                <img src="{{ $booking->barber->photo_url }}" alt="{{ $booking->barber->name }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-zinc-700 group-hover:border-barber-gold transition-colors flex-shrink-0">
                                <div>
                                    <p class="font-bold text-white text-sm group-hover:text-barber-gold transition-colors">{{ $booking->service->name }}</p>
                                    <p class="text-zinc-500 text-xs">{{ $booking->barber->name }} • {{ $booking->booking_date->translatedFormat('d M Y') }} • {{ substr($booking->booking_time, 0, 5) }} WIB</p>
                                    <p class="text-zinc-600 text-xs font-mono mt-1">{{ $booking->booking_code }} <span class="bg-zinc-800 text-zinc-300 px-2 py-0.5 rounded ml-2 text-[10px] tracking-wide">Antrian: <span class="text-barber-gold font-bold">{{ $booking->formatted_queue_number }}</span></span></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 flex-shrink-0">
                                <span class="text-barber-gold font-bold text-sm">{{ $booking->formatted_price }}</span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest transition-colors duration-500
                                    @if($booking->status === 'confirmed') bg-green-500/10 text-green-400 border border-green-500/20
                                    @elseif($booking->status === 'completed') bg-barber-gold/10 text-barber-gold border border-barber-gold/20
                                    @elseif($booking->status === 'cancelled') bg-red-500/10 text-red-400 border border-red-500/20
                                    @else bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 @endif">
                                    {{ ['pending' => 'Menunggu', 'confirmed' => 'Konfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Batal'][$booking->status] }}
                                </span>
                                <svg class="w-4 h-4 text-zinc-600 group-hover:text-barber-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-8">{{ $bookings->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingIndexPoller', () => ({
            intervalId: null,
            startPolling(url) {
                this.intervalId = setInterval(() => {
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('#booking-list-container');
                            if (newContent) {
                                document.querySelector('#booking-list-container').innerHTML = newContent.innerHTML;
                            }
                        })
                        .catch(err => console.error('Polling failed:', err));
                }, 5000); 
            },
            destroy() {
                if (this.intervalId) clearInterval(this.intervalId);
            }
        }));
    });
</script>
@endpush
