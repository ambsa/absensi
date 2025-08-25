<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        // Cek apakah pengguna ada di database
        $user = \App\Models\Pegawai::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors('Pengguna belum terdaftar. Silakan daftar terlebih dahulu.');
        }

        // Tentukan guard berdasarkan role pengguna
        $guard = match ($user->role?->name) {
            'admin' => 'admin',
            'mini_admin' => 'mini_admin', // Tambahkan guard untuk mini_admin
            default => 'web',
        };

        // Coba login dengan credentials menggunakan guard yang sesuai
        if (Auth::guard($guard)->attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember'))) {
            // Redirect berdasarkan role pengguna
            Log::info('Login berhasil dengan guard: ' . $guard);
            Log::info('Role pengguna: ' . Auth::guard($guard)->user()->role?->name);
            
            $redirectRoute = match ($guard) {
                'admin' => 'admin.index',
                'mini_admin' => 'miniadmin.index', // Tambahkan route untuk mini_admin
                'web' => 'user.index',
                default => 'login',
            };

            return redirect()->intended(route($redirectRoute));
        }

        return back()->withErrors('Email atau password salah. Silakan coba lagi.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}