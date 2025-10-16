<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo_documento', ['CC', 'TI', 'CE'])->default('CC');
            $table->string('numero_documento')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('telefono')->nullable();
            $table->string('contraseña');
            $table->rememberToken();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
