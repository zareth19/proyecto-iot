<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Actualizar datos existentes
        DB::table('users')->update([
            'tipo_documento' => DB::raw("CASE 
                WHEN tipo_documento = 'CC' THEN 'Cédula de Ciudadanía'
                WHEN tipo_documento = 'TI' THEN 'Tarjeta de Identidad' 
                WHEN tipo_documento = 'CE' THEN 'Cédula de Extranjería'
                ELSE tipo_documento 
            END")
        ]);
        
        // Modificar el enum
        DB::statement("ALTER TABLE users MODIFY COLUMN tipo_documento ENUM('Cédula de Ciudadanía', 'Tarjeta de Identidad', 'Cédula de Extranjería', 'Pasaporte') NOT NULL");
    }

    public function down()
    {
        // Revertir datos
        DB::table('users')->update([
            'tipo_documento' => DB::raw("CASE 
                WHEN tipo_documento = 'Cédula de Ciudadanía' THEN 'CC'
                WHEN tipo_documento = 'Tarjeta de Identidad' THEN 'TI' 
                WHEN tipo_documento = 'Cédula de Extranjería' THEN 'CE'
                ELSE 'CC' 
            END")
        ]);
        
        DB::statement("ALTER TABLE users MODIFY COLUMN tipo_documento ENUM('CC', 'TI', 'CE') NOT NULL");
    }
};