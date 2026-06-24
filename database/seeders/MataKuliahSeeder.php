<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['kode_mk' => '14620083', 'nama_mk' => 'Arsitektur dan Organisasi Komputer', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '000802',   'nama_mk' => 'Bahasa Indonesia', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14624533', 'nama_mk' => 'Deep Learning', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624452', 'nama_mk' => 'Desain dan Pengembangan Gim', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14624152', 'nama_mk' => 'Etika Teknologi Informasi', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14620063', 'nama_mk' => 'Graf dan Otomata', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624253', 'nama_mk' => 'Grafika Komputer', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624302', 'nama_mk' => 'Internet untuk Segala', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14624313', 'nama_mk' => 'Keamanan Komputer', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624203', 'nama_mk' => 'Kecerdasan Artifisial', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624292', 'nama_mk' => 'Manajemen Basis Data', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14624263', 'nama_mk' => 'Manajemen Jaringan Komputer', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14620094', 'nama_mk' => 'Matematika Komputasi', 'sks' => 4, 'kelas' => 'A'],
            ['kode_mk' => '14624272', 'nama_mk' => 'Pemelajaran Mesin', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14624372', 'nama_mk' => 'Pemrograman Gim', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14620074', 'nama_mk' => 'Pemrograman Berorientasi Objek Fungsional', 'sks' => 4, 'kelas' => 'A'],
            ['kode_mk' => '14624283', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624543', 'nama_mk' => 'Robotika', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624183', 'nama_mk' => 'Sistem Basis Data', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624232', 'nama_mk' => 'Sistem Jaringan Komputer', 'sks' => 2, 'kelas' => 'A'],
            ['kode_mk' => '14620053', 'nama_mk' => 'Sistem Operasi', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624193', 'nama_mk' => 'Sistem Tertanam', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14624173', 'nama_mk' => 'Statistika dan Probabilitas', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14620103', 'nama_mk' => 'Struktur Data dan Algoritma', 'sks' => 3, 'kelas' => 'A'],
            ['kode_mk' => '14620034', 'nama_mk' => 'Dasar-Dasar Pemrograman', 'sks' => 4, 'kelas' => 'A'],
            ['kode_mk' => '000102',   'nama_mk' => 'Pendidikan Pancasila', 'sks' => 2, 'kelas' => 'A'],
        ];

        foreach ($data as $val) {
            DB::table('mata_kuliahs')->updateOrInsert(
                ['kode_mk' => $val['kode_mk']], // Sesuai kolom di migrasi
                [
                    'nama_mk' => $val['nama_mk'], // Sesuai kolom di migrasi
                    'sks'     => $val['sks'],
                    'kelas'   => $val['kelas'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}