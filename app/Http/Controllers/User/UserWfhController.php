<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WFH;
use Illuminate\Support\Facades\Auth;

class UserWfhController extends Controller
{
    public function index(Request $request){

        // Query hanya untuk pegawai yang sedang login
        $query = WFH::where('id_pegawai', Auth::user()->id_pegawai);

        // Filter berdasarkan status jika parameter 'status' ada
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Paginate hasil query
        $wfhs = $query->paginate(10); // Menampilkan 10 item per halaman

        return view('user.wfh.index', compact('wfhs'));
    } 

    public function create()
    {
        return view('user.wfh.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        // Buat pengajuan WFH dengan id_pegawai dari user yang sedang login
        WFH::create([
            'id_pegawai' => Auth::user()->id_pegawai,
            'tanggal' => $request->tanggal,
            'status' => 'pending',
        ]);

        // Redirect ke halaman index wfh untuk user
        return redirect()->route('user.wfh.index')->with('success', 'Pengajuan WFH berhasil dikirim.');
    }

    public function show($id)
    {
        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = WFH::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Kembalikan view dengan data pengajuan WFH
        return view('user.wfh.show', compact('wfh'));
    }

    public function edit($id)
    {
        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = WFH::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Pastikan status pengajuan masih pending agar bisa diedit
        if ($wfh->status !== 'pending') {
            return redirect()->route('user.wfh.index')->with('error', 'Pengajuan WFH tidak dapat diedit karena sudah diproses.');
        }

        // Kembalikan view dengan data pengajuan WFH
        return view('user.wfh.edit', compact('wfh'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = WFH::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Pastikan status pengajuan masih pending agar bisa diperbarui
        if ($wfh->status !== 'pending') {
            return redirect()->route('user.wfh.index')->with('error', 'Pengajuan WFH tidak dapat diperbarui karena sudah diproses.');
        }

        // Perbarui data pengajuan WFH
        $wfh->update([
            'tanggal' => $request->tanggal,
        ]);

        // Redirect ke halaman index wfh untuk user
        return redirect()->route('user.wfh.index')->with('success', 'Pengajuan WFH berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Temukan pengajuan WFH berdasarkan ID dan pastikan milik user yang sedang login
        $wfh = WFH::where('id_pegawai', Auth::user()->id_pegawai)->findOrFail($id);

        // Pastikan status pengajuan masih pending agar bisa dihapus
        if ($wfh->status !== 'pending') {
            return redirect()->route('user.wfh.index')->with('error', 'Pengajuan WFH tidak dapat dihapus karena sudah diproses.');
        }

        // Hapus pengajuan WFH
        $wfh->delete();

        // Redirect ke halaman index wfh untuk user
        return redirect()->route('user.wfh.index')->with('success', 'Pengajuan WFH berhasil dihapus.');
    }
}
