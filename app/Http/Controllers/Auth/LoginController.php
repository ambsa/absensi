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
            'name' => 'required|string',
            'password' => 'required',
        ]);

        // Coba login dengan credentials
    if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
        $user = Auth::user();

        // Arahkan berdasarkan role pengguna
        if ($user->role && $user->role->name === 'admin') {
            return redirect()->route('admin.index'); // Arahkan ke dashboard admin
        } elseif ($user->role && $user->role->name === 'user') {
            return redirect()->route('user.index'); // Arahkan ke dashboard user
        } elseif ($user->role && $user->role->name === 'tap_rfid') {
            return redirect()->route('tap_rfid.index'); // Arahkan pengguna dengan role 'tap_rfid' ke halaman scan RFID
        }
    }

        // Jika login gagal
        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}

