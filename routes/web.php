<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CutiController;
use App\Http\Controllers\Admin\DatasenController;
use App\Http\Controllers\Admin\PegawaiController;

use App\Http\Controllers\User\UserCutiController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\DatasenController as UserDatasenController;
use Illuminate\Foundation\Auth\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// ROUTE UNTUK LOGIN
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ROUTE UNTUK MANAGEMENT DASHBOARD ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route untuk halaman dashboard admin
    Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin')->name('admin.index');

    // Data Pegawai
    Route::resource('admin/pegawai', PegawaiController::class)->names([
        'index' => 'admin.pegawai.index',
        'create' => 'admin.pegawai.create',
        'store' => 'admin.pegawai.store',
        'edit' => 'admin.pegawai.edit',
        'update' => 'admin.pegawai.update',
        'destroy' => 'admin.pegawai.destroy',
    ]);
    Route::get('/pegawai/{pegawai}', [PegawaiController::class, 'show']);

    // Data Absen
    Route::get('/admin/data_absen', [DatasenController::class, 'index'])->name('admin.data_absen.index');
    Route::post('/admin/data_absen/store', [DatasenController::class, 'store'])->name('admin.data_absen.store');
 
    // Route untuk preview PDF
    Route::get('/admin/data_absen/preview-pdf/{id}', [DatasenController::class, 'downloadPDF'])
        ->name('admin.data_absen.preview-pdf');

    // Route untuk download PDF
    Route::get('/admin/data_absen/download-pdf/{id}', [DatasenController::class, 'downloadPDF'])
        ->defaults('mode', 'download') // Set mode default ke 'download'
        ->name('admin.data_absen.download-pdf');

    // Route untuk download CSV
    Route::get('/admin/data_absen/download-csv/{id}', [DatasenController::class, 'downloadCSV'])->name('admin.data_absen.download-csv');
});
// ROUTE UNTUK CUTI
Route::middleware(['auth', 'admin'])->group(function () {
    // Halaman daftar cuti
    Route::get('/admin/cuti', [CutiController::class, 'index'])->name('admin.cuti.index');

    // Halaman form pengajuan cuti
    Route::get('admin/cuti/create', [CutiController::class, 'create'])->name('admin.cuti.create');

    // Proses menyimpan data cuti
    Route::post('admin/cuti/store', [CutiController::class, 'store'])->name('admin.cuti.store');

    // Ubah status pengajuan cuti
    Route::put('/admin/cuti/{id}/update-status', [CutiController::class, 'updateStatus'])->name('admin.cuti.update_status');

    // Hapus data cuti
    Route::delete('/admin/cuti/{id}', [CutiController::class, 'destroy'])->name('admin.cuti.destroy');
});

// SISI USER
Route::middleware(['auth', 'role:user'])->group(function () {
    // Dashboard user
    Route::get('/user', [UserController::class, 'index'])->middleware('role:user')->name('user.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/cuti', [UserCutiController::class, 'index'])->name('user.cuti.index');
    Route::get('/user/create', [UserCutiController::class, 'create'])->name('user.cuti.create');
    Route::post('user/cuti/store', [UserCutiController::class, 'store'])->name('user.cuti.store');
});

Route::post('/user/data_absen/store', [UserDatasenController::class, 'store'])->name('user.data_absen.store');

// Route untuk halaman scan RFID bagi pengguna dengan role 'tap_rfid'
// Route::middleware(['auth', 'role:tap_rfid'])->group(function () {
//     // Route untuk halaman scan RFID bagi pengguna dengan role 'tap_rfid'
//     Route::get('/user/tap_rfid', [AttendanceController::class, 'scanRfid'])->name('user.tap_rfid');
    
//     // Route untuk menyimpan absensi menggunakan RFID oleh pengguna dengan role 'tap_rfid'
//     Route::post('/user/attendance', [AttendanceController::class, 'store'])->name('user.attendance.store');
    
//     // Route untuk halaman scan RFID bagi pengguna dengan role 'tap_rfid'
//     Route::get('/tap_rfid', [AttendanceController::class, 'showScanRfidPage'])->name('tap_rfid.index');
    
//     // Route untuk menyimpan absensi menggunakan RFID
//     Route::post('/tap_rfid/attendance', [AttendanceController::class, 'store'])->name('tap_rfid.attendance.store');
// });
