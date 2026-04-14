<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        });

        // Safe transfer of existing data to pivot table
        DB::statement('INSERT INTO category_product (category_id, product_id) SELECT category_id, id FROM products WHERE category_id IS NOT NULL');

        // Drop category_id from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        });

        // Best effort to restore the 1-to-N relationship (keeps the first category assigned)
        DB::statement('UPDATE products p SET p.category_id = (SELECT cp.category_id FROM category_product cp WHERE cp.product_id = p.id LIMIT 1)');

        Schema::dropIfExists('category_product');
    }
};
