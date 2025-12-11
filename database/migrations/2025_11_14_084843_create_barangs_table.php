<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jastiper_id')->nullable()->constrained('jastipers')->nullOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_barang', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->enum('is_available', ['yes','no'])->default('yes');
            $table->string('foto_barang', 255)->nullable();
            $table->timestamp('tanggal_input')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('barangs'); }
};
  