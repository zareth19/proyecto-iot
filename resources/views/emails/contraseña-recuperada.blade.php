<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recuperación de Contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #16a34a;">Sistema IoT - Recuperación de Contraseña</h1>
        </div>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h2>Hola {{ $usuario->nombre }} {{ $usuario->apellido }},</h2>
            
            <p>Has solicitado recuperar tu contraseña para el Sistema IoT.</p>
            
            <p><strong>Tu nueva contraseña temporal es:</strong></p>
            <div style="background-color: #e5e7eb; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 18px; text-align: center; margin: 20px 0;">
                {{ $nuevaContraseña }}
            </div>
            
            <p><strong>Importante:</strong></p>
            <ul>
                <li>Esta es una contraseña temporal</li>
                <li>Deberás cambiarla en tu primer inicio de sesión</li>
                <li>Por seguridad, no compartas esta información</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; font-size: 14px;">
                Este correo fue enviado automáticamente. No responder a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>