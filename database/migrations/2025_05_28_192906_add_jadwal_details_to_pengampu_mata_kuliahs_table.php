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
            // Tambahkan kolom-kolom jadwal jika belum ada
            if (!Schema::hasColumn('pengampu_mata_kuliah', 'jam_mulai')) {
                $table->time('jam_mulai')->after('hari'); // Tambahkan setelah kolom 'hari'
            }
            if (!Schema::hasColumn('pengampu_mata_kuliah', 'jam_selesai')) {
                $table->time('jam_selesai')->after('jam_mulai');
            }
            if (!Schema::hasColumn('pengampu_mata_kuliah', 'ruangan')) {
                $table->string('ruangan', 50)->after('jam_selesai'); // Batasi panjang string
            }
            if (!Schema::hasColumn('pengampu_mata_kuliah', 'kelas')) {
                $table->string('kelas', 10)->after('ruangan'); // Batasi panjang string
            }

            // Jika Anda memiliki unique constraint yang melibatkan kolom-kolom ini
            // dan belum ditambahkan di migrasi create_pengampu_mata_kuliahs_table,
            // Anda bisa menambahkannya di sini.
            // Contoh:
            // $table->unique(['mata_kuliah_id', 'dosen_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'kelas'], 'unique_pengampu_mk_composite');
            // Pastikan Anda juga menghapus unique constraint lama jika ada dan perlu diubah.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PERBAIKAN: Pastikan nama tabelnya 'pengampu_mata_kuliah' (tunggal)
        Schema::table('pengampu_mata_kuliah', function (Blueprint $table) {
            // Hapus kolom-kolom yang ditambahkan di up()
            if (Schema::hasColumn('pengampu_mata_kuliah', 'jam_mulai')) {
                $table->dropColumn('jam_mulai');
            }
            if (Schema::hasColumn('pengampu_mata_kuliah', 'jam_selesai')) {
                $table->dropColumn('jam_selesai');
            }
            if (Schema::hasColumn('pengampu_mata_kuliah', 'ruangan')) {
                $table->dropColumn('ruangan');
            }
            if (Schema::hasColumn('pengampu_mata_kuliah', 'kelas')) {
                $table->dropColumn('kelas');
            }

            // Jika Anda menambahkan unique constraint di up(), hapus juga di down()
            // $table->dropUnique('unique_pengampu_mk_composite');
        });
    }
};
