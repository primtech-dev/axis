<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('sku')->nullable()->unique();
            $table->text('name');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            // JSONB for Postgres, json for MySQL — Laravel maps accordingly
            if (Schema::getConnection()->getDriverName() === 'pgsql') {
                // For Postgres, use jsonb column type
                $table->jsonb('attributes')->nullable();
            } else {
                $table->json('attributes')->nullable();
            }
            $table->integer('weight_gram')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        // Create a trigram / GIN index for `name` when using Postgres (optional)
        $driver = config('database.connections.' . config('database.default') . '.driver');

        if ($driver === 'pgsql') {
            // Ensure pg_trgm extension exists on DB; if not, DB admin should install it.
            try {
                DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm;');
                DB::statement('CREATE INDEX IF NOT EXISTS products_name_trgm_idx ON products USING gin (name gin_trgm_ops);');
            } catch (\Throwable $e) {
                // If permission denied or extension not available, ignore but log for dev
                // You may want to run the extension creation manually if needed.
            }
        } else {
            // For MySQL you may optionally add a fulltext index here (commented out by default)
            // DB::statement('ALTER TABLE products ADD FULLTEXT INDEX products_name_fulltext (name);');
        }
    }

    public function down(): void
    {
        $driver = config('database.connections.' . config('database.default') . '.driver');
        if ($driver === 'pgsql') {
            try { DB::statement('DROP INDEX IF EXISTS products_name_trgm_idx;'); } catch (\Throwable $e) { /* ignore */ }
        }
        Schema::dropIfExists('products');
    }
};
