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
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Tambahkan kolom 'kelas' setelah kolom 'angkatan' (atau di posisi lain yang Anda inginkan)
            if (!Schema::hasColumn('mahasiswas', 'kelas')) {
                $table->string('kelas', 10)->nullable()->after('angkatan'); // Sesuaikan panjang string dan properti nullable jika diperlukan
            }
            // Jika Anda juga ingin menambahkan status_mahasiswa, tambahkan di sini
            if (!Schema::hasColumn('mahasiswas', 'status_mahasiswa')) {
                $table->string('status_mahasiswa')->nullable()->after('kelas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Hapus kolom 'kelas' jika migrasi di-rollback
            if (Schema::hasColumn('mahasiswas', 'kelas')) {
                $table->dropColumn('kelas');
            }
            if (Schema::hasColumn('mahasiswas', 'status_mahasiswa')) {
                $table->dropColumn('status_mahasiswa');
            }
        });
    }
};
