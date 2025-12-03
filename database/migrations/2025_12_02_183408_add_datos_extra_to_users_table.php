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
    Schema::table('users', function (Blueprint $table) {
        
        // 1. Verificamos si NO existe 'rfc' antes de crearla
        if (!Schema::hasColumn('users', 'rfc')) {
            $table->string('rfc', 20)->nullable()->after('estatus');
        }

        // 2. Verificamos si NO existe 'telefonoReferencia'
        if (!Schema::hasColumn('users', 'telefonoReferencia')) {
            $table->string('telefonoReferencia', 20)->nullable()->after('rfc'); // o after estatus si rfc falló
        }

        // 3. Verificamos si NO existe 'clave_visible' (Esta es la más importante)
        if (!Schema::hasColumn('users', 'clave_visible')) {
            $table->string('clave_visible')->nullable()->after('password');
        }
    });
}
};
