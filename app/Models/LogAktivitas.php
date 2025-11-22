<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LogAktivitas extends Model {
    use SoftDeletes;
    protected $table = 'log_aktivitas';
    protected $fillable = ['user_id','aksi','deskripsi','waktu'];
    protected $casts = ['waktu'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
}
