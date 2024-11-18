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
        Schema::create('pagos_donacion', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 20);
            $table->string('descripcion', 100);
            $table->date('fecha_pago');
            $table->integer('monto')->notNull();
            $table->unsignedBigInteger('mes_id');
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('mes_id')->references('id')->on('meses')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_patrocinio');
    }
};
