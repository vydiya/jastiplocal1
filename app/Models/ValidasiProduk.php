<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidasiProduk extends Model
{
    protected $table = 'validasi_produks';

    protected $fillable = [
        'barang_id',
        'admin_id',
        'status_validasi',
        'tanggal_validasi',
        'catatan',
    ];

    protected $dates = ['tanggal_validasi', 'created_at', 'updated_at'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
