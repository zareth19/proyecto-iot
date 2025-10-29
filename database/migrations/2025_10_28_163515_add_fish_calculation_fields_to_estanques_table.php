<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->decimal('largo', 8, 2)->nullable()->after('capacidad');
            $table->decimal('ancho', 8, 2)->nullable()->after('largo');
            $table->decimal('area_m2', 8, 2)->nullable()->after('ancho');
            $table->integer('cantidad_sembrada')->nullable()->after('area_m2');
            $table->decimal('densidad_siembra', 8, 2)->nullable()->after('cantidad_sembrada');
        });
    }

    public function down(): void
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->dropColumn(['largo', 'ancho', 'area_m2', 'cantidad_sembrada', 'densidad_siembra']);
        });
    }
};