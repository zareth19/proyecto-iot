<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenido al Sistema IoT</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #16a34a; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .credentials { background: white; padding: 20px; border-left: 4px solid #16a34a; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .warning { background: #fef3c7; border: 1px solid #f59e0b; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐟 Sistema IoT - Piscicultura SENA</h1>
            <p>Bienvenido al sistema de monitoreo</p>
        </div>
        
        <div class="content">
            <h2>¡Hola {{ $usuario->nombre }} {{ $usuario->apellido }}!</h2>
            
            <p>Se ha creado tu cuenta en el Sistema IoT de Monitoreo de Piscicultura del SENA.</p>
            
            <div class="credentials">
                <h3>📋 Tus credenciales de acceso:</h3>
                <p><strong>Tipo de documento:</strong> {{ $usuario->tipo_documento }}</p>
                <p><strong>Número de documento:</strong> {{ $usuario->numero_documento }}</p>
                <p><strong>Contraseña temporal:</strong> <code>{{ $contraseñaTemporal }}</code></p>
                <p><strong>Rol asignado:</strong> {{ ucfirst($usuario->rol) }}</p>
            </div>
            
            <div class="warning">
                <h4>⚠️ IMPORTANTE - Cambio de contraseña obligatorio</h4>
                <p>Por seguridad, <strong>debes cambiar tu contraseña</strong> en el primer inicio de sesión.</p>
                <p>La contraseña temporal solo funcionará una vez.</p>
            </div>
            
            <h3>🚀 Cómo acceder:</h3>
            <ol>
                <li>Ve al sistema de login</li>
                <li>Ingresa tu tipo y número de documento</li>
                <li>Usa la contraseña temporal proporcionada</li>
                <li>Cambia tu contraseña por una personal y segura</li>
            </ol>
            
            <p>Si tienes problemas para acceder, contacta al administrador del sistema.</p>
        </div>
        
        <div class="footer">
            <p>Sistema IoT de Monitoreo de Piscicultura - SENA</p>
            <p>Este es un email automático, no responder.</p>
        </div>
    </div>
</body>
</html>