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
        Schema::table('pengampu_mata_kuliahs', function (Blueprint $table) {
            // Pastikan kolom 'semester' ada sebelum menghapusnya
            if (Schema::hasColumn('pengampu_mata_kuliahs', 'semester')) {
                $table->dropColumn('semester');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengampu_mata_kuliahs', function (Blueprint $table) {
            // Untuk rollback: Tambahkan kembali kolom 'semester'
            // Sesuaikan tipe data dan properti dengan kondisi sebelum dihapus
            // Contoh: string dengan panjang 10, bisa null
            // Anda mungkin perlu Doctrine/DBAL untuk ini jika ada data yang akan terpengaruh
            if (!Schema::hasColumn('pengampu_mata_kuliahs', 'semester')) {
                $table->string('semester', 10)->nullable();
            }
        });
    }
};