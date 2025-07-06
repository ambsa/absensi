<?php

namespace App\Exports;

use App\Models\Datasen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class DataAbsenExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua data absen beserta relasi pegawai
        return Datasen::with('pegawai')->get()->map(function ($item) {
            return [
                'ID Absen' => $item->id_absen,
                'Nama Pegawai' => $item->pegawai ? $item->pegawai->nama_pegawai : '-',
                'ID Pegawai' => $item->pegawai ? $item->pegawai->id_pegawai : '-',
                'Jam Masuk' => $item->jam_masuk ?? '-',
                'Jam Pulang' => $item->jam_pulang ?? '-',
                'Catatan' => $item->catatan ?? '-',
                'File Catatan' => $item->file_catatan ? asset('storage/' . $item->file_catatan) : '-',
                'Tanggal Absen' => $item->created_at ? Carbon::parse($item->created_at)->format('d-m-Y') : '-',
            ];
        });
    }

    public function headings(): array
    {
        // Header kolom untuk Excel
        return [
            'ID Absen',
            'Nama Pegawai',
            'ID Pegawai',
            'Jam Masuk',
            'Jam Pulang',
            'Catatan',
            'File Catatan',
            'Tanggal Absen',
        ];
    }
}
