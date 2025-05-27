<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\JenisCuti;
use Illuminate\Support\Facades\Auth;

class UserCutiController extends Controller
{
   // Menampilkan daftar pengajuan cuti milik pengguna
   public function index()
   {
       $cuti = Cuti::with(['jenis_cuti'])
                   ->where('id_pegawai', Auth::id())
                   ->get();

       return view('user.cuti.index', compact('cuti'));
   }

   public function create()
    {
        $jenis_cuti = JenisCuti::all();
        return view('user.cuti.create', compact('jenis_cuti'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis_cuti' => 'required|exists:jenis_cuti,id_jenis_cuti',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
        ]);

        Cuti::create([
            'id_pegawai' => Auth::id(),
            'id_jenis_cuti' => $validated['id_jenis_cuti'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => $validated['alasan'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.cuti.index')->with('success', 'Pengajuan cuti berhasil diajukan.');
    }
}
