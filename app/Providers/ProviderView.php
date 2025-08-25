<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class ProviderView extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Ambil route
            $route = Request::route();
    
            // Debugging jika route tidak ditemukan
            if (!$route) {
                Log::error('Route not found for request: ' . Request::fullUrl());
            }
    
            // Ambil route name atau default value
            $routeName = $route?->getName() ?? 'default';
    
            // Set page title berdasarkan route name
            $pageTitle = match ($routeName) {
            'admin.index' => 'Dashboard ',
            'admin.pegawai.index' => 'Daftar Pegawai',
            'admin.pegawai.create' => 'Tambah Pegawai',
            'admin.pegawai.edit' => 'Edit Pegawai',
            'admin.pegawai.show' => 'Detail Pegawai',
            'admin.data_absen.index' => 'Riwayat Absensi',
            'admin.data_absen.catatan' => 'Membuat Catatan Harian Pekerjaan',
            'admin.data_absen.preview-pdf' => 'Preview PDF Absensi',
            'admin.data_absen.download-pdf' => 'Download PDF Absensi',
            'admin.data_absen.download-csv' => 'Download CSV Absensi',
            'admin.cuti.index' => 'Permohonan Cuti',
            'admin.cuti.create' => 'Buat Permohonan Cuti',
            'admin.cuti.store' => 'Simpan Permohonan Cuti',
            'admin.cuti.update_status' => 'Ubah Status Cuti',
            'admin.cuti.destroy' => 'Hapus Permohonan Cuti',
            'admin.wfh.index' => 'Pengajuan WFH',
            'admin.wfh.create' => 'Tambah WFH',
            'admin.unknown_uids.index' => 'UID Tidak Terdaftar',
            'user.index' => 'Dashboard ',
            'user.cuti.index' => 'Riwayat Cuti Saya',
            'user.cuti.create' => 'Ajukan Cuti Baru',
            'user.cuti.store' => 'Simpan Permohonan Cuti',
            'user.catatan.catatanuser' => 'Catatan Harian',
            'user.catatan.store' => 'Simpan Catatan Harian',
            'miniadmin.index' => 'Mini Admin Dashboard',
                default => 'SISTEM INFORMASI MONITORING',
            };
    
            // Kirim page title ke view
            $view->with('pageTitle', $pageTitle);
        });
    }
}
