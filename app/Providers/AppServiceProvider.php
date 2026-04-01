<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Validation\Rules\Password::defaults(function () {
            // Berlakukan sandi yang kuat (minimal 8 karakter, harus ada huruf, angka, dan tidak pernah bocor di internet/pwnedpasswords)
            return \Illuminate\Validation\Rules\Password::min(8)
                ->letters()
                ->numbers()
                ->uncompromised();
        });
    }
}
