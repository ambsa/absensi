<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUser
{

    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna sudah login sebagai user
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai user.');
        }

        // Cek apakah pengguna memiliki role 'user'
        if (Auth::guard('web')->user()->role?->name !== 'user') {
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
