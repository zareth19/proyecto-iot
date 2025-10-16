<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VerificarSensores;

class VerificarSensoresCommand extends Command
{
    protected $signature = 'sensores:verificar';
    protected $description = 'Verificar sensores y enviar alertas automáticamente';

    public function handle()
    {
        $verificarSensores = app(VerificarSensores::class);
        $alertas = $verificarSensores->ejecutar();
        
        $this->info('Verificación completada. Alertas generadas: ' . count($alertas));
    }
}
