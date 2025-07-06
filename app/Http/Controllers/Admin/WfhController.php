<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wfh;
use App\Models\Pegawai;
use App\Models\Datasen;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WfhController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk mendapatkan semua pengajuan WFH
        $query = Wfh::query();

        // Filter berdasarkan status jika diminta
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Pagination
        $wfhs = $query->paginate(10);

        // Ambil ID pegawai dari user yang login
        $idPegawai = Auth::user()->pegawai?->id;


        return view('admin.wfh.index', compact('wfhs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.wfh.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $wfh = Wfh::create([
            'id_pegawai' => Auth::user()->id_pegawai,
            'tanggal' => $request->tanggal,
            'status' => 'pending',
        ]);

        // Cari admin dan kirim notifikasi
        $admin = Pegawai::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->first();

        if ($admin && $admin->device_token) {
            $firebaseService = new FirebaseNotificationService();
            $firebaseService->sendNotification(
                $admin->device_token,
                'Pengajuan WFH Baru',
                "Ada pengajuan WFH baru dari {$wfh->pegawai->nama_pegawai}"
            );
        }

        return redirect()->route('admin.wfh.index')->with('success', 'Status pengajuan WFH berhasil dikirim.')->with('alertType', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $wfh = Wfh::findOrFail($id);
        return view('admin.wfh.show', compact('wfhS'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     $wfh = WFH::findOrFail($id);
    //     return view('admin.wfh.edit', compact('wfh'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Cari data WFH berdasarkan ID
        $wfh = Wfh::findOrFail($id);

        // Pengecekan status dan waktu
        if ($wfh->status === 'pending') {
            // Jika status masih pending, izinkan perubahan tanpa batasan waktu
        } elseif (in_array($wfh->status, ['approved', 'rejected']) && $wfh->updated_at && now()->diffInHours($wfh->updated_at) >= 1) {
            // Jika status sudah approved/rejected dan lebih dari 1 jam yang lalu, tampilkan error
            return redirect()->back()->with('error', 'Waktu edit sudah lewat batas 1 jam.');
        }

        // Update status
        $wfh->update([
            'status' => $request->status,
            'updated_at' => now(), // Perbarui waktu terakhir update
        ]);

        // Kirim notifikasi ke pegawai (user)
        $pegawai = $wfh->pegawai;
        if ($pegawai && $pegawai->device_token) {
            $firebaseService = new FirebaseNotificationService();
            $title = $request->status === 'approved' ? 'Pengajuan WFH Disetujui' : 'Pengajuan WFH Ditolak';
            $body = $request->status === 'approved'
                ? "Pengajuan WFH Anda telah disetujui oleh admin."
                : "Pengajuan WFH Anda telah ditolak oleh admin.";

            $firebaseService->sendNotification(
                $pegawai->device_token,
                $title,
                $body
            );
        }

        return redirect()->route('admin.wfh.index')->with('success', 'Status pengajuan WFH berhasil diperbarui.')->with('alertType', 'success');
    }

    public function destroy($id)
    {
        // Cari data WFH berdasarkan ID
        $wfh = Wfh::findOrFail($id);

        // Hapus data
        $wfh->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.wfh.index')->with('success', 'Status pengajuan WFH berhasil dihapus.')->with('alertType', 'success');
    }

    public function absenMasuk(Request $request, $id)
    {
        try {
            $wfh = Wfh::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'WFH dengan ID tersebut tidak ditemukan.');
        }

        // Validasi apakah pengajuan WFH milik user yang login
        if (!Auth::user()->pegawai || $wfh->id_pegawai !== Auth::user()->pegawai->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk absen ini.');
        }

        // Validasi apakah WFH sudah disetujui
        if ($wfh->status !== 'approved') {
            return redirect()->back()->with('error', 'WFH belum disetujui admin.');
        }

        // Validasi apakah absen masuk sudah dilakukan
        if ($wfh->absen_masuk) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk.');
        }

        // Debugging: Log data absen masuk
        Log::info('Data Absen Masuk:', [
            'id_pegawai' => $wfh->id_pegawai,
            'jam_masuk' => now(),
        ]);

        // Cari atau buat data absen untuk hari ini
        $absen = $wfh->datasen()->firstOrNew([
            'id_pegawai' => $wfh->id_pegawai,
        ]);

        // Isi kolom jam_masuk jika belum ada
        if (!$absen->jam_masuk) {
            $absen->jam_masuk = now();
            $absen->catatan = 'Absen Masuk WFH';
            $absen->save();
        }

        // Update status absen masuk di tabel wfhs
        $wfh->update([
            'absen_masuk' => now(),
        ]);

        return redirect()->back()->with('success', 'Absen masuk berhasil!');
    }

    public function absenPulang(Request $request, $id)
    {
        try {
            $wfh = Wfh::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'WFH dengan ID tersebut tidak ditemukan.');
        }

        // Validasi apakah pengajuan WFH milik user yang login
        if (!Auth::user()->pegawai || $wfh->id_pegawai !== Auth::user()->pegawai->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk absen ini.');
        }

        // Validasi apakah WFH sudah disetujui
        if ($wfh->status !== 'approved') {
            return redirect()->back()->with('error', 'WFH belum disetujui admin.');
        }

        // Validasi apakah absen masuk sudah dilakukan
        if (!$wfh->absen_masuk) {
            return redirect()->back()->with('error', 'Anda belum melakukan absen masuk.');
        }

        // Validasi apakah absen pulang sudah dilakukan
        if ($wfh->absen_pulang) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen pulang.');
        }

        // Debugging: Log data absen pulang
        Log::info('Data Absen Pulang:', [
            'id_pegawai' => $wfh->id_pegawai,
            'jam_pulang' => now(),
        ]);

        // Cari data absen untuk hari ini
        $absen = $wfh->datasen()->firstOrNew([
            'id_pegawai' => $wfh->id_pegawai,
        ]);

        // Isi kolom jam_pulang jika belum ada
        if (!$absen->jam_pulang) {
            $absen->jam_pulang = now();
            $absen->catatan = $absen->catatan ? $absen->catatan . ', Absen Pulang WFH' : 'Absen Pulang WFH';
            $absen->save();
        }

        // Update status absen pulang di tabel wfhs
        $wfh->update([
            'absen_pulang' => now(),
        ]);

        return redirect()->back()->with('success', 'Absen pulang berhasil!');
    }
}
