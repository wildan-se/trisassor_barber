@extends('layouts.admin')
@section('title', 'Manajemen Layanan')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-white font-bold text-sm uppercase tracking-wider">{{ $services->total() }} Layanan</h2>
    <a wire:navigate href="{{ route('admin.services.create') }}" class="bg-barber-gold text-barber-dark font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-wider hover:bg-white transition-all">
        + Tambah Layanan
    </a>
</div>

<div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-zinc-800">
                @foreach(['Layanan', 'Harga', 'Durasi', 'Status', 'Aksi'] as $h)
                <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-zinc-500">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-800">
            @forelse($services as $service)
            <tr class="hover:bg-zinc-800/30 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $service->image_url }}" class="w-12 h-12 rounded-lg object-cover">
                        <div>
                            <p class="text-white font-bold">{{ $service->name }}</p>
                            @if($service->is_featured) <span class="text-[10px] text-barber-gold font-bold uppercase">★ Favorit</span> @endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-barber-gold font-bold">{{ $service->formatted_price }}</td>
                <td class="px-5 py-4 text-zinc-400">{{ $service->duration_minutes }} menit</td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ $service->is_active ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-zinc-700 text-zinc-400 border border-zinc-600' }}">
                        {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex gap-3">
                        <a wire:navigate href="{{ route('admin.services.edit', $service) }}" 
                           class="p-2 bg-barber-gold/10 text-barber-gold hover:bg-barber-gold hover:text-barber-dark rounded-lg transition-all" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" x-data>
                            @csrf @method('DELETE')
                            <button type="submit" @click.prevent="if(confirm('Hapus layanan ini?')) $el.closest('form').submit()" title="Hapus"
                                    class="p-2 bg-red-400/10 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-zinc-500">Belum ada layanan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-zinc-800">{{ $services->links() }}</div>
</div>
@endsection
