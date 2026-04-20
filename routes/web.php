<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\Admin\DokterController as AdminDokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Admin\PasienController as AdminPasienController;
use App\Http\Controllers\Admin\ObatController as AdminObatController;
use App\Http\Controllers\PoliController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:dokter'])->group(function () {
    Route::get('/dokter/dashboard', [DokterController::class, 'dashboard'])->name('dokter.dashboard');
    
    // Periksa Routes
    Route::get('/dokter/periksa', [App\Http\Controllers\DokterPeriksaController::class, 'index'])->name('dokter.periksa.index');
    Route::get('/dokter/periksa/{id_daftar_poli}/create', [App\Http\Controllers\DokterPeriksaController::class, 'create'])->name('dokter.periksa.create');
    Route::post('/dokter/periksa/{id_daftar_poli}', [App\Http\Controllers\DokterPeriksaController::class, 'store'])->name('dokter.periksa.store');

    // Excel Export Jadwal
    Route::get('/dokter/jadwal/export', [DokterController::class, 'exportJadwal'])->name('dokter.jadwal.export');

    // Riwayat Pasien
    Route::get('/dokter/riwayat', [DokterController::class, 'riwayatPasien'])->name('dokter.riwayat.index');
    Route::get('/dokter/riwayat/export', [DokterController::class, 'exportRiwayat'])->name('dokter.riwayat.export');
});

Route::middleware(['auth', 'role:pasien'])->group(function () {
    Route::get('/pasien', function () {
        return redirect()->route('pasien.dashboard');
    });
    Route::get('/pasien/dashboard', [PasienController::class, 'dashboard'])->name('pasien.dashboard');
    Route::get('/pasien/dashboard/live-queue', [PasienController::class, 'getLiveQueueStatus'])->name('pasien.dashboard.live_queue');
    
    Route::get('/pasien/daftar-poli', [PasienController::class, 'createDaftarPoli'])->name('pasien.daftar-poli.create');
    Route::post('/pasien/daftar-poli', [PasienController::class, 'storeDaftarPoli'])->name('pasien.daftar-poli.store');
    
    // Riwayat
    Route::get('/pasien/riwayat', [PasienController::class, 'riwayat'])->name('pasien.riwayat.index');
    Route::get('/pasien/riwayat/{id}', [PasienController::class, 'riwayatDetail'])->name('pasien.riwayat.detail');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/dokter/export', [AdminDokterController::class, 'export'])->name('dokter.export');
    Route::get('/pasien/export', [AdminPasienController::class, 'export'])->name('pasien.export');
    Route::get('/obat/export', [AdminObatController::class, 'export'])->name('obat.export');

    Route::resource('polis', PoliController::class);
    Route::resource('dokter', AdminDokterController::class);
    Route::resource('pasien', AdminPasienController::class);
    Route::resource('obat', AdminObatController::class);
});
