<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->role->name === 'admin') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda tidak memiliki izin admin.');
        }

        return $next($request);
    }
} 
