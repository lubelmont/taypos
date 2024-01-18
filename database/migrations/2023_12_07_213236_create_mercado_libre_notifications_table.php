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
        Schema::create('ml_notifications', function (Blueprint $table) {
            $table->uuid('_id')->primary();
            $table->string('topic');
            $table->string('resource');
            $table->bigInteger('user_id');
            $table->bigInteger('application_id');
            $table->timestamp('sent');
            $table->integer('attempts');
            $table->timestamp('received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_notifications');
    }
};
