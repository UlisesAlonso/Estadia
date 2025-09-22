<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .code {
            background-color: #1f2937;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 4px;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Cardio Vida</h1>
        <p>Recuperación de Contraseña</p>
    </div>
    
    <div class="content">
        <h2>Hola {{ $user->nombre }},</h2>
        
        <p>Has solicitado restablecer tu contraseña. Para continuar, utiliza el siguiente código de verificación:</p>
        
        <div class="code">
            {{ $token }}
        </div>
        
        <div class="warning">
            <strong>⚠️ Importante:</strong>
            <ul>
                <li>Este código expira en 15 minutos</li>
                <li>No compartas este código con nadie</li>
                <li>Si no solicitaste este cambio, ignora este correo</li>
            </ul>
        </div>
        
        <p>Para restablecer tu contraseña:</p>
        <ol>
            <li>Ve a la página de restablecimiento de contraseña</li>
            <li>Ingresa el código de verificación de arriba</li>
            <li>Ingresa tu nueva contraseña</li>
            <li>Confirma tu nueva contraseña</li>
        </ol>
        
        <p>Si tienes problemas, contacta al administrador del sistema.</p>
        
        <p>Saludos,<br>
        <strong>Equipo de Cardio Vida</strong></p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>© {{ date('Y') }} Cardio Vida. Todos los derechos reservados.</p>
    </div>
</body>
</html> 