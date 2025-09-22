<?php
/**
 * Configuración de Base de Datos
 * 
 * Para configurar la base de datos correctamente, sigue estos pasos:
 * 
 * 1. Copia el archivo .env.example a .env
 * 2. Configura las siguientes variables en tu archivo .env:
 * 
 * DB_CONNECTION=mysql
 * DB_HOST=127.0.0.1
 * DB_PORT=3306
 * DB_DATABASE=clinica_salud_total
 * DB_USERNAME=tu_usuario
 * DB_PASSWORD=tu_contraseña
 * 
 * 3. Asegúrate de que la base de datos 'clinica_salud_total' existe
 * 4. Ejecuta las migraciones si es necesario: php artisan migrate
 * 
 * Estructura de tablas utilizada:
 * - usuarios (id_usuario, nombre_completo, correo, contrasena, rol, activo)
 * - pacientes (id_paciente, id_usuario, fecha_nacimiento, sexo)
 * - medicos (id_medico, id_usuario, especialidad, cedula_profesional)
 * - citas (id_cita, id_paciente, id_medico, fecha, motivo, estado)
 * - diagnosticos (id_diagnostico, id_paciente, id_medico, fecha, descripcion)
 * - tratamientos (id_tratamiento, id_paciente, id_medico, nombre, dosis, frecuencia, duracion, observaciones, fecha_inicio, activo)
 * - historial_clinico (id_historial, id_paciente, id_diagnostico, id_tratamiento, observaciones, fecha_registro)
 * - analisis_clinicos (id_analisis, id_paciente, id_medico, tipo_analisis, resultado, fecha)
 * - actividades (id_actividad, nombre, descripcion)
 * - historial_actividades (id_historial_actividad, id_historial, id_actividad, fecha_asignacion, fecha_cumplimiento, cumplida)
 * - mensajes (id_mensaje, remitente_id, destinatario_id, mensaje, fecha_envio)
 * - preguntas (id_pregunta, texto, tipo, categoria)
 * - respuestas (id_respuesta, id_pregunta, id_usuario, respuesta, fecha)
 * - tokens_recuperacion (id_token, id_usuario, token, expiracion, usado)
 * - Administrador (idAdministrador, usuario, contrasena)
 */

return [
    'database_name' => 'clinica_salud_total',
    'tables' => [
        'usuarios' => 'Tabla principal de usuarios del sistema',
        'pacientes' => 'Información específica de pacientes',
        'medicos' => 'Información específica de médicos',
        'citas' => 'Citas médicas programadas',
        'diagnosticos' => 'Diagnósticos realizados por médicos',
        'tratamientos' => 'Tratamientos prescritos a pacientes',
        'historial_clinico' => 'Historial clínico de pacientes',
        'analisis_clinicos' => 'Análisis clínicos realizados',
        'actividades' => 'Actividades disponibles para pacientes',
        'historial_actividades' => 'Actividades asignadas a pacientes',
        'mensajes' => 'Mensajes entre usuarios',
        'preguntas' => 'Preguntas del sistema',
        'respuestas' => 'Respuestas de usuarios a preguntas',
        'tokens_recuperacion' => 'Tokens para recuperación de contraseñas',
        'Administrador' => 'Tabla de administradores del sistema'
    ]
]; 