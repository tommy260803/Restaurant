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
        // 1. Crear la tabla 'platos_productos'
        Schema::create('platos_productos', function (Blueprint $table) {
            // Clave Primaria - Coincide con protected $primaryKey = 'idPlatoProducto';
            // Laravel usa unsignedBigInteger, que es la práctica recomendada.
            $table->id('idPlatoProducto'); 

            // Atributos definidos en $fillable
            $table->string('nombre', 150); 
            $table->text('descripcion')->nullable(); 
            // Ajustado de (8, 2) a (10, 2) para coincidir con el esquema de la imagen
            $table->decimal('precio', 10, 2); 
            $table->string('imagen', 255)->nullable();
            // boolean() se traduce a tinyint(1), que coincide con el esquema
            $table->boolean('disponible')->default(true)->nullable(); // Permitir NULL si la base de datos lo permite

            // Clave Foránea (FK) - Coincide con 'idCategoria'
            // Se usa unsignedBigInteger por convención de Laravel.
            $table->unsignedBigInteger('idCategoria'); 

            // Timestamps (created_at, updated_at)
            // Se usa ->nullable() para coincidir con la base de datos que permite NULL
            $table->timestamps(); 

            // 2. Definición de la Clave Foránea
            $table->foreign('idCategoria')
                  ->references('idCategoria') // Columna en la tabla 'categorias'
                  ->on('categorias')         // Nombre de la tabla de categorías
                  ->onDelete('cascade');     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platos_productos');
    }
};
