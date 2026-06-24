<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\PengampuMataKuliah;
use App\Models\NilaiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // WAJIB ADA

class DashboardController extends Controller
{
    public function mahasiswaDashboard()
    {
        return view('mahasiswa.dashboard');
    }

    public function dosenDashboard()
    {
        return view('dosen.dashboard');
    }

    public function adminDashboard()
    {
        // Data untuk kartu statistik
        $totalUsers = User::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalMataKuliah = MataKuliah::count();

        // --- LOGIKA GRAFIK JURUSAN ---
        $jurusanData = Mahasiswa::select('jurusan', DB::raw('count(*) as total'))
            ->whereNotNull('jurusan')
            ->groupBy('jurusan')
            ->get();

        $labels = $jurusanData->pluck('jurusan');
        $totals = $jurusanData->pluck('total');

        // Data untuk tabel ringkasan
        $dataSummary = [
            'Dosen' => [
                'count' => Dosen::count(),
                'last_updated' => Dosen::max('updated_at')
            ],
            'Mata Kuliah' => [
                'count' => MataKuliah::count(),
                'last_updated' => MataKuliah::max('updated_at')
            ],
            'Mahasiswa' => [
                'count' => Mahasiswa::count(),
                'last_updated' => Mahasiswa::max('updated_at')
            ],
            'Jadwal Mengajar' => [
                'count' => PengampuMataKuliah::count(),
                'last_updated' => PengampuMataKuliah::max('updated_at')
            ],
            'Nilai Mahasiswa' => [
                'count' => NilaiMahasiswa::count(),
                'last_updated' => NilaiMahasiswa::max('updated_at')
            ],
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalMahasiswa',
            'totalDosen',
            'totalMataKuliah',
            'dataSummary',
            'labels',
            'totals'
        ));
    }

    // ... Method lainnya tetap sama ...
    public function kelolaJadwal() { return view('admin.kelola_jadwal'); }
    public function kelolaDataDosen() { return view('admin.kelola_data_dosen'); }
}