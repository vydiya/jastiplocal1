<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->enum('metode_pembayaran', ['transfer','e-wallet','cod'])->default('transfer');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('bukti_bayar', 255)->nullable();
            $table->enum('status_pembayaran', ['menunggu','valid','tidak_valid'])->default('menunggu');
            $table->timestamp('tanggal_bayar')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pembayarans'); }
};
