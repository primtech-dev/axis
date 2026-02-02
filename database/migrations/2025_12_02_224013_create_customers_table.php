<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->id();

            $table->uuid('public_id')
                ->unique()
                ->comment('user uuid publik');

            $table->text('email')
                ->unique()
                ->comment('Email (login)');

            $table->string('google_id')
                ->nullable()
                ->comment('Google OAuth ID');

            $table->text('phone')
                ->nullable()
                ->comment('Nomor telepon');

            $table->text('full_name')
                ->comment('Nama lengkap');

            $table->text('password_hash')
                ->comment('Hash password');

            $table->text('token')
                ->nullable()
                ->comment('Token sementara / reset (opsional)');

            $table->timestampTz('expired_at')
                ->nullable()
                ->comment('Waktu token expired');

            $table->boolean('is_active')
                ->default(true)
                ->comment('Aktif / non-aktif');

            $table->jsonb('meta')
                ->nullable()
                ->comment('Data tambahan (preferences, dsb)');

            // timestamps & soft delete with timezone
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
