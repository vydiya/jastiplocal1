<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
// 2025_11_10_040000_create_users_table.php (nama kamu mungkin berbeda)
Schema::create('user', function (Blueprint $table) {
    $table->id('id_user'); // BIGINT UNSIGNED (sama dengan bigIncrements)
    $table->string('nama_lengkap', 50);
    $table->string('username', 50)->unique();
    $table->string('password');
    $table->string('email', 30)->unique();
    $table->string('no_hp', 20)->nullable();
    $table->text('alamat')->nullable();
    $table->enum('peran', ['admin','pengguna','jastiper'])->default('pengguna');
    $table->dateTime('tanggal_daftar')->useCurrent();
    $table->rememberToken();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
