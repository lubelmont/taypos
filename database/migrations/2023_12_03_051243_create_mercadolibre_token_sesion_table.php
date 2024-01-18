<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ml_token_sesions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_mercadolibre');
            $table->string('access_token');
            $table->string('token_type');
            $table->integer('expires_in');
            $table->string('scope');
            $table->string('refresh_token');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ml_token_sesions');
    }
};
