<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index()
{
    $workschedule = WorkSchedule::with('user')->get(); // Ambil semua jadwal kerja beserta relasi pengguna
    return view('admin.work_schedule.index', compact('workschedule'));
}


    // Method untuk menampilkan form pembuatan jadwal kerja
    public function create()
    {
        // Ambil semua pengguna dengan role 'user'
        $users = User::where('role_id', 'user')->get();
        
        return view('admin.work_schedule.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'monday_start' => 'required|date_format:H:i',
            'monday_end' => 'required|date_format:H:i',
            'tuesday_start' => 'required|date_format:H:i',
            'tuesday_end' => 'required|date_format:H:i',
            'wednesday_start' => 'required|date_format:H:i',
            'wednesday_end' => 'required|date_format:H:i',
            'thursday_start' => 'required|date_format:H:i',
            'thursday_end' => 'required|date_format:H:i',
            'friday_start' => 'required|date_format:H:i',
            'friday_end' => 'required|date_format:H:i',
            'saturday_start' => 'required|date_format:H:i',
            'saturday_end' => 'required|date_format:H:i',
        ]);
    
        // Ambil semua pengguna dengan role 'user'
        $users = User::where('role', 'user')->get();
    
        // Menyimpan jadwal untuk setiap pengguna
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        foreach ($users as $user) {
            foreach ($days as $day) {
                WorkSchedule::create([
                    'user_id' => $user->id,
                    'day' => $day,
                    'start' => $request->get(strtolower($day) . '_start'),
                    'end' => $request->get(strtolower($day) . '_end'),
                ]);
            }
        }
    
        return redirect()->route('admin.work_schedule.create')->with('success', 'Work schedule for all users has been set successfully!');
    }
    
}
