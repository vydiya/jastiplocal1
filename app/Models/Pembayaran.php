<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pembayaran extends Model {
    use SoftDeletes;
    protected $fillable = ['pesanan_id','metode_pembayaran','jumlah_bayar','bukti_bayar','status_pembayaran','tanggal_bayar'];
    protected $casts = ['jumlah_bayar'=>'decimal:2','tanggal_bayar'=>'datetime'];
    public function pesanan(){ return $this->belongsTo(Pesanan::class); }
}
