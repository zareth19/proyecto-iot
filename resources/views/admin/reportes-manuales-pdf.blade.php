<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reportes Manuales</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reportes Manuales</h2>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha/Hora</th>
                <th>Estanque</th>
                <th>Operario</th>
                <th>Temperatura (°C)</th>
                <th>pH</th>
                <th>Turbidez (NTU)</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportes as $reporte)
            <tr>
                <td>{{ $reporte->fecha_toma->format('d/m/Y H:i') }}</td>
                <td>{{ $reporte->estanque ? $reporte->estanque->identificador : 'Sin estanque' }}</td>
                <td>{{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}</td>
                <td>{{ $reporte->temperatura }}°C</td>
                <td>{{ $reporte->ph }}</td>
                <td>{{ $reporte->turbidez }} NTU</td>
                <td>{{ $reporte->observaciones ?? 'Sin observaciones' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>