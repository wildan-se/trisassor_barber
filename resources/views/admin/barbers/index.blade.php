@extends('layouts.admin')
@section('title', 'Manajemen Barber')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-white font-bold text-sm uppercase tracking-wider">{{ $barbers->total() }} Barber Terdaftar</h2>
    <a wire:navigate href="{{ route('admin.barbers.create') }}" class="bg-barber-gold text-barber-dark font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider hover:bg-white transition-all">
        + Tambah Barber
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($barbers as $barber)
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all flex flex-col h-full">
        <div class="relative h-40">
            <img src="{{ $barber->photo_url }}" alt="{{ $barber->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 to-transparent"></div>
            @if(!$barber->is_active)
            <div class="absolute top-2 right-2 bg-red-500/80 text-white text-[10px] font-bold py-1 px-2 rounded">Nonaktif</div>
            @endif
        </div>
        <div class="p-4 flex flex-col flex-1">
            <h3 class="font-bold text-white text-base">{{ $barber->name }}</h3>
            <p class="text-barber-gold text-xs font-semibold">{{ $barber->specialty }}</p>
            <p class="text-zinc-500 text-xs mt-2 line-clamp-2">{{ $barber->bio }}</p>
            <div class="flex items-center justify-between mt-auto pt-4 border-t border-zinc-800">
                <span class="text-zinc-500 text-xs">{{ $barber->bookings_count }} booking</span>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ route('admin.barbers.edit', $barber) }}" 
                       class="p-2 bg-barber-gold/10 text-barber-gold hover:bg-barber-gold hover:text-barber-dark rounded-lg transition-all" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('admin.barbers.destroy', $barber) }}" x-data>
                        @csrf @method('DELETE')
                        <button type="submit" @click.prevent="if(confirm('Hapus {{ $barber->name }}?')) $el.closest('form').submit()" title="Hapus"
                                class="p-2 bg-red-400/10 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center text-zinc-500">Belum ada barber. <a wire:navigate href="{{ route('admin.barbers.create') }}" class="text-barber-gold hover:underline">Tambah sekarang</a></div>
    @endforelse
</div>
<div class="mt-6">{{ $barbers->links() }}</div>
@endsection
