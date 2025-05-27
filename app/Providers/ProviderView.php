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
                'admin.index' => 'Dashboard',
                'admin.pegawai.index' => 'Daftar Pegawai',
                'admin.pegawai.create' => 'Tambah Pegawai',
                'admin.pegawai.edit' => 'Edit Pegawai',
                'admin.data_absen.index' => 'Data Absen Karyawan',
                default => 'SISTEM INFORMASI MONITORING',
            };
    
            // Kirim page title ke view
            $view->with('pageTitle', $pageTitle);
        });
    }
}
