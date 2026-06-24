<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiMahasiswa extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'presensi_mahasiswas';

    // The attributes that are mass assignable.
    protected $fillable = [
        'mahasiswa_id',
        'pengampu_mata_kuliah_id', // Foreign key to pengampu_mata_kuliahs table
        'tanggal',
        'waktu_presensi',
        'status_kehadiran', // e.g., 'Hadir', 'Sakit', 'Izin', 'Alpha'
    ];

    // The attributes that should be cast.
    protected $casts = [
        'tanggal' => 'date',
        'waktu_presensi' => 'datetime', // Or 'time' if you only need the time part
    ];

    /**
     * Get the student that owns the attendance record.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Get the course offering (PengampuMataKuliah) associated with the attendance.
     */
    public function pengampuMataKuliah()
    {
        return $this->belongsTo(PengampuMataKuliah::class);
    }
}
