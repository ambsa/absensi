<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Wfh;
    use App\Models\Pegawai;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class WfhController extends Controller
    {
        public function index(Request $request)
        {
            // Query untuk mendapatkan semua pengajuan WFH
            $query = Wfh::query();

            // Tambahkan filter status jika parameter 'status' ada dalam request
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Paginate hasil query (menampilkan 10 item per halaman)
            $wfhs = $query->paginate(10);

            // Cek apakah pegawai memiliki pengajuan WFH yang approved hari ini
            $idPegawai = Auth::user()->pegawai?->id;

            if ($idPegawai) {
                $wfhApprovedToday = Wfh::where('id_pegawai', $idPegawai)
                    ->where('status', 'approved')
                    ->whereDate('tanggal', now()->toDateString())
                    ->whereNull('absen_masuk') // Belum absen masuk
                    ->orWhereNull('absen_pulang') // Belum absen pulang
                    ->first();
            } else {
                $wfhApprovedToday = null;
            }

            return view('admin.wfh.index', compact('wfhs', 'wfhApprovedToday'));
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            return view('admin.wfh.create');
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            $request->validate([
                'tanggal' => 'required|date',
            ]);

            WFH::create([
                'id_pegawai' => Auth::user()->id_pegawai, // Gunakan Auth::user()
                'tanggal' => $request->tanggal,
                'status' => 'pending',
            ]);

            return redirect()->route('admin.wfh.index')->with('success', 'Status pengajuan WFH berhasil dikirim.')->with('alertType', 'success');
        }

        /**
         * Display the specified resource.
         */
        public function show($id)
        {
            $wfh = WFH::findOrFail($id);
            return view('admin.wfh.show', compact('wfh'));
        }

        /**
         * Show the form for editing the specified resource.
         */
        // public function edit($id)
        // {
        //     $wfh = WFH::findOrFail($id);
        //     return view('admin.wfh.edit', compact('wfh'));
        // }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, $id)
        {
            // Validasi input
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);

            // Cari data WFH berdasarkan ID
            $wfh = Wfh::findOrFail($id);

            // Pengecekan status dan waktu
            if ($wfh->status === 'pending') {
                // Jika status masih pending, izinkan perubahan tanpa batasan waktu
            } elseif (in_array($wfh->status, ['approved', 'rejected']) && $wfh->updated_at && now()->diffInHours($wfh->updated_at) >= 1) {
                // Jika status sudah approved/rejected dan lebih dari 1 jam yang lalu, tampilkan error
                return redirect()->back()->with('error', 'Waktu edit sudah lewat batas 1 jam.');
            }

            // Update status
            $wfh->update([
                'status' => $request->status,
                'updated_at' => now(), // Perbarui waktu terakhir update
            ]);

            return redirect()->route('admin.wfh.index')->with('success', 'Status pengajuan WFH berhasil diperbarui.')->with('alertType', 'success');
        }

        public function destroy($id)
        {
            // Cari data WFH berdasarkan ID
            $wfh = Wfh::findOrFail($id);

            // Hapus data
            $wfh->delete();

            // Redirect dengan pesan sukses
            return redirect()->route('admin.wfh.index')->with('success', 'Status pengajuan WFH berhasil dihapus.')->with('alertType', 'success');
        }
    }
