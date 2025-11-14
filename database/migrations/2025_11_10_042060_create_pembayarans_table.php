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
        Schema::create('pembayaran', function (Blueprint $table) {
            
            // Primary Key: id_pembayaran (int(10) AUTO_INCREMENT)
            $table->id('id_pembayaran'); 
            
            // Foreign Key ke tabel pesanan
            // Diasumsikan tabel pesanan memiliki primary key 'id_pesanan'
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            
            // Kolom Metode Pembayaran (default 'transfer')
            $table->enum('metode_pembayaran', ['transfer', 'e-wallet', 'cod'])->default('transfer')->nullable();
            
            // Jumlah Bayar (decimal(12,2)) - Tidak boleh NULL
            $table->decimal('jumlah_bayar', 12, 2); 
            
            // Bukti Bayar (varchar(255)) - Bisa NULL
            $table->string('bukti_bayar', 255)->nullable();
            
            // Status Pembayaran (enum, default 'menunggu')
            $table->enum('status_pembayaran', ['menunggu', 'valid', 'tidak_valid'])->default('menunggu')->nullable();
            
            // Kolom Tanggal Bayar (default current_timestamp())
            $table->dateTime('tanggal_bayar')->useCurrent();
            
            // Nonaktifkan created_at/updated_at default Laravel
            // $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
