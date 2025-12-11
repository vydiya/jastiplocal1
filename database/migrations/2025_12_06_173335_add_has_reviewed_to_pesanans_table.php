<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Kolom untuk menandai apakah pesanan sudah diulas atau belum
            $table->boolean('has_reviewed')->default(false)->after('status_pesanan');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('has_reviewed');
        });
    }
};