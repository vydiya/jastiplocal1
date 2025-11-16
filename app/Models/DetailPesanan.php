<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPesanan extends Model
{
    use SoftDeletes;

    protected $fillable = ['pesanan_id','barang_id','jumlah','subtotal'];

    protected $casts = [
        'jumlah' => 'integer',
        'subtotal' => 'decimal:2'
    ];

    public function pesanan() {
        return $this->belongsTo(Pesanan::class);
    }

    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}
