<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'jenis_notifikasi',
        'pesan',
        'status_baca',
        'tanggal_kirim'
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
