<x-guest-layout>
    <h2 class="text-xl font-display font-bold text-white uppercase tracking-wider mb-6 text-center">Reset Password</h2>

    <div class="mb-6 text-sm text-zinc-400 text-center leading-relaxed">
        Lupa password Anda? Tidak masalah. Masukkan email Anda dan kami akan mengirimkan tautan untuk reset password.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Email</label>
            <input id="email" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-xs" />
        </div>

        <div class="flex flex-col gap-4 mt-8">
            <button type="submit" class="w-full bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-4 rounded-xl text-sm uppercase tracking-wider transition-all">
                Kirim Tautan Reset
            </button>
            <div class="flex justify-center mt-2">
                <a wire:navigate class="inline-flex items-center gap-2 text-xs text-zinc-500 hover:text-white transition-colors group p-2" href="{{ route('login') }}">
                    <svg class="w-4 h-4 text-barber-gold transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="font-medium tracking-wide uppercase">Kembali ke Login</span>
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
