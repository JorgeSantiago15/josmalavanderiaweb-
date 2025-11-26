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
    Schema::create('notas', function (Blueprint $table) {
        $table->id();
        
        // Relaciones con otras tablas (Llaves Foráneas)
        $table->foreignId('cliente_id')->constrained('clientes');
        $table->foreignId('usuario_id')->constrained('users'); // La empleada que la registró
        
        $table->enum('estado', ['en_proceso', 'terminado', 'pagado'])->default('en_proceso');
        $table->text('especificaciones')->nullable(); // Notas para el cuidado de la ropa
        $table->decimal('total', 8, 2)->default(0); // El total se calculará, pero lo guardamos aquí
        
        $table->timestamp('fecha_recepcion')->useCurrent(); // Se pone la fecha/hora actual al crear
        $table->timestamp('fecha_entrega_estimada')->nullable();
        $table->timestamp('fecha_pagado')->nullable(); // Se llena cuando el estado cambia a 'pagado'
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
