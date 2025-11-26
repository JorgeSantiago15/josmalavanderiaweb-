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
    Schema::create('nota_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('nota_id')->constrained('notas');
        $table->foreignId('producto_id')->constrained('productos');
        
        $table->decimal('cantidad', 8, 2); // Ej. 1.5 para 1.5 cargas, o 3 para 3 jabones
        $table->decimal('precio_unitario', 8, 2); // Guardamos el precio del producto AL MOMENTO de la venta
        $table->decimal('subtotal', 8, 2); // Se calcula (cantidad * precio_unitario)
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_items');
    }
};
