<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wfh;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WfhController extends Controller
{
    public function index()
    {
        $wfhs = Wfh::where('id_pegawai', Auth::id())->get();
        return view('admin.wfh.index', compact('wfhs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.wfh.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        WFH::create([
            'id_pegawai' => Auth::id(),
            'tanggal' => $request->tanggal,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.wfh.index')->with('success', 'Pengajuan WFH berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WFH  $wfh
     * @return \Illuminate\Http\Response
     */
    public function show(WFH $wfh)
    {
        return view('admin.wfh.show', compact('wfh'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WFH  $wfh
     * @return \Illuminate\Http\Response
     */
    public function edit(WFH $wfh)
    {
        return view('admin.wfh.edit', compact('wfh'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WFH  $wfh
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WFH $wfh)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $wfh->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.wfh.index')->with('success', 'Pengajuan WFH berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WFH  $wfh
     * @return \Illuminate\Http\Response
     */
    public function destroy(WFH $wfh)
    {
        $wfh->delete();
        return redirect()->route('admin.wfh.index')->with('success', 'Pengajuan WFH berhasil dihapus.');
    }
}
