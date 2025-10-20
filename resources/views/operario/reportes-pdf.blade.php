<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reportes Manuales</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 80px; height: auto; }
        .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
        .subtitle { font-size: 14px; color: #666; }
        .info-box { background: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #16a34a; color: white; font-weight: bold; }
        .normal { color: #16a34a; }
        .alerta { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">REPORTES MANUALES DE SENSORES</h1>
        <p class="subtitle">Sistema IoT - Monitoreo de Calidad del Agua</p>
        <p class="subtitle">Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
    </div>

    <div class="info-box">
        <strong>Resumen del Reporte:</strong><br>
        • Total de mediciones: {{ $reportes->count() }}<br>
        • Fecha de generación: {{ now()->format('d/m/Y H:i') }}<br>
        • Rangos normales: Temperatura (24-28°C), pH (6.5-8.5), Turbidez (≤5 NTU)
    </div>

    @if($reportes->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Fecha/Hora</th>
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
                        <td>{{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}</td>
                        <td class="{{ $reporte->temperatura >= 24 && $reporte->temperatura <= 28 ? 'normal' : 'alerta' }}">
                            {{ $reporte->temperatura }}°C
                        </td>
                        <td class="{{ $reporte->ph >= 6.5 && $reporte->ph <= 8.5 ? 'normal' : 'alerta' }}">
                            {{ $reporte->ph }}
                        </td>
                        <td class="{{ $reporte->turbidez <= 5 ? 'normal' : 'alerta' }}">
                            {{ $reporte->turbidez }} NTU
                        </td>
                        <td>{{ $reporte->observaciones ?: 'Sin observaciones' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Estadísticas -->
        <div class="info-box" style="margin-top: 30px;">
            <strong>Estadísticas del Período:</strong><br>
            • Temperatura promedio: {{ number_format($reportes->avg('temperatura'), 2) }}°C<br>
            • pH promedio: {{ number_format($reportes->avg('ph'), 2) }}<br>
            • Turbidez promedio: {{ number_format($reportes->avg('turbidez'), 2) }} NTU<br>
            • Total de mediciones: {{ $reportes->count() }}<br>
            • Mediciones fuera de rango: {{ $reportes->filter(function($r) { 
                return $r->temperatura < 24 || $r->temperatura > 28 || 
                       $r->ph < 6.5 || $r->ph > 8.5 || 
                       $r->turbidez > 5; 
            })->count() }}
        </div>
        
        @php
            $reportesFueraRango = $reportes->filter(function($r) { 
                return $r->temperatura < 24 || $r->temperatura > 28 || 
                       $r->ph < 6.5 || $r->ph > 8.5 || 
                       $r->turbidez > 5; 
            });
        @endphp
        
        @if($reportesFueraRango->count() > 0)
            <div class="info-box" style="margin-top: 20px; background: #fee; border: 1px solid #fcc;">
                <strong style="color: #c33;">ALERTAS DETECTADAS:</strong><br>
                @foreach($reportesFueraRango as $reporte)
                    <strong>{{ $reporte->fecha_toma->format('d/m/Y H:i') }} - {{ $reporte->usuario->nombre }}:</strong><br>
                    @if($reporte->temperatura < 24 || $reporte->temperatura > 28)
                        • Temperatura: {{ $reporte->temperatura }}°C (fuera de rango 24-28°C)<br>
                    @endif
                    @if($reporte->ph < 6.5 || $reporte->ph > 8.5)
                        • pH: {{ $reporte->ph }} (fuera de rango 6.5-8.5)<br>
                    @endif
                    @if($reporte->turbidez > 5)
                        • Turbidez: {{ $reporte->turbidez }} NTU (máximo permitido: 5 NTU)<br>
                    @endif
                    <br>
                @endforeach
            </div>
        @endif
    @else
        <p style="text-align: center; margin: 50px 0; color: #666;">
            No se encontraron reportes manuales en el período seleccionado.
        </p>
    @endif

    <div class="footer">
        <p>Documento generado automáticamente por el Sistema IoT de Monitoreo</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>