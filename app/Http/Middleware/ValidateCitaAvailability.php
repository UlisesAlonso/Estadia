<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;

class ValidateCitaAvailability
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
        // Solo validar en rutas de creación y actualización de citas
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $this->validateAvailability($request);
        }

        return $next($request);
    }

    /**
     * Validar disponibilidad de la cita
     */
    private function validateAvailability(Request $request)
    {
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');
        $idMedico = $request->input('id_medico');
        $idCita = $request->route('cita'); // Para actualizaciones

        if (!$fecha || !$hora || !$idMedico) {
            return; // Dejar que las validaciones normales manejen esto
        }

        // Combinar fecha y hora
        $fechaHora = Carbon::createFromFormat('Y-m-d H:i:s', $fecha . ' ' . $hora . ':00');

        // Validar que la fecha no sea en el pasado
        if ($fechaHora->isPast()) {
            return back()->withErrors([
                'fecha' => 'No se pueden programar citas en fechas pasadas.'
            ])->withInput();
        }

        // Validar que la fecha no sea más de 6 meses en el futuro
        if ($fechaHora->isAfter(now()->addMonths(6))) {
            return back()->withErrors([
                'fecha' => 'No se pueden programar citas con más de 6 meses de anticipación.'
            ])->withInput();
        }

        // Validar horario de atención (8:00 AM - 6:00 PM)
        $horaCita = $fechaHora->format('H:i');
        if ($horaCita < '08:00' || $horaCita > '18:00') {
            return back()->withErrors([
                'hora' => 'Las citas solo se pueden programar entre las 8:00 AM y 6:00 PM.'
            ])->withInput();
        }

        // Validar que no sea fin de semana
        if ($fechaHora->isWeekend()) {
            return back()->withErrors([
                'fecha' => 'No se pueden programar citas en fines de semana.'
            ])->withInput();
        }

        // Verificar disponibilidad del médico
        $query = Cita::where('id_medico', $idMedico)
                    ->where('fecha', $fechaHora)
                    ->where('estado', '!=', 'cancelada');

        // Excluir la cita actual en caso de actualización
        if ($idCita) {
            $query->where('id_cita', '!=', $idCita);
        }

        $citaExistente = $query->first();

        if ($citaExistente) {
            return back()->withErrors([
                'fecha' => 'El médico no está disponible en esa fecha y hora. Por favor, selecciona otro horario.'
            ])->withInput();
        }

        // Validar que no haya más de 3 citas por médico en el mismo día
        $citasDelDia = Cita::where('id_medico', $idMedico)
                          ->whereDate('fecha', $fechaHora->format('Y-m-d'))
                          ->where('estado', '!=', 'cancelada')
                          ->count();

        if ($citasDelDia >= 3) {
            return back()->withErrors([
                'fecha' => 'El médico ya tiene el máximo de citas programadas para este día. Por favor, selecciona otra fecha.'
            ])->withInput();
        }
    }
}
