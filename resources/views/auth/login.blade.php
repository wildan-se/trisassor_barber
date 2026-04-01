<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2 class="text-xl font-display font-bold text-white uppercase tracking-wider mb-6 text-center">Login ke Akun Anda</h2>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Email</label>
            <input id="email" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-xs" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password" class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Password</label>
            <input id="password" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:border-barber-gold focus:outline-none focus:ring-1 focus:ring-barber-gold transition-colors" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-zinc-700 bg-zinc-800 text-barber-gold accent-barber-gold focus:ring-barber-gold focus:ring-offset-zinc-900 cursor-pointer" name="remember">
                <span class="ml-2 text-sm text-zinc-400 group-hover:text-zinc-300 transition-colors">Ingat Saya</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 mt-8">
            <button type="submit" class="w-full bg-barber-gold hover:bg-white text-barber-dark font-bold py-3 px-4 rounded-xl text-sm uppercase tracking-wider transition-all">
                Login
            </button>

            <!-- Divider -->
            <div class="relative flex items-center my-2">
                <div class="grow border-t border-zinc-700"></div>
                <span class="shrink-0 mx-4 text-zinc-500 text-xs font-bold tracking-widest uppercase">Atau</span>
                <div class="grow border-t border-zinc-700"></div>
            </div>

            <!-- Google OAuth Button -->
            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all focus:ring-2 focus:ring-zinc-600 focus:outline-none relative group overflow-hidden">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Masuk dengan Google
            </a>
            <div class="flex items-center justify-between mt-2">
                @if (Route::has('password.request'))
                    <a wire:navigate class="text-xs text-zinc-500 hover:text-barber-gold transition-colors" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
                <p class="text-xs text-zinc-500">
                    Belum punya akun? <a wire:navigate class="text-barber-gold font-bold hover:text-white transition-colors" href="{{ route('register') }}">Daftar</a>
                </p>
            </div>
        </div>
    </form>
</x-guest-layout>
