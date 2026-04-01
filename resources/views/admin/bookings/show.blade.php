@extends('layouts.admin')
@section('title', 'Booking: ' . $booking->booking_code)

@section('content')

<!-- Modal Overlay Background -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" x-data="{ showCancelConfirm: false }">
    
    <!-- Modal Container -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl w-full max-w-2xl shadow-2xl relative flex flex-col max-h-[95vh] animate-in fade-in zoom-in-95 duration-200" x-show="!showCancelConfirm">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-zinc-800 flex items-center justify-between">
            <div>
                <p class="font-mono text-barber-gold font-bold">{{ $booking->booking_code }}</p>
                <p class="text-zinc-500 text-xs mt-1">Dibuat: {{ $booking->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest
                    @if($booking->status==='confirmed') bg-green-500/10 text-green-400 border border-green-500/20
                    @elseif($booking->status==='completed') bg-barber-gold/10 text-barber-gold border border-barber-gold/20
                    @elseif($booking->status==='cancelled') bg-red-500/10 text-red-400 border border-red-500/20
                    @else bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 @endif">
                    {{ ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','completed'=>'Selesai','cancelled'=>'Dibatalkan'][$booking->status] }}
                </span>
                <a wire:navigate href="{{ route('admin.bookings.index') }}" class="text-zinc-500 hover:text-white transition-colors p-1" title="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        </div>

        {{-- Detail --}}
        <div class="p-6 space-y-3 overflow-y-auto">
            @php
                $rows = [
                    ['Pelanggan', $booking->user->name . ' (' . $booking->user->email . ')'],
                    ['Telepon', $booking->user->phone ?? '-'],
                    ['No. Antrian', $booking->formatted_queue_number],
                    ['Layanan', $booking->service->name],
                    ['Barber', $booking->barber->name],
                    ['Tanggal', $booking->booking_date->translatedFormat('l, d F Y')],
                    ['Waktu', substr($booking->booking_time,0,5) . ' – ' . substr($booking->end_time,0,5) . ' WIB'],
                    ['Total', $booking->formatted_price],
                ];
            @endphp
            @foreach($rows as [$label, $value])
            <div class="flex justify-between py-2.5 border-b border-zinc-800 last:border-0">
                <span class="text-zinc-500 text-sm">{{ $label }}</span>
                <span class="text-white font-semibold text-sm text-right max-w-xs">{{ $value }}</span>
            </div>
            @endforeach

            @if($booking->notes)
            <div class="py-3 border-t border-zinc-800 mt-2">
                <p class="text-zinc-500 text-xs mb-1 uppercase tracking-widest font-bold">Catatan Pelanggan</p>
                <p class="text-zinc-300 text-sm bg-zinc-800/50 p-3 rounded-lg">{{ $booking->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Admin Actions --}}
        <div class="px-6 py-5 border-t border-zinc-800 bg-zinc-900/50 sticky bottom-0 rounded-b-2xl">
            @php
                $isLate = false;
                if (in_array($booking->status, ['pending', 'confirmed'])) {
                    $bookingDateTime = \Carbon\Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
                    if (now()->greaterThan($bookingDateTime->addMinutes(15))) {
                        $isLate = true;
                    }
                }
            @endphp

            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold uppercase tracking-widest text-zinc-500">Aksi Admin</p>
                @if($isLate)
                    <span class="px-2 py-1 bg-red-500/20 text-red-400 border border-red-500/50 rounded text-[10px] font-bold uppercase tracking-widest animate-pulse">
                        ⚠️ Terlambat > 15 Menit
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap gap-3">
                @if($booking->status === 'pending')
                <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}" class="flex-1">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-green-500/10 border border-green-500/30 text-green-400 hover:bg-green-500/20 font-bold py-3 px-5 rounded-xl text-xs uppercase tracking-wider transition-all">
                        ✓ Konfirmasi
                    </button>
                </form>
                @endif

                @if($booking->status === 'confirmed')
                <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}" class="flex-1">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-barber-gold/10 border border-barber-gold/30 text-barber-gold hover:bg-barber-gold/20 font-bold py-3 px-5 rounded-xl text-xs uppercase tracking-wider transition-all">
                        ★ Tandai Selesai
                    </button>
                </form>
                @endif

                @if(!in_array($booking->status, ['completed', 'cancelled']))
                <button type="button" @click="showCancelConfirm = true" 
                        class="flex-1 border font-bold py-3 px-5 rounded-xl text-xs uppercase tracking-wider transition-all {{ $isLate ? 'bg-red-500 border-red-400 text-white hover:bg-red-600 shadow-[0_0_15px_rgba(239,68,68,0.5)]' : 'bg-red-500/10 border-red-500/30 text-red-400 hover:bg-red-500/20' }}">
                    {{ $isLate ? '✕ Batalkan (No-Show)' : '✕ Batalkan' }}
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom Cancel/Delete Confirmation Modal specifically for Bookings -->
    <div x-cloak x-show="showCancelConfirm" class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-sm shadow-2xl p-6 relative animate-in zoom-in-95 duration-200" style="display: none;">
        <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/20">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <h3 class="text-xl font-display font-bold text-white text-center mb-2 tracking-wide uppercase">Batalkan Booking?</h3>
        <p class="text-zinc-400 text-sm text-center mb-6 leading-relaxed">Keputusan ini tidak dapat dibatalkan. Booking <strong>{{ $booking->booking_code }}</strong> akan ditandai sebagai Batal.</p>
        
        <div class="flex gap-3">
            <button type="button" @click="showCancelConfirm = false" class="flex-1 py-3 px-4 rounded-xl border border-zinc-700 text-zinc-300 hover:text-white hover:border-zinc-500 transition-all text-sm font-bold uppercase tracking-wider">
                Kembali
            </button>
            <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" class="flex-1">
                @csrf @method('PATCH')
                <button type="submit" class="w-full bg-red-500 hover:bg-red-400 text-white font-bold py-3 px-4 rounded-xl text-sm uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(239,68,68,0.3)] hover:shadow-[0_0_20px_rgba(239,68,68,0.5)]">
                    Ya, Batalkan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
