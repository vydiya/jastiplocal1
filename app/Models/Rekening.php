<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rekening extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tipe_rekening',
        'nama_penyedia',
        'nama_pemilik',
        'nomor_akun',
        'status_aktif',
        'tanggal_input',
    ];

    protected $casts = [
        'tanggal_input' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jastiper()
    {
        return $this->hasOne(Jastiper::class, 'rekening_id');
    }
}
