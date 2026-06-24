<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
use HasFactory;

protected $fillable = [
    'kode_mk',
    'nama_mk',
    'sks',
    'kelas',
    'deskripsi',
];
public function jadwalKuliah()
    {
        // Pastikan path dan nama model JadwalKuliah sudah benar.
        // Jika model Anda ada di App\Models\JadwalKuliah, maka kode ini sudah benar.
        return $this->hasMany(JadwalKuliah::class);
    }
}