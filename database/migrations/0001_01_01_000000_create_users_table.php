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
        // 1. TABLA DE USUARIOS (Tu versión personalizada)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('usuario')->unique();
            $table->string('password');
            $table->enum('tipo', ['empleada', 'gerente']);
            $table->enum('turno_asignado', ['matutino', 'vespertino']);
            $table->enum('estatus', ['activo', 'inactivo'])->default('activo');
            $table->string('rfc')->nullable();
            $table->string('telefonoReferencia')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. TABLA DE RECUPERACIÓN DE CONTRASEÑA (Estándar de Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. TABLA DE SESIONES (¡ESTA ES LA QUE FALTABA!)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};