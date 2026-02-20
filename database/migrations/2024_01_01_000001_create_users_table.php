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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->integer('edad');
            $table->string('telefono', 15);
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'cliente'])->default('cliente');
            $table->enum('tipo_cliente', ['temporal', 'permanente'])->default('temporal');
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->timestamps();
            
            // Índices para optimización
            $table->index(['role', 'activo']);
            $table->index(['tipo_cliente']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
