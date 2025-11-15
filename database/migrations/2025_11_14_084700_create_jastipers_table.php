<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('jastipers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_toko', 100);
            $table->string('no_hp', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('metode_pembayaran', ['transfer','e-wallet'])->default('transfer');
            $table->enum('status_verifikasi', ['pending','disetujui','ditolak'])->default('pending');
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('jastipers'); }
};
