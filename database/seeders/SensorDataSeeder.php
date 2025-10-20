<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorData;
use Carbon\Carbon;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear datos de los últimos 30 registros
        for ($i = 29; $i >= 0; $i--) {
            SensorData::create([
                'fecha' => Carbon::now()->subMinutes($i * 5),
                'temperatura' => round(25 + (rand(-20, 30) / 10), 2),
                'ph' => round(7.0 + (rand(-15, 15) / 10), 2),
                'oxigeno_disuelto' => round(5.0 + (rand(-10, 20) / 10), 2),
                'amoniaco' => round(0.02 + (rand(0, 5) / 100), 3),
                'nitritos' => round(0.1 + (rand(0, 10) / 100), 2),
                'nitratos' => round(50.0 + rand(-20, 40), 2),
                'alcalinidad' => round(80.0 + rand(-30, 30), 2),
                'dureza' => round(150.0 + rand(-50, 100), 2),
                'turbidez' => round(25.0 + rand(-10, 20), 2),
                'conductividad' => round(200.0 + rand(-50, 100), 2)
            ]);
        }
    }
}