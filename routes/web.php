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
use App\Http\Controllers\Admin\WfhController;
use App\Http\Controllers\Admin\DeviceTokenController;
use App\Http\Controllers\Admin\UnknownUidController;


use App\Http\Controllers\User\UserCutiController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserDatasenController;
use App\Http\Controllers\User\UserWfhController;


use App\Http\Controllers\Miniadmin\MiniAdminController;
use App\Http\Controllers\Miniadmin\MiniadminCutiController;
use App\Http\Controllers\Miniadmin\MiniadminWfhController;
use App\Http\Controllers\Miniadmin\MiniadminDatasenController;


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

// ROUTE UNTUK LOGIN ADMIN
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ADMIN
Route::middleware(['auth:admin', 'check.admin'])->group(function () {
    // Halaman dashboard admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Data Pegawai
    Route::resource('admin/pegawai', PegawaiController::class)->names([
        'index' => 'admin.pegawai.index',
        'create' => 'admin.pegawai.create',
        'store' => 'admin.pegawai.store',
        'edit' => 'admin.pegawai.edit',
        'update' => 'admin.pegawai.update',
        'destroy' => 'admin.pegawai.destroy',
    ]);
    Route::get('/pegawai/{pegawai}', [PegawaiController::class, 'show'])->name('admin.pegawai.show');

    // Data Absen
    Route::get('/admin/data_absen', [DatasenController::class, 'index'])->name('admin.data_absen.index');
    Route::post('/admin/data_absen/store', [DatasenController::class, 'store'])->name('admin.data_absen.store');
    Route::get('/admin/data_absen/catatan', [DatasenController::class, 'catatan'])->name('admin.data_absen.catatan');
    Route::get('/admin/data_absen/preview-pdf/{id}', [DatasenController::class, 'downloadPDF'])
        ->name('admin.data_absen.preview-pdf');
    Route::get('/admin/data_absen/download-pdf/{id}', [DatasenController::class, 'downloadPDF'])
        ->defaults('mode', 'download') // Set mode default ke 'download'
        ->name('admin.data_absen.download-pdf');
    Route::get('/admin/data_absen/download-csv/{id}', [DatasenController::class, 'downloadCSV'])->name('admin.data_absen.download-csv');
    Route::get('/admin/data-absen/download-all-pdf', [DatasenController::class, 'downloadAllPDF'])->name('admin.data_absen.download-all-pdf');
    Route::get('/admin/data-absen/download-all-excel', [DatasenController::class, 'downloadAllExcel'])->name('admin.data_absen.download-all-excel');

    // Data Cuti
    Route::get('/admin/cuti', [CutiController::class, 'index'])->name('admin.cuti.index');
    Route::get('admin/cuti/create', [CutiController::class, 'create'])->name('admin.cuti.create');
    Route::post('admin/cuti/store', [CutiController::class, 'store'])->name('admin.cuti.store');
    Route::put('/admin/cuti/{id}/update-status', [CutiController::class, 'updateStatus'])->name('admin.cuti.update_status');
    Route::delete('/admin/cuti/{id}', [CutiController::class, 'destroy'])->name('admin.cuti.destroy');

    // Data WFH
    Route::get('/admin/wfh', [WfhController::class, 'index'])->name('admin.wfh.index'); // Menampilkan daftar pengajuan WFH
    Route::get('admin/wfh/create', [WfhController::class, 'create'])->name('admin.wfh.create'); // Menampilkan form pengajuan WFH
    Route::post('admin/wfh/store', [WfhController::class, 'store'])->name('admin.wfh.store'); // Menyimpan pengajuan WFH baru
    Route::get('admin/wfh/{id}/show', [WfhController::class, 'show'])->name('admin.wfh.show'); // Menampilkan detail pengajuan WFH
    Route::put('admin/wfh/{id}/update', [WfhController::class, 'update'])->name('admin.wfh.update'); // Memperbarui pengajuan WFH
    Route::delete('admin/wfh/{id}/destroy', [WfhController::class, 'destroy'])->name('admin.wfh.destroy'); // Menghapus pengajuan WFH

    Route::post('/store-device-token', [DeviceTokenController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/admin/wfh/{id}/absen-masuk', [WfhController::class, 'absenMasuk'])
        ->middleware('auth:admin')
        ->name('admin.wfh.absen.masuk');

    Route::post('/admin/wfh/{id}/absen-pulang', [WfhController::class, 'absenPulang'])
        ->middleware('auth:admin')
        ->name('admin.wfh.absen.pulang');

    Route::get('/admin/unknown-uids', [UnknownUidController::class, 'index'])->name('admin.unknown_uids.index');
    Route::delete('/admin/unknown-uids/{id}', [UnknownUidController::class, 'destroy'])->name('admin.unknown_uids.destroy');
});

// USER
Route::middleware(['auth', 'check.user'])->group(function () {
    // Rute-rute user
    Route::get('/user', [UserController::class, 'index'])->name('user.index');

    // Data Cuti User
    Route::get('/user/cuti', [UserCutiController::class, 'index'])->name('user.cuti.index');
    Route::get('/user/create', [UserCutiController::class, 'create'])->name('user.cuti.create');
    Route::post('user/cuti/store', [UserCutiController::class, 'store'])->name('user.cuti.store');

    // Catatan User
    Route::get('/user/catatan/catatanuser', [UserDatasenController::class, 'catatanuser'])->name('user.catatan.catatanuser');
    Route::post('/user/catatan/store', [UserDatasenController::class, 'store'])->name('user.catatan.store');

    // Data WFH User
    Route::prefix('user/wfh')->name('user.wfh.')->group(function () {
        Route::get('/', [UserWfhController::class, 'index'])->name('index');
        Route::get('/create', [UserWfhController::class, 'create'])->name('create');
        Route::post('/store', [UserWfhController::class, 'store'])->name('store'); // Simpan pengajuan WFH
        Route::get('/{id}/show', [UserWfhController::class, 'show'])->name('show'); // Tampilkan detail pengajuan WFH
        Route::get('/{id}/edit', [UserWfhController::class, 'edit'])->name('edit'); // Form edit pengajuan WFH
        Route::put('/{id}/update', [UserWfhController::class, 'update'])->name('update'); // Perbarui pengajuan WFH
        Route::delete('/{id}/destroy', [UserWfhController::class, 'destroy'])->name('destroy'); // Hapus pengajuan WFH

        // Route untuk absen WFH
        Route::post('/{id}/absen-masuk', [UserWfhController::class, 'absenMasuk'])->name('absen.masuk');
        Route::post('/{id}/absen-pulang', [UserWfhController::class, 'absenPulang'])->name('absen.pulang');
    });

    // Data Absen User
    Route::get('/user/data_absen', [UserDatasenController::class, 'index'])->name('user.data_absen.index');
});

// WFH
Route::middleware(['auth'])->group(function () {
    // Route untuk absen WFH/WFA
    Route::post('/absen-wfh', [DatasenController::class, 'wfhAbsen'])->name('absen.wfh.store');
});


Route::middleware(['auth:mini_admin'])->group(function () {
    Route::get('/mini-admin', [MiniAdminController::class, 'index'])->name('miniadmin.index');
    Route::get('/mini-admin/cuti', [MiniadminCutiController::class, 'index'])->name('miniadmin.cuti.index');
    Route::get('/mini-admin/wfh', [MiniadminWfhController::class, 'index'])->name('miniadmin.wfh.index');


    Route::get('/mini-admin/riwayatabsen', [MiniadminDatasenController::class, 'index'])->name('mini_admin.riwayatabsen.index');
    // Tambahkan route lain untuk miniadmin di sini
});
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
