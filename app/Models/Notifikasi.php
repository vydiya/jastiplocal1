<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Notifikasi extends Model {
    use SoftDeletes;
protected $fillable = ['user_id','jenis_notifikasi','pesan','status_baca','tanggal_kirim'];    protected $casts = ['tanggal_kirim'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
}
