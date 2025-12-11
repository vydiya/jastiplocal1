<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom 'profile_toko' ke tabel jastipers.
     */
    public function up(): void
    {
        if (Schema::hasTable('jastipers')) {
            // Pastikan kolom belum ada untuk mencegah error
            if (!Schema::hasColumn('jastipers', 'profile_toko')) {
                Schema::table('jastipers', function (Blueprint $table) {
                    // Tambahkan kolom string untuk menyimpan path/URL gambar toko
                    $table->string('profile_toko', 2048)->nullable()->after('user_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom 'profile_toko' dari tabel jastipers.
     */
    public function down(): void
    {
        if (Schema::hasTable('jastipers')) {
            // Pastikan kolom ada sebelum mencoba menghapusnya
            if (Schema::hasColumn('jastipers', 'profile_toko')) {
                Schema::table('jastipers', function (Blueprint $table) {
                    $table->dropColumn('profile_toko');
                });
            }
        }
    }
};