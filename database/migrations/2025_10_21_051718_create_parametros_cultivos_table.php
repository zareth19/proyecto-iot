<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parametros_cultivos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_cultivo');
            $table->decimal('temp_min', 5, 2);
            $table->decimal('temp_max', 5, 2);
            $table->decimal('ph_min', 4, 2);
            $table->decimal('ph_max', 4, 2);
            $table->decimal('turbidez_min', 6, 2);
            $table->decimal('turbidez_max', 6, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parametros_cultivos');
    }
};