<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Dosen;

class KrsSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil mahasiswa Zurais berdasarkan NIM
        $mhs = Mahasiswa::where('nim', '1462100001')->first();
        
        // Ambil Mata Kuliah dan Dosen pertama sebagai contoh
        $mk = MataKuliah::first();
        $dsn = Dosen::first();

        if ($mhs && $mk && $dsn) {
            DB::table('krs')->insert([
                'nbi'          => $mhs->nim, // Menggunakan NIM mahasiswa
                'tahun_ajaran' => '2025/2026',
                'semester'     => 'Ganjil',
                'kode_mk'      => $mk->kode_mk,
                'kelas'        => 'R',
                'sks'          => $mk->sks,
                'kode_dsn'     => $dsn->id,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}