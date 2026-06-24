<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenDosen extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi Laravel (plural dari nama model)
    protected $table = 'absen_dosens';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'dosen_id',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'status',
        'keterangan',
    ];

    // Relasi dengan model Dosen (asumsi Anda memiliki model Dosen)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}