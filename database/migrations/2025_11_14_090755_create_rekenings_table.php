<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rekenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tipe_rekening', ['bank','e-wallet'])->nullable();
            $table->string('nama_penyedia', 100);
            $table->string('nama_pemilik', 100);
            $table->string('nomor_akun', 50);
            $table->enum('status_aktif', ['aktif','nonaktif'])->default('aktif');
            $table->timestamp('tanggal_input')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rekenings'); }
};
