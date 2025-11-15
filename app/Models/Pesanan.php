<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pesanan extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','jastiper_id','tanggal_pesan','total_harga','status_pesanan','alamat_pengiriman','no_hp'];
    protected $casts = ['total_harga'=>'decimal:2','tanggal_pesan'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
    public function jastiper(){ return $this->belongsTo(Jastiper::class); }
    public function detailPesanans(){ return $this->hasMany(DetailPesanan::class); }
    public function pembayaran(){ return $this->hasOne(Pembayaran::class); }
}
