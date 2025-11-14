<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('kelola_dana')) {
            Schema::create('kelola_dana', function (Blueprint $table) {
                $table->id('id_kelola');
                $table->foreignId('id_pembayaran')
                      ->constrained('pembayaran', 'id_pembayaran')
                      ->cascadeOnDelete();
                $table->foreignId('id_admin')
                      ->nullable()
                      ->constrained('user', 'id_user')
                      ->nullOnDelete();
                $table->enum('status_dana', ['ditahan','dilepaskan','dikembalikan'])
                      ->default('ditahan');
                $table->dateTime('tanggal_update')->useCurrent();
                $table->text('catatan')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kelola_dana');
    }
};
