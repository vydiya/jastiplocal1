<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'jastiper_id',
        'tanggal_pesan',
        'total_harga',
        'status_pesanan',
        'status_dana_jastiper', 
        'alamat_pengiriman',
        'no_hp',
        'catatan',
    ];

    protected $casts = [
        'total_harga'       => 'decimal:2',
        'tanggal_pesan'     => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jastiper(): BelongsTo
    {
        return $this->belongsTo(Jastiper::class);
    }

    // public function detail_pesanans(): HasMany
    // {
    //     return $this->hasMany(DetailPesanan::class);
    // }

    public function detailPesanans() 
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    public function alurDana(): HasMany
    {
        return $this->hasMany(AlurDana::class, 'pesanan_id');
    }

    public function pembayaranUser(): HasOne
    {
        return $this->hasOne(AlurDana::class, 'pesanan_id')
                    ->where('jenis_transaksi', 'PEMBAYARAN_USER');
    }

    public function pelepasanDana(): HasOne
    {
        return $this->hasOne(AlurDana::class, 'pesanan_id')
                    ->where('jenis_transaksi', 'PELEPASAN_DANA');
    }
}