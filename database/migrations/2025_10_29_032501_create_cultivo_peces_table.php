<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cultivo_peces', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_especie');
            $table->string('nombre_cientifico')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('densidad_recomendada', 5, 2)->default(10.00); // peces por m²
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivo_peces');
    }
};
