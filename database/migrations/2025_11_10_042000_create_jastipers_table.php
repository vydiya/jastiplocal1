<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
Schema::create('jastiper', function (Blueprint $table) {
    $table->id('id_jastiper'); // BIGINT

    // FK -> user.id_user (BIGINT)
    $table->foreignId('id_user')
          ->constrained('user', 'id_user')
          ->cascadeOnDelete();

    $table->string('nama_toko', 50);
    $table->string('no_hp', 20)->nullable();
    $table->text('alamat')->nullable();
    $table->enum('metode_pembayaran', ['transfer','e-wallet'])->default('transfer');
    $table->enum('status_verifikasi', ['pending','disetujui','ditolak'])->default('pending');
    $table->decimal('rating', 2, 1)->default(0.0);
    $table->dateTime('tanggal_daftar')->useCurrent();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('jastiper');
    }
};

