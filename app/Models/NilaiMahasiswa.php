<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Log;

class NilaiMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'dosen_id',
        'kelas',
        'semester',
        'kehadiran',       // Sekarang akan menyimpan JUMLAH pertemuan yang dihadiri
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        // 'nilai_angka' dihapus dari fillable karena akan dihitung dinamis
        'nilai_huruf',
    ];

    /**
     * Get the mahasiswa associated with the nilai.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Get the mata kuliah associated with the nilai.
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Get the dosen who gave the nilai.
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    // --- Accessor untuk menghitung Nilai Angka Otomatis ---
    // Nilai Angka akan selalu dihitung berdasarkan komponen nilai
    public function getNilaiAngkaAttribute()
    {
        // Pastikan mata kuliah sudah di-load agar SKS bisa diakses
        if (!$this->relationLoaded('mataKuliah') || !$this->mataKuliah) {
            // Log warning jika mata kuliah tidak ter-load, ini penting untuk debugging
            \Log::warning('NilaiMahasiswa: Mata Kuliah tidak ter-load saat mencoba menghitung nilai_angka untuk ID: ' . $this->id);
            return null; // Tidak bisa menghitung tanpa data mata kuliah
        }

        $sks = $this->mataKuliah->sks;
        if ($sks === null) {
            \Log::warning('NilaiMahasiswa: SKS mata kuliah tidak ditemukan saat mencoba menghitung nilai_angka untuk ID: ' . $this->id);
            return null; // Tidak bisa menghitung tanpa SKS
        }

        // Tentukan jumlah pertemuan maksimum berdasarkan SKS
        $maksimumPertemuan = 0;
        if ($sks == 3) {
            $maksimumPertemuan = 21;
        } elseif ($sks == 2) {
            $maksimumPertemuan = 14;
        } else {
            // Jika ada SKS lain, definisikan maksimum pertemuannya di sini
            // Atau berikan nilai default / tangani error
            \Log::warning('NilaiMahasiswa: SKS tidak didukung untuk perhitungan kehadiran otomatis untuk ID: ' . $this->id . ', SKS: ' . $sks);
            return null; // SKS tidak didukung untuk perhitungan kehadiran otomatis
        }

        // Jika semua komponen nilai tidak ada, tidak bisa menghitung
        if ($this->kehadiran === null && $this->nilai_tugas === null && $this->nilai_uts === null && $this->nilai_uas === null) {
            return null;
        }

        // Bobot nilai (sesuaikan dengan kebijakan penilaian Anda)
        // Pastikan total bobot adalah 1 (100%)
        $bobotKehadiran = 0.10; // Contoh: 10%
        $bobotTugas     = 0.30; // Contoh: 30%
        $bobotUTS       = 0.30; // Contoh: 30%
        $bobotUAS       = 0.30; // Contoh: 30%

        // Hitung nilai kehadiran dalam skala 0-100%
        $jumlahHadir = $this->kehadiran ?? 0;
        $kehadiranDalamPersen = ($maksimumPertemuan > 0) ? ($jumlahHadir / $maksimumPertemuan) * 100 : 0;
        
        // Pastikan nilai komponen tidak null saat dihitung, anggap 0 jika null
        $nilai_tugas = $this->nilai_tugas ?? 0;
        $nilai_uts = $this->nilai_uts ?? 0;
        $nilai_uas = $this->nilai_uas ?? 0;

        $nilaiAngkaHitung = ($kehadiranDalamPersen * $bobotKehadiran) +
                            ($nilai_tugas * $bobotTugas) +
                            ($nilai_uts * $bobotUTS) +
                            ($nilai_uas * $bobotUAS);

        // Bulatkan ke 2 desimal
        return round($nilaiAngkaHitung, 2);
    }

    // --- Accessor untuk menghitung Nilai Huruf Otomatis ---
    // Akan selalu dihitung berdasarkan nilai_angka yang dihitung dinamis
    public function getNilaiHurufAttribute()
    {
        // Mengambil hasil perhitungan nilai_angka
        $nilaiAngka = $this->getNilaiAngkaAttribute(); 

        if ($nilaiAngka === null) {
            return null;
        }

        // --- TARUH KODE GRADE DI SINI ---
        if ($nilaiAngka >= 80) {
            return 'A';
        } elseif ($nilaiAngka >= 75) {
            return 'AB';
        } elseif ($nilaiAngka >= 70) {
            return 'B+';
        } elseif ($nilaiAngka >= 65) {
            return 'B';
        } elseif ($nilaiAngka >= 55) {
            return 'C';
        } elseif ($nilaiAngka >= 45) {
            return 'D';
        } else {
            return 'E';
        }
    }

    // --- Accessor untuk menghitung Mutu (Grade Point) Otomatis ---
    // Akan selalu dihitung berdasarkan nilai_angka yang dihitung dinamis
    public function getMutuAttribute()
    {
        // Menggunakan accessor nilai_angka di sini, yang akan memicu perhitungan dinamis
        $nilaiAngka = $this->getNilaiAngkaAttribute(); 

        if ($nilaiAngka === null) {
            return null;
        }

        // Skala Mutu (Grade Point, sesuaikan dengan kebijakan kampus Anda)
        if ($nilaiAngka >= 85) return 4.00; // A
        if ($nilaiAngka >= 80) return 3.70; // A-
        if ($nilaiAngka >= 75) return 3.30; // B+
        if ($nilaiAngka >= 70) return 3.00; // B
        if ($nilaiAngka >= 65) return 2.70; // B-
        if ($nilaiAngka >= 60) return 2.30; // C+
        if ($nilaiAngka >= 55) return 2.00; // C
        if ($nilaiAngka >= 50) return 1.70; // C-
        if ($nilaiAngka >= 40) return 1.00; // D
        return 0.00; // E
    }
    protected function sksXMutu(): Attribute
    {
        return Attribute::make(get: function () {
            // Pastikan relasi mataKuliah sudah dimuat untuk mengakses SKS
            if (!$this->relationLoaded('mataKuliah')) {
                $this->load('mataKuliah');
            }
    
            // Jika salah satu dari sks atau mutu tidak ada, kembalikan 0
            if (optional($this->mataKuliah)->sks === null || $this->mutu === null) {
                return 0;
            }
    
            // Lakukan perkalian
            return (float)$this->mataKuliah->sks * (float)$this->mutu;
        });
    }

}
