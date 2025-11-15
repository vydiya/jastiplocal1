<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jastiper_id')->nullable()->constrained('jastipers')->nullOnDelete();
            $table->timestamp('tanggal_pesan')->useCurrent();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_pesanan', ['menunggu','diproses','dikirim','selesai','dibatalkan'])->default('menunggu');
            $table->text('alamat_pengiriman')->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pesanans'); }
};
