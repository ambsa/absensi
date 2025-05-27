<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai; // Import model User
use App\Models\Attendance;
use App\Models\Cuti;
use App\Models\Datasen;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Departemen;
use App\Models\RfidCard; // Import model RfidCard
use Illuminate\Support\Facades\Hash; // Import class Hash

class AdminController extends Controller
{
    public function index(Request $request)
{
    // Statistik Cuti
    $totalCuti = Cuti::count();
    $cutiPending = Cuti::where('status', 'pending')->count();
    $cutiApproved = Cuti::where('status', 'approved')->count();
    $cutiRejected = Cuti::where('status', 'rejected')->count();

    // Statistik Ketepatan Waktu Pegawai (Data Harian)
    $absensi = Datasen::selectRaw('DATE(jam_masuk) as tanggal')
        ->selectRaw('SUM(CASE WHEN TIME(jam_masuk) <= "08:00:00" THEN 1 ELSE 0 END) as tepat_waktu')
        ->selectRaw('SUM(CASE WHEN TIME(jam_masuk) > "08:00:00" THEN 1 ELSE 0 END) as terlambat')
        ->groupBy('tanggal')
        ->get();

    // Inisialisasi array kosong
    $labels = [];
    $dataTepatWaktu = [];
    $dataTerlambat = [];

    foreach ($absensi as $item) {
        $labels[] = $item->tanggal;
        $dataTepatWaktu[] = (int) $item->tepat_waktu; // Pastikan tipe data integer
        $dataTerlambat[] = (int) $item->terlambat;   // Pastikan tipe data integer
    }

    // Query untuk data catatan pegawai
    $query = Datasen::with('pegawai') // Hubungkan dengan model Pegawai
        ->select('id_absen', 'id_pegawai', 'catatan', 'file_catatan');

    // Pencarian berdasarkan nama pegawai (hanya jika ada input search)
    if ($request->has('search') && !empty($request->search)) {
        $query->whereHas('pegawai', function ($q) use ($request) {
            $q->where('nama_pegawai', 'like', '%' . $request->search . '%');
        });
    }

    // Filter berdasarkan tanggal (hanya jika ada input tanggal)
    if ($request->has('tanggal') && !empty($request->tanggal)) {
        $query->whereDate('jam_masuk', $request->tanggal);
    }

    // Pagination
    $catatans = Datasen::with('pegawai')
    ->select('id_absen', 'id_pegawai', 'catatan', 'file_catatan', 'jam_masuk') // Pastikan jam_masuk disertakan
    ->paginate(10);

    return view('admin.index', compact(
        'totalCuti',
        'cutiPending',
        'cutiApproved',
        'cutiRejected',
        'labels',
        'dataTepatWaktu',
        'dataTerlambat',
        'catatans' // Kirim data catatan ke view
    ));
}


    // ============================================== Menampilkan data user pada halaman admin ==============================================
    // public function pegawai()
    // {
    //     // Mengambil semua data pengguna beserta relasi role dan departemen
    //     $pegawais = Pegawai::with(['role', 'departemen'])->get();
    //     return view('admin.pegawai.index', compact('pegawais'));
    // }

    // // Method untuk menampilkan form create
    // public function create()
    // {
    //     // Mengambil data roles dan departemen
    //     $role = Role::all();
    //     $departemen = Departemen::all();

    //     // Mengirim data ke view create
    //     return view('admin.pegawai.create', compact('role', 'departemen'));
    // }

    // // Method untuk menyimpan data user baru
    // public function store(Request $request)
    // {
    //     // Validasi form
    //     $request->validate([
    //         'name' => 'required|string|max:100',
    //         'email' => 'required|email|unique:pegawais,email',
    //         'password' => 'required|string|min:8',
    //         'id_role' => 'required|exists:role,id',
    //         'id_departemen' => 'required|exists:departemen,id',
    //         'uuid' => 'required|string|unique:rfid_cards,card_number', // Validasi RFID
            
    //     ]);

    //     // Menyimpan data pengguna (user)
    //     $user = Pegawai::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'id_role' => $request->role_id,
    //         'departemen_id' => $request->departemen_id,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // Menyimpan data kartu RFID yang terkait dengan pengguna
    //     // $rfidCard = RfidCard::create([
    //     //     'card_number' => $request->rfid_card_number, // Menyimpan nomor kartu RFID
    //     //     'user_id' => $user->id,
    //     // ]);

    //     return redirect()->route('admin.manage_user.index')->with('success', 'User dan kartu RFID berhasil ditambahkan!');
    // }

    //  Method untuk edit user
//     public function edit($id)
//     {
//         $user = Pegawai::findOrFail($id);
//         $roles = Role::all(); // Ambil data role untuk dropdown
//         $departemens = Departemen::all(); // Ambil data departemen untuk dropdown
//         return view('admin.manage_user.edit', compact('user', 'roles', 'departemens'));
//     }

//     // Method untuk hapus user
//     public function destroy($id)
//     {
//         $user = Pegawai::findOrFail($id);

//         // Hapus data terkait pengguna jika diperlukan, misalnya kartu RFID
//         $user->rfidCard()->delete(); // Jika ada hubungan dengan model RfidCard

//         $user->delete();  // Hapus pengguna

//         return redirect()->route('admin.manage_user.index')->with('success', 'User berhasil dihapus!');
//     }

// public function update(Request $request, $id)
// {
//     // Validasi form
//     $request->validate([
//         'name' => 'required|string|max:100',
//         'email' => 'required|email|unique:users,email,' . $id,
//         'role_id' => 'required|exists:roles,id',
//         'departemen_id' => 'required|exists:departemen,id',
//         'rfid_card_number' => 'required|string|unique:rfid_cards,card_number,' . $id,  // Validasi RFID
//     ]);

//     // Update user
//     $user = Pegawai::findOrFail($id);
//     $user->update([
//         'name' => $request->name,
//         'email' => $request->email,
//         'role_id' => $request->role_id,
//         'departemen_id' => $request->departemen_id,
//     ]);

//     return redirect()->route('admin.manage_user.index')->with('success', 'User berhasil diperbarui!');
// }


    // ============================================== Menampilkan data user ==============================================

    // ============================================== Menampilkan data absensi ==============================================
    // public function attendance()
    // {
    //     // Ambil data absensi untuk semua karyawan
    //     $attendances = Attendance::with(['user', 'rfidCard'])->get();

    //     // Kirim data ke view
    //     return view('admin.attendance.index', compact('attendances'));
    // }

    // Menyimpan data absensi setelah tap kartu RFID
    // public function storeAttendance(Request $request)
    // {
    //     $rfidCard = RfidCard::where('card_number', $request->rfid_card_number)->first();

    //     if ($rfidCard) {
    //         // Cek apakah karyawan sudah absen untuk hari ini
    //         $attendance = Attendance::where('user_id', $rfidCard->user_id)
    //             ->whereDate('date', now()->toDateString())
    //             ->first();

    //         if (!$attendance || ($attendance && $attendance->check_out)) {
    //             // Jika belum absen atau sudah absen pulang, absen masuk
    //             Attendance::create([
    //                 'user_id' => $rfidCard->user_id,
    //                 'rfid_card_id' => $rfidCard->id,
    //                 'date' => now()->toDateString(),
    //                 'check_in' => now()->toTimeString(),
    //                 'status' => 'masuk',
    //             ]);
    //             return response()->json(['message' => 'Berhasil absen masuk!']);
    //         } else {
    //             // Jika sudah absen masuk, maka absen pulang
    //             $attendance->update([
    //                 'check_out' => now()->toTimeString(),
    //                 'status' => 'pulang',
    //             ]);
    //             return response()->json(['message' => 'Berhasil absen pulang!']);
    //         }
    //     } else {
    //         return response()->json(['message' => 'Kartu RFID tidak valid!'], 400);
    //     }
    // }
    // ============================================== Menampilkan data absensi ==============================================
}
