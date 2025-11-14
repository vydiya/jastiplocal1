<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // Nama tabel & PK kustom
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    // Tabel tidak punya created_at/updated_at
    public $timestamps = false;

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = ['nama_kategori'];
}
