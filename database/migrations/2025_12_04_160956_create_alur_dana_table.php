<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alur_dana', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pesanan_id')->constrained('pesanans');
            $table->decimal('biaya_admin', 12, 2)->default(0.00)->comment('Biaya administrasi yang dibebankan');

            $table->enum('jenis_transaksi', [
                'PEMBAYARAN_USER',
                'PELEPASAN_DANA'
            ]);

            $table->decimal('jumlah_dana', 12, 2);
            $table->string('bukti_tf_path', 255);

            $table->enum('status_konfirmasi', [
                'MENUNGGU_CEK',
                'DIKONFIRMASI',
                'DITOLAK'
            ])->default('MENUNGGU_CEK');

            $table->unsignedBigInteger('konfirmator_id')->nullable();

            $table->timestamp('tanggal_transfer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alur_dana');
    }
};
