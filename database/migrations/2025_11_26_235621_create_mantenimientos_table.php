<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "Revisión Red Hidráulica" o "Fuga en Lavadora 3"
            $table->text('descripcion')->nullable(); // Los detalles técnicos que me diste
            
            // Clasificación
            $table->enum('categoria', ['infraestructura', 'maquinaria']); 
            $table->enum('tipo', ['preventivo', 'urgente']); 
            
            // Lógica de Tiempos
            $table->integer('frecuencia_dias')->nullable(); // Solo para preventivos (ej: 45, 90)
            $table->date('fecha_programada'); // Cuándo toca (o cuándo se reportó si es urgente)
            $table->date('fecha_realizada')->nullable(); // Cuándo se hizo por última vez
            
            $table->enum('estado', ['pendiente', 'realizado'])->default('pendiente');
            
            // Quién reportó (para urgentes) o quién realizó
            $table->foreignId('usuario_id')->constrained('users'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};