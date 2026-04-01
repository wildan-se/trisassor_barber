@extends('layouts.app')

@section('title', 'Reservasi Online — TRISASSOR')

@section('content')
<div class="min-h-screen bg-barber-dark pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 md:px-8">

        {{-- Header --}}
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-10 h-px bg-barber-gold"></div>
                <span class="text-barber-gold uppercase tracking-widest font-bold text-xs">Online Booking</span>
                <div class="w-10 h-px bg-barber-gold"></div>
            </div>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-white uppercase tracking-tighter">
                Buat <span class="text-barber-gold">Reservasi</span>
            </h1>
            <p class="text-zinc-500 text-sm mt-3">Pilih layanan, barber, dan waktu yang pas untukmu.</p>
        </div>

        {{-- Booking Form (Multi-step Alpine.js) --}}
        <div x-data="bookingForm()" class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-2xl">

            {{-- Step Indicator --}}
            <div class="flex border-b border-zinc-800">
                @foreach([1 => 'Layanan', 2 => 'Barber & Waktu', 3 => 'Konfirmasi'] as $num => $label)
                <div class="flex-1 py-4 text-center text-xs font-bold uppercase tracking-wider transition-all"
                     :class="step >= {{ $num }} ? 'text-barber-gold border-b-2 border-barber-gold' : 'text-zinc-600'">
                    <span class="hidden sm:inline">{{ $num }}. </span>{{ $label }}
                </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('booking.store') }}" id="booking-form">
                @csrf

                {{-- Step 1: Pilih Layanan --}}
                <div x-show="step === 1" class="p-6 md:p-8">
                    <h2 class="font-display text-2xl text-white uppercase tracking-wide mb-6">Pilih Layanan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($services as $service)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="service_id" value="{{ $service->id }}"
                                   x-model="serviceId"
                                   @change="serviceName = '{{ addslashes($service->name) }}'; serviceDuration = {{ $service->duration_minutes }}; servicePrice = '{{ $service->formatted_price }}'"
                                   class="sr-only">
                            <div class="p-5 border rounded-xl transition-all duration-200"
                                 :class="serviceId == '{{ $service->id }}' ? 'border-barber-gold bg-barber-gold/5' : 'border-zinc-800 hover:border-zinc-600'">
                                @if($service->is_featured)
                                    <span class="inline-block bg-barber-gold text-barber-dark text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded mb-2">★ Favorit</span>
                                @endif
                                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-32 object-cover rounded-lg mb-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-white text-sm">{{ $service->name }}</h3>
                                        <p class="text-zinc-500 text-xs mt-1 leading-relaxed">{{ Str::limit($service->description, 60) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-zinc-800">
                                    <span class="text-barber-gold font-bold text-sm">{{ $service->formatted_price }}</span>
                                    <span class="text-zinc-500 text-xs">{{ $service->duration_minutes }} menit</span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('service_id') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror

                    <div class="mt-8 flex justify-end">
                        <button type="button" @click="nextStep()" :disabled="!serviceId"
                                class="bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-8 rounded-full transition-all uppercase tracking-wider text-sm disabled:opacity-40 disabled:cursor-not-allowed">
                            Lanjutkan →
                        </button>
                    </div>
                </div>

                {{-- Step 2: Pilih Barber & Waktu --}}
                <div x-show="step === 2" class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <button type="button" @click="step = 1" class="text-zinc-500 hover:text-barber-gold transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <h2 class="font-display text-2xl text-white uppercase tracking-wide">Pilih Barber &amp; Waktu</h2>
                    </div>

                    {{-- Pilih Barber --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-3">Pilih Barber</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($barbers as $barber)
                            <label class="cursor-pointer">
                                <input type="radio" name="barber_id" value="{{ $barber->id }}"
                                       x-model="barberId"
                                       @change="barberName = '{{ addslashes($barber->name) }}'; slots = []; selectedSlot = ''; fetchSlots()"
                                       class="sr-only">
                                <div class="p-4 border rounded-xl text-center transition-all"
                                     :class="barberId == '{{ $barber->id }}' ? 'border-barber-gold bg-barber-gold/5' : 'border-zinc-800 hover:border-zinc-700'">
                                    <img src="{{ $barber->photo_url }}" alt="{{ $barber->name }}"
                                         class="w-16 h-16 rounded-full mx-auto mb-2 object-cover border-2"
                                         :class="barberId == '{{ $barber->id }}' ? 'border-barber-gold' : 'border-zinc-700'">
                                    <p class="font-bold text-white text-sm">{{ $barber->name }}</p>
                                    <p class="text-zinc-500 text-xs">{{ $barber->specialty }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('barber_id') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pilih Tanggal --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-3">Pilih Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" name="booking_date" x-model="bookingDate"
                                   @change="slots = []; selectedSlot = ''; if(barberId) fetchSlots()"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full bg-zinc-800 border border-zinc-700 rounded-xl pl-12 pr-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors cursor-pointer scheme-dark">
                        </div>
                        @error('booking_date') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pilih Slot --}}
                    <div class="mb-6" x-show="barberId && bookingDate">
                        <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-3">
                            Pilih Waktu
                            <span x-show="loadingSlots" class="text-barber-gold ml-2">Loading...</span>
                        </label>
                        <div x-show="!loadingSlots && slots.length === 0 && barberId && bookingDate" class="text-zinc-500 text-sm py-3">
                            <span x-text="slotsMessage || 'Tidak ada slot tersedia untuk tanggal ini.'"></span>
                        </div>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                            <template x-for="slot in slots" :key="slot">
                                <label class="cursor-pointer">
                                    <input type="radio" name="booking_time" :value="slot" x-model="selectedSlot" class="sr-only">
                                    <div class="py-2 px-1 rounded-lg text-center text-xs font-bold transition-all"
                                         :class="selectedSlot === slot ? 'bg-barber-gold text-barber-dark' : 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700'">
                                        <span x-text="slot"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                        @error('booking_time') <p x-show="serverError" class="text-red-400 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-3">Catatan (Opsional)</label>
                        <textarea name="notes" x-model="notes" rows="3" placeholder="Mis: ingin gaya tertentu, alergi produk, dll."
                                  class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none transition-colors resize-none placeholder-zinc-600"></textarea>
                    </div>

                    <div class="flex gap-4 justify-between">
                        <button type="button" @click="step = 1" class="py-3 px-6 rounded-full border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-all text-sm font-bold uppercase tracking-wider">
                            ← Kembali
                        </button>
                        <button type="button" @click="nextStep()" :disabled="!barberId || !bookingDate || !selectedSlot"
                                class="bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-8 rounded-full transition-all uppercase tracking-wider text-sm disabled:opacity-40 disabled:cursor-not-allowed">
                            Lanjutkan →
                        </button>
                    </div>
                </div>

                {{-- Step 3: Konfirmasi --}}
                <div x-show="step === 3" class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <button type="button" @click="step = 2" class="text-zinc-500 hover:text-barber-gold transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <h2 class="font-display text-2xl text-white uppercase tracking-wide">Konfirmasi Booking</h2>
                    </div>

                    <div class="bg-zinc-800/50 border border-zinc-700 rounded-xl p-6 mb-6 space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-zinc-700">
                            <span class="text-zinc-400 text-sm">Layanan</span>
                            <span class="text-white font-bold text-sm" x-text="serviceName"></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-zinc-700">
                            <span class="text-zinc-400 text-sm">Barber</span>
                            <span class="text-white font-bold text-sm" x-text="barberName"></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-zinc-700">
                            <span class="text-zinc-400 text-sm">Tanggal</span>
                            <span class="text-white font-bold text-sm" x-text="bookingDate"></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-zinc-700">
                            <span class="text-zinc-400 text-sm">Waktu</span>
                            <span class="text-white font-bold text-sm" x-text="selectedSlot"></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-zinc-700">
                            <span class="text-zinc-400 text-sm">Durasi</span>
                            <span class="text-white font-bold text-sm" x-text="serviceDuration + ' menit'"></span>
                        </div>
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-zinc-300 font-bold uppercase tracking-widest text-sm">Total</span>
                            <span class="text-barber-gold font-bold text-xl" x-text="servicePrice"></span>
                        </div>
                    </div>

                    @if(auth()->check() && empty(auth()->user()->phone))
                    <div class="mb-6 bg-red-500/10 border border-red-500/20 p-4 rounded-xl">
                        <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">WhatsApp / No. HP<span class="text-red-500 ml-1">*</span></label>
                        <p class="text-xs text-zinc-400 mb-3 leading-relaxed">Anda masuk via Google. Kami memerlukan No. HP Anda untuk konfirmasi reservasi & info antrean.</p>
                        <input type="text" name="phone" x-model="phoneInput" placeholder="Misal: 081234567890" 
                               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors placeholder-zinc-600">
                        @error('phone') <p class="text-red-400 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <p class="text-zinc-500 text-xs mb-6 text-center">
                        Booking akan berstatus <strong class="text-zinc-300">Menunggu</strong> hingga dikonfirmasi oleh admin.
                    </p>

                    <div class="flex gap-4 justify-between">
                        <button type="button" @click="step = 2"
                                class="py-3 px-6 rounded-full border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-all text-sm font-bold uppercase tracking-wider">
                            ← Kembali
                        </button>
                        <button type="submit" 
                                :disabled="requirePhone && !phoneInput"
                                class="bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-8 rounded-full transition-all uppercase tracking-wider text-sm shadow-xl disabled:opacity-40 disabled:cursor-not-allowed">
                            ✓ Konfirmasi Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bookingForm() {
    const config = {!! json_encode([
        'step' => $errors->has('booking_time') || $errors->has('barber_id') || $errors->has('booking_date') ? 2 : 1,
        'serviceId' => old('service_id'),
        'barberId' => old('barber_id'),
        'bookingDate' => old('booking_date'),
        'selectedSlot' => old('booking_time'),
        'notes' => old('notes'),
        'phoneInput' => old('phone', ''),
        'requirePhone' => auth()->check() && empty(auth()->user()->phone),
        'serverError' => $errors->has('booking_time')
    ]) !!};

    return {
        step: config.step,
        serviceId: config.serviceId,
        serviceName: '',
        servicePrice: '',
        serviceDuration: 0,
        barberId: config.barberId,
        barberName: '',
        bookingDate: config.bookingDate,
        selectedSlot: config.selectedSlot,
        notes: config.notes,
        phoneInput: config.phoneInput,
        requirePhone: config.requirePhone,
        slots: [],
        loadingSlots: false,
        slotsMessage: '',
        serverError: config.serverError,

        init() {
            // Restore proper data names if service exists
            if (this.serviceId) {
                let sInput = document.querySelector('input[name="service_id"][value="'+this.serviceId+'"]');
                if(sInput) sInput.dispatchEvent(new Event('change'));
            }
            if (this.barberId) {
                let bInput = document.querySelector('input[name="barber_id"][value="'+this.barberId+'"]');
                if(bInput) bInput.dispatchEvent(new Event('change'));
            }
            
            // If we have old data, fetch slots immediately
            if (this.barberId && this.bookingDate && this.serviceId) {
                this.fetchSlots(false);
            }

            // Polling (Live Refresh) setiap 5 detik agar jika customer 1 batal, 
            // slot otomatis muncul lagi di layar customer 2 tanpa harus refresh manual
            setInterval(() => {
                if (this.step === 2 && this.barberId && this.bookingDate && this.serviceId) {
                    this.fetchSlots(true);
                }
            }, 5000);
        },

        nextStep() {
            if (this.step === 1 && !this.serviceId) return;
            if (this.step === 2 && (!this.barberId || !this.bookingDate || !this.selectedSlot)) return;
            this.serverError = false; // Hide error when moving forward
            this.step++;
        },

        async fetchSlots(isSilentPoll = false) {
            if (!this.barberId || !this.bookingDate || !this.serviceId) return;
            
            if (!isSilentPoll) this.loadingSlots = true;
            
            try {
                const res = await fetch(`/booking/slots?barber_id=${this.barberId}&service_id=${this.serviceId}&date=${this.bookingDate}`, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                
                // Cek apakah slot yang sedang error tadi tiba-tiba sudah tersedia lagi?
                if (isSilentPoll && this.serverError && data.slots && data.slots.includes(this.selectedSlot)) {
                    this.serverError = false; // Hilangkan pesan error merah karena slot kini kosong
                }

                this.slots = data.slots || [];
                this.slotsMessage = data.message;
            } catch(e) {
                if (!isSilentPoll) this.slotsMessage = 'Gagal memuat slot. Coba lagi.';
            }
            if (!isSilentPoll) this.loadingSlots = false;
        }
    }
}
</script>
@endpush
