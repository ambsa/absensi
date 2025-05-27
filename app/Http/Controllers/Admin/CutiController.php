<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\JenisCuti;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    /**
     * Menampilkan halaman daftar cuti.
     */
     // Menampilkan daftar semua pengajuan cuti
     public function index()
    {
        $cuti = Cuti::with(['pegawai', 'jenis_cuti'])
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Mengembalikan Paginator

        return view('admin.cuti.index', compact('cuti'));
    }

    /**
     * Menampilkan form pengajuan cuti.
     */
    public function create()
    {
        $jenis_cuti = JenisCuti::all();

        return view('admin.cuti.create', compact('jenis_cuti'));
    }

    /**
     * Menyimpan data cuti ke database.
     */
    public function store(Request $request)
    {
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

        return redirect()->route('admin.cuti.index')->with('success', 'Pengajuan cuti berhasil diajukan.');
    }

    /**
     * Mengubah status pengajuan cuti.
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Cari data cuti berdasarkan ID
        $cuti = Cuti::findOrFail($id);

        // Pengecekan waktu
        if ($cuti->updated_at_status && now()->diffInHours($cuti->updated_at_status) >= 1) {
            return redirect()->back()->with('error', 'Waktu edit sudah lewat batas 1 jam.');
        }

        // Update status
        $cuti->update([
            'status' => $request->status,
            'updated_at_status' => now(), // Perbarui waktu terakhir update status
        ]);

        return redirect()->route('admin.cuti.index')->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Menghapus data cuti.
     */
    public function destroy($id)
    {
        $cuti = Cuti::findOrFail($id);
        $cuti->delete();

        return redirect()->route('admin.cuti.index')->with('success', 'Data cuti berhasil dihapus.');
    }
}
