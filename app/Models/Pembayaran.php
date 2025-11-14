<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'metode_pembayaran',
        'jumlah_bayar',
        'bukti_bayar',
        'status_pembayaran',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar' => 'decimal:2'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
