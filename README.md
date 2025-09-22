# Sistema Web para Pacientes con Enfermedades Cardiovasculares

## Descripción

Sistema web desarrollado en Laravel 10 para la gestión de pacientes con enfermedades cardiovasculares. Permite la administración de citas médicas, tratamientos, diagnósticos e historial clínico.

## Características

- **Autenticación segura** con roles de usuario (Administrador, Médico, Paciente)
- **Gestión de citas médicas** con estados y confirmaciones
- **Gestión de tratamientos** con seguimiento y observaciones
- **Historial clínico** completo de pacientes
- **Dashboard personalizado** según el rol del usuario
- **Interfaz moderna** con Tailwind CSS
- **Base de datos MySQL** optimizada

## Requisitos del Sistema

- PHP 8.1 o superior
- Composer
- Node.js y npm
- MySQL 5.7 o superior
- Apache/Nginx

## Instalación

### 1. Clonar el repositorio
```bash
git clone <url-del-repositorio>
cd sistema-cardiovascular
```

### 2. Instalar dependencias de PHP
```bash
composer install
```

### 3. Instalar dependencias de Node.js
```bash
npm install
```

### 4. Configurar el archivo .env
```bash
cp .env.example .env
```

Editar el archivo `.env` con la configuración de tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinica_salud_total
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generar clave de aplicación
```bash
php artisan key:generate
```

### 6. Importar la base de datos
Importar el archivo SQL de la base de datos `clinica_salud_total.sql` en tu servidor MySQL.

### 7. Compilar assets
```bash
npm run build
```

### 8. Configurar permisos (si es necesario)
```bash
chmod -R 775 storage bootstrap/cache
```

## Estructura del Proyecto

### Modelos
- `User` - Usuarios del sistema
- `Paciente` - Información de pacientes
- `Medico` - Información de médicos
- `Cita` - Citas médicas
- `Tratamiento` - Tratamientos médicos
- `Diagnostico` - Diagnósticos clínicos
- `HistorialClinico` - Historial médico
- `AnalisisClinico` - Análisis clínicos

### Controladores
- `AuthController` - Autenticación y recuperación de contraseña
- `UserController` - Gestión de usuarios (CRUD)
- `CitaController` - Gestión de citas médicas
- `DashboardController` - Dashboards por rol
- `TratamientoController` - Gestión de tratamientos
- `DiagnosticoController` - Gestión de diagnósticos
- `HistorialClinicoController` - Gestión de historial clínico

### Vistas
- `layouts/app.blade.php` - Layout principal
- `auth/login.blade.php` - Página de login
- `admin/dashboard.blade.php` - Dashboard de administrador
- `medico/dashboard.blade.php` - Dashboard de médico
- `paciente/dashboard.blade.php` - Dashboard de paciente

## Roles de Usuario

### Administrador
- Gestión completa de usuarios
- Visualización de estadísticas generales
- Acceso a reportes del sistema

### Médico
- Gestión de citas asignadas
- Creación y seguimiento de tratamientos
- Registro de diagnósticos
- Acceso al historial clínico de pacientes

### Paciente
- Agendar y gestionar citas
- Visualizar tratamientos activos
- Consultar historial clínico personal
- Comunicación con médicos

## Rutas Principales

- `/login` - Página de inicio de sesión
- `/admin/dashboard` - Dashboard de administrador
- `/medico/dashboard` - Dashboard de médico
- `/paciente/dashboard` - Dashboard de paciente
- `/admin/users` - Gestión de usuarios
- `/citas` - Gestión de citas
- `/tratamientos` - Gestión de tratamientos

## Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Compilar assets en desarrollo
npm run dev

# Compilar assets para producción
npm run build
```

## Tecnologías Utilizadas

- **Backend**: Laravel 10, PHP 8.1+
- **Frontend**: Tailwind CSS, JavaScript
- **Base de Datos**: MySQL
- **Servidor Web**: Apache/Nginx
- **Herramientas**: Composer, npm

## Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Soporte

Para soporte técnico, contactar a:
- Email: soporte@sistemacardiovascular.com
- Teléfono: +52 777 123 4567

## Changelog

### v1.0.0
- Implementación inicial del sistema
- Autenticación con roles
- Gestión básica de citas y tratamientos
- Dashboard personalizado por rol
