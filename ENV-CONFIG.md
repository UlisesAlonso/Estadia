# Configuración de Base de Datos - Estadia

## Cambios Realizados

Se ha actualizado el nombre de la base de datos de "sistema_cardiovascular" a "estadia".

## Configuración Requerida en .env

Para que la aplicación funcione correctamente, asegúrate de que tu archivo `.env` tenga la siguiente configuración:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=estadia
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

## Migraciones Creadas

Se han creado las siguientes migraciones para reflejar los cambios en la base de datos:

1. **2025_01_27_000001_add_fecha_nacimiento_to_medicos_table.php**
   - Agrega el campo `fecha_nacimiento` (tipo date, nullable) a la tabla `medicos`

2. **2025_01_27_000002_add_apellidos_to_usuarios_table.php**
   - Agrega el campo `apPaterno` (string, nullable) a la tabla `usuarios`
   - Agrega el campo `apMaterno` (string, nullable) a la tabla `usuarios`

## Modelos Actualizados

### Modelo Medico
- Se agregó `fecha_nacimiento` al array `$fillable`
- Se agregó casting para `fecha_nacimiento` como tipo `date`

### Modelo User
- Se agregaron `apPaterno` y `apMaterno` al array `$fillable`

## Pasos para Aplicar los Cambios

1. Actualiza tu archivo `.env` con el nuevo nombre de base de datos
2. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```
3. Verifica que los cambios se hayan aplicado correctamente

## Notas Importantes

- Los nuevos campos son opcionales (nullable) para mantener compatibilidad con datos existentes
- El campo `fecha_nacimiento` se convierte automáticamente a objeto Carbon cuando se accede desde el modelo
- Los campos de apellidos tienen una longitud máxima de 100 caracteres


