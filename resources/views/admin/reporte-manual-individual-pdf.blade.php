<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Manual Individual</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info { margin-bottom: 20px; }
        .label { font-weight: bold; }
        .value { margin-left: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte Manual Individual</h2>
        <p>ID: {{ $reporte->id }}</p>
    </div>

    <div class="info">
        <p><span class="label">Fecha y Hora:</span><span class="value">{{ $reporte->fecha_toma->format('d/m/Y H:i') }}</span></p>
        <p><span class="label">Estanque:</span><span class="value">{{ $reporte->estanque ? $reporte->estanque->identificador : 'Sin estanque' }}</span></p>
        <p><span class="label">Operario:</span><span class="value">{{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}</span></p>
        <p><span class="label">Temperatura:</span><span class="value">{{ $reporte->temperatura }}°C</span></p>
        <p><span class="label">pH:</span><span class="value">{{ $reporte->ph }}</span></p>
        <p><span class="label">Turbidez:</span><span class="value">{{ $reporte->turbidez }} NTU</span></p>
        <p><span class="label">Observaciones:</span><span class="value">{{ $reporte->observaciones ?? 'Sin observaciones' }}</span></p>
    </div>
</body>
</html>