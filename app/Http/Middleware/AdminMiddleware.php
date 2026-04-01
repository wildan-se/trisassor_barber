<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! \Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        if (! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}
