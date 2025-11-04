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
        Schema::create('reserva_platos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserva_id');
            $table->unsignedBigInteger('plato_id');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 8, 2); // Precio del plato al momento de la reserva
            $table->text('notas')->nullable(); // Notas especiales por plato (ej: sin sal)
            $table->timestamps();

            // Foreign keys
            $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
            $table->foreign('plato_id')->references('idPlatoProducto')->on('platos_productos')->onDelete('cascade');

            // Índices para mejorar rendimiento
            $table->index(['reserva_id', 'plato_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_platos');
    }
};
