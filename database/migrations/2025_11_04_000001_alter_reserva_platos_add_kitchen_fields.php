<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reserva_platos', function (Blueprint $table) {
            if (!Schema::hasColumn('reserva_platos', 'id')) {
                $table->bigIncrements('id');
            }
            if (!Schema::hasColumn('reserva_platos', 'estado')) {
                $table->string('estado')->default('Enviado a cocina');
            }
            if (!Schema::hasColumn('reserva_platos', 'observaciones')) {
                $table->text('observaciones')->nullable();
            }
            if (!Schema::hasColumn('reserva_platos', 'en_preparacion_at')) {
                $table->dateTime('en_preparacion_at')->nullable();
            }
            if (!Schema::hasColumn('reserva_platos', 'preparado_at')) {
                $table->dateTime('preparado_at')->nullable();
            }
            if (!Schema::hasColumn('reserva_platos', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reserva_platos', function (Blueprint $table) {
            if (Schema::hasColumn('reserva_platos', 'preparado_at')) {
                $table->dropColumn('preparado_at');
            }
            if (Schema::hasColumn('reserva_platos', 'en_preparacion_at')) {
                $table->dropColumn('en_preparacion_at');
            }
            if (Schema::hasColumn('reserva_platos', 'observaciones')) {
                $table->dropColumn('observaciones');
            }
            if (Schema::hasColumn('reserva_platos', 'estado')) {
                $table->dropColumn('estado');
            }
            // No eliminamos 'id' ni timestamps en down() para evitar pérdida de PK si ya hay datos críticos.
        });
    }
};
