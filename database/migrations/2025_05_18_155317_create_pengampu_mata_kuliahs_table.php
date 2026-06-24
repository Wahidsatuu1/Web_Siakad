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
        Schema::create('pengampu_mata_kuliah', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel mata_kuliahs
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            // Foreign key ke tabel dosens
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            
            // PERBAIKAN: Tentukan panjang eksplisit untuk kolom string yang menjadi bagian dari unique index
            $table->string('hari', 10); // 'Senin' sampai 'Minggu' maksimal 6 karakter, 10 cukup aman
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 50); // Sesuaikan dengan panjang maksimum nama ruangan Anda
            $table->string('kelas', 10);   // '06TPLP0010' adalah 10 karakter, 10 cukup aman
            $table->timestamps();

            // Unique constraint akan berfungsi setelah panjang kolom string dibatasi
            $table->unique(['mata_kuliah_id', 'dosen_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'kelas'], 'unique_pengampu_mk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengampu_mata_kuliah');
    }
};
