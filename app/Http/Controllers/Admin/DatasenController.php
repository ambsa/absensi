<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Datasen;
use App\Models\Wfh;
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

class DatasenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Memastikan pengguna sudah login
    }
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
        $datasens = Datasen::with('pegawai')->paginate($perPage);

        // Kirim data absen dan jumlah data per halaman ke view
        return view('admin.data_absen.index', compact('datasens', 'perPage'));
    }


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

        return redirect()->back()->with('success', 'Catatan berhasil disimpan.');
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




    // public function destroy($id_absen)
    // {
    //     try {
    //         $datasen = Datasen::findOrFail($id_absen);

    //         // Hapus file jika ada
    //         if ($datasen->file_catatan) {
    //             Storage::disk('public')->delete($datasen->file_catatan);
    //         }

    //         // Hapus data dari database
    //         $datasen->delete();

    //         return redirect()->route('admin.data_absen.index')->with('success', 'Data absensi berhasil dihapus!');
    //     } catch (Exception $e) {
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }
    // Method untuk menampilkan halaman scan RFID
    // public function showScanRfidPage()
    // {
    //     return view('tap_rfid.index'); // Sesuaikan dengan nama view yang sesuai
    // }
    // public function store(Request $request)
    // {
    //     // Validasi bahwa data RFID yang dikirim valid
    //     $request->validate([
    //         'rfid_card_number' => 'required|string',
    //     ]);

    //     $response = ['message' => '', 'status' => 200];

    //     // Mencari kartu RFID berdasarkan nomor yang dibaca
    //     $rfidCard = RfidCard::where('card_number', $request->rfid_card_number)->first();

    //     // Cek apakah kartu RFID terdaftar
    //     if (!$rfidCard) {
    //         $response['message'] = 'Kartu RFID tidak terdaftar!';
    //         $response['status'] = 404;
    //     } else {
    //         $user = $rfidCard->user; // Ambil user yang terhubung dengan kartu RFID

    //         if (!$user) {
    //             $response['message'] = 'Pengguna tidak ditemukan!';
    //             $response['status'] = 404;
    //         } else {
    //             // Cek apakah user terjadwal untuk hari ini (work_schedule)
    //             $workSchedule = WorkSchedule::where('user_id', $user->id)
    //                 ->where('day', Carbon::today()->format('l')) // Menggunakan nama hari dari tanggal hari ini
    //                 ->first();

    //             if (!$workSchedule) {
    //                 $response['message'] = 'Pengguna tidak terjadwal bekerja hari ini!';
    //                 $response['status'] = 404;
    //             } else {
    //                 // Cek apakah user sudah terdaftar absensi hari ini
    //                 $attendance = Attendance::where('user_id', $user->id)
    //                     ->whereDate('date', Carbon::today())
    //                     ->first();

    //                 if (!$attendance) {
    //                     // Jika absensi belum ada, tambahkan data check-in
    //                     Attendance::create([
    //                         'user_id' => $user->id,
    //                         'rfid_card_id' => $rfidCard->id,
    //                         'date' => Carbon::today(),
    //                         'check_in' => Carbon::now(),
    //                         'status' => 'checked_in',
    //                     ]);

    //                     $response['message'] = 'Berhasil Absen Masuk!';
    //                 } else {
    //                     // Jika absensi sudah ada, tambahkan waktu check-out
    //                     $attendance->update([
    //                         'check_out' => Carbon::now(),
    //                         'status' => 'checked_out',
    //                     ]);

    //                     $response['message'] = 'Berhasil Pulang!';
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json(['message' => $response['message']], $response['status']);
    // }
}
