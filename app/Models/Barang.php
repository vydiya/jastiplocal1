<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Barang extends Model {
    use SoftDeletes;
    protected $fillable = ['jastiper_id','kategori_id','admin_id','nama_barang','deskripsi','harga','stok','is_available','foto_barang','status_validasi','tanggal_input'];
    protected $casts = ['harga'=>'decimal:2','stok'=>'integer','tanggal_input'=>'datetime'];
    public function jastiper(){ return $this->belongsTo(Jastiper::class); }
    public function kategori(){ return $this->belongsTo(Kategori::class); }
    public function admin(){ return $this->belongsTo(User::class,'admin_id'); }
    public function detailPesanans(){ return $this->hasMany(DetailPesanan::class); }
    // public function laporanPenjualans(){ return $this->hasMany(LaporanPenjualan::class); }
}
