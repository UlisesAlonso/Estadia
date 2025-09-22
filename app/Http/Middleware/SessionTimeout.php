<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Solo aplicar a usuarios autenticados
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            $timeout = config('session.timeout', 5); // 5 minutos por defecto
            
            // Si no hay última actividad, establecerla ahora
            if (!$lastActivity) {
                Session::put('last_activity', time());
            } else {
                // Verificar si ha pasado el tiempo de timeout
                $timeSinceLastActivity = time() - $lastActivity;
                
                if ($timeSinceLastActivity > ($timeout * 60)) {
                    // Cerrar sesión
                    Auth::logout();
                    Session::flush();
                    
                    // Redirigir con mensaje
                    return redirect()->route('login')->with('timeout', 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.');
                }
            }
            
            // Actualizar última actividad solo si no es una petición AJAX de extensión
            if (!$request->is('extend-session')) {
                Session::put('last_activity', time());
            }
        }

        return $next($request);
    }
}