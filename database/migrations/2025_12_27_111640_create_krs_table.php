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
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->string('nbi'); // Menampung NIM mahasiswa
            $table->string('tahun_ajaran');
            $table->string('semester');
            $table->string('kode_mk');
            $table->string('kelas');
            $table->integer('sks');
            $table->string('kode_dsn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};