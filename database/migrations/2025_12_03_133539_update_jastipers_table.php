<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        Schema::table('jastipers', function (Blueprint $table) {

            $table->dropColumn('metode_pembayaran');
            $table->dropColumn('status_verifikasi');

            $table->renameColumn('alamat', 'jangkauan');

            $table->foreignId('rekening_id')
                  ->nullable()
                  ->constrained('rekenings')
                  ->nullOnDelete();
        });
    }

    public function down(): void {

        Schema::table('jastipers', function (Blueprint $table) {

            $table->enum('metode_pembayaran', ['transfer','e-wallet'])->default('transfer');
            $table->enum('status_verifikasi', ['pending','disetujui','ditolak'])->default('pending');

            $table->renameColumn('jangkauan', 'alamat');

            $table->dropForeign(['rekening_id']);
            $table->dropColumn('rekening_id');
        });
    }
};
