<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\{
    DashboardController,
    KecamatanController,
    DesaController,
    JembatanController,
    KomponenController,
    PenilaianController,
    LaporanController,
    PerhitunganController,
    UserController,
    ProfileController,
};

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

// Route untuk tamu
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.submit');
});

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route untuk dashboard (bisa diakses admin dan petugas)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/alternatif', [DashboardController::class, 'alternatif'])->name('alternatif');

    // Route untuk admin
    Route::middleware(['role:admin'])->group(function () {
        // Profile Admin
        Route::get('/profile', [ProfileController::class, 'show'])->name('pageadmin.profile.show');
        Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('pageadmin.profile.update');

        // Kecamatan & Desa
        Route::get('/fetch-kecamatan', [KecamatanController::class, 'fetchAndStoreKecamatan'])->name('fetch-kecamatan');
        Route::get('/fetch-desa', [DesaController::class, 'fetchAndStoreDesa'])->name('fetch-desa');
        Route::get('/kecamatan', [KecamatanController::class, 'index'])->name('kecamatan');
        Route::post('/kecamatan', [KecamatanController::class, 'store'])->name('kecamatan.store');
        Route::put('/kecamatan/{id}', [KecamatanController::class, 'update'])->name('kecamatan.update');
        Route::delete('/kecamatan/{id}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');

        Route::get('/desa', [DesaController::class, 'index'])->name('desa');
        Route::post('/desa', [DesaController::class, 'store'])->name('desa.store');
        Route::put('/desa/{id}', [DesaController::class, 'update'])->name('desa.update');
        Route::delete('/desa/{id}', [DesaController::class, 'destroy'])->name('desa.destroy');
        Route::get('/desas/{kecamatanKode}', [JembatanController::class, 'getDesasByKecamatan'])->name('desas.get');

        // Jembatan
        Route::get('/jembatan', [JembatanController::class, 'index'])->name('jembatan');
        // Route::get('/jembatan/create', [JembatanController::class, 'create'])->name('jembatan.create');
        Route::post('/jembatan', [JembatanController::class, 'store'])->name('jembatan.store');
        Route::get('/jembatan/{kode}/edit', [JembatanController::class, 'edit'])->name('jembatan.edit');
        Route::put('/jembatan/{kode}', [JembatanController::class, 'update'])->name('jembatan.update');
        Route::delete('/jembatan/{kode}', [JembatanController::class, 'destroy'])->name('jembatan.destroy');

        // Komponen
        Route::get('/komponen', [KomponenController::class, 'index'])->name('komponen');
        // Route::get('/komponen/create', [KomponenController::class, 'create'])->name('komponen.create');
        Route::post('/komponen', [KomponenController::class, 'store'])->name('komponen.store');
        Route::get('/komponen/{id}/edit', [KomponenController::class, 'edit'])->name('komponen.edit');
        Route::put('/komponen/{id}', [KomponenController::class, 'update'])->name('komponen.update');
        Route::delete('/komponen/{id}', [KomponenController::class, 'destroy'])->name('komponen.destroy');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/cetak/{tahun}', [LaporanController::class, 'cetak'])->name('laporan.cetak');

        // Perhitungan
        Route::get('/perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan');
        Route::post('/perhitungan/simpan', [PerhitunganController::class, 'simpan'])->name('perhitungan.simpan');

        // User Routes
        Route::get('/user', [UserController::class, 'index'])->name('user');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    // Route untuk petugas dan admin
    Route::middleware(['role:admin,petugas'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/alternatif', [DashboardController::class, 'alternatif'])->name('alternatif');

        // Penilaian
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
        Route::get('/penilaian/create', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::get('/penilaian/{jembatan_kode}/{tahun}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::put('/penilaian/{jembatan_kode}/{tahun}', [PenilaianController::class, 'update'])->name('penilaian.update');
        Route::delete('/penilaian/{jembatan_kode}/{tahun}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');
        // Profile
        Route::get('/profile', [App\Http\Controllers\admin\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile/{id}', [App\Http\Controllers\admin\ProfileController::class, 'update'])->name('profile.update');
    });
});


// Route::get('/kecamatan', [KecamatanController::class, 'index'])->name('kecamatan');
// Route::get('/desa', [DesaController::class, 'index'])->name('desa');

// Route::get('/perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan');
// Route::get('/perhitungan/show', [PerhitunganController::class, 'show'])->name('perhitungan.show');

// Route::get('/jembatan', [JembatanController::class, 'index'])->name('jembatan');
// Route::get('/jembatan/create', [JembatanController::class, 'create'])->name('jembatan.create');
// Route::post('/jembatan', [JembatanController::class, 'store'])->name('jembatan.store');
// Route::get('/jembatan/{id}/edit', [JembatanController::class, 'edit'])->name('jembatan.edit');
// Route::put('/jembatan/{id}', [JembatanController::class, 'update'])->name('jembatan.update');
// Route::delete('/jembatan/{id}', [JembatanController::class, 'destroy'])->name('jembatan.destroy');
// Route::get('/desas/{kecamatanKode}', [JembatanController::class, 'getDesasByKecamatan'])->name('desas.get');

// Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria');
// Route::get('/kriteria/create', [KriteriaController::class, 'create'])->name('kriteria.create');
// Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
// Route::get('/kriteria/{id}/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
// Route::put('/kriteria/{id}', [KriteriaController::class, 'update'])->name('kriteria.update');
// Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');

// Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
// Route::get('/penilaian/create', [PenilaianController::class, 'create'])->name('penilaian.create');
// Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
// Route::get('/penilaian/{jembatan_kode}/{tahun}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
// Route::put('/penilaian/{jembatan_kode}/{tahun}', [PenilaianController::class, 'update'])->name('penilaian.update');
// Route::delete('/penilaian/{id}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

// Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
// Route::get('/laporan/cetak/{tahun}', [LaporanController::class, 'cetak'])->name('laporan.cetak');

Route::post('/upload-temp', [UploadController::class, 'uploadTemp'])->name('upload.temp');
