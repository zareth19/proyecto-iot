<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@piscicola.com',
            'password' => Hash::make('admin123'),
        ]);

        // Operario
        User::create([
            'name' => 'Operario',
            'email' => 'operario@piscicola.com', 
            'password' => Hash::make('operario123'),
        ]);

        // Usuario para recibir alertas
        User::create([
            'name' => 'Zareth Fuentes',
            'email' => 'zarethfuentes2@gmail.com',
            'password' => Hash::make('zareth123'),
        ]);
    }
}