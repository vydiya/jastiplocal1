<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'rekening';
    protected $primaryKey = 'id_rekening';
    public $timestamps = false;                 // kita pakai tanggal_input, bukan created_at/updated_at

    protected $fillable = [
        'id_user',
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

    /** Relasi ke user (pemilik rekening) */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
