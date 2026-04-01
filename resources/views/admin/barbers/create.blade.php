@extends('layouts.admin')
@section('title', 'Tambah Barber')

@section('content')
<!-- Modal Overlay Background -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <!-- Modal Container -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl w-full max-w-xl shadow-2xl relative flex flex-col max-h-[95vh] animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
            <h2 class="text-white font-display font-bold text-lg uppercase tracking-wider">Tambah Barber</h2>
            <a wire:navigate href="{{ route('admin.barbers.index') }}" class="text-zinc-500 hover:text-white transition-colors p-1" title="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Form Body (Scrollable) -->
        <div class="p-6 overflow-y-auto">
            <form method="POST" action="{{ route('admin.barbers.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Nama *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Spesialisasi</label>
                    <input type="text" name="specialty" value="{{ old('specialty') }}" placeholder="Mis: Classic & Pompadour"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Bio</label>
                    <textarea name="bio" rows="4" placeholder="Deskripsi singkat tentang barber..."
                              class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors resize-none">{{ old('bio') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Instagram (tanpa @)</label>
                    <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="username_instagram"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Foto</label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-zinc-400 text-sm focus:border-barber-gold focus:outline-none file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-barber-gold file:text-barber-dark cursor-pointer transition-colors hover:border-zinc-600">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-zinc-600 bg-zinc-800 text-barber-gold focus:ring-barber-gold cursor-pointer accent-barber-gold">
                    <label for="is_active" class="text-sm text-zinc-300 font-semibold cursor-pointer">Aktif (bisa menerima booking)</label>
                </div>

                <!-- Footer / Actions -->
                <div class="flex gap-3 pt-6 border-t border-zinc-800 sticky bottom-0 bg-zinc-900">
                    <button type="submit" class="flex-1 bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-8 rounded-xl text-sm uppercase tracking-wider transition-all">
                        Simpan Barber
                    </button>
                    <a wire:navigate href="{{ route('admin.barbers.index') }}" class="flex-1 text-center py-3 px-6 rounded-xl border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-all text-sm font-bold uppercase tracking-wider">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
