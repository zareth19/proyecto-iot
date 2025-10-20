<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Manual Individual</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
        .subtitle { font-size: 14px; color: #666; }
        .info-box { background: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .data-row { display: flex; justify-content: space-between; margin: 10px 0; }
        .label { font-weight: bold; color: #333; }
        .value { color: #666; }
        .normal { color: #16a34a; font-weight: bold; }
        .alerta { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
        .measurements { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin: 20px 0; }
        .measurement-card { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">REPORTE MANUAL INDIVIDUAL</h1>
        <p class="subtitle">Sistema IoT - Monitoreo de Calidad del Agua</p>
        <p class="subtitle">Reporte #{{ $reporte->id }}</p>
    </div>

    <div class="info-box">
        <div class="data-row">
            <span class="label">Operario:</span>
            <span class="value">{{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}</span>
        </div>
        <div class="data-row">
            <span class="label">Fecha y Hora de Toma:</span>
            <span class="value">{{ $reporte->fecha_toma->format('d/m/Y H:i') }}</span>
        </div>
        <div class="data-row">
            <span class="label">Fecha de Generación:</span>
            <span class="value">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <h3>Mediciones Registradas:</h3>
    <div class="measurements">
        <div class="measurement-card">
            <h4>Temperatura</h4>
            <p class="{{ $reporte->temperatura >= 24 && $reporte->temperatura <= 28 ? 'normal' : 'alerta' }}">
                {{ $reporte->temperatura }}°C
            </p>
            <small>Rango normal: 24-28°C</small>
        </div>
        
        <div class="measurement-card">
            <h4>pH</h4>
            <p class="{{ $reporte->ph >= 6.5 && $reporte->ph <= 8.5 ? 'normal' : 'alerta' }}">
                {{ $reporte->ph }}
            </p>
            <small>Rango normal: 6.5-8.5</small>
        </div>
        
        <div class="measurement-card">
            <h4>Turbidez</h4>
            <p class="{{ $reporte->turbidez <= 5 ? 'normal' : 'alerta' }}">
                {{ $reporte->turbidez }} NTU
            </p>
            <small>Máximo: 5 NTU</small>
        </div>
    </div>

    @if($reporte->observaciones)
        <div class="info-box">
            <strong>Observaciones:</strong><br>
            {{ $reporte->observaciones }}
        </div>
    @endif

    <div class="info-box">
        <strong>Análisis de Parámetros:</strong><br>
        @php
            $tempOk = $reporte->temperatura >= 24 && $reporte->temperatura <= 28;
            $phOk = $reporte->ph >= 6.5 && $reporte->ph <= 8.5;
            $turbidezOk = $reporte->turbidez <= 5;
            $estadoGeneral = $tempOk && $phOk && $turbidezOk;
            $sensoresFueraRango = [];
            
            if (!$tempOk) {
                $sensoresFueraRango[] = "Temperatura (" . $reporte->temperatura . "°C) - " . 
                    ($reporte->temperatura < 24 ? "Por debajo del mínimo (24°C)" : "Por encima del máximo (28°C)");
            }
            
            if (!$phOk) {
                $sensoresFueraRango[] = "pH (" . $reporte->ph . ") - " . 
                    ($reporte->ph < 6.5 ? "Por debajo del mínimo (6.5)" : "Por encima del máximo (8.5)");
            }
            
            if (!$turbidezOk) {
                $sensoresFueraRango[] = "Turbidez (" . $reporte->turbidez . " NTU) - Por encima del máximo permitido (5 NTU)";
            }
        @endphp
        
        @if($estadoGeneral)
            <span class="normal">✓ ESTADO NORMAL - Todos los parámetros dentro del rango aceptable</span>
        @else
            <span class="alerta">⚠ ALERTA DETECTADA - Los siguientes parámetros están fuera de rango:</span><br><br>
            @foreach($sensoresFueraRango as $sensor)
                <span class="alerta">• {{ $sensor }}</span><br>
            @endforeach
            
            <br><strong>Recomendaciones:</strong><br>
            @if(!$tempOk)
                • Verificar sistema de calefacción/enfriamiento del agua<br>
            @endif
            @if(!$phOk)
                • Revisar sistema de regulación de pH y calidad del agua<br>
            @endif
            @if(!$turbidezOk)
                • Inspeccionar sistema de filtración y limpieza<br>
            @endif
        @endif
    </div>

    <div class="footer">
        <p>Documento generado automáticamente por el Sistema IoT de Monitoreo</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>