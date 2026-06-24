<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'jurusan',
        'angkatan',
        'tanggal_lahir',      
        'program_studi',       
        'prog_perkuliahan',   
        'kelas',              
        'status_mahasiswa',   
        'email',
        'telepon',
        'alamat',
        'user_id',
    ];

    // Jika Anda memiliki relasi user ke mahasiswa (Auth::user()->mahasiswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
