<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Datasen;
use App\Models\Wfh;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Log;

class UserDatasenController extends Controller
{
    public function index(Request $request)
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Ambil data absen untuk user yang sedang login
        $perPage = $request->get('per_page', 10);
        $userAbsen = Datasen::where('id_pegawai', $user->id_pegawai)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('user.data_absen.index', compact('userAbsen', 'perPage'));
    }

    public function catatanuser()
    {
        return view('user.catatan.catatanuser');
    }
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'catatan' => 'required|string|min:10|max:1000',
                'file_catatan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                'wfh_id' => 'nullable|exists:wfh,id_wfh',
            ], [
                'catatan.required' => 'Catatan harian wajib diisi.',
                'catatan.min' => 'Catatan harian minimal 10 karakter.',
                'catatan.max' => 'Catatan harian maksimal 1000 karakter.',
                'file_catatan.file' => 'File yang diupload harus berupa file.',
                'file_catatan.mimes' => 'File harus berformat: pdf, doc, docx, jpg, jpeg, png.',
                'file_catatan.max' => 'Ukuran file maksimal 5MB.',
            ]);

            // Jika ada wfh_id, ini adalah catatan WFH
            if ($request->has('wfh_id')) {
                return $this->storeWfhCatatan($request);
            }

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
                return redirect()->back()->with([
                    'error' => 'Catatan harian sudah diisi. Tidak dapat mengedit lagi.',
                    'catatanSudahDiisi' => true
                ]);
            }

            // Simpan catatan
            $absen->catatan = $request->input('catatan');

            // Simpan file jika ada
            if ($request->hasFile('file_catatan')) {
                $file = $request->file('file_catatan');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Path ke direktori uploads/catatan di public_html
                $uploadPath = public_path('uploads/catatan'); // public_path() → /home/user/public_html

                // Pastikan direktori ada
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Pindahkan file ke direktori tujuan
                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception("Gagal mengunggah file.");
                }

                // Simpan nama file ke database
                $absen->file_catatan = $fileName;
            }

            // Simpan perubahan
            $absen->save();

            return redirect()->back()->with('success', 'Catatan berhasil disimpan.')->with('alertType', 'success');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in store method: ' . $e->getMessage());

            // Kembalikan pengguna ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    private function storeWfhCatatan(Request $request)
    {
        try {
            // Cari data WFH
            $wfh = Wfh::findOrFail($request->wfh_id);

            // Validasi kepemilikan
            if ($wfh->id_pegawai !== Auth::user()->id_pegawai) {
                return redirect()->back()->with('error', 'Akses ditolak.');
            }

            // Cari data absen berdasarkan tanggal WFH
            $absen = Datasen::where('id_pegawai', $wfh->id_pegawai)
                ->whereDate('created_at', $wfh->tanggal)
                ->first();

            // Jika tidak ada record absen untuk tanggal WFH
            if (!$absen) {
                return redirect()->back()->with('error', 'Data absen tidak ditemukan.');
            }

            // Cek apakah catatan sudah diisi
            if (!empty($absen->catatan)) {
                return redirect()->back()->with([
                    'error' => 'Catatan harian sudah diisi. Tidak dapat mengedit lagi.',
                    'catatanSudahDiisi' => true
                ]);
            }

            // Simpan catatan
            $absen->catatan = $request->input('catatan');

            // Simpan file jika ada
            if ($request->hasFile('file_catatan')) {
                $file = $request->file('file_catatan');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Path ke direktori uploads/catatan di public folder
                $uploadPath = public_path('uploads/catatan/');

                // Pastikan direktori ada
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Pindahkan file ke direktori tujuan
                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception("Gagal mengunggah file.");
                }

                // Simpan nama file ke database
                $absen->file_catatan = $fileName;
            }

            // Simpan perubahan ke database
            $absen->save();

            // Redirect ke halaman WFH
            return redirect()->route('user.wfh.index')->with('success', 'Catatan WFH berhasil disimpan.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in storeWfhCatatan method: ' . $e->getMessage());

            // Kembalikan pengguna ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
