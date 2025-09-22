# 🕐 Sistema de Timeout de Sesión Simplificado

## 📋 **Cómo Funciona**

### **🔄 Reinicio del Contador**
- **Toca cualquier parte** de la página para reiniciar el contador
- **Cualquier actividad** (mouse, teclado, scroll) reinicia el timer
- **5 minutos** de inactividad antes de mostrar la alerta

### **⚠️ Alerta de Timeout**
- **30 segundos** antes del cierre automático
- **Modal visible** que no se puede cerrar accidentalmente
- **Dos opciones**: Extender sesión o cerrar sesión

## 🎯 **Eventos que Reinician el Contador**

### **🖱️ Mouse:**
- Clic en cualquier parte
- Movimiento del mouse
- Scroll de la página
- Pasar el mouse sobre elementos

### **⌨️ Teclado:**
- Presionar cualquier tecla
- Escribir en campos de texto

### **📱 Touch (Móviles):**
- Tocar la pantalla
- Deslizar (scroll)

### **🪟 Ventana:**
- Cambiar de pestaña y volver
- Redimensionar la ventana
- Enfocar/desenfocar la ventana

## 🚫 **Cuándo NO se Reinicia**

### **⚠️ Durante la Alerta:**
- Cuando aparece el modal de timeout
- Los eventos se ignoran para que puedas decidir
- Solo se reinicia al extender o cerrar sesión

## 💡 **Indicadores Visuales**

### **🟢 Actividad Normal:**
- Pequeño indicador verde "🔄 Sesión activa" (2 segundos)
- Aparece en la esquina inferior derecha

### **🟡 Alerta de Timeout:**
- Modal amarillo con contador regresivo
- Botones para extender o cerrar sesión

### **🔴 Sesión Expirada:**
- Modal rojo con mensaje de sesión expirada
- Redirección automática al login

## ⚙️ **Configuración**

### **Tiempos (en `session-timeout.js`):**
```javascript
this.timeoutMinutes = 5;        // 5 minutos de inactividad
this.warningSeconds = 30;       // 30 segundos de advertencia
```

### **Cambiar Tiempos:**
1. Abre `public/js/session-timeout.js`
2. Modifica los valores en el constructor
3. Recarga la página

## 🎮 **Uso del Sistema**

### **✅ Para Mantener la Sesión Activa:**
- **Simplemente usa la página** normalmente
- **Cualquier toque** reinicia el contador
- **No necesitas hacer nada especial**

### **⚠️ Cuando Aparezca la Alerta:**
- **Presiona "Extender Sesión"** para continuar
- **Presiona "Cerrar Sesión"** si quieres salir
- **Tienes 30 segundos** para decidir

## 🔧 **Debugging**

### **Abrir Consola del Navegador (F12):**
- Verás mensajes de cuando se reinicia el timer
- Indicadores de estado del sistema
- Errores si los hay

### **Mensajes en Consola:**
```
🏥 Sistema de timeout de sesión iniciado
💡 Toca cualquier parte de la página para reiniciar el contador de 5 minutos
⚠️ La alerta aparecerá 30 segundos antes del cierre automático
🔄 Timer reiniciado - nueva actividad detectada
⚠️ Alerta visible - no se reinicia el timer
```

## ✨ **Beneficios del Sistema Simplificado**

- **🎯 Intuitivo**: Cualquier toque reinicia el contador
- **🛡️ Seguro**: Solo se bloquea durante la alerta
- **👀 Visual**: Indicadores claros del estado
- **🔧 Configurable**: Fácil de modificar los tiempos
- **📱 Universal**: Funciona en desktop y móvil

---

**¡El sistema ahora es súper fácil de usar! Solo toca cualquier parte de la página para mantener tu sesión activa.** 🎉


