<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AnnouncementController; 
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AbsenDosenController as AdminAbsenDosenController;
use App\Http\Controllers\Admin\KelolaPresensiController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Admin\JadwalKuliahController; 
use App\Http\Controllers\Dosen\AbsenDosenController;
use App\Http\Controllers\Admin\PengampuMataKuliahController; 
use App\Http\Controllers\Admin\NilaiMahasiswaController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::resource('dosens', DosenController::class);
    Route::resource('mataKuliahs', MataKuliahController::class);
    Route::resource('mahasiswas', AdminMahasiswaController::class);
    Route::resource('jadwalKuliahs', JadwalKuliahController::class);
    Route::get('/mahasiswas/{mahasiswa}/kelas', [NilaiMahasiswaController::class, 'getMahasiswaKelas']);
    Route::get('/pengampu/dosen', [NilaiMahasiswaController::class, 'getDosenPengampu']);
    Route::resource('pengampuMataKuliah', PengampuMataKuliahController::class)->except(['show']); 
    Route::resource('nilaiMahasiswas', NilaiMahasiswaController::class)->except(['show']);
    Route::resource('absenDosens', AdminAbsenDosenController::class);
    Route::resource('kelola-presensi', KelolaPresensiController::class)->names('kelolaPresensi');
    Route::get('/settings/change-password', [AdminController::class, 'showChangePasswordForm'])->name('changePasswordForm');
    Route::post('/settings/change-password', [AdminController::class, 'changePassword'])->name('changePassword');

    Route::post('mahasiswas/import', [AdminMahasiswaController::class, 'import'])->name('mahasiswas.import');
    Route::get('mahasiswas/import/template', [AdminMahasiswaController::class, 'downloadTemplate'])->name('mahasiswas.import.template');
});

// Route Dosen
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->as('dosen.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal-mengajar', [DosenDashboardController::class, 'lihatJadwalMengajar'])->name('lihatJadwalMengajar');
    Route::get('/kelola-nilai', [DosenDashboardController::class, 'kelolaNilaiMahasiswa'])->name('kelolaNilaiMahasiswa');
    Route::get('/kelola-nilai/create', [DosenDashboardController::class, 'createNilai'])->name('kelolaNilaiMahasiswa.create');
    Route::post('/kelola-nilai', [DosenDashboardController::class, 'storeNilai'])->name('kelolaNilaiMahasiswa.store');
    Route::get('/daftar-mahasiswa', [DosenDashboardController::class, 'lihatDaftarMahasiswa'])->name('lihatDaftarMahasiswa');
    Route::post('/presensi-harian/toggle', [DosenDashboardController::class, 'togglePresensiHarian'])->name('togglePresensiHarian');
    Route::get('/absen', [AbsenDosenController::class, 'index'])->name('absen.index');
    Route::post('/absen/check-in', [AbsenDosenController::class, 'checkIn'])->name('absen.checkIn');
    Route::post('/absen/check-out', [AbsenDosenController::class, 'checkOut'])->name('absen.checkOut');

    Route::get('/settings/change-password', [DosenDashboardController::class, 'showChangePasswordForm'])->name('change_password_form');
    Route::post('/settings/change-password', [DosenDashboardController::class, 'changePassword'])->name('change_password');
});

// Route Mahasiswa
// Route Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->as('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal-kuliah', [MahasiswaController::class, 'lihatJadwalKuliah'])->name('lihatJadwalKuliah');
    Route::get('/khs', [MahasiswaController::class, 'lihatKHS'])->name('lihatKHS');
    
    // Grouping KRS untuk merapikan rute
    Route::get('/krs', [MahasiswaController::class, 'lihatKRS'])->name('lihatKRS');
    Route::post('/krs', [MahasiswaController::class, 'storeKrs'])->name('krs.store');
    Route::delete('/krs/{id}', [MahasiswaController::class, 'destroyKrs'])->name('krs.destroy');
    
    // TAMBAHKAN DUA BARIS INI UNTUK FITUR EXPORT
    Route::get('/krs/export-pdf', [MahasiswaController::class, 'exportPdf'])->name('krs.exportPdf');
    Route::get('/krs/export-xls', [MahasiswaController::class, 'exportXls'])->name('krs.exportXls');

    Route::get('/rangkuman-nilai', [MahasiswaController::class, 'lihatRangkumanNilai'])->name('lihatRangkumanNilai');
    Route::get('/presensi', [MahasiswaController::class, 'showPresensiForm'])->name('presensi.form');
    Route::post('/presensi', [MahasiswaController::class, 'submitPresensi'])->name('presensi.submit');
    Route::get('/detail-pribadi', [MahasiswaController::class, 'lihatDetailPribadi'])->name('lihatDetailPribadi');
    Route::get('/profil', [MahasiswaController::class, 'lihatDetailPribadi'])->name('profil.detail');
    Route::get('/profil/edit', [MahasiswaController::class, 'editDetailPribadi'])->name('profil.edit');
    Route::put('/profil', [MahasiswaController::class, 'updateDetailPribadi'])->name('profil.update');
    Route::get('/settings/change-password', [MahasiswaController::class, 'showChangePasswordForm'])->name('change_password_form');
    Route::post('/settings/change-password', [MahasiswaController::class, 'changePassword'])->name('change_password');
});