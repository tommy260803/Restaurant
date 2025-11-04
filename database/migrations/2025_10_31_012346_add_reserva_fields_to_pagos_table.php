<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Añadir columnas nuevas si aún no existen
        Schema::table('pagos', function (Blueprint $table) {
            if (! Schema::hasColumn('pagos', 'numero_operacion')) {
                $table->string('numero_operacion')->nullable()->after('metodo');
            }
            if (! Schema::hasColumn('pagos', 'estado')) {
                $table->enum('estado', ['pendiente', 'confirmado', 'fallido'])->default('pendiente')->after('fecha');
            }

            // Asegurar que reserva_id existe y es compatible con reservas.id (unsignedBigInteger)
            if (! Schema::hasColumn('pagos', 'reserva_id')) {
                $table->unsignedBigInteger('reserva_id')->nullable()->after('venta_id');
            }
        });

        // Intentar crear la FK en una operación separada; envolver en try para evitar errores si ya existe
        try {
            Schema::table('pagos', function (Blueprint $table) {
                $table->foreign('reserva_id')->references('id')->on('reservas')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Si falla, registrar el mensaje en el log para revisión manual
            Log::warning('No se pudo crear la clave foránea pagos.reserva_id: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            // intentar quitar la FK si existe
            try {
                $table->dropForeign(['reserva_id']);
            } catch (\Exception $e) {
                // ignorar si no existe
            }

            // quitar columnas creadas
            if (Schema::hasColumn('pagos', 'numero_operacion')) {
                $table->dropColumn('numero_operacion');
            }
            if (Schema::hasColumn('pagos', 'estado')) {
                $table->dropColumn('estado');
            }

            // opcional: quitar reserva_id si quieres revertir totalmente
            if (Schema::hasColumn('pagos', 'reserva_id')) {
                $table->dropColumn('reserva_id');
            }
        });
    }
};
