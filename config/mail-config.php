<?php
/**
 * Configuración de Correo Electrónico
 * 
 * Para configurar el envío de correos electrónicos, sigue estos pasos:
 * 
 * 1. Copia el archivo .env.example a .env
 * 2. Configura las siguientes variables en tu archivo .env:
 * 
 * MAIL_MAILER=smtp
 * MAIL_HOST=smtp.gmail.com
 * MAIL_PORT=587
 * MAIL_USERNAME=tu-correo@gmail.com
 * MAIL_PASSWORD=tu-contraseña-de-aplicación
 * MAIL_ENCRYPTION=tls
 * MAIL_FROM_ADDRESS=tu-correo@gmail.com
 * MAIL_FROM_NAME="Cardio Vida"
 * 
 * Para Gmail:
 * - Activa la verificación en dos pasos
 * - Genera una contraseña de aplicación
 * - Usa esa contraseña en MAIL_PASSWORD
 * 
 * Para otros proveedores:
 * - Consulta la documentación de tu proveedor de correo
 * - Ajusta MAIL_HOST, MAIL_PORT y MAIL_ENCRYPTION según corresponda
 * 
 * Configuración de prueba (para desarrollo):
 * MAIL_MAILER=log
 * 
 * Esto guardará los correos en storage/logs/laravel.log en lugar de enviarlos
 */

return [
    'instructions' => [
        'gmail' => [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
            'steps' => [
                'Activar verificación en dos pasos',
                'Generar contraseña de aplicación',
                'Usar la contraseña de aplicación en MAIL_PASSWORD'
            ]
        ],
        'outlook' => [
            'host' => 'smtp-mail.outlook.com',
            'port' => 587,
            'encryption' => 'tls'
        ],
        'yahoo' => [
            'host' => 'smtp.mail.yahoo.com',
            'port' => 587,
            'encryption' => 'tls'
        ]
    ]
]; 