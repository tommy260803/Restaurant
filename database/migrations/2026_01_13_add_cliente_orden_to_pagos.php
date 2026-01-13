<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'cliente_id')) {
                $table->unsignedInteger('cliente_id')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('pagos', 'orden_id')) {
                $table->unsignedBigInteger('orden_id')->nullable()->after('cliente_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (Schema::hasColumn('pagos', 'orden_id')) {
                $table->dropColumn('orden_id');
            }
            
            if (Schema::hasColumn('pagos', 'cliente_id')) {
                $table->dropColumn('cliente_id');
            }
        });
    }
};
