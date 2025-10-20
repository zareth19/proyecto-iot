<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reportes_manuales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->decimal('temperatura', 5, 2);
            $table->decimal('ph', 4, 2);
            $table->decimal('turbidez', 6, 2);
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_toma');
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes_manuales');
    }
};