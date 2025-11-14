<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jastiper extends Model
{
    protected $table = 'jastiper';
    protected $primaryKey = 'id_jastiper';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_toko',
        'no_hp',
        'alamat',
        'metode_pembayaran',
        'status_verifikasi',
        'rating',
        'tanggal_daftar',
    ];

    protected $casts = [
        'rating' => 'float',
        'tanggal_daftar' => 'datetime',
    ];

    // Relasi (opsional tapi disarankan)
    public function user()
    {
        // tabel 'user', PK 'id_user'
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_jastiper', 'id_jastiper');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_jastiper', 'id_jastiper');
    }
}

