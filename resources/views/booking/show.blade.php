@extends('layouts.app')
@section('title', 'Detail Booking #' . $booking->booking_code . ' — TRISASSOR')

@section('content')
<div class="min-h-screen bg-barber-dark pt-24 pb-16" x-data="bookingShowPoller()" x-init="startPolling('{{ request()->fullUrl() }}')">
    <div class="max-w-2xl mx-auto px-4 md:px-8" id="booking-detail-content">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center transition-colors duration-500
                @if($booking->status === 'confirmed') bg-green-500/10 text-green-400
                @elseif($booking->status === 'completed') bg-barber-gold/10 text-barber-gold
                @elseif($booking->status === 'cancelled') bg-red-500/10 text-red-400
                @else bg-yellow-500/10 text-yellow-400 @endif">
                @if($booking->status === 'confirmed')
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($booking->status === 'completed')
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                @elseif($booking->status === 'cancelled')
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
            </div>
            <h1 class="font-display text-3xl font-bold text-white uppercase tracking-wide mb-1">Detail Booking</h1>
            <p class="text-barber-gold font-mono font-bold tracking-widest">{{ $booking->booking_code }}</p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-2xl">

            {{-- Status Badge --}}
            <div class="px-6 py-4 border-b border-zinc-800 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-widest text-zinc-500">Status</span>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest
                    @if($booking->status === 'confirmed') bg-green-500/10 text-green-400 border border-green-500/20
                    @elseif($booking->status === 'completed') bg-barber-gold/10 text-barber-gold border border-barber-gold/20
                    @elseif($booking->status === 'cancelled') bg-red-500/10 text-red-400 border border-red-500/20
                    @else bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 @endif">
                    @if($booking->status === 'pending') ⏳ Menunggu Konfirmasi
                    @elseif($booking->status === 'confirmed') ✓ Dikonfirmasi
                    @elseif($booking->status === 'completed') ★ Selesai
                    @else ✗ Dibatalkan @endif
                </span>
            </div>

            {{-- Detail --}}
            <div class="p-6 space-y-4">
                @foreach([
                    ['label' => 'Layanan',  'value' => $booking->service->name],
                    ['label' => 'Barber',   'value' => $booking->barber->name],
                    ['label' => 'Tanggal',  'value' => $booking->booking_date->translatedFormat('l, d F Y')],
                    ['label' => 'Waktu',    'value' => substr($booking->booking_time, 0, 5) . ' – ' . substr($booking->end_time, 0, 5) . ' WIB'],
                    ['label' => 'Total',    'value' => $booking->formatted_price],
                ] as $row)
                <div class="flex justify-between items-center py-3 border-b border-zinc-800 last:border-0">
                    <span class="text-zinc-500 text-sm">{{ $row['label'] }}</span>
                    <span class="text-white font-bold text-sm {{ $row['label'] === 'Total' ? 'text-barber-gold text-lg' : '' }}">{{ $row['value'] }}</span>
                </div>
                @endforeach

                @if($booking->notes)
                <div class="py-3 border-t border-zinc-800">
                    <span class="text-zinc-500 text-sm block mb-1">Catatan Kamu</span>
                    <p class="text-zinc-300 text-sm">{{ $booking->notes }}</p>
                </div>
                @endif

                @if($booking->admin_notes)
                <div class="py-3 border-t border-zinc-800 bg-zinc-800/50 rounded-lg px-3">
                    <span class="text-zinc-500 text-xs block mb-1 uppercase tracking-widest font-bold">Catatan Admin</span>
                    <p class="text-zinc-300 text-sm">{{ $booking->admin_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="px-6 pb-6 flex gap-3 justify-between">
                <a wire:navigate href="{{ route('booking.index') }}"
                   class="py-2.5 px-5 rounded-full border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-all text-xs font-bold uppercase tracking-wider">
                    ← Riwayat
                </a>
                @if($booking->canBeCancelled())
                <button type="button" @click="showCancelConfirm = true"
                        class="py-2.5 px-5 rounded-full bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 transition-all text-xs font-bold uppercase tracking-wider">
                    Batalkan Booking
                </button>
                @endif
            </div>
        </div>

        <!-- Custom Cancel Confirmation Modal -->
        <div x-cloak x-show="showCancelConfirm" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" style="display: none;">
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-sm shadow-2xl p-6 relative animate-in zoom-in-95 duration-200" @click.outside="showCancelConfirm = false">
                <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/20">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-white text-center mb-2 tracking-wide uppercase">Pembatalan</h3>
                <p class="text-zinc-400 text-sm text-center mb-6 leading-relaxed">Apakah kamu yakin ingin membatalkan jadwal cukur ini? Slot waktu akan dikembalikan.</p>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" @click="showCancelConfirm = false" class="flex-1 py-3 px-4 rounded-full border border-zinc-700 text-zinc-300 hover:text-white hover:border-zinc-500 transition-all text-sm font-bold uppercase tracking-wider">
                        Kembali
                    </button>
                    <form method="POST" action="{{ route('booking.cancel', $booking) }}" class="flex-1 m-0">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-400 text-white font-bold py-3 px-4 rounded-full text-sm uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(239,68,68,0.3)] hover:shadow-[0_0_20px_rgba(239,68,68,0.5)]">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingShowPoller', () => ({
            intervalId: null,
            showCancelConfirm: false,
            startPolling(url) {
                this.intervalId = setInterval(() => {
                    // Do not poll if modal is open
                    if (this.showCancelConfirm) return;
                    
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('#booking-detail-content');
                            if (newContent) {
                                document.querySelector('#booking-detail-content').innerHTML = newContent.innerHTML;
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
