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
        // ----------------------
        // USERS TABLE (final)
        // ----------------------
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT unsigned

            // Laravel display name & login fields
            $table->string('name', 100);                  // display name
            $table->string('email', 255)->unique();      // login email

            // legacy / profile fields from your old DB
            $table->string('nama_lengkap', 150)->nullable();
            $table->string('username', 50)->nullable()->unique();
            $table->string('no_hp', 30)->nullable();
            $table->text('alamat')->nullable();

            // role/peran + tanggal daftar
            $table->enum('role', ['pengguna','admin','jastiper'])->default('pengguna');
            $table->timestamp('tanggal_daftar')->useCurrent();

            // auth fields
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->rememberToken(); // remember_token (important for login)

            $table->timestamps();
        });

        // ----------------------
        // PASSWORD RESET TOKENS (Laravel 10+ style)
        // ----------------------
        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // ----------------------
        // SESSIONS (for SESSION_DRIVER=database)
        // ----------------------
        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index()->constrained('users')->nullOnDelete();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        } else {
            // ensure user_id exists and has FK
            if (! Schema::hasColumn('sessions', 'user_id')) {
                Schema::table('sessions', function (Blueprint $table) {
                    $table->foreignId('user_id')->nullable()->index()->after('id');
                });
                // add FK if possible
                try {
                    Schema::table('sessions', function (Blueprint $table) {
                        $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                    });
                } catch (\Throwable $e) {
                    // ignore if cannot add FK (e.g. ordering issues)
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop sessions first (FK dependency)
        Schema::dropIfExists('sessions');

        // drop password reset tokens
        Schema::dropIfExists('password_reset_tokens');

        // drop users last
        Schema::dropIfExists('users');
    }
};
