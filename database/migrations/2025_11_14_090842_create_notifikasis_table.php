<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_notifikasi', ['pembayaran','pesanan','ulasan','sistem'])->default('sistem');
            $table->text('pesan');
            $table->enum('status_baca', ['belum','sudah'])->default('belum');
            $table->timestamp('tanggal_kirim')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notifikasis'); }
};
