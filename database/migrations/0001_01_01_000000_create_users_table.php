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
        // Tabla de usuarios personalizada
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id_usuario')->autoIncrement()->primary();
            $table->integer('dni_usuario')->index();
            $table->string('nombre_usuario', 30);
            $table->string('contrasena', 100);
            $table->string('email_mi_acta', 30)->unique();
            $table->string('email_respaldo', 30)->nullable();
            $table->char('estado', 1)->nullable();
            $table->string('rol', 50)->nullable();
            $table->string('foto', 191)->nullable();
            $table->string('portada', 191)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabla para recuperación de contraseña
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabla de sesiones (para SESSION_DRIVER=database)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('user_id')->nullable()->index(); // apunta a 'id_usuario'
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
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('usuarios');
    }
};
