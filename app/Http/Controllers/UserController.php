<?php

namespace App\Http\Controllers;

use App\Models\User; // Import model User
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Departemen;
use App\Models\RfidCard; // Import model RfidCard
use Illuminate\Support\Facades\Hash; // Import class Hash
use Illuminate\Support\Facades\Auth; // Import Auth facade

class UserController extends Controller
{
   
    public function index(){
        return view('user.index');
    }

    public function attendanceHistory()
{
    // Ambil data absensi berdasarkan user yang sedang login
    $attendances = Auth::user()->attendances;  // Ambil data absensi dari relasi user

    // Kirim data absensi ke view
    return view('user.history.attendance_history', compact('attendances'));
}
}
