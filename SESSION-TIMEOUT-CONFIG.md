# Configuración de Timeout de Sesión por Inactividad

## 📋 **Descripción**
Sistema completo de cierre automático de sesión por inactividad para todos los tipos de usuarios (administrador, médico, paciente).

## ⚙️ **Configuración**

### **1. Variables de Entorno (.env)**
Agrega estas líneas a tu archivo `.env`:

```env
# Timeout de sesión por inactividad (en minutos)
SESSION_TIMEOUT=5

# Tiempo de vida de la sesión (en minutos)
SESSION_LIFETIME=120
```

### **2. Configuración Personalizable**

#### **Tiempo de Timeout**
- **Por defecto**: 5 minutos de inactividad
- **Configurable**: Cambia `SESSION_TIMEOUT` en `.env`
- **Rango recomendado**: 1-30 minutos

#### **Tiempo de Advertencia**
- **Por defecto**: 30 segundos antes del timeout
- **Configurable**: Modifica `warningSeconds` en `session-timeout.js`

## 🔧 **Funcionalidades Implementadas**

### **1. Middleware de Timeout**
- **Archivo**: `app/Http/Middleware/SessionTimeout.php`
- **Función**: Verifica inactividad en cada request
- **Aplicado a**: Todas las rutas protegidas

### **2. Monitoreo JavaScript**
- **Archivo**: `public/js/session-timeout.js`
- **Eventos monitoreados**: 
  - Clicks del mouse
  - Movimiento del mouse
  - Teclas presionadas
  - Scroll de página
  - Touch en dispositivos móviles

### **3. Alertas Visuales**
- **Advertencia**: Modal 30 segundos antes del timeout
- **Contador regresivo**: Segundos restantes visibles
- **Opciones**: Extender sesión o cerrar inmediatamente

### **4. Rutas de API**
- **`/check-session`**: Verificar estado de sesión
- **`/extend-session`**: Extender sesión activa

## 🎯 **Comportamiento del Sistema**

### **Flujo Normal**
1. **Usuario activo**: La sesión se mantiene activa
2. **Inactividad detectada**: Timer inicia cuenta regresiva
3. **Advertencia**: Modal aparece 30 segundos antes del timeout
4. **Extensión**: Usuario puede extender la sesión
5. **Timeout**: Cierre automático si no hay respuesta

### **Estados de Sesión**
- **🟢 Activa**: Usuario interactuando normalmente
- **🟡 Advertencia**: Modal de timeout visible
- **🔴 Expirada**: Sesión cerrada automáticamente

## 🛡️ **Seguridad**

### **Protecciones Implementadas**
- **Verificación server-side**: El middleware valida en cada request
- **Tokens CSRF**: Protección contra ataques CSRF
- **Limpieza de sesión**: Datos eliminados al expirar
- **Redirección segura**: Envío al login con mensaje

### **Configuración de Seguridad**
```php
// En config/session.php
'timeout' => env('SESSION_TIMEOUT', 30), // Minutos
'lifetime' => env('SESSION_LIFETIME', 120), // Minutos
'expire_on_close' => false, // No expirar al cerrar navegador
```

## 📱 **Compatibilidad**

### **Dispositivos Soportados**
- ✅ **Desktop**: Windows, Mac, Linux
- ✅ **Mobile**: iOS, Android
- ✅ **Tablets**: iPad, Android tablets

### **Navegadores Soportados**
- ✅ **Chrome**: 80+
- ✅ **Firefox**: 75+
- ✅ **Safari**: 13+
- ✅ **Edge**: 80+

## 🔄 **Personalización**

### **Cambiar Tiempo de Timeout**
```env
# En .env
SESSION_TIMEOUT=45  # 45 minutos
```

### **Cambiar Tiempo de Advertencia**
```javascript
// En public/js/session-timeout.js
this.warningSeconds = 60; // 60 segundos antes del timeout
```

### **Desactivar para Usuarios Específicos**
```php
// En el middleware, agregar excepción
if ($user->id === 1) { // Usuario admin
    return $next($request);
}
```

## 🚀 **Instalación y Activación**

### **1. Archivos Creados/Modificados**
- ✅ `app/Http/Middleware/SessionTimeout.php`
- ✅ `public/js/session-timeout.js`
- ✅ `config/session.php` (modificado)
- ✅ `app/Http/Kernel.php` (modificado)
- ✅ `routes/web.php` (modificado)
- ✅ `resources/views/layouts/app.blade.php` (modificado)
- ✅ `resources/views/auth/login.blade.php` (modificado)

### **2. Comandos Ejecutados**
```bash
php artisan make:middleware SessionTimeout
php artisan config:clear
php artisan route:clear
```

### **3. Verificación**
1. Inicia sesión en el sistema
2. Deja el navegador inactivo por 4.5 minutos
3. Deberías ver el modal de advertencia con cuenta regresiva de 30 segundos
4. Prueba extender la sesión
5. Prueba el cierre automático

## 📊 **Monitoreo y Logs**

### **Logs de Sesión**
Los timeouts se registran en:
- **Laravel Logs**: `storage/logs/laravel.log`
- **Session Storage**: `storage/framework/sessions/`

### **Métricas Disponibles**
- Tiempo de inactividad por usuario
- Frecuencia de extensiones de sesión
- Patrones de uso del sistema

## ⚠️ **Consideraciones Importantes**

### **Rendimiento**
- El middleware se ejecuta en cada request
- Impacto mínimo en el rendimiento
- Verificación optimizada

### **Experiencia de Usuario**
- Alertas no intrusivas
- Opción de extender sesión
- Mensajes claros y útiles

### **Mantenimiento**
- Configuración centralizada
- Fácil modificación de tiempos
- Logs detallados para debugging

---

**¡El sistema de timeout de sesión está completamente implementado y listo para usar!** 🎉
