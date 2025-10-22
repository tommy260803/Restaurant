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
        Schema::create('categorias', function (Blueprint $table) {
            // Clave Primaria - Coincide con idCategoria INT
            // Usamos $table->increments() o $table->integer() ya que en tu BD es INT
            // Sin embargo, por convención de Laravel, id() es mejor, 
            // pero si quieres INT explícito:
            // $table->integer('idCategoria')->unsigned()->autoIncrement()->primary();
            
            // Usaremos id(), que crea una PK y se recomienda en Laravel, 
            // aunque genere BIGINT, es compatible y más seguro.
            $table->id('idCategoria'); 

            // Atributos definidos en $fillable
            $table->string('nombre', 100); 
            $table->text('descripcion')->nullable(); 
            // Coincide con el tipo INT de tu tabla
            $table->integer('estado')->default(1); 

            // Timestamps (created_at, updated_at)
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
