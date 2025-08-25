<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\JenisCuti;
use App\Models\Pegawai;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    /**
     * Menampilkan halaman daftar cuti.
     */
    // Menampilkan daftar semua pengajuan cuti
    public function index(Request $request)
{
    // nilai 1 halamannya
    $perPage = $request->input('per_page', 10);

    // buat nyesuain nampilin datanya
    $allowedPerPageValues = [10, 25, 100, 500];
    if (!in_array($perPage, $allowedPerPageValues)) {
        $perPage = 10; // Reset ke default jika input tidak valid
    }

    // Ambil data cuti dengan relasi pegawai dan jenis_cuti
    $cuti = Cuti::with(['pegawai', 'jenis_cuti'])
        ->orderBy('created_at', 'desc')
        ->paginate($perPage); // Paginate berdasarkan nilai per_page

    return view('miniadmin.cuti.index', compact('cuti', 'perPage'));
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
        // Validasi input
        $validated = $request->validate([
            'id_jenis_cuti' => 'required|exists:jenis_cuti,id_jenis_cuti',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
        ]);

        // Simpan data cuti ke database
        $cuti = Cuti::create([
            'id_pegawai' => Auth::id(),
            'id_jenis_cuti' => $validated['id_jenis_cuti'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => $validated['alasan'],
            'status' => 'pending',
        ]);

        // Cari admin dan kirim notifikasi Firebase
        $admin = Pegawai::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->first();

        if ($admin && $admin->device_token) {
            $firebaseService = new FirebaseNotificationService();
            $firebaseService->sendNotification(
                $admin->device_token,
                'Pengajuan Cuti Baru',
                "Ada pengajuan cuti baru dari {$cuti->pegawai->nama_pegawai}"
            );
        }

        // Redirect dengan pesan sukses
        return redirect()->route('admin.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil diajukan.')
            ->with('alertType', 'success'); // Tambahkan alertType untuk SweetAlert
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

        // Kirim notifikasi ke pegawai (user)
        $pegawai = $cuti->pegawai;
        if ($pegawai && $pegawai->device_token) {
            $firebaseService = new FirebaseNotificationService();
            $title = $request->status === 'approved' ? 'Pengajuan Cuti Disetujui' : 'Pengajuan Cuti Ditolak';
            $body = $request->status === 'approved'
                ? "Pengajuan cuti Anda telah disetujui oleh admin."
                : "Pengajuan cuti Anda telah ditolak oleh admin.";

            $firebaseService->sendNotification(
                $pegawai->device_token,
                $title,
                $body
            );
        }

        return redirect()->route('admin.cuti.index')->with('success', 'Status pengajuan cuti berhasil diperbarui.')->with('alertType', 'success');
    }

    /**
     * Menghapus data cuti.
     */
    public function destroy($id)
    {
        $cuti = Cuti::findOrFail($id);
        $cuti->delete();

        return redirect()->route('admin.cuti.index')->with('success', 'Data cuti berhasil dihapus.')->with('alertType', 'success');
    }
}
