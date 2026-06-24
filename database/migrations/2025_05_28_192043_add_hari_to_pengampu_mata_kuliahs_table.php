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
        // PERBAIKAN: Pastikan nama tabelnya 'pengampu_mata_kuliah' (tunggal)
        Schema::table('pengampu_mata_kuliah', function (Blueprint $table) {
            // Cek apakah kolom 'hari' sudah ada sebelum menambahkannya
            if (!Schema::hasColumn('pengampu_mata_kuliah', 'hari')) {
                $table->string('hari', 10)->after('dosen_id'); // Menambahkan batasan panjang string
            }
            // Tambahkan kolom lain jika migrasi ini juga yang menambahkannya
            // Misalnya:
            // if (!Schema::hasColumn('pengampu_mata_kuliah', 'jam_mulai')) {
            //     $table->time('jam_mulai')->after('hari');
            // }
            // if (!Schema::hasColumn('pengampu_mata_kuliah', 'jam_selesai')) {
            //     $table->time('jam_selesai')->after('jam_mulai');
            // }
            // if (!Schema::hasColumn('pengampu_mata_kuliah', 'ruangan')) {
            //     $table->string('ruangan', 50)->after('jam_selesai'); // Menambahkan batasan panjang string
            // }
            // if (!Schema::hasColumn('pengampu_mata_kuliah', 'kelas')) {
            //     $table->string('kelas', 10)->after('ruangan'); // Menambahkan batasan panjang string
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PERBAIKAN: Pastikan nama tabelnya 'pengampu_mata_kuliah' (tunggal)
        Schema::table('pengampu_mata_kuliah', function (Blueprint $table) {
            if (Schema::hasColumn('pengampu_mata_kuliah', 'hari')) {
                $table->dropColumn('hari');
            }
            // Hapus kolom lain jika migrasi ini yang menambahkannya
            // Misalnya:
            // if (Schema::hasColumn('pengampu_mata_kuliah', 'jam_mulai')) {
            //     $table->dropColumn('jam_mulai');
            // }
            // if (Schema::hasColumn('pengampu_mata_kuliah', 'jam_selesai')) {
            //     $table->dropColumn('jam_selesai');
            // }
            // if (Schema::hasColumn('pengampu_mata_kuliah', 'ruangan')) {
            //     $table->dropColumn('ruangan');
            // }
            // if (Schema::hasColumn('pengampu_mata_kuliah', 'kelas')) {
            //     $table->dropColumn('kelas');
            // }
        });
    }
};
