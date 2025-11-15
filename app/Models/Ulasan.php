<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Ulasan extends Model {
    use SoftDeletes;
    protected $fillable = ['pesanan_id','user_id','jastiper_id','rating','komentar','tanggal_ulasan'];
    protected $casts = ['tanggal_ulasan'=>'datetime','rating'=>'integer'];
    public function user(){ return $this->belongsTo(User::class); }
    public function pesanan(){ return $this->belongsTo(Pesanan::class); }
    public function jastiper(){ return $this->belongsTo(Jastiper::class); }
}
