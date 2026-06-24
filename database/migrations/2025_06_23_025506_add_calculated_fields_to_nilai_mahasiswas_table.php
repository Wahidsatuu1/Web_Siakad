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
            // HAPUS baris untuk 'kehadiran' karena sudah ada.
            // Kita hanya menambahkan 'nilai_angka' dan 'nilai_huruf'.
            // Perhatikan 'after' sekarang merujuk ke 'kehadiran' yang sudah ada.
            $table->decimal('nilai_angka', 5, 2)->default(0)->after('kehadiran');
            $table->string('nilai_huruf', 2)->nullable()->after('nilai_angka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            // Hanya hapus kolom yang kita tambahkan di method up()
            $table->dropColumn(['nilai_angka', 'nilai_huruf']);
        });
    }
};