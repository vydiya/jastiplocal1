<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelolaDana extends Model
{
    protected $table = 'kelola_dana';
    protected $primaryKey = 'id_kelola';
    public $timestamps = false;

    protected $fillable = [
        'id_pembayaran',
        'id_admin',
        'status_dana',
        'tanggal_update',
        'catatan',
    ];

    protected $casts = [
        'tanggal_update' => 'datetime',
    ];

    // Relasi: KelolaDana milik Pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }

    // Relasi: Admin (user) yang melakukan aksi
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_user');
    }
}
