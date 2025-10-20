<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Sensores IoT</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
        .subtitle { color: #666; margin-bottom: 20px; }
        .stats { display: table; width: 100%; margin-bottom: 30px; }
        .stat-box { display: table-cell; width: 25%; padding: 10px; border: 1px solid #ddd; text-align: center; }
        .stat-title { font-weight: bold; color: #333; }
        .stat-value { font-size: 14px; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title"> Reporte de Sensores IoT - Piscicultura</h1>
        <p class="subtitle">Período: {{ $fechaInicio }} al {{ $fechaFin }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-title">Total Registros</div>
            <div class="stat-value">{{ $estadisticas['total_registros'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Temperatura Promedio</div>
            <div class="stat-value">{{ $estadisticas['temp_promedio'] }}°C</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">pH Promedio</div>
            <div class="stat-value">{{ $estadisticas['ph_promedio'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Turbidez Promedio</div>
            <div class="stat-value">{{ $estadisticas['turbidez_promedio'] }} NTU</div>
        </div>
    </div>

    <h3>Estadísticas Detalladas</h3>
    <table>
        <tr>
            <th>Parámetro</th>
            <th>Mínimo</th>
            <th>Máximo</th>
            <th>Promedio</th>
        </tr>
        <tr>
            <td>Temperatura (°C)</td>
            <td>{{ $estadisticas['temp_min'] }}</td>
            <td>{{ $estadisticas['temp_max'] }}</td>
            <td>{{ $estadisticas['temp_promedio'] }}</td>
        </tr>
        <tr>
            <td>pH</td>
            <td>{{ $estadisticas['ph_min'] }}</td>
            <td>{{ $estadisticas['ph_max'] }}</td>
            <td>{{ $estadisticas['ph_promedio'] }}</td>
        </tr>
        <tr>
            <td>Turbidez (NTU)</td>
            <td>{{ $datos->min('turbidez') }}</td>
            <td>{{ $datos->max('turbidez') }}</td>
            <td>{{ $estadisticas['turbidez_promedio'] }}</td>
        </tr>
    </table>

    <h3> Análisis de Impacto por Especies</h3>
    <div style="margin-bottom: 20px;">
        <h4 style="color: #007bff; margin-bottom: 10px;">Tilapia:</h4>
        @if(count($analisisEspecies['tilapia']) > 0)
            <ul style="color: #dc3545; margin-left: 20px;">
                @foreach($analisisEspecies['tilapia'] as $alerta)
                    <li> {{ $alerta }}</li>
                @endforeach
            </ul>
        @else
            <p style="color: #28a745;"> Condiciones óptimas para tilapia en el período analizado</p>
        @endif

        <h4 style="color: #007bff; margin-bottom: 10px; margin-top: 15px;">Cachama:</h4>
        @if(count($analisisEspecies['cachama']) > 0)
            <ul style="color: #dc3545; margin-left: 20px;">
                @foreach($analisisEspecies['cachama'] as $alerta)
                    <li> {{ $alerta }}</li>
                @endforeach
            </ul>
        @else
            <p style="color: #28a745;"> Condiciones óptimas para cachama en el período analizado</p>
        @endif
    </div>

    <h3>Registros de Sensores (Últimos 100)</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Temperatura (°C)</th>
                <th>pH</th>
                <th>Turbidez (NTU)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos->take(100) as $dato)
            <tr>
                <td>{{ \Carbon\Carbon::parse($dato->fecha)->format('d/m/Y H:i:s') }}</td>
                <td>{{ $dato->temperatura }}</td>
                <td>{{ $dato->ph }}</td>
                <td>{{ $dato->turbidez }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($datos->count() > 100)
    <p><em>Nota: Se muestran los primeros 100 registros de {{ $datos->count() }} total.</em></p>
    @endif

    <div class="footer">
        <p>Sistema IoT de Monitoreo de Piscicultura - SENA</p>
        <p>Reporte generado automáticamente</p>
    </div>
</body>
</html>