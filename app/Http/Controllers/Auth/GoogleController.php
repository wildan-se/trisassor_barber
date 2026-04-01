<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Bypass SSL verification for local development (cURL error 60 fix)
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');
            
            // Bypass SSL verification ONLY on local machine (fixes Windows cURL 60 SSL error)
            if (app()->environment('local')) {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }

            $googleUser = $driver->stateless()->user();
            
            // Check if a user with this google ID already exists
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                Auth::login($user);
                return redirect()->intended(route('dashboard', absolute: false));
            }

            // Alternatively check if email has been used for manual registration
            $existingUser = User::where('email', $googleUser->email)->first();
            if ($existingUser) {
                // Link the Google account to the existing account
                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? $existingUser->avatar,
                ]);
                Auth::login($existingUser);
                return redirect()->intended(route('dashboard', absolute: false));
            }

            // If user doesn't exist, create a new one
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'role' => 'customer',
                'password' => null, // Password is null for OAuth users
                // phone is nullable, so we leave it null for now
            ]);

            Auth::login($newUser);
            return redirect()->intended(route('dashboard', absolute: false));

        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google karena kendala server (SSL). Silakan hubungi admin.');
        }
    }
}
