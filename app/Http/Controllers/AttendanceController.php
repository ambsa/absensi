<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\RfidCard;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
        // Method untuk menampilkan halaman scan RFID
        public function showScanRfidPage()
        {
            return view('tap_rfid.index'); // Sesuaikan dengan nama view yang sesuai
        }
    public function store(Request $request)
    {
        // Validasi bahwa data RFID yang dikirim valid
        $request->validate([
            'rfid_card_number' => 'required|string',
        ]);

        $response = ['message' => '', 'status' => 200];

        // Mencari kartu RFID berdasarkan nomor yang dibaca
        $rfidCard = RfidCard::where('card_number', $request->rfid_card_number)->first();

        // Cek apakah kartu RFID terdaftar
        if (!$rfidCard) {
            $response['message'] = 'Kartu RFID tidak terdaftar!';
            $response['status'] = 404;
        } else {
            $user = $rfidCard->user; // Ambil user yang terhubung dengan kartu RFID

            if (!$user) {
                $response['message'] = 'Pengguna tidak ditemukan!';
                $response['status'] = 404;
            } else {
                // Cek apakah user terjadwal untuk hari ini (work_schedule)
                $workSchedule = WorkSchedule::where('user_id', $user->id)
                    ->where('day', Carbon::today()->format('l')) // Menggunakan nama hari dari tanggal hari ini
                    ->first();

                if (!$workSchedule) {
                    $response['message'] = 'Pengguna tidak terjadwal bekerja hari ini!';
                    $response['status'] = 404;
                } else {
                    // Cek apakah user sudah terdaftar absensi hari ini
                    $attendance = Attendance::where('user_id', $user->id)
                        ->whereDate('date', Carbon::today())
                        ->first();

                    if (!$attendance) {
                        // Jika absensi belum ada, tambahkan data check-in
                        Attendance::create([
                            'user_id' => $user->id,
                            'rfid_card_id' => $rfidCard->id,
                            'date' => Carbon::today(),
                            'check_in' => Carbon::now(),
                            'status' => 'checked_in',
                        ]);

                        $response['message'] = 'Berhasil Absen Masuk!';
                    } else {
                        // Jika absensi sudah ada, tambahkan waktu check-out
                        $attendance->update([
                            'check_out' => Carbon::now(),
                            'status' => 'checked_out',
                        ]);

                        $response['message'] = 'Berhasil Pulang!';
                    }
                }
            }
        }

        return response()->json(['message' => $response['message']], $response['status']);
    }
}
