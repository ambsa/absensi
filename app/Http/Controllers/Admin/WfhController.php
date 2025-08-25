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

        // Urutkan berdasarkan waktu dibuat terbaru
        $query->orderBy('created_at', 'desc');

        // Pagination
        $wfhs = $query->paginate(10);

        // Ambil data absen harian untuk setiap WFH
        foreach ($wfhs as $wfh) {
            $wfh->absen_harian = Datasen::where('id_pegawai', $wfh->id_pegawai)
                ->whereDate('created_at', $wfh->tanggal)
                ->first();
        }

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
        return view('admin.wfh.show', compact('wfh'));
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
            // Cari data WFH berdasarkan ID
            $wfh = Wfh::findOrFail($id);
            Log::info('Data WFH Ditemukan:', $wfh->toArray());

            // Validasi kepemilikan dan status WFH
            if ($wfh->id_pegawai !== Auth::user()->id_pegawai) {
                Log::error('Akses Absen Masuk Ditolak (ID Pegawai Tidak Sesuai):', [
                    'id_pegawai_login' => Auth::user()->id_pegawai,
                    'id_pegawai_wfh' => $wfh->id_pegawai,
                ]);
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }

            if ($wfh->status !== 'approved') {
                Log::error('Akses Absen Masuk Ditolak (Status WFH Belum Disetujui):', [
                    'status' => $wfh->status,
                ]);
                return response()->json(['success' => false, 'message' => 'WFH belum disetujui admin.'], 403);
            }

            // Validasi apakah absen masuk sudah dilakukan
            if ($wfh->absen_masuk) {
                Log::error('Absen Masuk Sudah Dilakukan:', [
                    'absen_masuk' => $wfh->absen_masuk,
                ]);
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen masuk.'], 400);
            }

            // Simpan data absen masuk
            $absen = Datasen::firstOrNew([
                'id_pegawai' => $wfh->id_pegawai,
                'created_at' => now()->toDateString(),
            ]);
            Log::info('Data Absen Sebelum Disimpan:', $absen->toArray());

            $absen->jam_masuk = now();

            try {
                $absen->save();
                Log::info('Data Absen Masuk Tersimpan:', $absen->toArray());
            } catch (\Exception $e) {
                Log::error('Error Saat Menyimpan Absen Masuk:', ['message' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan absen masuk.'], 500);
            }

            // Update kolom absen_masuk di tabel WFH
            $wfh->update(['absen_masuk' => now()]);
            Log::info('Kolom absen_masuk di tabel WFH berhasil diperbarui.');

            // Kembalikan respons JSON
            return response()->json([
                'success' => true,
                'message' => 'Absen masuk berhasil!',
                'pegawai_nama' => $wfh->pegawai->nama_pegawai,
                'tanggal' => \Carbon\Carbon::parse($wfh->tanggal)->format('d-m-Y'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error Umum di Method absenMasuk:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function absenPulang(Request $request, $id)
    {
        try {
            // Cari data WFH berdasarkan ID
            $wfh = Wfh::findOrFail($id);
            Log::info('Data WFH Ditemukan:', $wfh->toArray());

            // Validasi kepemilikan dan status WFH
            if ($wfh->id_pegawai !== Auth::user()->id_pegawai) {
                Log::error('Akses Absen Pulang Ditolak (ID Pegawai Tidak Sesuai):', [
                    'id_pegawai_login' => Auth::user()->id_pegawai,
                    'id_pegawai_wfh' => $wfh->id_pegawai,
                ]);
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }

            if ($wfh->status !== 'approved') {
                Log::error('Akses Absen Pulang Ditolak (Status WFH Belum Disetujui):', [
                    'status' => $wfh->status,
                ]);
                return response()->json(['success' => false, 'message' => 'WFH belum disetujui admin.'], 403);
            }

            // Ambil data absen hari ini
            $absen = Datasen::where('id_pegawai', $wfh->id_pegawai)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            // Validasi apakah absen masuk sudah dilakukan
            if (!$absen || !$absen->jam_masuk) {
                Log::error('Absen Pulang Ditolak (Belum Absen Masuk):', [
                    'absen' => $absen,
                ]);
                return response()->json(['success' => false, 'message' => 'Anda belum melakukan absen masuk.'], 400);
            }

            // Validasi apakah absen pulang sudah dilakukan
            if ($absen->jam_pulang) {
                Log::error('Absen Pulang Sudah Dilakukan:', [
                    'jam_pulang' => $absen->jam_pulang,
                ]);
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen pulang.'], 400);
            }

            // Validasi apakah catatan sudah diisi
            if (!$absen->catatan || empty(trim($absen->catatan))) {
                Log::error('Absen Pulang Ditolak (Belum Mengisi Catatan):', [
                    'catatan' => $absen->catatan,
                ]);
                return response()->json(['success' => false, 'message' => 'Anda harus mengisi catatan harian terlebih dahulu sebelum absen pulang.'], 400);
            }

            // Simpan data absen pulang
            $absen->jam_pulang = now();

            try {
                $absen->save();
                Log::info('Data Absen Pulang Tersimpan:', $absen->toArray());
            } catch (\Exception $e) {
                Log::error('Error Saat Menyimpan Absen Pulang:', ['message' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan absen pulang.'], 500);
            }

            // Kembalikan respons JSON
            return response()->json([
                'success' => true,
                'message' => 'Absen pulang berhasil!',
                'pegawai_nama' => $wfh->pegawai->nama_pegawai,
                'tanggal' => \Carbon\Carbon::parse($wfh->tanggal)->format('d-m-Y'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error Umum di Method absenPulang:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
