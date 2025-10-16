<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesYUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operarioRole = Role::firstOrCreate(['name' => 'operario']);
        $estandarRole = Role::firstOrCreate(['name' => 'estandar']);
        

        // Crear usuarios
        $admin = User::create([
            'tipo_documento' => 'CC',
            'numero_documento' => '1046527089',
            'nombre' => 'Brainer',
            'apellido' => 'Clemente',
            'correo' => 'admin@gmail.com',
            'telefono' => '3212609253',
            'contraseña' => Hash::make('admin123'),
        ]);
        $admin->assignRole($adminRole);

        $operario = User::create([
            'tipo_documento' => 'CC',
            'numero_documento' => '100000003',
            'nombre' => 'Luis',
            'apellido' => 'Medrano',
            'correo' => 'operario@gmail.com',
            'telefono' => '3000000000',
            'contraseña' => Hash::make('operario123'),
        ]);
        $operario->assignRole($operarioRole);

        $estandar = User::create([
            'tipo_documento' => 'CC',
            'numero_documento' => '100000002',
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'correo' => 'estandar@gmail.com',
            'telefono' => '3200000000',
            'contraseña' => Hash::make('estandar123'),
        ]);
        $estandar->assignRole($estandarRole);

        
    }
}
