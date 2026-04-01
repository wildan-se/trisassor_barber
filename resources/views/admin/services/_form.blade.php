{{-- Shared form partial for create & edit --}}
@php $s = $service ?? null; @endphp

<div>
    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Nama Layanan *</label>
    <input type="text" name="name" value="{{ old('name', $s?->name) }}" required
           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none">
    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Deskripsi</label>
    <textarea name="description" rows="3"
              class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none resize-none">{{ old('description', $s?->description) }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Harga (Rp) *</label>
        <input type="number" name="price" value="{{ old('price', $s?->price) }}" min="0" required
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none">
        @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Durasi (menit) *</label>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $s?->duration_minutes ?? 60) }}" min="15" required
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none">
    </div>
</div>

<div>
    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">URL Gambar</label>
    <input type="url" name="image" value="{{ old('image', $s?->image) }}" placeholder="https://..."
           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none">
</div>

<div>
    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-500 mb-2">Urutan Tampil</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $s?->sort_order ?? 0) }}" min="0"
           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none">
</div>

<div class="flex flex-col gap-3">
    <div class="flex items-center gap-3">
        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $s?->is_featured) ? 'checked' : '' }}
               class="w-4 h-4 rounded border-zinc-600 bg-zinc-800 text-barber-gold focus:ring-barber-gold">
        <label for="is_featured" class="text-sm text-zinc-300 font-semibold">Tandai sebagai Favorit (⭐)</label>
    </div>
    <div class="flex items-center gap-3">
        <input type="checkbox" name="is_active" id="is_active_svc" value="1" {{ old('is_active', $s?->is_active ?? true) ? 'checked' : '' }}
               class="w-4 h-4 rounded border-zinc-600 bg-zinc-800 text-barber-gold focus:ring-barber-gold">
        <label for="is_active_svc" class="text-sm text-zinc-300 font-semibold">Aktif (tampil di halaman utama)</label>
    </div>
</div>
