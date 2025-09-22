<?php
/**
 * Configuración de Gmail para Envío de Correos
 * 
 * PASOS PARA CONFIGURAR GMAIL:
 * 
 * 1. ACTIVAR VERIFICACIÓN EN DOS PASOS:
 *    - Ve a tu cuenta de Google
 *    - Configuración → Seguridad
 *    - Activa "Verificación en dos pasos"
 * 
 * 2. GENERAR CONTRASEÑA DE APLICACIÓN:
 *    - Ve a tu cuenta de Google
 *    - Configuración → Seguridad → Contraseñas de aplicación
 *    - Selecciona "Correo" y "Windows"
 *    - Copia la contraseña generada (16 caracteres)
 * 
 * 3. EDITAR EL ARCHIVO .env:
 *    Cambia estas líneas en tu archivo .env:
 * 
 * MAIL_USERNAME=tu-correo@gmail.com
 * MAIL_PASSWORD=tu-contraseña-de-aplicación-de-16-caracteres
 * MAIL_FROM_ADDRESS="tu-correo@gmail.com"
 * MAIL_FROM_NAME="Sistema Cardiovascular"
 * 
 * 4. DESPUÉS DE EDITAR:
 *    php artisan config:clear
 * 
 * 5. PROBAR:
 *    - Ve a /forgot-password
 *    - Ingresa tu correo
 *    - Deberías recibir el correo real
 */

return [
    'gmail_config' => [
        'MAIL_MAILER' => 'smtp',
        'MAIL_HOST' => 'smtp.gmail.com',
        'MAIL_PORT' => '587',
        'MAIL_USERNAME' => 'tu-correo@gmail.com',
        'MAIL_PASSWORD' => 'tu-contraseña-de-aplicación',
        'MAIL_ENCRYPTION' => 'tls',
        'MAIL_FROM_ADDRESS' => 'tu-correo@gmail.com',
        'MAIL_FROM_NAME' => 'Sistema Cardiovascular'
    ],
    'steps' => [
        'step1' => 'Activar verificación en dos pasos en Gmail',
        'step2' => 'Generar contraseña de aplicación',
        'step3' => 'Editar archivo .env con tu información',
        'step4' => 'Limpiar caché de configuración',
        'step5' => 'Probar envío de correos'
    ]
]; 