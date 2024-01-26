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
        Schema::create('ml_order_items', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('item_id');
            $table->string('title');
            $table->string('category_id');
            $table->string('seller_sku');
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('full_unit_price', 8, 2);
            $table->decimal('sale_fee', 8, 2);
            $table->string('currency_id');
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('ml_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_order_items');
    }
};
