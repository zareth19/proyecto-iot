<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SensorData;
use App\Notifications\AlertaNotificacion;

class TestEmail extends Command
{
    protected $signature = 'test:email';
    protected $description = 'Probar envío de correos de alerta';

    public function handle()
    {
        $this->info('Probando envío de correo...');
        
        // Buscar un usuario con correo
        $usuario = User::whereNotNull('correo')->first();
        
        if (!$usuario) {
            $this->error('No hay usuarios con correo configurado');
            return;
        }
        
        // Crear datos de sensor de prueba
        $sensorData = new SensorData([
            'id' => 999,
            'fecha' => now(),
            'temperatura' => 35.0,
            'ph' => 5.0,
            'turbidez' => 100.0
        ]);
        
        $alertasTilapia = [
            'temperatura (35) fuera del rango ideal para tilapia [26-30]',
            'ph (5) fuera del rango ideal para tilapia [6.5-9.0]'
        ];
        
        try {
            $usuario->notify(new AlertaNotificacion($sensorData, $alertasTilapia, []));
            $this->info("Correo enviado exitosamente a: {$usuario->correo}");
        } catch (\Exception $e) {
            $this->error("Error enviando correo: " . $e->getMessage());
        }
    }
}