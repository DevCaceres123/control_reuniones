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
        Schema::create('reporte_pago_final', function (Blueprint $table) {
            $table->id();
            $table->year('anio'); // Año del reporte
            $table->string('nombre_completo',150); 
            $table->integer('total');
            $table->string('tipo',20);
            $table->unsignedBigInteger('estudiante_id'); // ID del estudiante
         
            // Columnas para los meses
            $table->string('enero', 20)->nullable();
            $table->string('febrero', 20)->nullable();
            $table->string('marzo', 20)->nullable();
            $table->string('abril', 20)->nullable();
            $table->string('mayo', 20)->nullable();
            $table->string('junio', 20)->nullable();
            $table->string('julio', 20)->nullable();
            $table->string('agosto', 20)->nullable();
            $table->string('septiembre', 20)->nullable();
            $table->string('octubre', 20)->nullable();
            $table->string('noviembre', 20)->nullable();
            $table->string('diciembre', 20)->nullable();
           
            $table->unique(['estudiante_id', 'anio']); // Para evitar duplicados

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_pago_final');
    }
};
