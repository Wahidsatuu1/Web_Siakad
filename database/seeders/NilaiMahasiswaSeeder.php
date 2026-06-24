<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Support\Facades\DB;

class NilaiMahasiswaSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil data pendukung yang sudah ada
        $mhs = Mahasiswa::where('nim', '1462100001')->first();
        $mkList = MataKuliah::all();
        $dosen = Dosen::first();

        if (!$mhs || $mkList->isEmpty()) {
            return;
        }

        // 2. Data Nilai yang akan dimasukkan
        $dataNilai = [
            [
                'mk_id' => $mkList[0]->id,
                'tugas' => 85, 'uts' => 80, 'uas' => 90, 'angka' => 86.00, 'huruf' => 'A'
            ],
            [
                'mk_id' => $mkList[1]->id,
                'tugas' => 75, 'uts' => 70, 'uas' => 80, 'angka' => 76.50, 'huruf' => 'B+'
            ],
            [
                'mk_id' => $mkList[2]->id,
                'tugas' => 90, 'uts' => 85, 'uas' => 85, 'angka' => 86.50, 'huruf' => 'A'
            ],
        ];

        foreach ($dataNilai as $n) {
            DB::table('nilai_mahasiswas')->updateOrInsert(
                [
                    'mahasiswa_id'   => $mhs->id,
                    'mata_kuliah_id' => $n['mk_id'],
                ],
                [
                    'dosen_id'    => $dosen->id ?? null,
                    'kelas'       => 'R',
                    'kehadiran'   => 14,
                    'nilai_tugas' => $n['tugas'],
                    'nilai_uts'   => $n['uts'],
                    'nilai_uas'   => $n['uas'],
                    'nilai_angka' => $n['angka'],
                    'nilai_huruf' => $n['huruf'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}