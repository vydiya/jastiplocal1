<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table      = 'detail_pesanan';
    protected $primaryKey = 'id_detail';
    public $timestamps    = false;

    protected $fillable = [
        'id_pesanan',
        'id_barang',
        'jumlah',
        'subtotal',
    ];

    // Relasi
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
