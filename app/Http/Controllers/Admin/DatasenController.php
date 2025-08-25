<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Datasen;
use App\Models\Wfh;
use Illuminate\Support\Facades\Str;
use App\Models\UnknownUid;
use App\Models\DataAbsen;
use App\Exports\DataAbsenExport;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DatasenController extends Controller
{
    public function __construct() {}
    public function index(Request $request)
    {
        // Default jumlah data per halaman
        $perPage = $request->input('per_page', 10); // Default ke 10 jika tidak ada input

        // Validasi input per_page untuk mencegah nilai tidak valid
        $allowedPerPageValues = [10, 25, 100, 500];
        if (!in_array($perPage, $allowedPerPageValues)) {
            $perPage = 10; // Reset ke default jika input tidak valid
        }

        // Mengambil data absen dengan pagination
         $datasens = Datasen::with('pegawai')
        ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
        ->paginate($perPage);

        // Kirim data absen dan jumlah data per halaman ke view
        return view('admin.data_absen.index', compact('datasens', 'perPage'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'uid' => 'nullable|string', // UID dari ESP8266
            'catatan' => 'nullable|string|min:10|max:1000',
            'file_catatan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'wfh_id' => 'nullable|exists:wfh,id_wfh',
        ]);

        // Jika ada wfh_id, ini adalah catatan WFH
        if ($request->has('wfh_id')) {
            return $this->storeWfhCatatan($request);
        }

        // Jika ada UID, ini adalah absensi dari ESP8266
        if ($request->has('uid')) {
            return $this->handleRfidAbsen($request);
        }

        // Ambil id_pegawai dari pengguna yang sedang login
        $idPegawai = Auth::user()->id_pegawai;

        // Cari record absen hari ini berdasarkan id_pegawai
        $today = now()->toDateString();
        $absen = Datasen::where('id_pegawai', $idPegawai)
            ->whereDate('created_at', $today)
            ->first();

        if (!$absen) {
            return redirect()->back()->with('error', 'Data absen tidak ditemukan.');
        }

        if (!empty($absen->catatan)) {
            return redirect()->back()->with('error', 'Catatan harian sudah diisi. Tidak dapat mengedit lagi.');
        }

        $absen->catatan = $request->input('catatan');

        if ($request->hasFile('file_catatan')) {
            $file = $request->file('file_catatan');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/catatan/';
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $fileName);
            $absen->file_catatan = $fileName;
        }

        $absen->save();

        return redirect()->back()->with('success', 'Catatan berhasil disimpan.');
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

            if (!$absen) {
                return redirect()->back()->with('error', 'Data absen tidak ditemukan.');
            }

            // Cek apakah catatan sudah diisi
            if (!empty($absen->catatan)) {
                return redirect()->back()->with('error', 'Catatan harian sudah diisi. Tidak dapat mengedit lagi.');
            }

            $absen->catatan = $request->input('catatan');

            // Simpan file jika ada
            if ($request->hasFile('file_catatan')) {
                $file = $request->file('file_catatan');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Path ke direktori uploads/catatan di public_html
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/catatan/';

                // Pastikan direktori ada
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Pindahkan file ke direktori tujuan
                $file->move($uploadPath, $fileName);

                // Simpan nama file ke database
                $absen->file_catatan = $fileName;
            }
            $absen->save();

            // Redirect ke halaman WFH
            return redirect()->route('admin.wfh.index')->with('success', 'Catatan WFH berhasil disimpan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function catatan()
    {
        return view('admin.data_absen.catatan');
    }

    public function downloadCSV($id)
    {
        // Ambil data absen berdasarkan ID
        $absen = DB::table('data_absen')
            ->join('pegawai', 'data_absen.id_pegawai', '=', 'pegawai.id_pegawai')
            ->where('id_absen', $id)
            ->first();

        if (!$absen) {
            return redirect()->back()->with('error', 'Data absen tidak ditemukan.');
        }

        // Header CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data_absen_' . $absen->id_absen . '.csv"',
        ];

        // Data untuk CSV
        $data = [
            ['ID Absen', 'Nama Pegawai', 'Jam Masuk', 'Jam Pulang', 'Catatan', 'File Catatan', 'Tanggal'],
            [
                $absen->id_absen,
                $absen->nama_pegawai,
                $absen->jam_masuk,
                $absen->jam_pulang,
                $absen->catatan,
                $absen->file_catatan,
                $absen->created_at,
            ],
        ];

        // Generate CSV
        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        // Unduh file CSV
        return response()->stream($callback, 200, $headers);
    }

    public function downloadPDF($id, $mode = 'preview')
    {
        // Ambil data absen berdasarkan ID
        $absen = DB::table('data_absen')
            ->join('pegawai', 'data_absen.id_pegawai', '=', 'pegawai.id_pegawai')
            ->where('id_absen', $id)
            ->first();

        if (!$absen) {
            return redirect()->back()->with('error', 'Data absen tidak ditemukan.');
        }

        // Data untuk PDF
        $data = [
            'id_absen' => $absen->id_absen,
            'nama_pegawai' => $absen->nama_pegawai,
            'jam_masuk' => $absen->jam_masuk,
            'jam_pulang' => $absen->jam_pulang,
            'created_at' => $absen->created_at,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('admin.data_absen.pdf', $data);

        if ($mode === 'download') {
            // Unduh file PDF
            return $pdf->download('data_absen_' . $absen->id_absen . '.pdf');
        } else {
            // Preview PDF di browser
            return $pdf->stream('data_absen_' . $absen->id_absen . '.pdf');
        }
    }

    public function downloadAllPDF()
    {
        // Ambil semua data absen
        $datasens = Datasen::with('pegawai')->get();

        // Format data untuk PDF
        $data = [
            'datasens' => $datasens->map(function ($item) {
                return [
                    'id_absen' => $item->id_absen,
                    'nama_pegawai' => $item->pegawai->nama_pegawai ?? '-',
                    'jam_masuk' => $item->jam_masuk,
                    'jam_pulang' => $item->jam_pulang,
                    'catatan' => $item->catatan,
                    'tanggal' => Carbon::parse($item->created_at)->format('d-m-Y'),
                ];
            }),
        ];

        // Generate dan unduh PDF
        $pdf = Pdf::loadView('admin.data_absen.all-pdf', $data);
        return $pdf->download('all_data_absen.pdf');
    }

    public function downloadAllExcel()
    {
        return Excel::download(new DataAbsenExport(), 'Data_Absen.xlsx');
    }

    // BELUM
    public function wfhAbsen(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_wfh' => 'required|exists:wfh,id_wfh',
            'type' => 'required|in:masuk,pulang',
        ]);

        // Ambil data WFH
        $wfh = Wfh::findOrFail($request->id_wfh);

        // Pastikan absen hanya bisa dilakukan pada tanggal yang sesuai
        if ($wfh->tanggal !== now()->toDateString()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak dapat melakukan absen di luar tanggal WFH/WFA.'], 403);
        }

        // Ambil ID pegawai dari user yang login
        $idPegawai = Auth::user()->id_pegawai;

        // Cari atau buat record absen hari ini
        $absen = Datasen::firstOrCreate(
            ['id_pegawai' => $idPegawai, 'tanggal' => now()->toDateString()],
            [
                'id_pegawai' => $idPegawai,
                'tanggal' => now()->toDateString(),
            ]
        );

        // Inisialisasi respons
        $response = ['status' => 'success', 'message' => 'Absen berhasil dilakukan.'];
        $statusCode = 200;

        // Update jam masuk/pulang berdasarkan jenis absen
        if ($request->type === 'masuk') {
            if ($absen->jam_masuk) {
                $response = ['status' => 'error', 'message' => 'Anda sudah absen masuk hari ini.'];
                $statusCode = 403;
            } else {
                $absen->jam_masuk = now();
            }
        } elseif ($request->type === 'pulang') {
            if (!$absen->jam_masuk) {
                $response = ['status' => 'error', 'message' => 'Anda belum absen masuk hari ini.'];
                $statusCode = 403;
            } elseif ($absen->jam_pulang) {
                $response = ['status' => 'error', 'message' => 'Anda sudah absen pulang hari ini.'];
                $statusCode = 403;
            } else {
                $absen->jam_pulang = now();
            }
        }

        // Simpan perubahan jika status berhasil
        if ($statusCode === 200) {
            $absen->save();
        }

        return response()->json($response, $statusCode);
    }

    // UNTUK ANALISIS KECURANGAN ABSEN
    public function handleRfidAbsen(Request $request)
    {
         // Validasi input UID sebagai integer
    $validator = Validator::make($request->all(), [
        'uid' => 'required|integer|min:1|max:4294967295',
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
            // Simpan UUID yang tidak terdaftar ke tabel unknown_uids
            UnknownUid::firstOrCreate(['uuid' => $uid]);
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $idPegawai = $pegawai->id_pegawai; // Ambil id_pegawai dari tabel pegawai
        $namaPegawai = $pegawai->nama_pegawai; // Ambil nama pegawai

        // Logika absen masuk/pulang...
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
    } catch (Exception $e) {
        Log::error('Error saat memproses data absen:', ['error' => $e->getMessage()]);
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

    private function analyzeSuspiciousPatterns($idPegawai, $waktuAbsen)
    {
        // Ambil data absen beberapa hari terakhir untuk pegawai ini
        $recentAbsens = DB::table('data_absen')
            ->where('id_pegawai', $idPegawai)
            ->where('created_at', '>=', now()->subDays(7)) // Ambil data 7 hari terakhir
            ->orderBy('created_at', 'desc')
            ->get();

        // Cek apakah ada pola jam masuk yang berulang
        $suspiciousCount = 0;
        foreach ($recentAbsens as $absen) {
            if (abs(Carbon::parse($absen->jam_masuk)->diffInSeconds($waktuAbsen)) <= 5) {
                $suspiciousCount++;
            }
        }

        if ($suspiciousCount > 3) { // Misalnya, jika lebih dari 3 kali absen dengan pola serupa
            $this->logSuspiciousActivity($idPegawai, null, $waktuAbsen, "Jam masuk selalu sama dalam 7 hari terakhir.");
        }

        // Cek apakah ada pegawai lain yang absen pada waktu yang hampir bersamaan
        $suspiciousRecords = DB::table('data_absen')
            ->where('id_pegawai', '!=', $idPegawai)
            ->whereBetween('created_at', [
                Carbon::parse($waktuAbsen)->subSeconds(30),
                Carbon::parse($waktuAbsen)->addSeconds(60),
            ])
            ->get();

        if ($suspiciousRecords->isNotEmpty()) {
            foreach ($suspiciousRecords as $record) {
                $this->logSuspiciousActivity($idPegawai, $record->id_pegawai, $waktuAbsen, "Absen bersamaan dengan pegawai lain. Ada indikasi kecurangan dalam proses absensi.");
            }
        }
    }

    private function logSuspiciousActivity($idPegawai1, $idPegawai2, $waktuAbsen, $keterangan)
    {
        // Buat daftar pegawai yang terlibat dalam anomali
        $pegawaiList = [];
        if ($idPegawai1) {
            $pegawaiList[] = [
                'id_pegawai' => $idPegawai1,
                'nama_pegawai' => $this->getNamaPegawai($idPegawai1),
                'waktu' => Carbon::parse($waktuAbsen)->format('Y-m-d H:i:s'),
            ];
        }
        if ($idPegawai2) {
            $pegawaiList[] = [
                'id_pegawai' => $idPegawai2,
                'nama_pegawai' => $this->getNamaPegawai($idPegawai2),
                'waktu' => Carbon::parse($waktuAbsen)->format('Y-m-d H:i:s'),
            ];
        }

        // Buat pesan notifikasi
        $message = "Terdapat Anomali Dalam Proses Absensi:\n\n";

        // Tambahkan detail pegawai
        foreach ($pegawaiList as $index => $pegawai) {
            $message .= ($index + 1) . ". Pegawai ID {$pegawai['id_pegawai']} ({$pegawai['nama_pegawai']}) = {$pegawai['waktu']}\n";
        }

        // Tambahkan keterangan
        $message .= "\nKeterangan: $keterangan\n";

        // Nomor penerima WhatsApp (ganti dengan nomor admin yang valid)
        $to = "62882002451967";

        // Kirim notifikasi via WhatsApp
        $this->sendWhatsAppNotification($to, $message);
    }

    private function getNamaPegawai($idPegawai)
    {
        // Ambil nama pegawai dari database
        $pegawai = DB::table('pegawai')->where('id_pegawai', $idPegawai)->first();
        return $pegawai ? $pegawai->nama_pegawai : 'Nama Tidak Diketahui';
    }

    private function sendWhatsAppNotification($to, $message)
    {
        // URL WhatsApp API
        $apiUrl = "http://localhost:3000/send/message";

        try {
            $response = Http::post($apiUrl, [
                'phone' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("Pesan WhatsApp berhasil dikirim ke $to.");
            } else {
                Log::error("Gagal mengirim pesan WhatsApp: " . $response->body());
            }
        } catch (Exception $e) {
            Log::error("Error saat mengirim pesan WhatsApp: " . $e->getMessage());
        }
    }
}
