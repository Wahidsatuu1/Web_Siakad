<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\JadwalKuliah;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AkademikSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan tabel (Urutan penting karena Foreign Key)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JadwalKuliah::truncate();
        Krs::truncate();
        MataKuliah::truncate(); // Kita segarkan data mata kuliah
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Data Dosen & User Dosen
        $dataDosen = [
            ['nama' => 'Dr. Aris Sudaryanto, M.Kom', 'email' => 'aris@univ.ac.id'],
            ['nama' => 'Siti Aminah, S.T., M.T.', 'email' => 'siti@univ.ac.id'],
            ['nama' => 'Budi Raharjo, M.Cs', 'email' => 'budi@univ.ac.id'],
        ];

        $dosenIds = [];
        foreach ($dataDosen as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                ['name' => $d['nama'], 'password' => Hash::make('password'), 'role' => 'dosen']
            );
            $dsn = Dosen::firstOrCreate(
                ['user_id' => $user->id],
                ['nama' => $user->name, 'email' => $user->email]
            );
            $dosenIds[] = $dsn->id;
        }

        // 3. Data Mata Kuliah
        $dataMK = [
            ['kode' => 'MK001', 'nama' => 'Pemrograman Web Framework', 'sks' => 3],
            ['kode' => 'MK002', 'nama' => 'Basis Data Lanjut', 'sks' => 3],
            ['kode' => 'MK003', 'nama' => 'Kecerdasan Buatan', 'sks' => 3],
            ['kode' => 'MK004', 'nama' => 'Jaringan Komputer', 'sks' => 2],
            ['kode' => 'MK005', 'nama' => 'Etika Profesi', 'sks' => 2],
        ];

        $mkObjs = [];
        foreach ($dataMK as $m) {
            $mkObjs[] = MataKuliah::create([
                'kode_mk' => $m['kode'],
                'nama_mk' => $m['nama'],
                'sks' => $m['sks'],
                'kelas' => 'R'
            ]);
        }

        // 4. Data Jadwal Kuliah (Admin Side)
        $jadwal = [
            ['mk' => $mkObjs[0]->id, 'dsn' => $dosenIds[0], 'hari' => 'Senin', 'mulai' => '08:00', 'selesai' => '10:30', 'ruang' => 'Lab 1'],
            ['mk' => $mkObjs[1]->id, 'dsn' => $dosenIds[1], 'hari' => 'Selasa', 'mulai' => '10:00', 'selesai' => '12:30', 'ruang' => 'R.302'],
            ['mk' => $mkObjs[2]->id, 'dsn' => $dosenIds[2], 'hari' => 'Rabu', 'mulai' => '13:00', 'selesai' => '15:30', 'ruang' => 'R.405'],
            ['mk' => $mkObjs[3]->id, 'dsn' => $dosenIds[0], 'hari' => 'Kamis', 'mulai' => '08:00', 'selesai' => '10:00', 'ruang' => 'Lab 2'],
            ['mk' => $mkObjs[4]->id, 'dsn' => $dosenIds[1], 'hari' => 'Jumat', 'mulai' => '09:00', 'selesai' => '11:00', 'ruang' => 'R.101'],
        ];

        foreach ($jadwal as $j) {
            JadwalKuliah::create([
                'mata_kuliah_id' => $j['mk'],
                'dosen_id'       => $j['dsn'],
                'hari'           => $j['hari'],
                'jam_mulai'      => $j['mulai'],
                'jam_selesai'    => $j['selesai'],
                'ruangan'        => $j['ruang'],
                'kelas'          => 'R'
            ]);
        }

        // 5. Data KRS Mahasiswa (Zurais)
        $userMhs = User::where('email', 'zurais@gmail.com')->first();
        if ($userMhs && $userMhs->mahasiswa) {
            $mhs = $userMhs->mahasiswa;
            
            // Masukkan 3 mata kuliah pertama ke KRS mahasiswa
            for ($i = 0; $i < 3; $i++) {
                Krs::create([
                    'nbi'          => $mhs->nim, 
                    'tahun_ajaran' => '2025/2026',
                    'semester'     => 'Ganjil',
                    'kode_mk'      => $mkObjs[$i]->kode_mk,
                    'kelas'        => 'R',
                    'sks'          => $mkObjs[$i]->sks,
                    'kode_dsn'     => $dosenIds[$i]
                ]);
            }
        }
    }
}