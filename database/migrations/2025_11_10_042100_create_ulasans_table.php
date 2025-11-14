<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            
            // Primary Key: id_ulasan (int(10))
            $table->id('id_ulasan'); 
            
            // Foreign Key ke tabel pesanan
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            
            // Foreign Key ke tabel user (yang memberikan ulasan)
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            
            // Foreign Key ke tabel jastiper (yang menerima ulasan)
            $table->foreignId('id_jastiper')->constrained('jastiper', 'id_jastiper')->onDelete('cascade');
            
            // Kolom Rating dan Komentar
            $table->enum('rating', ['1', '2', '3', '4', '5']);
            $table->text('komentar')->nullable();
            
            // Kolom Tanggal Ulasan
            $table->dateTime('tanggal_ulasan')->useCurrent();
            
            // Opsional: Jika Anda perlu kolom created_at/updated_at di luar tanggal_ulasan
            // $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};