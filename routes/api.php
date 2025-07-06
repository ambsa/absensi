<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// endpoint RFID
Route::post('/insert-uid', function (Request $request) {
    // Validasi input UID
    $validator = Validator::make($request->all(), [
        'uid' => 'required|string|min:1|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
    }

    $uid = $request->input('uid'); // UID dari NodeMCU
    Log::info('UID received:', ['uid' => $uid]);

    try {
        // Cari data pegawai berdasarkan UUID
        $pegawai = DB::table('pegawai')->where('uuid', $uid)->first();

        if (!$pegawai) {
            return response()->json(['status' => 'error', 'message' => 'Pegawai tidak ditemukan'], 404);
        }

        $idPegawai = $pegawai->id_pegawai; // Ambil id_pegawai dari tabel pegawai
        $namaPegawai = $pegawai->nama_pegawai; // Ambil nama pegawai

        // Cari record absen hari ini berdasarkan id_pegawai
        $today = now()->toDateString();
        $absen = DB::table('data_absen')
            ->where('id_pegawai', $idPegawai)
            ->whereDate('created_at', $today)
            ->first();

        if ($absen) {
            // Jika sudah absen masuk, cek apakah jam pulang sudah diisi
            if ($absen->jam_pulang) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Anda sudah absen masuk dan pulang hari ini.',
                ], 200);
            }

            // Jika belum absen pulang, cek apakah catatan sudah diisi
            if (empty($absen->catatan)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Catatan harian belum diisi. Silakan isi catatan terlebih dahulu.',
                ], 400);
            }

            // Update jam pulang jika catatan sudah diisi
            DB::table('data_absen')
                ->where('id_absen', $absen->id_absen)
                ->update([
                    'jam_pulang' => now(),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Jam pulang berhasil disimpan.',
                'nama_pegawai' => $namaPegawai,
            ], 200);
        } else {
            // Jika belum absen hari ini, simpan data absen baru (tap masuk)
            $idAbsen = DB::table('data_absen')->insertGetId([
                'id_pegawai' => $idPegawai,
                'jam_masuk' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Jam masuk berhasil disimpan.',
                'nama_pegawai' => $namaPegawai,
            ], 200);
        }
    } catch (\Exception $e) {
        Log::error('Error saat memproses data absen:', ['error' => $e->getMessage()]);
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});
