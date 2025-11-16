<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_penjualans', function (Blueprint $table) {
            $table->id();

            // Barang yang dijual
            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->onDelete('cascade');

            // Salin nama & harga barang ke tabel laporan (supaya historinya tidak berubah)
            $table->string('nama_barang', 150);
            $table->decimal('harga_barang', 12, 2)->default(0);

            // Dana yang masuk ke jastiper
            $table->decimal('dana_masuk', 12, 2)->default(0);

            // Status alur laporan
            $table->enum('status', ['pending', 'selesai', 'dana_masuk'])
                  ->default('pending');

            // Tanggal dana masuk / tanggal transaksi
            $table->timestamp('tanggal_masuk')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_penjualans');
    }
};