<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\Admin\DokterController as AdminDokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Admin\PasienController as AdminPasienController;
use App\Http\Controllers\Admin\ObatController as AdminObatController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;


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

    // Manajemen Jadwal (explicit routes to avoid conflict with export)
    Route::get('/dokter/jadwal', [\App\Http\Controllers\Dokter\JadwalPeriksaController::class, 'index'])->name('dokter.jadwal.index');
    Route::get('/dokter/jadwal/export', [DokterController::class, 'exportJadwal'])->name('dokter.jadwal.export');
    Route::get('/dokter/jadwal/create', [\App\Http\Controllers\Dokter\JadwalPeriksaController::class, 'create'])->name('dokter.jadwal.create');
    Route::post('/dokter/jadwal', [\App\Http\Controllers\Dokter\JadwalPeriksaController::class, 'store'])->name('dokter.jadwal.store');
    Route::get('/dokter/jadwal/{jadwal}/edit', [\App\Http\Controllers\Dokter\JadwalPeriksaController::class, 'edit'])->name('dokter.jadwal.edit');
    Route::put('/dokter/jadwal/{jadwal}', [\App\Http\Controllers\Dokter\JadwalPeriksaController::class, 'update'])->name('dokter.jadwal.update');
    Route::delete('/dokter/jadwal/{jadwal}', [\App\Http\Controllers\Dokter\JadwalPeriksaController::class, 'destroy'])->name('dokter.jadwal.destroy');


    // Riwayat Pasien
    Route::get('/dokter/riwayat', [\App\Http\Controllers\Dokter\RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/dokter/riwayat/{id}', [\App\Http\Controllers\Dokter\RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
    Route::get('/dokter/riwayat-export/export', [DokterController::class, 'exportRiwayat'])->name('dokter.riwayat.export');



});

Route::middleware(['auth', 'role:pasien'])->group(function () {
    Route::get('/pasien', function () {
        return redirect()->route('pasien.dashboard');
    });
    Route::get('/pasien/dashboard', [PasienController::class, 'dashboard'])->name('pasien.dashboard');
    Route::get('/pasien/dashboard/live-queue', [PasienController::class, 'getLiveQueueStatus'])->name('pasien.dashboard.live_queue');
    
    // Riwayat
    Route::get('/pasien/riwayat', [PasienController::class, 'riwayat'])->name('pasien.riwayat.index');
    Route::get('/pasien/riwayat/{id}', [PasienController::class, 'riwayatDetail'])->name('pasien.riwayat.detail');

    // Pembayaran
    Route::get('/pasien/pembayaran', [PasienController::class, 'pembayaran'])->name('pasien.pembayaran.index');
    Route::post('/pasien/pembayaran/{id}/upload', [PasienController::class, 'storePembayaran'])->name('pasien.pembayaran.store');

    Route::get('/pasien/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/pasien/daftar', [PasienPoliController::class, 'submit'])->name('pasien.daftar.submit');
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

    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::post('/pembayaran/{id}/verifikasi', [AdminPembayaranController::class, 'verifikasi'])->name('admin.pembayaran.verifikasi');
});
