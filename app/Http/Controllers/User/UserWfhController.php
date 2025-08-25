<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wfh;
use App\Models\Datasen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserWfhController extends Controller
{
    public function index(Request $request){

        // Query hanya untuk pegawai yang sedang login
        $query = Wfh::where('id_pegawai', Auth::user()->id_pegawai);

        // Filter berdasarkan status jika parameter 'status' ada
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Paginate hasil query
        $wfhs = $query->paginate(10); // Menampilkan 10 item per halaman

        // Ambil data absen harian untuk setiap WFH
        foreach ($wfhs as $wfh) {
            $wfh->absen_harian = Datasen::where('id_pegawai', $wfh->id_pegawai)
                ->whereDate('created_at', $wfh->tanggal)
                ->first();
        }

        return view('user.wfh.index', compact('wfhs'));
    } 

    public function create()
    {
        return view('user.wfh.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        // Buat pengajuan WFH dengan id_pegawai dari user yang sedang login
        Wfh::create([
            'id_pegawai' => Auth::user()->id_pegawai,
            'tanggal' => $request->tanggal,
            'status' => 'pending',
        ]);

        // Redirect ke halaman index wfh untuk user
        return redirect()->route('user.wfh.index')->with('success', 'Pengajuan WFH berhasil dikirim.');
    }

    public function show($id)
    {
        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = Wfh::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Kembalikan view dengan data pengajuan WFH
        return view('user.wfh.show', compact('wfh'));
    }

    public function edit($id)
    {
        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = Wfh::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Pastikan status pengajuan masih pending agar bisa diedit
        if ($wfh->status !== 'pending') {
            return redirect()->route('user.wfh.index')->with('error', 'Pengajuan WFH tidak dapat diedit karena sudah diproses.');
        }

        // Kembalikan view dengan data pengajuan WFH
        return view('user.wfh.edit', compact('wfh'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = Wfh::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Pastikan status pengajuan masih pending agar bisa diperbarui
        if ($wfh->status !== 'pending') {
            return redirect()->route('user.wfh.index')->with('error', 'Pengajuan WFH tidak dapat diperbarui karena sudah diproses.');
        }

        // Perbarui data pengajuan WFH
        $wfh->update([
            'tanggal' => $request->tanggal,
        ]);

        // Redirect ke halaman index wfh untuk user
        return redirect()->route('user.wfh.index')->with('success', 'Pengajuan WFH berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = Wfh::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Pastikan status pengajuan masih pending agar bisa dihapus
        if ($wfh->status !== 'pending') {
            return redirect()->route('user.wfh.index')->with('error', 'Pengajuan WFH tidak dapat dihapus karena sudah diproses.');
        }

        // Hapus pengajuan WFH
        $wfh->delete();

        // Redirect ke halaman index wfh untuk user
        return redirect()->route('user.wfh.index')->with('success', 'Pengajuan WFH berhasil dihapus.');
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
            $absen = Datasen::where('id_pegawai', $wfh->id_pegawai)
                ->whereDate('created_at', $wfh->tanggal)
                ->first();

            if ($absen && $absen->jam_masuk) {
                Log::error('Absen Masuk Sudah Dilakukan:', [
                    'jam_masuk' => $absen->jam_masuk,
                ]);
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen masuk.'], 400);
            }

            // Simpan data absen masuk
            if (!$absen) {
                $absen = new Datasen();
                $absen->id_pegawai = $wfh->id_pegawai;
                $absen->created_at = $wfh->tanggal;
            }
            
            $absen->jam_masuk = now();

            try {
                $absen->save();
                Log::info('Data Absen Masuk Tersimpan:', $absen->toArray());
            } catch (\Exception $e) {
                Log::error('Error Saat Menyimpan Absen Masuk:', ['message' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan absen masuk.'], 500);
            }

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

            // Ambil data absen berdasarkan tanggal WFH
            $absen = Datasen::where('id_pegawai', $wfh->id_pegawai)
                ->whereDate('created_at', $wfh->tanggal)
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
