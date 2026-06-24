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
        Schema::create('presensi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            // PERBAIKAN: Ubah 'pengampu_mata_kuliahs' menjadi 'pengampu_mata_kuliah'
            $table->foreignId('pengampu_mata_kuliah_id')->constrained('pengampu_mata_kuliah')->onDelete('cascade'); 
            $table->date('tanggal');
            $table->time('waktu_presensi');
            $table->string('status_kehadiran'); // e.g., 'Hadir', 'Sakit', 'Izin', 'Alpha'
            $table->timestamps();

            // Add a unique constraint to prevent duplicate attendance for the same student, course, and date
            $table->unique(['mahasiswa_id', 'pengampu_mata_kuliah_id', 'tanggal'], 'unique_presensi_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_mahasiswas');
    }
};
