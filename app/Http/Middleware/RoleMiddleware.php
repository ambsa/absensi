<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle($request, Closure $next, ...$role)
    {
        if (!Auth::check()) {
            return redirect('/login'); // Redirect ke halaman login jika pengguna belum login
        }

        $pegawai = Auth::user();
        if (!$pegawai->role || !in_array($pegawai->role->name, $role)) {
            abort(403, 'Unauthorized action.'); // Kembalikan error 403 jika role tidak sesuai
        }

        return $next($request); // Lanjutkan request jika role sesuai
    }
}
