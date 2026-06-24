<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $dataDosen = [
            "Ir. Sugiono, MT", "Mochammad Sidqon, S.Si, M.Si", "Naufal Abdillah, S.Kom., M.Kom",
            "Ahmad Habib, S.Kom., MM", "Ery Sadewa Yudha W, S.Kom, MM", "Ir. Agus Darwanto, MM",
            "Aris Sudaryanto, S.ST., MT", "Ardy Januantoro, S.Kom., M.MT", "Anton Breva Yunanda, ST., M.MT.",
            "Anang Pramono, S.Kom., MM", "Aidil Primasetya Armin, S.ST., M.T.", 
            "Agus Hermanto, S.Kom, M.MT, ITIL, COBIT, SFC", "Agyl Ardi Rahmadi, S.Kom., M.A",
            "Dwi Harini Sulistyawati, S.ST., MT", "Luvia Friska Narulita, S.ST., MT",
            "Dr. Fajar Astuti Hermawati, S.Kom., M.Kom", "Agung Kridoyono, S.ST., MT",
            "Andrey Kartika Widhy Hapantenda,S.Kom.,M.Kom", "Chaidir Chalaf Islamy, S.Kom., M.Kom",
            "Elsen Ronando, S.Si., M.Si., M.Sc", "Elvianto Dwi Hartono, ST., MM., M.Kom., MT",
            "Muhamad Firdaus, ST., M.Kom", "Geri Kusnanto, S.Kom, MM", "Ir. Roenadi Koesdijarto, MM",
            "Nuril Esti Khomariah, S.ST., MT", "Puteri Noraisya Primandari, S.ST., M.IM",
            "Yusrida Muflihah, S.Kom., M.Kom", "Dr. Ir. Muaffaq Achmad Jani, M.Eng",
            "Supangat,M.Kom,ITIL,Cobit", "Fridy Mandita, S.Kom., M.Sc", 
            "Bagus Hardiansyah, S.Kom., M.Si", "Intan Dzikria, S.Kom., MIM.",
            "Samsul Huda, S.ST., M.T., Ph.D"
        ];

        foreach ($dataDosen as $nama) {
            // 1. Buat User untuk login dosen (karena ada kolom user_id di tabel dosens)
            $email = Str::slug($nama) . "@univ.ac.id";
            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'dosen',
            ]);

            // 2. Masukkan ke tabel dosens
            Dosen::create([
                'user_id' => $user->id,
                'nama' => $nama,
                'email' => $email,
                'nidn' => rand(10000000, 99999999), // Dummy NIDN
                'prodi' => 'Teknik Informatika',     // Dummy Prodi
            ]);
        }
    }
}