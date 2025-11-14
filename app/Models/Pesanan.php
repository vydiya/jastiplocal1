<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_jastiper',
        'tanggal_pesan',
        'total_harga',
        'status_pesanan'
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'total_harga' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public function jastiper()
    {
        return $this->belongsTo(Jastiper::class, 'id_jastiper', 'id_jastiper');
    }
}
