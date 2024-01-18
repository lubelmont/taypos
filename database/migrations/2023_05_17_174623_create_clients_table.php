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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            $table->string('taxpayer_id',20);
            $table->string('taxpayer_name',150);
            $table->string('taxpayer_regfiscal',10);
            $table->string('zip',150);
            $table->string('email',150);


            $table->string('street',100)->nullable();
            $table->string('num_ext',25)->nullable();
            $table->string('num_int',50)->nullable();
            $table->string('col',100)->nullable();
            $table->string('locality',50)->nullable();
            $table->string('municipality',150)->nullable();
            $table->string('state',50)->nullable();
            $table->string('country',255)->nullable();


            $table->string('payment_default',150)->nullable();
            $table->string('payment_method_default',150)->nullable();
            $table->string('cfdi_use_default',150)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
