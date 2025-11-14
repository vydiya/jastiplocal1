<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
Schema::create('barang', function (Blueprint $table) {
    $table->id('id_barang'); // BIGINT

    // FK -> jastiper (BIGINT)
    $table->foreignId('id_jastiper')
          ->constrained('jastiper', 'id_jastiper')
          ->cascadeOnDelete();

    // FK -> kategori (INT)
    $table->unsignedInteger('id_kategori')->nullable();
    $table->foreign('id_kategori')
          ->references('id_kategori')->on('kategori')
          ->nullOnDelete();

    $table->string('nama_barang', 50);
    $table->text('deskripsi')->nullable();
    $table->decimal('harga', 12, 2);
    $table->integer('stok')->default(0);
    $table->enum('is_available', ['yes','no'])->default('yes');
    $table->string('foto_barang', 255)->nullable();
    $table->enum('status_validasi', ['pending','disetujui','ditolak'])->default('pending');
    $table->unsignedBigInteger('id_admin')->nullable(); // kolom biasa
    $table->dateTime('tanggal_input')->useCurrent();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
