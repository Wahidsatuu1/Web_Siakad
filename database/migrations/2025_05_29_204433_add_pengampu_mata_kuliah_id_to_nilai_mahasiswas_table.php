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
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            // Tambahkan kolom pengampu_mata_kuliah_id
            // PERBAIKAN: Ubah 'pengampu_mata_kuliahs' menjadi 'pengampu_mata_kuliah'
            $table->foreignId('pengampu_mata_kuliah_id')->nullable()->constrained('pengampu_mata_kuliah')->onDelete('set null')->after('dosen_id');

            // Jika Anda juga perlu menghapus unique constraint lama dan menambahkan yang baru
            // karena penambahan pengampu_mata_kuliah_id mengubah identifikasi unik
            // Contoh:
            // $table->dropUnique('unique_nilai_mk_mhs_kelas'); // Ganti dengan nama unique constraint yang ada
            // $table->unique(['mahasiswa_id', 'pengampu_mata_kuliah_id'], 'unique_nilai_mhs_pengampu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            // Hapus foreign key dan kolomnya saat rollback
            $table->dropForeign(['pengampu_mata_kuliah_id']);
            $table->dropColumn('pengampu_mata_kuliah_id');

            // Kembalikan unique constraint lama jika dihapus di up()
            // $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'kelas'], 'unique_nilai_mk_mhs_kelas');
        });
    }
};
