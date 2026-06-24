<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
use HasFactory;
protected $table = 'jadwal_kuliahs';
protected $fillable = [
    'mata_kuliah_id',
    'dosen_id',
    'hari',
    'jam_mulai',
    'jam_selesai',
    'kelas', // Tambahkan
    'tahun_ajaran', // Tambahkan
    'ruangan',
    'semester',
];

/**
 * Get the mata kuliah that owns the jadwal kuliah.
 */
public function mataKuliah()
{
    return $this->belongsTo(MataKuliah::class);
}

/**
 * Get the dosen that owns the jadwal kuliah.
 */
public function dosen()
{
    return $this->belongsTo(Dosen::class);
}
}