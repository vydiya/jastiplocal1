<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('validasi_produks') && Schema::hasColumn('validasi_produks', 'status_validasi')) {
            Schema::table('validasi_produks', function (Blueprint $table) {
                $table->dropColumn('status_validasi');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('validasi_produks') && !Schema::hasColumn('validasi_produks', 'status_validasi')) {
            Schema::table('validasi_produks', function (Blueprint $table) {
                $table->string('status_validasi')->default('pending')->after('admin_id');
                // Jika kamu sebelumnya pakai enum, sesuaikan tipe pada down()
            });
        }
    }
};
