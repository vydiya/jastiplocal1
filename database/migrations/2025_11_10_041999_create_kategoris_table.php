<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            // INT(10) unsigned AUTO_INCREMENT (match phpMyAdmin)
            $table->increments('id_kategori');
            $table->string('nama_kategori', 30);
            // Tidak pakai timestamps (sesuai struktur kamu)
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
