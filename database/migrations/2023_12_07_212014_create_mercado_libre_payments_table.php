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
        Schema::create('ml_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payer_id');
            $table->string('payment_method_id');
            $table->string('currency_id');
            $table->integer('installments');
            $table->string('status');
            $table->decimal('transaction_amount', 8, 2);
            $table->decimal('total_paid_amount', 8, 2);
            $table->timestamp('date_approved')->nullable();
            $table->timestamp('date_created');
            $table->timestamp('date_last_modified')->nullable();
            $table->decimal('marketplace_fee', 8, 2)->nullable();
            $table->string('authorization_code')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('ml_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_payments');
    }
};
