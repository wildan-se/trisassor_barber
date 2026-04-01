@extends('layouts.admin')
@section('title', 'Jadwal Barber')
@section('subtitle', 'Atur jadwal operasional tiap barber')

@section('content')

<div class="space-y-6">
    @foreach($barbers as $barber)
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-800 flex items-center gap-4">
            <img src="{{ $barber->photo_url }}" class="w-10 h-10 rounded-full object-cover border border-zinc-700">
            <div>
                <h2 class="text-white font-bold">{{ $barber->name }}</h2>
                <p class="text-zinc-500 text-xs">{{ $barber->specialty }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.schedules.update', $barber) }}" class="p-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @php
                    $days = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
                @endphp
                @foreach($days as $dayNum => $dayName)
                    @php
                        $schedule = $barber->schedules->where('day_of_week', $dayNum)->first();
                    @endphp
                    <div class="bg-zinc-800/50 border border-zinc-700 rounded-xl p-4" x-data="{ available: {{ $schedule?->is_available ? 'true' : 'false' }} }">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-white font-bold text-sm">{{ $dayName }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="schedules[{{ $dayNum }}][is_available]" value="1"
                                       x-model="available"
                                       {{ $schedule?->is_available ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-9 h-5 bg-zinc-600 rounded-full peer peer-checked:bg-barber-gold peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:w-4 after:h-4 after:rounded-full after:transition-all"></div>
                            </label>
                        </div>
                        <input type="hidden" name="schedules[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">
                        <div x-show="available" class="space-y-2">
                            <div>
                                <label class="text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Mulai</label>
                                <input type="time" name="schedules[{{ $dayNum }}][start_time]"
                                       value="{{ $schedule ? substr($schedule->start_time, 0, 5) : '10:00' }}"
                                       class="w-full bg-zinc-700 border border-zinc-600 rounded-lg px-3 py-1.5 text-white text-sm focus:border-barber-gold focus:outline-none mt-1">
                            </div>
                            <div>
                                <label class="text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Selesai</label>
                                <input type="time" name="schedules[{{ $dayNum }}][end_time]"
                                       value="{{ $schedule ? substr($schedule->end_time, 0, 5) : '21:00' }}"
                                       class="w-full bg-zinc-700 border border-zinc-600 rounded-lg px-3 py-1.5 text-white text-sm focus:border-barber-gold focus:outline-none mt-1">
                            </div>
                        </div>
                        <p x-show="!available" class="text-zinc-600 text-xs text-center py-2">Libur</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-barber-gold hover:bg-white text-barber-dark font-bold py-2.5 px-6 rounded-xl text-xs uppercase tracking-wider transition-all">
                    Simpan Jadwal {{ $barber->name }}
                </button>
            </div>
        </form>
    </div>
    @endforeach
</div>
@endsection
