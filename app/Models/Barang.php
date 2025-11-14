<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    protected $fillable = [
        'id_jastiper',
        'id_kategori',
        'nama_barang',
        'deskripsi',
        'harga',
        'stok',
        'is_available',
        'foto_barang',
        'status_validasi',
        'id_admin',
        'tanggal_input',
    ];

    // Relasi ke jastiper
    public function jastiper()
    {
        return $this->belongsTo(Jastiper::class, 'id_jastiper', 'id_jastiper');
    }

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
