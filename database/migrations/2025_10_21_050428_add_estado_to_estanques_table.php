<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
        
        Schema::table('estanques', function (Blueprint $table) {
            $table->enum('estado', ['activo', 'mantenimiento', 'inactivo'])->default('activo')->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
        
        Schema::table('estanques', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('descripcion');
        });
    }
};