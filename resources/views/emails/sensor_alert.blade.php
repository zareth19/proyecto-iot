<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alerta de Sensores</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .header { background: #dc3545; color: white; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .alert-section { margin: 15px 0; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; }
        .sensor-info { background: #e9ecef; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .footer { text-align: center; margin-top: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>⚠️ Alerta de Sensores Fuera de Rango</h2>
        </div>
        
        <div class="content">
            <div class="sensor-info">
                <h3>Información del Sensor</h3>
                <p><strong>ID:</strong> {{ $sensor->id }}</p>
                <p><strong>Fecha:</strong> {{ $sensor->fecha }}</p>
                <p><strong>Temperatura:</strong> {{ $sensor->temperatura }}°C</p>
                <p><strong>pH:</strong> {{ $sensor->ph }}</p>
                <p><strong>Turbidez:</strong> {{ $sensor->turbidez }} NTU</p>
            </div>

            @if(!empty($alertasTilapia))
            <div class="alert-section">
                <h3>🐟 Alertas para Tilapia</h3>
                <ul>
                    @foreach($alertasTilapia as $alerta)
                    <li>{{ $alerta }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($alertasCachama))
            <div class="alert-section">
                <h3>🐠 Alertas para Cachama</h3>
                <ul>
                    @foreach($alertasCachama as $alerta)
                    <li>{{ $alerta }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <p>Por favor, revisa los detalles completos en el panel de control.</p>
        </div>
        
        <div class="footer">
            <p>Sistema de Monitoreo IoT - Acuicultura</p>
        </div>
    </div>
</body>
</html>