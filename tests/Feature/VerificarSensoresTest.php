<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SensorData;
use App\Models\User;
use App\Services\VerificarSensores;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerificarSensoresTest extends TestCase
{
    use RefreshDatabase;

    public function test_envia_correo_si_parametros_estan_fuera_de_rango()
    {
        Mail::fake();

        User::factory()->create(['email' => 'test@example.com']);

        $sensor = SensorData::factory()->create([
            'ph' => 9.0, // fuera de rango
            'temperatura' => 35, // fuera de rango
            'turbidez' => 60, // fuera de rango
        ]);

        $servicio = new VerificarSensores();
        $alertas = $servicio->ejecutar($sensor);

        $this->assertNotEmpty($alertas);
    }

    public function test_no_envia_correo_si_parametros_estan_ok()
    {
        Mail::fake();

        $sensor = SensorData::factory()->create([
            'ph' => 7.2,
            'temperatura' => 26,
            'turbidez' => 20,
        ]);

        $servicio = new VerificarSensores();
        $alertas = $servicio->ejecutar($sensor);

        $this->assertEmpty($alertas);
        Mail::assertNothingSent();
    }
}
