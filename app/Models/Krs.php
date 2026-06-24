<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 'krs';

    protected $fillable = [
        'nbi', 
        'tahun_ajaran', 
        'semester', 
        'kode_mk', 
        'nama_mk', 
        'kelas', 
        'sks', 
        'kode_dsn'
    ];

    // Relasi opsional (jika ingin pakai join nanti)
    public function mataKuliah()
    {
        // foreign key: kode_mk (di tabel krs), owner key: kode_mk (di tabel mata_kuliahs)
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }
}