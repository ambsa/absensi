<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna sudah login sebagai admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai admin.');
        }

        // Cek apakah pengguna memiliki role 'admin'
        if (Auth::guard('admin')->user()->role?->name !== 'admin') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        return $next($request);
    }
} 
