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
            // Menambahkan kolom-kolom baru
            $table->date('tanggal_lahir')->nullable()->after('angkatan');
            $table->string('program_studi')->nullable()->after('jurusan'); // Jika berbeda dengan 'jurusan'
            $table->string('prog_perkuliahan')->nullable()->after('program_studi'); // Contoh: Reguler, Ekstensi
            $table->string('kelas')->nullable()->after('prog_perkuliahan'); // Contoh: A, B, C
            $table->string('status_mahasiswa')->default('Aktif')->after('kelas'); // Contoh: Aktif, Cuti, Lulus, DO

            // Jika 'jurusan' sudah cukup sebagai 'Program Studi', Anda bisa menghapus baris $table->string('program_studi')
            // dan mengganti referensi 'program_studi' dengan 'jurusan' di model dan tampilan.
            // Untuk saat ini, saya asumsikan Anda ingin kolom terpisah.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn([
                'tanggal_lahir',
                'program_studi',
                'prog_perkuliahan',
                'kelas',
                'status_mahasiswa'
            ]);
        });
    }
};
