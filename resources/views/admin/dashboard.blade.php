@extends('layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Overview operasional barbershop hari ini')

@section('content')

<div x-data="adminDashboardPoller()" x-init="startPolling('{{ request()->fullUrl() }}')">
    <div id="admin-dashboard-content">
        {{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $statCards = [
            ['label' => 'Total Booking', 'value' => number_format($stats['total_bookings']), 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/10 border-blue-500/20'],
            ['label' => 'Menunggu',      'value' => number_format($stats['pending_bookings']), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-yellow-400', 'bg' => 'bg-yellow-500/10 border-yellow-500/20'],
            ['label' => 'Hari Ini',      'value' => number_format($stats['today_bookings']), 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707', 'color' => 'text-barber-gold', 'bg' => 'bg-barber-gold/10 border-barber-gold/20'],
            ['label' => 'Pendapatan Bulan Ini', 'value' => 'Rp ' . number_format($stats['this_month_revenue'], 0, ',', '.'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-green-400', 'bg' => 'bg-green-500/10 border-green-500/20'],
        ];
    @endphp

    @foreach($statCards as $card)
    <div class="bg-zinc-900 border rounded-xl p-5 {{ $card['bg'] }}">
        <div class="flex items-start justify-between mb-3">
            <p class="text-zinc-400 text-xs uppercase tracking-widest font-bold">{{ $card['label'] }}</p>
            <div class="{{ $card['color'] }} opacity-70">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
        </div>
        <p class="font-display text-2xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Booking Hari Ini --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-800 flex items-center justify-between">
            <h2 class="font-bold text-white text-sm uppercase tracking-wider">Jadwal Hari Ini</h2>
            <span class="text-xs text-barber-gold font-bold">{{ $todayBookings->count() }} booking</span>
        </div>
        @if($todayBookings->isEmpty())
            <div class="p-8 text-center text-zinc-600 text-sm">Tidak ada booking hari ini.</div>
        @else
            <div class="divide-y divide-zinc-800">
                @foreach($todayBookings as $b)
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-3">
                        <span class="text-barber-gold font-mono font-bold text-sm w-12">{{ substr($b->booking_time, 0, 5) }}</span>
                        <div>
                            <p class="text-white text-sm font-semibold">{{ $b->user->name }}</p>
                            <p class="text-zinc-500 text-xs">{{ $b->service->name }} • {{ $b->barber->name }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full
                        {{ $b->status === 'confirmed' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' }}">
                        {{ $b->status === 'confirmed' ? 'Konfirmasi' : 'Menunggu' }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Bookings --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-800 flex items-center justify-between">
            <h2 class="font-bold text-white text-sm uppercase tracking-wider">Booking Terbaru</h2>
            <a wire:navigate href="{{ route('admin.bookings.index') }}" class="text-xs text-barber-gold hover:text-white transition-colors font-bold">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-zinc-800">
            @foreach($recentBookings as $b)
            <a wire:navigate href="{{ route('admin.bookings.show', $b) }}" class="flex items-center gap-3 px-6 py-3 hover:bg-zinc-800/50 transition-colors">
                <img src="{{ $b->barber->photo_url }}" class="w-9 h-9 rounded-full object-cover border border-zinc-700">
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ $b->user->name }}</p>
                    <p class="text-zinc-500 text-xs">{{ $b->booking_code }} • {{ $b->booking_date->format('d M') }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-barber-gold text-sm font-bold">{{ $b->formatted_price }}</p>
                    <span class="text-[10px] font-bold uppercase tracking-widest
                        {{ ['pending' => 'text-yellow-400', 'confirmed' => 'text-green-400', 'completed' => 'text-barber-gold', 'cancelled' => 'text-red-400'][$b->status] }}">
                        {{ ['pending' => 'Menunggu', 'confirmed' => 'Konfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Batal'][$b->status] }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminDashboardPoller', () => ({
            intervalId: null,
            startPolling(url) {
                this.intervalId = setInterval(() => {
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('#admin-dashboard-content');
                            if (newContent) {
                                document.querySelector('#admin-dashboard-content').innerHTML = newContent.innerHTML;
                            }
                        })
                        .catch(err => console.error('Dashboard polling failed:', err));
                }, 5000); // 5 seconds interval
            },
            destroy() {
                if (this.intervalId) clearInterval(this.intervalId);
            }
        }));
    });
</script>
@endpush

@endsection
