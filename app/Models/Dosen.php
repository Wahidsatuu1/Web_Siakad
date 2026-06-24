<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Menentukan kolom-kolom yang boleh diisi secara massal (mass assignable)
    protected $fillable = [
        'nama',
        'email',
        'nidn',
        'prodi',
        'user_id',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}