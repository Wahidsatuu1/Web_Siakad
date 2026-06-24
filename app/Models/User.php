<?php

namespace App\Models;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'nim', 
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the mahasiswa record associated with the user.
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    /**
     * Get the dosen record associated with the user.
     */

    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id'); 
    }
    public function getProfileNameAttribute()
    {
        if ($this->role === 'mahasiswa' && $this->mahasiswa) {
            return $this->mahasiswa->nama; // Ambil nama dari profil Mahasiswa
        } elseif ($this->role === 'dosen' && $this->dosen) {
            return $this->dosen->nama; // Ambil nama dari profil Dosen
        }
        // Jika peran lain atau profil tidak ditemukan, kembali ke nama default dari tabel users
        return $this->name;
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}