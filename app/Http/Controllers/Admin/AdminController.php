<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai; // Import model User
use App\Models\Attendance;
use App\Models\Cuti;
use App\Models\Wfh;
use App\Models\Datasen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Departemen;
use App\Models\RfidCard; // Import model RfidCard
use Illuminate\Support\Facades\Hash; // Import class Hash
use Illuminate\Support\Facades\DB; // Import class DB

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $totalWfh = Wfh::count();
        $wfhPending = Wfh::where('status', 'pending')->count();
        $wfhApproved = Wfh::where('status', 'approved')->count();
        $wfhRejected = Wfh::where('status', 'rejected')->count();

        // Statistik Cuti
        $totalCuti = Cuti::count();
        $cutiPending = Cuti::where('status', 'pending')->count();
        $cutiApproved = Cuti::where('status', 'approved')->count();
        $cutiRejected = Cuti::where('status', 'rejected')->count();

        // Ambil bulan dari request, default ke bulan saat ini
        $bulan = $request->input('bulan') ?? now()->format('Y-m');
        $selectedMonthStart = Carbon::parse($bulan)->startOfMonth();
        $selectedMonthEnd = Carbon::parse($bulan)->endOfMonth();

        $absensiQuery = Datasen::selectRaw('DATE(jam_masuk) as tanggal')
            ->selectRaw('SUM(CASE WHEN TIME(jam_masuk) <= "09:00:00" THEN 1 ELSE 0 END) as tepat_waktu')
            ->selectRaw('SUM(CASE WHEN TIME(jam_masuk) > "09:00:00" THEN 1 ELSE 0 END) as terlambat');

        if ($request->filled('tanggal')) {
            // Statistik hanya untuk tanggal yang dipilih
            $absensiQuery->whereDate('jam_masuk', $request->tanggal);
        } else {
            // Statistik untuk satu bulan penuh
            $absensiQuery->whereBetween('jam_masuk', [$selectedMonthStart, $selectedMonthEnd]);
        }

        $absensi = $absensiQuery->groupBy('tanggal')->get();

        $labels = [];
        $dataTepatWaktu = [];
        $dataTerlambat = [];

        foreach ($absensi as $item) {
            $labels[] = $item->tanggal;
            $dataTepatWaktu[] = (int) $item->tepat_waktu;
            $dataTerlambat[] = (int) $item->terlambat;
        }

        // Query catatan dengan pagination
        $query = Datasen::with('pegawai')->select('id_absen', 'id_pegawai', 'catatan', 'file_catatan', 'jam_masuk');

        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('nama_pegawai', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('jam_masuk', $request->tanggal);
        }

        // Paginate dengan 8 data per halaman
        $catatans = $query->paginate(8)->appends($request->all());

        // Statistik Kehadiran & Catatan Harian untuk Karyawan Terbaik (berdasarkan bulan yang dipilih)
        $karyawanQuery = Datasen::with('pegawai')
            ->select(
                'id_pegawai',
                DB::raw('COUNT(*) as total_kehadiran'),
                DB::raw('SUM(CASE WHEN TIME(jam_masuk) <= "09:00:00" THEN 1 ELSE 0 END) as tepat_waktu'),
                DB::raw('SUM(CASE WHEN TIME(jam_masuk) > "09:00:00" THEN 1 ELSE 0 END) as terlambat'),
                DB::raw('SUM(CASE WHEN catatan IS NOT NULL AND catatan != "" THEN 1 ELSE 0 END) as jumlah_catatan')
            )
            ->whereBetween('jam_masuk', [$selectedMonthStart, $selectedMonthEnd])
            ->groupBy('id_pegawai');

        $karyawanList = $karyawanQuery->get();

        $jumlahHariKerja = 24; // atau tetap gunakan $selectedMonthStart->daysInMonth jika ingin dinamis

        $karyawanScores = collect();

        foreach ($karyawanList as $karyawan) {
            // A. Skor Kehadiran (70 poin)
            $absen = $jumlahHariKerja - $karyawan->total_kehadiran;
            if ($absen < 0) $absen = 0;
            $skorKehadiran = 70 - ($absen * 3);
            if ($skorKehadiran < 0) $skorKehadiran = 0;

            // B. Skor Ketepatan Waktu (30 poin)
            $terlambat = $karyawan->terlambat;
            if ($terlambat == 0) {
                $skorTepatWaktu = 30;
            } elseif ($terlambat <= 2) {
                $skorTepatWaktu = 28;
            } elseif ($terlambat <= 5) {
                $skorTepatWaktu = 25;
            } elseif ($terlambat <= 10) {
                $skorTepatWaktu = 20;
            } elseif ($terlambat <= 15) {
                $skorTepatWaktu = 10;
            } else {
                $skorTepatWaktu = 0;
            }

            // Total Skor
            $totalSkor = $skorKehadiran + $skorTepatWaktu;

            // Kategori
            if ($totalSkor >= 90) {
                $kategori = 'Sangat Disiplin ⭐';
            } elseif ($totalSkor >= 80) {
                $kategori = 'Disiplin';
            } elseif ($totalSkor >= 65) {
                $kategori = 'Cukup Disiplin';
            } else {
                $kategori = 'Perlu Pembinaan ❗';
            }

            // Catatan evaluasi jika absen > 5 hari atau terlambat > 15 kali
            $catatanEvaluasi = null;
            if ($absen > 5) {
                $catatanEvaluasi = 'Perlu evaluasi kinerja (absen lebih dari 5 hari)';
            }
            if ($terlambat > 15) {
                $catatanEvaluasi = 'Perlu evaluasi keras (terlambat lebih dari 15 kali)';
            }

            $karyawanScores->push([
                'nama' => $karyawan->pegawai->nama_pegawai,
                'kehadiran' => $karyawan->total_kehadiran,
                'tepat_waktu' => $karyawan->tepat_waktu,
                'terlambat' => $karyawan->terlambat,
                'catatan' => $karyawan->jumlah_catatan,
                'score' => $totalSkor,
                'kategori' => $kategori,
                'catatan_evaluasi' => $catatanEvaluasi,
            ]);
        }

        $karyawanTerbaik = $karyawanScores->sortByDesc('score')->first();

        return view('admin.index', compact(
            'totalCuti',
            'cutiPending',
            'cutiApproved',
            'cutiRejected',
            'labels',
            'dataTepatWaktu',
            'dataTerlambat',
            'catatans',
            'karyawanTerbaik',
            'totalWfh',
            'wfhPending',
            'wfhApproved',
            'wfhRejected',
            'bulan' // Kirimkan bulan yang dipilih ke view
        ));
    }
    // ============================================== Menampilkan data absensi ==============================================
}
