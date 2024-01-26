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
        Schema::create('ml_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); 
            $table->string('id_mercadolibre')->unique();
            $table->string('nickname');
            $table->timestamp('registration_date');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('country_id');
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_usuarios');
    }
};
