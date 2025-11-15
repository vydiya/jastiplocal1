<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Nama lengkap
            if (!Schema::hasColumn('users', 'nama_lengkap')) {
                $table->string('nama_lengkap', 150)->nullable()->after('name');
            }

            // Username
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('nama_lengkap');
            }

            // Nomor HP
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 30)->nullable()->after('username');
            }

            // Alamat
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_hp');
            }

            // Peran (admin / pengguna / jastiper)
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['pengguna', 'admin', 'jastiper'])
                      ->default('pengguna')
                      ->after('alamat');
            }

            // Tanggal daftar
            if (!Schema::hasColumn('users', 'tanggal_daftar')) {
                $table->timestamp('tanggal_daftar')
                      ->useCurrent()
                      ->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [
                'nama_lengkap',
                'username',
                'no_hp',
                'alamat',
                'role',
                'tanggal_daftar',
            ];

            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
