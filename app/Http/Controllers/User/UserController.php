<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pegawai; // Import model User
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Departemen;
use App\Models\RfidCard; // Import model RfidCard
use App\Models\Cuti;
use App\Models\Wfh;
use App\Models\Datasen;
use Illuminate\Support\Facades\Hash; // Import class Hash
use Illuminate\Support\Facades\Auth; // Import Auth facade

class UserController extends Controller
{
    public function index(Request $request)
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

        // Ambil data WFH untuk user yang sedang login
        $wfh = Wfh::where('id_pegawai', $user->id_pegawai)->get();

        // Hitung total pengajuan WFH
        $totalWfh = $wfh->count();

        // Hitung pengajuan WFH berdasarkan status
        $wfhPending = $wfh->where('status', 'pending')->count();
        $wfhApproved = $wfh->where('status', 'approved')->count();
        $wfhRejected = $wfh->where('status', 'rejected')->count();

        // Ambil data absen untuk user yang sedang login
        $perPage = $request->get('per_page', 10);
        $userAbsen = Datasen::where('id_pegawai', $user->id_pegawai)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Kirim data ke view
        return view('user.index', compact(
            'totalCuti',
            'cutiPending',
            'cutiApproved',
            'cutiRejected',
            'totalWfh',
            'wfhPending',
            'wfhApproved',
            'wfhRejected',
            'userAbsen',
            'perPage'
        ));
    }
}
