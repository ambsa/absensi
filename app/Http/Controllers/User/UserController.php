<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pegawai; // Import model User
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Departemen;
use App\Models\RfidCard; // Import model RfidCard
use App\Models\Cuti;
use Illuminate\Support\Facades\Hash; // Import class Hash
use Illuminate\Support\Facades\Auth; // Import Auth facade

class UserController extends Controller
{
    public function index()
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Gunakan relasi untuk mengambil data cuti
        $cuti = $user->cuti;

        // Hitung total pengajuan cuti
        $totalCuti = $cuti->count();

        // Hitung pengajuan cuti berdasarkan status
        $cutiPending = $cuti->where('status', 'pending')->count();
        $cutiApproved = $cuti->where('status', 'approved')->count();
        $cutiRejected = $cuti->where('status', 'rejected')->count();

        // Kirim data ke view
        return view('user.index', compact(
            'totalCuti',
            'cutiPending',
            'cutiApproved',
            'cutiRejected'
        ));
    }
}
