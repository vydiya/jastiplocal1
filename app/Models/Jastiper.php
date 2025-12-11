<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Jastiper extends Model
{
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'user_id',
        'nama_toko',
        'no_hp',
        'jangkauan',      
        'rating',
        'tanggal_daftar',
        'rekening_id',    
        'profile_toko'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'tanggal_daftar' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'rekening_id');
    }
}
