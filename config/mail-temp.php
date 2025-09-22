<?php
/**
 * Configuración Temporal de Correo para Desarrollo
 * 
 * Para solucionar el error de conexión, configura tu archivo .env con:
 * 
 * MAIL_MAILER=log
 * MAIL_HOST=127.0.0.1
 * MAIL_PORT=1025
 * MAIL_USERNAME=null
 * MAIL_PASSWORD=null
 * MAIL_ENCRYPTION=null
 * MAIL_FROM_ADDRESS="sistema@cardiovascular.com"
 * MAIL_FROM_NAME="Sistema Cardiovascular"
 * 
 * Esto hará que los correos se guarden en storage/logs/laravel.log
 * en lugar de intentar enviarlos por SMTP.
 * 
 * Para ver los correos enviados:
 * Get-Content storage/logs/laravel.log -Tail 50
 */

return [
    'development_config' => [
        'MAIL_MAILER' => 'log',
        'MAIL_HOST' => '127.0.0.1',
        'MAIL_PORT' => '1025',
        'MAIL_USERNAME' => 'null',
        'MAIL_PASSWORD' => 'null',
        'MAIL_ENCRYPTION' => 'null',
        'MAIL_FROM_ADDRESS' => 'sistema@cardiovascular.com',
        'MAIL_FROM_NAME' => 'Sistema Cardiovascular'
    ],
    'production_config' => [
        'MAIL_MAILER' => 'smtp',
        'MAIL_HOST' => 'smtp.gmail.com',
        'MAIL_PORT' => '587',
        'MAIL_USERNAME' => 'tu-correo@gmail.com',
        'MAIL_PASSWORD' => 'tu-contraseña-de-aplicación',
        'MAIL_ENCRYPTION' => 'tls',
        'MAIL_FROM_ADDRESS' => 'tu-correo@gmail.com',
        'MAIL_FROM_NAME' => 'Sistema Cardiovascular'
    ]
]; 