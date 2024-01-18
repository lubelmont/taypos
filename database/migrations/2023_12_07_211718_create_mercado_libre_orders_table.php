<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ml_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id');
            $table->bigInteger('buyer_id');
            $table->string('buyer_nickname');
            $table->string('buyer_first_name');
            $table->string('buyer_last_name');
            $table->timestamp('date_created');
            $table->timestamp('last_updated');
            $table->timestamp('expiration_date');
            $table->timestamp('date_closed')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('fulfilled');
            $table->string('buying_mode');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('paid_amount', 8, 2);
            $table->string('currency_id');
            $table->string('status');
            $table->timestamps();

            $table->foreign('seller_id')->references('id_mercadolibre')->on('ml_usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ml_orders');
    }
};