<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        'nama_pegawai' => 'required|string',
        'password' => 'required|string',
    ]);

    // Cek apakah pengguna ada di database
    $user = \App\Models\Pegawai::where('nama_pegawai', $request->nama_pegawai)->first();

    if (!$user) {
        return back()->withErrors('Pengguna belum terdaftar. Silakan daftar terlebih dahulu.');
    }

    // Coba login dengan credentials
    if (Auth::attempt(['nama_pegawai' => $request->nama_pegawai, 'password' => $request->password], $request->has('remember'))) {
        $pegawai = Auth::user(); // Mengambil user yang sudah login

        // Redirect berdasarkan role pengguna
        $redirectRoute = match ($pegawai->role?->name) {
            'admin' => 'admin.index',
            'user' => 'user.index',
            default => 'login',
        };

        return redirect()->route($redirectRoute);
    }

    return back()->withErrors('Username atau password salah. Silakan coba lagi.');
}

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
