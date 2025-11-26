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
    Schema::create('cortes_caja', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')->constrained('users'); // La empleada que hizo el corte
        
        $table->date('fecha');
        $table->enum('turno', ['matutino', 'vespertino']);
        $table->decimal('fondo_caja_inicial', 8, 2)->default(0);
        
        // Campos calculados por el sistema
        $table->decimal('total_ventas_calculado', 8, 2);
        $table->integer('total_servicios_lavadora');
        $table->integer('total_servicios_secadora');
        $table->integer('total_servicios_doblado');
        $table->decimal('total_comisiones_pagadas', 8, 2); // (total_servicios_doblado * 5.00)
        
        // Campos llenados por la empleada
        $table->decimal('total_efectivo_reportado', 8, 2);
        $table->decimal('total_transferencia_reportado', 8, 2);
        $table->decimal('total_general_reportado', 8, 2); // (efectivo + transferencia)
        
        // El resultado final
        $table->decimal('diferencia', 8, 2); // (total_general_reportado - (total_ventas_calculado - total_comisiones_pagadas))
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cortes_caja');
    }
};
