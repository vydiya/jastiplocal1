<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Jastiper extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','nama_toko','no_hp','alamat','metode_pembayaran','status_verifikasi','rating','tanggal_daftar'];
    protected $casts = ['rating'=>'decimal:1','tanggal_daftar'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
    public function barangs(){ return $this->hasMany(Barang::class); }
}
