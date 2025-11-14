<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
Schema::create('detail_pesanan', function (Blueprint $table) {
    $table->id('id_detail'); // BIGINT

    $table->foreignId('id_pesanan')
          ->constrained('pesanan', 'id_pesanan')
          ->cascadeOnDelete();

    $table->foreignId('id_barang')
          ->constrained('barang', 'id_barang')
          ->restrictOnDelete(); // atau cascade
    $table->integer('jumlah');
    $table->decimal('subtotal', 12, 2);
});

    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
