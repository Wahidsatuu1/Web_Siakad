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
            // Pastikan kolom 'kehadiran' ada sebelum mengubahnya
            if (Schema::hasColumn('nilai_mahasiswas', 'kehadiran')) {
                // Mengubah tipe kolom menjadi integer
                // Pastikan untuk menginstal doctrine/dbal jika belum: composer require doctrine/dbal
                $table->integer('kehadiran')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            // Mengembalikan tipe kolom ke decimal (jika diperlukan untuk rollback)
            // Sesuaikan dengan definisi awal kolom Anda jika bukan decimal
            if (Schema::hasColumn('nilai_mahasiswas', 'kehadiran')) {
                $table->decimal('kehadiran', 5, 2)->nullable()->change(); // Contoh: jika sebelumnya decimal(5,2)
            }
        });
    }
};
