<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->integer('capacidad')->nullable()->after('tipo_cultivo')->comment('Capacidad máxima de especies');
        });
    }

    public function down()
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->dropColumn('capacidad');
        });
    }
};