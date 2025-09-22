# Cardio Vida - Configuración

## Requisitos Previos

- PHP 8.0 o superior
- Composer
- MySQL 5.7 o superior
- Node.js y NPM (para compilar assets)

## Instalación

### 1. Clonar el proyecto
```bash
git clone <url-del-repositorio>
cd sistema-cardiovascular
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar variables de entorno
```bash
cp .env.example .env
```

Editar el archivo `.env` con la siguiente configuración:

```env
APP_NAME="Cardio Vida"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinica_salud_total
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-correo@gmail.com
MAIL_FROM_NAME="Cardio Vida"
```

### 4. Generar clave de aplicación
```bash
php artisan key:generate
```

### 5. Crear la base de datos
```sql
CREATE SCHEMA IF NOT EXISTS `clinica_salud_total` DEFAULT CHARACTER SET utf8mb4;
USE `clinica_salud_total`;
```

### 6. Ejecutar las migraciones (si es necesario)
```bash
php artisan migrate
```

### 7. Compilar assets
```bash
npm run dev
```

### 8. Iniciar el servidor
```bash
php artisan serve
```

## Configuración del Correo Electrónico

### Para Gmail:
1. Activa la verificación en dos pasos en tu cuenta de Gmail
2. Genera una contraseña de aplicación
3. Usa esa contraseña en `MAIL_PASSWORD`

### Para desarrollo/pruebas:
```env
MAIL_MAILER=log
```
Esto guardará los correos en `storage/logs/laravel.log` en lugar de enviarlos.

## Estructura de la Base de Datos

El sistema utiliza las siguientes tablas:

- **usuarios**: Usuarios del sistema (pacientes, médicos, administradores)
- **pacientes**: Información específica de pacientes
- **medicos**: Información específica de médicos
- **citas**: Citas médicas programadas
- **diagnosticos**: Diagnósticos realizados por médicos
- **tratamientos**: Tratamientos prescritos a pacientes
- **historial_clinico**: Historial clínico de pacientes
- **analisis_clinicos**: Análisis clínicos realizados
- **actividades**: Actividades disponibles para pacientes
- **historial_actividades**: Actividades asignadas a pacientes
- **mensajes**: Mensajes entre usuarios
- **preguntas**: Preguntas del sistema
- **respuestas**: Respuestas de usuarios a preguntas
- **tokens_recuperacion**: Tokens para recuperación de contraseñas
- **Administrador**: Tabla de administradores del sistema

## Funcionalidades del Sistema

### Recuperación de Contraseña
El sistema implementa un sistema de recuperación de contraseña mediante códigos de 6 dígitos:

1. El usuario solicita recuperar su contraseña
2. Se genera un código de 6 dígitos
3. Se envía el código por correo electrónico
4. El usuario ingresa el código en el formulario de restablecimiento
5. Se valida el código y se permite cambiar la contraseña

### Roles del Sistema
- **Administrador**: Acceso completo al sistema
- **Médico**: Gestión de pacientes, citas, diagnósticos y tratamientos
- **Paciente**: Visualización de su información médica

## Solución de Problemas

### Error de conexión a la base de datos
- Verifica que MySQL esté ejecutándose
- Confirma las credenciales en el archivo `.env`
- Asegúrate de que la base de datos `clinica_salud_total` existe

### Error al enviar correos
- Verifica la configuración SMTP en `.env`
- Para pruebas, usa `MAIL_MAILER=log`
- Los correos se guardarán en `storage/logs/laravel.log`

### Error de permisos
```bash
chmod -R 755 storage bootstrap/cache
```

## Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Ver rutas disponibles
php artisan route:list

# Verificar estado del sistema
php artisan about
```

## Soporte

Para reportar problemas o solicitar ayuda, contacta al administrador del sistema. 