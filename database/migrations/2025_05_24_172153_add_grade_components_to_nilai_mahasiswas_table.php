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
            // Hapus kolom yang tidak lagi disimpan (jika ada di tabel dari migrasi sebelumnya)
            // Ini akan mencoba drop jika kolom ada. Jika tidak ada, Laravel akan mengabaikannya.
            if (Schema::hasColumn('nilai_mahasiswas', 'nilai_angka')) {
                $table->dropColumn('nilai_angka');
            }
            if (Schema::hasColumn('nilai_mahasiswas', 'nilai_huruf')) {
                $table->dropColumn('nilai_huruf');
            }

            // Hapus penambahan kolom komponen nilai dan kehadiran dari migrasi ini
            // Karena kolom-kolom ini seharusnya sudah ada di migrasi create_nilai_mahasiswas_table.php
            // $table->integer('kehadiran')->nullable()->after('kelas');
            // $table->decimal('nilai_tugas', 5, 2)->nullable()->after('kehadiran');
            // $table->decimal('nilai_uts', 5, 2)->nullable()->after('nilai_tugas');
            // $table->decimal('nilai_uas', 5, 2)->nullable()->after('nilai_uts');

            // Jika unique constraint lama Anda menyertakan 'semester', Anda harus menghapusnya dan menambahkannya kembali.
            // Contoh jika constraint lama adalah unique_nilai_mk_mhs_smt:
            // if (Schema::hasTable('nilai_mahasiswas') && Schema::hasColumn('nilai_mahasiswas', 'semester')) { // Check if semester exists and constraint might be there
            //     $table->dropUnique('unique_nilai_mk_mhs_smt'); // Sesuaikan nama constraint lama
            // }
            // $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'kelas'], 'unique_nilai_mk_mhs_kelas_new'); // Nama baru
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            // Logika untuk mengembalikan perubahan jika rollback diperlukan
            // Ini tergantung pada struktur awal tabel sebelum migrasi ini dijalankan
            // Contoh:
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->string('nilai_huruf', 2)->nullable();
            // Anda mungkin perlu menambahkan kembali kolom-kolom ini jika migrasi ini yang menghapusnya
            // $table->dropColumn(['kehadiran', 'nilai_tugas', 'nilai_uts', 'nilai_uas']); // Ini harusnya di down() dari migrasi yang menambahkannya
            // Jika Anda mengubah unique constraint di up(), kembalikan di down()
            // $table->dropUnique('unique_nilai_mk_mhs_kelas_new');
            // $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'semester'], 'unique_nilai_mk_mhs_smt');
        });
    }
};
