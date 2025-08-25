<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMiniadmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna sudah login sebagai mini_admin
        if (!Auth::guard('mini_admin')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai mini_admin.');
        }

        // Cek apakah pengguna memiliki role 'mini_admin'
        if (Auth::guard('mini_admin')->user()->role?->name !== 'mini_admin') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan mini_admin.');
        }

        return $next($request);
    }
}
