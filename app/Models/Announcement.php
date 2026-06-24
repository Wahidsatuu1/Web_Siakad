<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    /**
     * ====================================================================
     * PROPERTI FILLABLE SEHARUSNYA ADA DI SINI
     * ====================================================================
     * Atribut yang dapat diisi secara massal.
     * Pastikan 'target_role' ada di sini.
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'target_role',
    ];

    /**
     * Definisikan relasi ke model User (pembuat pengumuman).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}