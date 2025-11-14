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
        Schema::create('notifikasi', function (Blueprint $table) {
            
            // Primary Key: id_notifikasi (int(10) AUTO_INCREMENT)
            $table->id('id_notifikasi'); 
            
            // Foreign Key ke tabel user (penerima notifikasi)
            // Diasumsikan tabel user memiliki primary key 'id_user'
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            
            // Kolom Jenis Notifikasi (default 'sistem')
            $table->enum('jenis_notifikasi', ['pembayaran', 'pesanan', 'ulasan', 'sistem'])->default('sistem')->nullable();
            
            // Kolom Pesan (text) - Tidak boleh NULL
            $table->text('pesan'); 
            
            // Kolom Status Baca (enum, default 'belum')
            $table->enum('status_baca', ['belum', 'sudah'])->default('belum')->nullable();
            
            // Kolom Tanggal Kirim (default current_timestamp())
            $table->dateTime('tanggal_kirim')->useCurrent();
            
            // Nonaktifkan created_at/updated_at default Laravel
            // $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
