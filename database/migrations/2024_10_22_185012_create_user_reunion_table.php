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
        Schema::create('user_reunion', function (Blueprint $table) {
            $table->id();
            $table->string('manual',50)->nullable();
            $table->string('user_manual')->nullable();
            $table->dateTime('entrada')->nullable();
            $table->dateTime('salida')->nullable();
            $table->dateTime('atraso')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reunion_id');
           
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('reunion_id')->references('id')->on('reuniones')->onDelete('restrict')->onUpdate('cascade');
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reunion');
    }
};
