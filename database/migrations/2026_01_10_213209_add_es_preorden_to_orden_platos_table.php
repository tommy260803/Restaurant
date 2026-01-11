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
        Schema::table('orden_platos', function (Blueprint $table) {
            // Agregar campo para identificar si el plato es de pre-orden
            $table->boolean('es_preorden')->default(false)->after('estado_cocina');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_platos', function (Blueprint $table) {
            $table->dropColumn('es_preorden');
        });
    }
};