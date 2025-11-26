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
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 100);
        $table->decimal('precio', 8, 2); // 8 dígitos en total, 2 después del punto (ej. 110.00)
        $table->enum('tipo', ['servicio', 'producto', 'descuento']);
        $table->enum('reporte_categoria', ['lavadora', 'secadora', 'doblado'])->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
