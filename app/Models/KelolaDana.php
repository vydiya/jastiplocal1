<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class KelolaDana extends Model {
    use SoftDeletes;
    protected $fillable = ['pembayaran_id','admin_id','status_dana','tanggal_update','catatan'];
    protected $casts = ['tanggal_update'=>'datetime'];
    public function pembayaran(){ return $this->belongsTo(Pembayaran::class); }
    public function admin(){ return $this->belongsTo(User::class,'admin_id'); }
}
