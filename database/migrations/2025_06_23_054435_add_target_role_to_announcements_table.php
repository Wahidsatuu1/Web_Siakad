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
        // Gunakan Schema::table, BUKAN Schema::create
        Schema::table('announcements', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada agar tidak error lagi
            if (!Schema::hasColumn('announcements', 'target_role')) {
                $table->string('target_role')->default('Semua')->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('target_role');
        });
    }
};