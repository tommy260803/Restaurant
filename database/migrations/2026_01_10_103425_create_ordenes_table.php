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
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mesa_id');
            $table->enum('estado', ['abierta', 'pagada', 'cancelada'])->default('abierta');
            $table->decimal('total', 10, 2)->default(0);
            $table->unsignedBigInteger('abierta_por')->nullable(); // user_id del mesero
            $table->timestamp('fecha_apertura')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('mesa_id')->references('id')->on('mesas')->onDelete('cascade');
            $table->foreign('abierta_por')->references('idUsuario')->on('usuarios_mi_acta')->onDelete('set null');

            // Índices
            $table->index('mesa_id');
            $table->index('estado');
            $table->index('fecha_apertura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};