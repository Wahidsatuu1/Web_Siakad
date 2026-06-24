<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengampuMataKuliah extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit agar sesuai dengan migrasi Anda
    protected $table = 'pengampu_mata_kuliah';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas',
        'ruangan',
        // 'tahun_ajaran' dihapus karena tidak digunakan
    ];

    /**
     * Get the mata kuliah that is being taught.
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Get the dosen who teaches the mata kuliah.
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
