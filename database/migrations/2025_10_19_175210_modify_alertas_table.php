<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alertas', function (Blueprint $table) {
            // Eliminar columnas antiguas si existen
            if (Schema::hasColumn('alertas', 'sensor')) {
                $table->dropColumn('sensor');
            }
            if (Schema::hasColumn('alertas', 'valor')) {
                $table->dropColumn('valor');
            }
            if (Schema::hasColumn('alertas', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
            
            // Agregar nuevas columnas si no existen
            if (!Schema::hasColumn('alertas', 'sensor_id')) {
                $table->unsignedBigInteger('sensor_id')->after('id');
            }
            if (!Schema::hasColumn('alertas', 'tipo')) {
                $table->string('tipo')->after('sensor_id');
            }
            if (!Schema::hasColumn('alertas', 'mensaje')) {
                $table->text('mensaje')->after('tipo');
            }
            if (!Schema::hasColumn('alertas', 'nivel')) {
                $table->string('nivel')->default('warning')->after('mensaje');
            }
            if (!Schema::hasColumn('alertas', 'leida')) {
                $table->boolean('leida')->default(false)->after('nivel');
            }
            if (!Schema::hasColumn('alertas', 'fecha_alerta')) {
                $table->timestamp('fecha_alerta')->after('leida');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->dropColumn(['sensor_id', 'tipo', 'mensaje', 'nivel', 'leida', 'fecha_alerta']);
            $table->string('sensor');
            $table->float('valor');
            $table->text('descripcion')->nullable();
        });
    }
};