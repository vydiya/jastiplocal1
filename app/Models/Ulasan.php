<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    // Mapping table & PK non-standar
    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';

    // Karena tabel pakai `tanggal_ulasan` (bukan created_at/updated_at)
    public $timestamps = false;

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'id_pesanan',
        'id_user',
        'id_jastiper',
        'rating',
        'komentar',
        'tanggal_ulasan',
    ];

    // --- Relasi ---
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function jastiper()
    {
        return $this->belongsTo(Jastiper::class, 'id_jastiper', 'id_jastiper');
    }
}
