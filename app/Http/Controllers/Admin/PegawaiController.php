<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\Departemen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class PegawaiController extends Controller
{
    // public function boot()
    // {
    //     parent::boot();

    //     // Menyesuaikan route model binding untuk Pegawai
    //     Route::model('pegawai', Pegawai::class);
    // }
    public function index()
    {
        $pegawais = Pegawai::all();
        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        $roles = Role::all();
        $departemens = Departemen::all();

        // dd($roles, $departemens); // Debugging: Cek data roles dan departemen

        return view('admin.pegawai.create', compact('roles', 'departemens'));
    }

    public function store(Request $request)
    {
        // dd($request->all()); // Debugging: Cek semua data yang dikirimkan

        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai,email',
            'password' => 'required|string|min:4',
            'id_role' => 'required|exists:role,id_role', // Pastikan role dipilih
            'id_departemen' => 'required|exists:departemen,id_departemen', // Pastikan departemen dipilih
            'uuid' => 'required|string|unique:pegawai,uuid',
        ]);

        Pegawai::create([
            'nama_pegawai' => $request->nama_pegawai,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'id_departemen' => $request->id_departemen,
            'uuid' => $request->uuid,
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function edit(Pegawai $pegawai)
    {
        // Mengambil data roles dan departemen
        $roles = Role::all();
        $departemens = Departemen::all();
        // dd($roles, $departemens); // Debugging untuk memastikan data tersedia

        // Mengirim data ke view edit
        return view('admin.pegawai.edit', compact('pegawai', 'roles', 'departemens'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        // Validasi input
        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai,email',
            'id_role' => 'required|exists:role,id_role', // Pastikan role dipilih
            'id_departemen' => 'required|exists:departemen,id_departemen', // Pastikan departemen dipilih
            'uuid' => 'required|string|unique:pegawai,uuid',
        ]);

        // Update data pegawai
        $pegawai->update([
            'nama_pegawai' => $request->nama_pegawai,
            'email' => $request->email,
            'id_role' => $request->id_role,
            'id_departemen' => $request->id_departemen,
            'uuid' => $request->uuid,
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil diperbarui!');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil dihapus!');
    }
}
