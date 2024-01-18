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
        Schema::create('esimgo_orders', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('item');
            $table->integer('quantity');
            $table->decimal('subTotal', 8, 2);
            $table->decimal('pricePerUnit', 8, 2);
            $table->decimal('total', 8, 2);
            $table->boolean('valid');
            $table->string('currency');
            $table->timestamp('createdDate');
            $table->boolean('assigned');
            $table->string('status');
            $table->string('statusMessage');
            $table->string('orderReference')->unique();
            $table->string('order_from');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esimgo_orders');
    }
};
