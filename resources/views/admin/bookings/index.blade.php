@extends('layouts.admin')
@section('title', 'Semua Booking')
@section('subtitle', 'Kelola dan pantau semua reservasi')

@section('content')

{{-- Filter Bar --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..."
           class="bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-white text-sm focus:border-barber-gold focus:outline-none w-full sm:w-56">
    <select name="status" class="bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-white text-sm focus:border-barber-gold focus:outline-none">
        <option value="">Semua Status</option>
        @foreach(['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}"
           class="bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-white text-sm focus:border-barber-gold focus:outline-none">
    <button type="submit" class="bg-barber-gold text-barber-dark font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-white transition-all">Filter</button>
    @if(request()->hasAny(['search','status','date']))
        <a wire:navigate href="{{ route('admin.bookings.index') }}" class="text-zinc-400 hover:text-white transition-colors px-3 py-2.5 text-sm font-semibold">Reset</a>
    @endif
</form>

{{-- Table --}}
<div x-data="adminBookingsPoller()" x-init="startPolling('{{ request()->fullUrl() }}')">
    <div id="admin-bookings-table-container" class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-800">
                    @foreach(['Kode', 'Antrian', 'Pelanggan', 'Layanan', 'Barber', 'Tanggal & Waktu', 'Total', 'Status', 'Aksi'] as $h)
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-zinc-500">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @forelse($bookings as $booking)
                <tr class="hover:bg-zinc-800/30 transition-colors">
                    <td class="px-4 py-3 font-mono text-barber-gold text-xs font-bold">{{ $booking->booking_code }}</td>
                    <td class="px-4 py-3 font-bold"><span class="bg-zinc-800 text-zinc-300 px-2 py-1 rounded text-xs">{{ $booking->formatted_queue_number }}</span></td>
                    <td class="px-4 py-3">
                        <p class="text-white font-semibold">{{ $booking->user->name }}</p>
                        <p class="text-zinc-500 text-xs">{{ $booking->user->phone }}</p>
                    </td>
                    <td class="px-4 py-3 text-zinc-300">{{ $booking->service->name }}</td>
                    <td class="px-4 py-3 text-zinc-300">{{ $booking->barber->name }}</td>
                    <td class="px-4 py-3">
                        <p class="text-zinc-200">{{ $booking->booking_date->format('d M Y') }}</p>
                        <p class="text-zinc-500 text-xs">{{ substr($booking->booking_time, 0, 5) }}</p>
                    </td>
                    <td class="px-4 py-3 text-white font-bold">{{ $booking->formatted_price }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            @if($booking->status==='confirmed') bg-green-500/10 text-green-400 border border-green-500/20
                            @elseif($booking->status==='completed') bg-barber-gold/10 text-barber-gold border border-barber-gold/20
                            @elseif($booking->status==='cancelled') bg-red-500/10 text-red-400 border border-red-500/20
                            @else bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 @endif">
                            {{ ['pending'=>'Menunggu','confirmed'=>'Konfirmasi','completed'=>'Selesai','cancelled'=>'Batal'][$booking->status] }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a wire:navigate href="{{ route('admin.bookings.show', $booking) }}" 
                           class="p-2 inline-block bg-barber-gold/10 text-barber-gold hover:bg-barber-gold hover:text-barber-dark rounded-lg transition-all" title="Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-zinc-500">Tidak ada booking ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-4 border-t border-zinc-800">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminBookingsPoller', () => ({
            intervalId: null,
            startPolling(url) {
                this.intervalId = setInterval(() => {
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newTable = doc.querySelector('#admin-bookings-table-container');
                            if (newTable) {
                                document.querySelector('#admin-bookings-table-container').innerHTML = newTable.innerHTML;
                            }
                        })
                        .catch(err => console.error('Bookings polling failed:', err));
                }, 5000); // 5 seconds interval
            },
            destroy() {
                if (this.intervalId) clearInterval(this.intervalId);
            }
        }));
    });
</script>
@endpush
