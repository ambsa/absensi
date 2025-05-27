<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Datasen;

class DatasenController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'catatan' => 'nullable|string|max:1000',
            'file_catatan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Ambil id_pegawai dari pengguna yang sedang login
        $idPegawai = Auth::user()->id_pegawai;

        // Cari record absen hari ini berdasarkan id_pegawai
        $today = now()->toDateString();
        $absen = Datasen::where('id_pegawai', $idPegawai)
            ->whereDate('created_at', $today)
            ->first();

        // Jika tidak ada record absen untuk hari ini
        if (!$absen) {
            return redirect()->back()->with('error', 'Data absen tidak ditemukan.');
        }

        // Cek apakah catatan sudah diisi
        if (!empty($absen->catatan)) {
            return redirect()->back()->with('error', 'Catatan harian sudah diisi. Tidak dapat mengedit lagi.');
        }

        // Simpan catatan
        $absen->catatan = $request->input('catatan');

        // Simpan file jika ada
        if ($request->hasFile('file_catatan')) {
            $file = $request->file('file_catatan');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/catatan', $fileName, 'public');
            $absen->file_catatan = $filePath;
        }

        // Simpan perubahan
        $absen->save();

        return redirect()->back()->with('success', 'Catatan berhasil disimpan.');
    }
}
