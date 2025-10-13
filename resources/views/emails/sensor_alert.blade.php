<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alerta de Sensores</title>
</head>
<body>
    <h2>🚨 Alerta de Sensores Fuera de Rango 🚨</h2>

    <h3>Detalles del Sensor:</h3>
    <ul>
        <li><strong>ID del Sensor:</strong> {{ $sensor->id ?? 'N/A' }}</li>
        <li><strong>Fecha y Hora:</strong> {{ $sensor->fecha ?? 'N/A' }}</li>
        <li><strong>Temperatura:</strong> {{ $sensor->temperatura ?? 'N/A' }}°C</li>
        <li><strong>PH:</strong> {{ $sensor->ph ?? 'N/A' }}</li>
        <li><strong>Oxígeno Disuelto:</strong> {{ $sensor->oxigeno_disuelto ?? 'N/A' }} mg/L</li>
        <li><strong>Amoníaco:</strong> {{ $sensor->amoniaco ?? 'N/A' }}</li>
        <li><strong>Nitritos:</strong> {{ $sensor->nitritos ?? 'N/A' }}</li>
        <li><strong>Nitratos:</strong> {{ $sensor->nitratos ?? 'N/A' }}</li>
        <li><strong>Alcalinidad:</strong> {{ $sensor->alcalinidad ?? 'N/A' }} mg/L CaCO3</li>
        <li><strong>Dureza:</strong> {{ $sensor->dureza ?? 'N/A' }} mg/L CaCO3</li>
        <li><strong>Turbidez:</strong> {{ $sensor->turbidez ?? 'N/A' }} NTU</li>
        <li><strong>Conductividad:</strong> {{ $sensor->conductividad ?? 'N/A' }} µS/cm</li>
    </ul>

    @if (!empty($alertasTilapia))
        <h3>Alertas para Tilapia:</h3>
        <ul>
            @foreach ($alertasTilapia as $alerta)
                <li>{{ $alerta }}</li>
            @endforeach
        </ul>
    @endif

    @if (!empty($alertasCachama))
        <h3>Alertas para Cachama:</h3>
        <ul>
            @foreach ($alertasCachama as $alerta)
                <li>{{ $alerta }}</li>
            @endforeach
        </ul>
    @endif

    <p>Por favor, revisa los detalles en el <a href="{{ url('/dashboard') }}">panel de control</a>.</p>
</body>
</html>
