<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin';
    protected $description = 'Crear un nuevo usuario administrador';

    public function handle()
    {
        $user = User::create([
            'tipo_documento' => 'CC',
            'numero_documento' => '12345678',
            'nombre' => 'Admin',
            'apellido' => 'Sistema',
            'primer_nombre' => 'Admin',
            'primer_apellido' => 'Sistema',
            'correo' => 'admin@sistema.com',
            'telefono' => '3001234567',
            'contraseña' => Hash::make('admin123'),
            'rol' => 'admin',
            'debe_cambiar_contraseña' => false
        ]);

        $this->info('Usuario administrador creado exitosamente:');
        $this->info('Email: admin@sistema.com');
        $this->info('Contraseña: admin123');
        $this->warn('¡Cambia la contraseña después de iniciar sesión!');
    }
}