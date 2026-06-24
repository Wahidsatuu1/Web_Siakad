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
            // Pastikan kolom 'semester' ada sebelum mengganti namanya
            // Ini penting agar migrasi tidak error jika kolom sudah tidak ada (misal karena sudah diubah manual)
            if (Schema::hasColumn('nilai_mahasiswas', 'semester')) {
                // Mengganti nama kolom 'semester' menjadi 'kelas'
                // Anda mungkin perlu menginstal doctrine/dbal jika belum: composer require doctrine/dbal
                $table->renameColumn('semester', 'kelas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            // Pastikan kolom 'kelas' ada sebelum mengganti namanya kembali
            if (Schema::hasColumn('nilai_mahasiswas', 'kelas')) {
                // Mengganti nama kolom 'kelas' kembali menjadi 'semester' untuk rollback
                $table->renameColumn('kelas', 'semester');
            }
        });
    }
};