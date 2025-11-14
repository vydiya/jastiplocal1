<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
Schema::create('pesanan', function (Blueprint $table) {
    $table->id('id_pesanan'); // BIGINT

    $table->foreignId('id_pengguna')
          ->constrained('user', 'id_user')
          ->cascadeOnDelete();

    $table->foreignId('id_jastiper')
          ->constrained('jastiper', 'id_jastiper')
          ->cascadeOnDelete();

    $table->dateTime('tanggal_pesan')->useCurrent();
    $table->decimal('total_harga', 12, 2);
    $table->enum('status_pesanan', ['menunggu','diproses','dikirim','selesai','dibatalkan'])
          ->default('menunggu');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};

