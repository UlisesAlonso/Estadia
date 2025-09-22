/**
 * Sistema de Timeout de Sesión por Inactividad
 * Monitorea la actividad del usuario y muestra alertas antes del cierre automático
 */

class TimeoutSesion {
    constructor() {
        this.tiempoTimeout = 5; // Tiempo de timeout en minutos
        this.segundosAdvertencia = 30; // Segundos antes del timeout para mostrar advertencia
        this.intervaloVerificacion = 60000; // Verificar cada minuto (60000ms)
        this.tiempoInactividad = null;
        this.advertenciaMostrada = false;
        this.estaActivo = true;
        this.intervaloCountdown = null; // Guarda el intervalo del countdown
        
        this.inicializar();
    }

    inicializar() {
        // Solo ejecutar si el usuario está autenticado
        if (!this.estaAutenticado()) {
            console.log('❌ Usuario no autenticado - sistema de timeout no iniciado');
            return;
        }

        console.log('✅ Sistema de timeout iniciado - 5 min timeout, 30s alerta');

        this.configurarEventos();
        this.iniciarMonitoreoActividad();
        this.verificarEstadoSesion();
    }

    estaAutenticado() {
        // Verificar si hay un token de autenticación o indicador de sesión
        const metaAuth = document.querySelector('meta[name="authenticated"]')?.content === 'true';
        const bodyAuth = document.body.classList.contains('authenticated');
        
        console.log('🔍 Auth check - Meta:', metaAuth, 'Body:', bodyAuth);
        
        return metaAuth || bodyAuth;
    }

    configurarEventos() {
        // Remover listeners existentes para evitar duplicados
        this.removerEventos();
        
        // Eventos que indican actividad del usuario
        const eventos = [
            'mousedown', 'mousemove', 'keypress', 'keydown', 'scroll', 'touchstart', 'click'
        ];

        // Crear función de callback reutilizable
        this.callbackActividad = () => {
            this.reiniciarTimerActividad();
        };

        eventos.forEach(evento => {
            document.addEventListener(evento, this.callbackActividad, true);
        });

        console.log('✅ Event listeners configurados');

        // Detectar cuando la ventana pierde/gana foco
        this.callbackVisibilidad = () => {
            if (document.hidden) {
                this.pausarMonitoreo();
            } else {
                this.reanudarMonitoreo();
            }
        };
        document.addEventListener('visibilitychange', this.callbackVisibilidad);

        // Detectar cuando la ventana se cierra
        this.callbackAntesCerrar = () => {
            this.limpiar();
        };
        window.addEventListener('beforeunload', this.callbackAntesCerrar);
    }

    removerEventos() {
        if (this.callbackActividad) {
            const eventos = [
                'mousedown', 'mousemove', 'keypress', 'keydown', 'scroll', 'touchstart', 'click'
            ];
            eventos.forEach(evento => {
                document.removeEventListener(evento, this.callbackActividad, true);
            });
        }

        if (this.callbackVisibilidad) {
            document.removeEventListener('visibilitychange', this.callbackVisibilidad);
        }
        if (this.callbackAntesCerrar) {
            window.removeEventListener('beforeunload', this.callbackAntesCerrar);
        }
    }

    iniciarMonitoreoActividad() {
        if (this.intervaloVerificacionEstado) {
            clearInterval(this.intervaloVerificacionEstado);
        }

        this.intervaloVerificacionEstado = setInterval(() => {
            this.verificarEstadoSesion();
        }, this.intervaloVerificacion);

        this.reiniciarTimerActividad();
    }

    reiniciarTimerActividad() {
        if (!this.estaActivo) return;
        if (this.advertenciaMostrada) return;

        // Limpiar timer anterior
        if (this.tiempoInactividad) {
            clearTimeout(this.tiempoInactividad);
        }

        // Establecer nuevo timer (5 minutos - 30 segundos = 4.5 minutos)
        this.tiempoInactividad = setTimeout(() => {
            console.log('⏰ Mostrando alerta de inactividad');
            this.mostrarAdvertencia();
        }, (this.tiempoTimeout * 60 - this.segundosAdvertencia) * 1000);

        console.log('🔄 Timer reiniciado');
    }

    verificarEstadoSesion() {
        if (!this.estaActivo) return;

        fetch('/check-session', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        .then(response => {
            if (response.status === 401) {
                this.manejarSesionExpirada();
            }
        })
        .catch(error => {
            console.log('❌ Error:', error);
        });
    }

    mostrarAdvertencia() {
        if (this.advertenciaMostrada) return;

        console.log('🚨 Mostrando alerta de inactividad');
        this.advertenciaMostrada = true;
        this.crearModalAdvertencia();
    }

    crearModalAdvertencia() {
        // Crear modal de advertencia
        const modal = document.createElement('div');
        modal.id = 'session-timeout-modal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900">Sesión por expirar</h3>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">
                            No has tenido actividad en <span id="countdown" class="font-semibold text-yellow-600 text-lg">${this.segundosAdvertencia}</span> segundos.
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Toca cualquier parte de la página para continuar usándola o cierra sesión.
                        </p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button id="extend-session" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Continuar Usando
                        </button>
                        <button id="logout-now" class="btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Configurar eventos
        this.configurarEventosModal(modal);
        this.iniciarCountdown();
    }

    configurarEventosModal(modal) {
        // Botón extender sesión
        modal.querySelector('#extend-session').addEventListener('click', () => {
            this.extenderSesion();
        });

        // Botón cerrar sesión
        modal.querySelector('#logout-now').addEventListener('click', () => {
            this.cerrarSesion();
        });

        // Prevenir cierre accidental del modal
        modal.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    iniciarCountdown() {
        let tiempoRestante = this.segundosAdvertencia; // 30 segundos
        
        this.intervaloCountdown = setInterval(() => {
            const elementoCountdown = document.getElementById('countdown');
            if (elementoCountdown) {
                elementoCountdown.textContent = tiempoRestante;
            }
            
            tiempoRestante--;
            
            if (tiempoRestante < 0) {
                clearInterval(this.intervaloCountdown);
                this.intervaloCountdown = null;
                console.log('⏰ Sesión cerrada por timeout');
                this.manejarSesionExpirada();
            }
        }, 1000);
    }
    

    extenderSesion() {
        console.log('🔄 Extendiendo sesión...');
        
        // Deshabilitar el botón para evitar múltiples clics
        const botonExtender = document.getElementById('extend-session');
        if (botonExtender) {
            botonExtender.disabled = true;
            botonExtender.textContent = 'Continuando...';
        }

        // Hacer petición para extender la sesión
        fetch('/extend-session', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Sesión extendida');
                this.ocultarAdvertencia();
                this.reiniciarMonitoreoSesion();
                this.mostrarMensajeExito('Puedes continuar usando la página');
            } else {
                console.log(' Error:', data.message);
                this.mostrarMensajeError('Error al continuar la sesión');
                if (botonExtender) {
                    botonExtender.disabled = false;
                    botonExtender.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Continuar Usando';
                }
            }
        })
        .catch(error => {
            console.log(' Error:', error);
            this.mostrarMensajeError('Error de conexión');
            if (botonExtender) {
                botonExtender.disabled = false;
                botonExtender.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Continuar Usando';
            }
        });
    }

    ocultarAdvertencia() {
        const modal = document.getElementById('session-timeout-modal');
        if (modal) {
            modal.remove();
        }
    
        if (this.intervaloCountdown) {
            clearInterval(this.intervaloCountdown);
            this.intervaloCountdown = null;
        }
    
        this.advertenciaMostrada = false; // Habilita de nuevo el reset
        this.reiniciarTimerActividad(); // Reinicia el contador
        console.log('Alerta ocultada');
    }
    

    manejarSesionExpirada() {
        console.log('Sesión expirada');
        this.estaActivo = false;
        this.ocultarAdvertencia();
        this.mostrarModalSesionExpirada();
    }

    mostrarModalSesionExpirada() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Sesión Expirada</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Tu sesión ha expirado por inactividad. Serás redirigido al login.
                    </p>
                    <button id="ir-al-login" class="btn-primary">
                        Ir al Login
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        
        // Configurar el botón para ir al login
        const botonLogin = modal.querySelector('#ir-al-login');
        if (botonLogin) {
            botonLogin.addEventListener('click', () => {
                window.location.href = '/login';
            });
        }
    }

    mostrarMensajeExito(mensaje) {
        const notificacion = document.createElement('div');
        notificacion.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notificacion.textContent = mensaje;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.remove();
        }, 3000);
    }

    mostrarMensajeError(mensaje) {
        const notificacion = document.createElement('div');
        notificacion.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notificacion.textContent = mensaje;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.remove();
        }, 5000);
    }


    cerrarSesion() {
        this.estaActivo = false;
        this.ocultarAdvertencia();
        
        // Crear un formulario para hacer POST a logout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        
        // Agregar token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }
        
        // Agregar el formulario al DOM y enviarlo
        document.body.appendChild(form);
        form.submit();
    }

    pausarMonitoreo() {
        this.estaActivo = false;
        if (this.tiempoInactividad) {
            clearTimeout(this.tiempoInactividad);
        }
    }

    reanudarMonitoreo() {
        this.estaActivo = true;
        this.reiniciarTimerActividad();
    }

    reiniciarMonitoreoSesion() {
        this.limpiar();
        
        this.advertenciaMostrada = false;
        this.estaActivo = true;
        this.tiempoInactividad = null;
        this.intervaloVerificacionEstado = null;
        
        this.configurarEventos();
        this.iniciarMonitoreoActividad();
        this.verificarEstadoSesion();
        
        console.log('✅ Sistema reiniciado');
    }

    verificarExtensionSesion() {
        fetch('/check-session', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        .then(response => {
            if (response.status === 401) {
                this.mostrarMensajeError('La sesión no se pudo extender correctamente');
                this.manejarSesionExpirada();
            }
        })
        .catch(error => {
            console.log('❌ Error:', error);
        });
    }

    limpiar() {
        if (this.tiempoInactividad) {
            clearTimeout(this.tiempoInactividad);
        }
        if (this.intervaloVerificacionEstado) {
            clearInterval(this.intervaloVerificacionEstado);
        }
        this.removerEventos();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new TimeoutSesion();
});
