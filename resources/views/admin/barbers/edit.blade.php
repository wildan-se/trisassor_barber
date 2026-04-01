@extends('layouts.admin')
@section('title', 'Edit Barber: ' . $barber->name)

@section('content')
<!-- Modal Overlay Background -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" x-data="{ showDeleteConfirm: false }">
    <!-- Edit Modal Container -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl w-full max-w-xl shadow-2xl relative flex flex-col max-h-[95vh] animate-in fade-in zoom-in-95 duration-200" x-show="!showDeleteConfirm">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
            <h2 class="text-white font-display font-bold text-lg uppercase tracking-wider">Edit Barber</h2>
            <a wire:navigate href="{{ route('admin.barbers.index') }}" class="text-zinc-500 hover:text-white transition-colors p-1" title="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Form Body (Scrollable) -->
        <div class="p-6 overflow-y-auto">
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-zinc-800">
                <img src="{{ $barber->photo_url }}" alt="{{ $barber->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-zinc-700">
                <div>
                    <h2 class="text-white font-bold text-lg">{{ $barber->name }}</h2>
                    <p class="text-barber-gold text-sm">{{ $barber->specialty }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.barbers.update', $barber) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Nama *</label>
                    <input type="text" name="name" value="{{ old('name', $barber->name) }}" required
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Spesialisasi</label>
                    <input type="text" name="specialty" value="{{ old('specialty', $barber->specialty) }}"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Bio</label>
                    <textarea name="bio" rows="4"
                              class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors resize-none">{{ old('bio', $barber->bio) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Instagram (tanpa @)</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $barber->instagram) }}"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Ganti Foto (opsional)</label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-zinc-400 text-sm focus:border-barber-gold focus:outline-none file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-barber-gold file:text-barber-dark cursor-pointer transition-colors hover:border-zinc-600">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $barber->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-zinc-600 bg-zinc-800 text-barber-gold focus:ring-barber-gold cursor-pointer accent-barber-gold">
                    <label for="is_active" class="text-sm text-zinc-300 font-semibold cursor-pointer">Aktif (bisa menerima booking)</label>
                </div>

                <!-- Footer / Actions -->
                <div class="flex gap-3 pt-6 border-t border-zinc-800 sticky bottom-0 bg-zinc-900 mt-6">
                    <button type="submit" class="flex-1 bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-4 rounded-xl text-xs uppercase tracking-wider transition-all">
                        Update
                    </button>
                    <button type="button" @click="showDeleteConfirm = true" class="px-6 rounded-xl border border-red-500/30 text-red-500 hover:bg-red-500/10 transition-all text-xs font-bold uppercase tracking-wider">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div x-cloak x-show="showDeleteConfirm" class="bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-sm shadow-2xl p-6 relative animate-in zoom-in-95 duration-200" style="display: none;">
        <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/20">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-display font-bold text-white text-center mb-2 tracking-wide uppercase">Hapus Barber?</h3>
        <p class="text-zinc-400 text-sm text-center mb-6 leading-relaxed">Tindakan ini permanen. Anda yakin ingin menghapus <strong>{{ $barber->name }}</strong> dari sistem?</p>
        
        <div class="flex gap-3">
            <button type="button" @click="showDeleteConfirm = false" class="flex-1 py-3 px-4 rounded-xl border border-zinc-700 text-zinc-300 hover:text-white hover:border-zinc-500 transition-all text-sm font-bold uppercase tracking-wider">
                Batal
            </button>
            <form method="POST" action="{{ route('admin.barbers.destroy', $barber) }}" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full bg-red-500 hover:bg-red-400 text-white font-bold py-3 px-4 rounded-xl text-sm uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(239,68,68,0.3)] hover:shadow-[0_0_20px_rgba(239,68,68,0.5)]">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
