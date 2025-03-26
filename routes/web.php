<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WorkScheduleController;

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

// Route untuk admin dashboard dan user management
Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin')->name('admin.index');
// Menampilkan halaman manage user
Route::get('admin/manage_user', [AdminController::class, 'user'])->name('admin.manage_user.index');
// Aksi untuk tambah user (manage user)
Route::resource('users', AdminController::class);
// Aksi untuk edit user (manage user)
Route::get('users/{user}/edit', [AdminController::class, 'edit'])->name('users.edit');
// Menghapus user (manage user)
Route::delete('users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');



// Route untuk halaman absensi admin (menampilkan semua data absensi)
Route::get('admin/attendance', [AdminController::class, 'attendance'])->name('admin.attendance.index');
// Route untuk menyimpan absensi (dari halaman scan RFID untuk pengguna dengan role 'tap_rfid')
Route::post('admin/attendance', [AttendanceController::class, 'store'])->name('admin.attendance.store');
// Route untuk halaman scan RFID bagi pengguna dengan role 'tap_rfid'
Route::middleware(['auth', 'role:tap_rfid'])->get('/user/tap_rfid', [AttendanceController::class, 'scanRfid'])->name('user.tap_rfid');
// Route untuk menyimpan absensi menggunakan RFID oleh pengguna dengan role 'tap_rfid'
Route::post('/user/attendance', [AttendanceController::class, 'store'])->name('user.attendance.store');



// Route untuk halaman scan RFID bagi pengguna dengan role 'tap_rfid'
Route::middleware(['auth', 'role:tap_rfid'])->get('/tap_rfid', [AttendanceController::class, 'showScanRfidPage'])->name('tap_rfid.index');
// Route untuk menyimpan absensi menggunakan RFID
Route::post('/tap_rfid/attendance', [AttendanceController::class, 'store'])->name('tap_rfid.attendance.store');
// Kartu RFID







// Route untuk work schedule
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Route untuk mengedit jadwal kerja
    Route::get('admin/work_schedule/edit/{id}', [WorkScheduleController::class, 'edit'])->name('admin.work_schedule.edit');
    Route::put('admin/work_schedule/update/{id}', [WorkScheduleController::class, 'update'])->name('admin.work_schedule.update');

    // Menampilkan form untuk menambahkan jadwal kerja
    Route::get('work_schedule/create', [WorkScheduleController::class, 'create'])->name('admin.work_schedule.create');

    // Menyimpan jadwal kerja
    Route::post('work_schedule/store', [WorkScheduleController::class, 'store'])->name('admin.work_schedule.store');

    // Menampilkan semua jadwal kerja
    Route::get('work_schedule', [WorkScheduleController::class, 'index'])->name('admin.work_schedule.index');

    // Menampilkan form untuk mengedit jadwal kerja
    Route::get('work_schedule/edit/{id}', [WorkScheduleController::class, 'edit'])->name('admin.work_schedule.edit');

    // Menyimpan perubahan jadwal kerja
    Route::put('work_schedule/update/{id}', [WorkScheduleController::class, 'update'])->name('admin.work_schedule.update');

    // Menghapus jadwal kerja
    Route::delete('work_schedule/delete/{id}', [WorkScheduleController::class, 'destroy'])->name('admin.work_schedule.delete');
});

// Sisi User
Route::get('/user', [UserController::class, 'index'])->middleware('role:user')->name('user.index'); // Dashboard user
// Route untuk menampilkan aktivitas absensi user
Route::get('/user/attendance', [UserController::class, 'attendanceHistory'])->name('user.attendance');


// Route untuk login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

