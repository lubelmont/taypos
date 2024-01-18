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
        Schema::create('esimgo_orders_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('iccid');
            $table->string('matchingId');
            $table->string('rspUrl');
            $table->string('bundle');
            $table->string('orderReference');
            $table->timestamps();

            $table->foreign('orderReference')->references('orderReference')->on('esimgo_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esimgo_orders_assignments');
    }
};
