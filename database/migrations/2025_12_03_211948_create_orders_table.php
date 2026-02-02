<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create orders table.
     *
     * Catatan workflow stok:
     * - saat checkout -> buat reservation movement & update inventory.reserved/available.
     * - saat cancel -> unreserve.
     * - saat ship/confirm -> buat sale_shipment movement & update inventory.on_hand/reserved.
     *
     * Pastikan trigger/logic inventory di service/repository, bukan di migration ini.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            // PK internal (BIGSERIAL)
            $table->bigIncrements('id')->comment('Primary key internal (bigserial)');

            // Public UUID untuk referensi di API/client
            $table->uuid('public_id')
                ->unique()
                ->comment('UUID publik order, gunakan untuk exposed ID');

            // Nomor order human readable / merchant-facing
            $table->text('order_no')
                ->comment("Nomor transaksi. Format disarankan: ORD{{RANDOMSTRING 8 CHAR}}");
            $table->unique('order_no');

            // Relasi ke customer
            $table->unsignedBigInteger('customer_id')
                ->index()
                ->comment('FK -> customers(id)');

            // Relasi branch yang menangani order
            $table->unsignedBigInteger('branch_id')
                ->index()
                ->comment('FK -> branches(id)');

            // Status order (pending/paid/processing/shipped/complete/cancelled/return)
            $table->string('status')
                ->index()
                ->comment('Order status: pending/paid/processing/shipped/complete/cancelled/return');

            // Currency code (IDR default)
            $table->string('currency', 10)
                ->default('IDR')
                ->comment("Mata uang, contohnya 'IDR'");

            // Monetary fields (stored in smallest currency unit, e.g. cents/IDR)
            $table->bigInteger('subtotal_before_discount')
                ->comment('Sum(line_total_before_discount)');
            $table->bigInteger('discount_total')
                ->default(0)
                ->comment('Total diskon (produk + coupon + promotion)');
            $table->bigInteger('subtotal_after_discount')
                ->comment('subtotal_before_discount - discount_total');
            $table->bigInteger('shipping_cost')
                ->nullable()
                ->comment('Biaya kirim, null kalau free shipping');
            $table->bigInteger('other_charges')
                ->nullable()
                ->comment('Biaya lain (handling, insurance). Nullable');
            $table->bigInteger('tax_total')
                ->nullable()
                ->comment('Total pajak yang dibebankan. Nullable');
            $table->bigInteger('total_amount')
                ->comment('Final amount payable (subtotal_after_discount + tax + shipping + other)');

            // payment
            $table->unsignedBigInteger('payment_method_id')
                ->nullable()
                ->comment('FK -> payment_methods(id), nullable (boleh belum dipilih)');
            $table->string('payment_status')
                ->index()
                ->comment('pending/paid/failed/refunded');

            // Rincian JSON
            $table->json('tax_breakdown')
                ->nullable()
                ->comment('Rincian pajak per rule (JSON/JSONB)');
            $table->json('discount_breakdown')
                ->nullable()
                ->comment('Rincian diskon (produk, coupon, promotion) (JSON/JSONB)');
            $table->json('applied_promotions')
                ->nullable()
                ->comment('Snapshot promotions/coupons applied (JSON/JSONB)');

            // Catatan & metadata
            $table->text('notes')->nullable()->comment('Catatan order (opsional)');
            $table->json('meta')->nullable()->comment('Metadata bebas (original_cart_id, internal flags, dsb)');

            // Waktu
            $table->timestampTz('placed_at')->nullable()->comment('Waktu order dipasang / placed (bisa berbeda dari created_at)');
            $table->timestampsTz(); // created_at, updated_at (with timezone)
            $table->timestampTz('cancelled_at')->nullable()->comment('Jika dibatalkan, waktu pembatalan');
            $table->softDeletes('deleted_at')->comment('Soft delete timestamp (nullable)');

            // Refund flag
            $table->boolean('is_refunded')
                ->default(false)
                ->comment('Flag apakah order sudah/refunded');

            // Index tambahan: composite dan performa
            $table->index(['branch_id', 'status', 'placed_at'], 'orders_branch_status_placed_idx');
            $table->index(['placed_at', 'branch_id'], 'orders_placed_branch_idx'); // untuk query laporan by date + branch
        });

        // Add foreign keys after table created to keep clarity (and allow cross-db portability).
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('customer_id', 'orders_customer_fk')
                ->references('id')->on('customers')
                ->onUpdate('cascade')
                ->onDelete('restrict'); // jangan langsung hapus customer jika ada order

            $table->foreign('branch_id', 'orders_branch_fk')
                ->references('id')->on('branches')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('payment_method_id', 'orders_payment_method_fk')
                ->references('id')->on('payment_methods')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });

        // Optional: add a table comment (Postgres). Jika DB bukan Postgres, ignore error.
        try {
            DB::statement("COMMENT ON TABLE orders IS 'Orders master table. Monetary fields stored in smallest currency unit. See column comments for detail.';");
        } catch (\Throwable $e) {
            // ignore if DB doesn't support table comments
        }
    }

    public function down(): void
    {
        // Drop foreign keys first (guard against missing fks)
        Schema::table('orders', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            // Attempt to drop named FKs gracefully
            try { $table->dropForeign('orders_customer_fk'); } catch (\Throwable $e) {}
            try { $table->dropForeign('orders_branch_fk'); } catch (\Throwable $e) {}
            try { $table->dropForeign('orders_payment_method_fk'); } catch (\Throwable $e) {}
        });

        Schema::dropIfExists('orders');
    }
};
