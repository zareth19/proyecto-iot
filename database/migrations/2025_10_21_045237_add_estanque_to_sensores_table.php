<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sensores_final', function (Blueprint $table) {
            $table->unsignedBigInteger('estanque_id')->nullable()->after('id');
            $table->foreign('estanque_id')->references('id')->on('estanques');
        });
    }

    public function down()
    {
        Schema::table('sensores_final', function (Blueprint $table) {
            $table->dropForeign(['estanque_id']);
            $table->dropColumn('estanque_id');
        });
    }
};