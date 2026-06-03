<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurante_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurante_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('estado')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('restaurante_id')->references('id')->on('restaurantes')->onDelete('cascade');
            $table->index('restaurante_id');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurante_users');
    }
};




