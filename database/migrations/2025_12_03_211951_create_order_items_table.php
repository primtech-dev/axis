<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create order_items table.
     *
     * Meant as snapshot lines: simpan SKU, product_name, harga & jumlah pada saat checkout.
     * Hapus baris ketika order dihapus (ON DELETE CASCADE pada order_id).
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            // PK internal
            $table->bigIncrements('id')->comment('Primary key internal (bigserial)');

            // Relasi ke orders (cascade delete)
            $table->unsignedBigInteger('order_id')
                ->index()
                ->comment('FK -> orders(id) ON DELETE CASCADE');

            // Snapshot FK (opsional: tetap simpan FK referensi jika ingin referensi)
            $table->unsignedBigInteger('product_id')
                ->nullable()
                ->comment('FK -> products(id) (snapshot). Nullable kalau produk sudah dihapus dari katalog');
            $table->unsignedBigInteger('variant_id')
                ->nullable()
                ->comment('FK -> product_variants(id) (snapshot). Nullable jika tidak ada variant');

            // Snapshot detail produk
            $table->string('sku')->comment('SKU snapshot pada saat order');
            $table->text('product_name')->comment('Snapshot nama produk pada saat order');

            // Pricing & quantities (smallest currency unit)
            $table->bigInteger('unit_price')->comment('Harga per unit (tanpa diskon) pada saat order');
            $table->integer('line_quantity')->comment('Jumlah unit di line');
            $table->bigInteger('line_total_before_discount')->comment('unit_price * qty');
            $table->bigInteger('line_discount')->default(0)->comment('Diskon yang diaplikasikan ke line');
            $table->bigInteger('line_total_after_discount')->comment('Setelah diskon, sebelum pajak');
            $table->bigInteger('line_tax')->nullable()->comment('Pajak yang dialokasikan ke line');
            $table->bigInteger('line_total')->comment('Final line total (after discount + tax)');

            // JSON details per line
            $table->json('tax_details')->nullable()->comment('Rincian pajak per-line (JSON/JSONB)');
            $table->json('discount_details')->nullable()->comment('Rincian diskon per-line (JSON/JSONB)');

            // Timestamps
            $table->timestampsTz();

            // Index yang membantu query by order
            $table->index(['order_id'], 'order_items_order_id_idx');
        });

        // Foreign keys
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('order_id', 'order_items_order_fk')
                ->references('id')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // jika order dihapus, items ikut terhapus

            // produk & variant FK dibuat dengan onDelete set null untuk menjaga snapshot
            $table->foreign('product_id', 'order_items_product_fk')
                ->references('id')->on('products')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('variant_id', 'order_items_variant_fk')
                ->references('id')->on('product_variants')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });

        try {
            DB::statement("COMMENT ON TABLE order_items IS 'Order line items (snapshot). Contains pricing & tax information per line.';");
        } catch (\Throwable $e) {
            // ignore if DB doesn't support table comments
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            try { $table->dropForeign('order_items_order_fk'); } catch (\Throwable $e) {}
            try { $table->dropForeign('order_items_product_fk'); } catch (\Throwable $e) {}
            try { $table->dropForeign('order_items_variant_fk'); } catch (\Throwable $e) {}
        });

        Schema::dropIfExists('order_items');
    }
};
