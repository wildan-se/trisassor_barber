@extends('layouts.admin')
@section('title', 'Edit Layanan: ' . $service->name)

@section('content')
<div class="max-w-xl">
    <a wire:navigate href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 text-zinc-500 hover:text-barber-gold transition-colors text-sm font-semibold mb-6">← Kembali</a>

    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6">
        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('admin.services._form', ['service' => $service])
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-8 rounded-xl text-sm uppercase tracking-wider transition-all">Update</button>
                <a wire:navigate href="{{ route('admin.services.index') }}" class="py-3 px-6 rounded-xl border border-zinc-700 text-zinc-400 hover:text-white transition-all text-sm font-bold uppercase">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
