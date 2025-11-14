<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    // Nama tabel di database
    protected $table = 'log_aktivitas';

    // Primary key custom
    protected $primaryKey = 'id_log';

    // Primary key bukan auto-increment? 
    // Jika iya ubah ke false, tapi biasanya auto increment → true
    public $incrementing = true;

    // Primary key bukan tipe string
    protected $keyType = 'int';

    // Kalau tabel tidak memiliki kolom created_at & updated_at
    public $timestamps = false;

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'id_user',
        'aksi',
        'deskripsi',
        'waktu',
    ];

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
