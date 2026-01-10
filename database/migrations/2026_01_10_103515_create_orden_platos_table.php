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
        Schema::create('orden_platos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_id');
            $table->unsignedBigInteger('plato_id');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 8, 2);
            $table->decimal('subtotal', 10, 2);
            $table->enum('estado_cocina', [
                'Enviado a cocina',
                'En preparación',
                'Preparado',
                'Entregado'
            ])->default('Enviado a cocina');
            $table->text('notas')->nullable();
            $table->timestamp('enviado_cocina_at')->nullable();
            $table->timestamp('en_preparacion_at')->nullable();
            $table->timestamp('preparado_at')->nullable();
            $table->timestamp('entregado_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('orden_id')->references('id')->on('ordenes')->onDelete('cascade');
            $table->foreign('plato_id')->references('idPlatoProducto')->on('platos_productos')->onDelete('cascade');

            // Índices
            $table->index(['orden_id', 'estado_cocina']);
            $table->index('estado_cocina');
            $table->index('enviado_cocina_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_platos');
    }
};