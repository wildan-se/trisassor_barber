<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * NoCacheMiddleware
 * 
 * Mencegah browser menyimpan halaman admin di cache.
 * Sehingga setelah admin logout, tombol Back di browser 
 * tidak bisa menampilkan halaman admin lagi dari cache.
 */
class NoCacheMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        return $response->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);
    }
}
