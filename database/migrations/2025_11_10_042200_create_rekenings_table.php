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
        Schema::create('rekening', function (Blueprint $table) {
            
            // Primary Key: id_rekening (int(10) AUTO_INCREMENT)
            $table->id('id_rekening'); 
            
            // Foreign Key ke tabel user
            // Diasumsikan tabel user memiliki primary key 'id_user'
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            
            // Kolom Tipe Rekening (bisa NULL)
            $table->enum('tipe_rekening', ['bank', 'e-wallet'])->nullable();
            
            // Kolom Detail Rekening
            $table->string('nama_penyedia', 50); // varchar(50)
            $table->string('nama_pemilik', 50); // varchar(50)
            $table->string('nomor_akun', 30)->unique(); // varchar(30) unik
            
            // Kolom Status Aktif (default 'aktif')
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif')->nullable();
            
            // Kolom Tanggal Input (default current_timestamp())
            $table->dateTime('tanggal_input')->useCurrent();
            
            // Nonaktifkan created_at/updated_at default Laravel
            // $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening');
    }
};