<?php

namespace App\Http\Controllers;

use App\Models\User; // Import model User
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Departemen;
use App\Models\RfidCard; // Import model RfidCard
use Illuminate\Support\Facades\Hash; // Import class Hash

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    // ============================================== Menampilkan data user ==============================================
    public function user()
    {
        // Mengambil semua data pengguna beserta relasi role dan departemen
        $users = User::with(['role', 'departemen'])->get();

        // Mengirim data pengguna ke view
        return view('admin.manage_user.index', compact('users'));
    }

    // Method untuk menampilkan form create
    public function create()
    {
        // Mengambil data roles dan departemen
        $roles = Role::all();
        $departemens = Departemen::all();

        // Mengirim data ke view create
        return view('admin.manage_user.create', compact('roles', 'departemens'));
    }

    // Method untuk menyimpan data user baru
    public function store(Request $request)
    {
        // Validasi form
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'departemen_id' => 'required|exists:departemen,id',
            'rfid_card_number' => 'required|string|unique:rfid_cards,card_number', // Validasi RFID
            'password' => 'required|string|min:8',
        ]);

        // Menyimpan data pengguna (user)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'departemen_id' => $request->departemen_id,
            'password' => Hash::make($request->password),
        ]);

        // Menyimpan data kartu RFID yang terkait dengan pengguna
        $rfidCard = RfidCard::create([
            'card_number' => $request->rfid_card_number, // Menyimpan nomor kartu RFID
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.manage_user.index')->with('success', 'User dan kartu RFID berhasil ditambahkan!');
    }

    //  Method untuk edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Ambil data role untuk dropdown
        $departemens = Departemen::all(); // Ambil data departemen untuk dropdown
        return view('admin.manage_user.edit', compact('user', 'roles', 'departemens'));
    }

    // Method untuk hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus data terkait pengguna jika diperlukan, misalnya kartu RFID
        $user->rfidCard()->delete(); // Jika ada hubungan dengan model RfidCard

        $user->delete();  // Hapus pengguna

        return redirect()->route('admin.manage_user.index')->with('success', 'User berhasil dihapus!');
    }

public function update(Request $request, $id)
{
    // Validasi form
    $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email,' . $id,
        'role_id' => 'required|exists:roles,id',
        'departemen_id' => 'required|exists:departemen,id',
        'rfid_card_number' => 'required|string|unique:rfid_cards,card_number,' . $id,  // Validasi RFID
    ]);

    // Update user
    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role_id' => $request->role_id,
        'departemen_id' => $request->departemen_id,
    ]);

    // Jika ada perubahan pada nomor kartu RFID, perbarui kartu RFID
    if ($request->has('rfid_card_number')) {
        // Jika kartu RFID sudah ada, update nomor kartu RFID
        if ($user->rfidCard) {
            $user->rfidCard()->update([
                'card_number' => $request->rfid_card_number,
            ]);
        } else {
            // Jika tidak ada, buat entri baru untuk kartu RFID
            RfidCard::create([
                'card_number' => $request->rfid_card_number,
                'user_id' => $user->id,
            ]);
        }
    }

    return redirect()->route('admin.manage_user.index')->with('success', 'User berhasil diperbarui!');
}


    // ============================================== Menampilkan data user ==============================================

    // ============================================== Menampilkan data absensi ==============================================
    public function attendance()
    {
        // Ambil data absensi untuk semua karyawan
        $attendances = Attendance::with(['user', 'rfidCard'])->get();

        // Kirim data ke view
        return view('admin.attendance.index', compact('attendances'));
    }

    // Menyimpan data absensi setelah tap kartu RFID
    public function storeAttendance(Request $request)
    {
        $rfidCard = RfidCard::where('card_number', $request->rfid_card_number)->first();

        if ($rfidCard) {
            // Cek apakah karyawan sudah absen untuk hari ini
            $attendance = Attendance::where('user_id', $rfidCard->user_id)
                ->whereDate('date', now()->toDateString())
                ->first();

            if (!$attendance || ($attendance && $attendance->check_out)) {
                // Jika belum absen atau sudah absen pulang, absen masuk
                Attendance::create([
                    'user_id' => $rfidCard->user_id,
                    'rfid_card_id' => $rfidCard->id,
                    'date' => now()->toDateString(),
                    'check_in' => now()->toTimeString(),
                    'status' => 'masuk',
                ]);
                return response()->json(['message' => 'Berhasil absen masuk!']);
            } else {
                // Jika sudah absen masuk, maka absen pulang
                $attendance->update([
                    'check_out' => now()->toTimeString(),
                    'status' => 'pulang',
                ]);
                return response()->json(['message' => 'Berhasil absen pulang!']);
            }
        } else {
            return response()->json(['message' => 'Kartu RFID tidak valid!'], 400);
        }
    }
    // ============================================== Menampilkan data absensi ==============================================
}
