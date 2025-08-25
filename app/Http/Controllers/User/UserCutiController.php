<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\JenisCuti;
use Illuminate\Support\Facades\Auth;

class UserCutiController extends Controller
{
    // Menampilkan daftar pengajuan cuti milik pengguna
    public function index()
    {
        // Ambil semua cuti milik pengguna, urutkan dari terbaru ke terlama
        $cuti = Cuti::with(['jenis_cuti'])
                    ->where('id_pegawai', Auth::id())
                    ->orderBy('created_at', 'desc') // Urutan terbaru dulu
                    ->get();

        // Cek apakah ada pengajuan cuti dengan status 'pending'
        $pendingCuti = $cuti->where('status', 'pending')->first();

        return view('user.cuti.index', compact('cuti', 'pendingCuti'));
    }

    public function create()
    {
        $jenis_cuti = JenisCuti::all();

        // Opsional: Cegah akses ke form jika sudah ada cuti pending
        $pendingCuti = Cuti::where('id_pegawai', Auth::id())
                           ->where('status', 'pending')
                           ->exists();

        if ($pendingCuti) {
            return redirect()
                ->route('user.cuti.index')
                ->with('error', 'Anda sudah memiliki pengajuan cuti yang sedang diproses (Pending). Tidak dapat mengajukan lagi.');
        }

        return view('user.cuti.create', compact('jenis_cuti'));
    }

    public function store(Request $request)
    {
        // Cek lagi di server: pastikan tidak ada cuti pending
        $existingPending = Cuti::where('id_pegawai', Auth::id())
                               ->where('status', 'pending')
                               ->exists();

        if ($existingPending) {
            return redirect()
                ->route('user.cuti.index')
                ->with('error', 'Pengajuan cuti Anda sedang diproses. Tidak dapat mengajukan lebih dari satu cuti secara bersamaan.');
        }

        $validated = $request->validate([
            'id_jenis_cuti' => 'required|exists:jenis_cuti,id_jenis_cuti',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
        ]);

        Cuti::create([
            'id_pegawai' => Auth::id(),
            'id_jenis_cuti' => $validated['id_jenis_cuti'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => $validated['alasan'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.cuti.index')->with('success', 'Pengajuan cuti berhasil diajukan.');
    }
}