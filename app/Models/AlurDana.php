<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlurDana extends Model
{
    protected $table = 'alur_dana'; 

    protected $fillable = [
        'pesanan_id',
        'jenis_transaksi',
        'jumlah_dana',
        'bukti_tf_path',
        'status_konfirmasi',
        'konfirmator_id',
        'tanggal_transfer',
        'biaya_admin'
    ];

    protected $casts = [
        'jumlah_dana'       => 'decimal:2',
        'tanggal_transfer'  => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function konfirmator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'konfirmator_id');
    }
}
