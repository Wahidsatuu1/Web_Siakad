<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            // Kolom jadwal_kuliah_id ini sepertinya salah, karena tabel yang sedang dibuat adalah jadwal_kuliahs.
            // Jika ini dimaksudkan sebagai self-referencing foreign key, pastikan logikanya benar.
            // Untuk saat ini, saya akan mengomentarinya karena tidak ada di error log atau pembahasan sebelumnya.
            // $table->foreignId('jadwal_kuliah_id')->nullable()->constrained('jadwal_kuliahs')->onDelete('set null');

            // PERBAIKAN: Tentukan panjang eksplisit untuk kolom string yang menjadi bagian dari unique index
            $table->string('hari', 10); // 'Senin' sampai 'Minggu' maksimal 6 karakter, 10 cukup aman
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 50); // Sesuaikan dengan panjang maksimum nama ruangan Anda
            $table->string('kelas', 10);   // '06TPLP0010' adalah 10 karakter, 10 cukup aman
            $table->timestamps();

            // Unique constraint akan berfungsi setelah panjang kolom string dibatasi
            $table->unique([
                'mata_kuliah_id',
                'dosen_id',
                'hari',
                'jam_mulai',
                'jam_selesai',
                'ruangan',
                'kelas'
            ], 'unique_jadwal_kuliah');

            // Catatan: Jika ada kolom 'tahun_ajaran' atau 'semester' yang ingin dimasukkan dalam unique constraint
            // dan belum ada di migrasi, Anda perlu menambahkannya dan mempertimbangkan panjangnya juga.
            // Berdasarkan model JadwalKuliah yang Anda berikan sebelumnya, ada 'tahun_ajaran' dan 'semester'.
            // Jika Anda ingin memasukkannya ke dalam tabel dan unique constraint, Anda harus menambahkannya di sini.
            // Contoh:
            // $table->string('tahun_ajaran', 9)->nullable(); // Contoh: '2023/2024'
            // $table->string('semester', 10)->nullable(); // Contoh: 'Ganjil', 'Genap'
            // Dan kemudian tambahkan ke unique constraint jika relevan:
            // $table->unique(['mata_kuliah_id', 'dosen_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'kelas', 'tahun_ajaran', 'semester'], 'unique_jadwal_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kuliahs');
    }
};
