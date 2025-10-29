<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->dropColumn('tipo_cultivo');
        });
        
        Schema::table('estanques', function (Blueprint $table) {
            $table->string('tipo_cultivo')->after('identificador');
        });
    }

    public function down()
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->dropColumn('tipo_cultivo');
        });
        
        Schema::table('estanques', function (Blueprint $table) {
            $table->enum('tipo_cultivo', ['tilapia', 'cachama', 'trucha', 'bagre', 'otro'])->after('identificador');
        });
    }
};