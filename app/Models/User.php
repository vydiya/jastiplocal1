<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nama_lengkap',
        'username',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
        'tanggal_daftar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_daftar'    => 'datetime',
        'password'          => 'hashed',
    ];

    // contoh relasi umum
    public function jastiper()
    {
        return $this->hasOne(Jastiper::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function rekenings()
    {
        return $this->hasMany(Rekening::class);
    }

    
}
