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
        Schema::create('reporte_pagos', function (Blueprint $table) {
            $table->id();
            $table->json('reporte_json')->nullable();
            $table->string('codigo_unico')->unique();
            $table->unsignedBigInteger('user_id');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_pagos');
    }
};
