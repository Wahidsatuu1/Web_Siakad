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
        Schema::create('nilai_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->string('kelas', 10); // Pastikan kolom kelas ada di sini
            // Kolom 'semester' DIHAPUS dari sini
            
            // Kolom komponen nilai yang akan disimpan
            $table->integer('kehadiran')->nullable();
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            
            // Kolom ini TIDAK perlu ada di database jika dihitung dinamis
            // $table->decimal('nilai_angka', 5, 2)->nullable();
            // $table->string('nilai_huruf', 2)->nullable();

            $table->timestamps();

            // Unique constraint, sesuaikan jika Anda tidak ingin semester menjadi bagiannya
            // Contoh dengan mahasiswa_id, mata_kuliah_id, dan kelas sebagai unique identifier
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'kelas'], 'unique_nilai_mk_mhs_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswas');
    }
};
