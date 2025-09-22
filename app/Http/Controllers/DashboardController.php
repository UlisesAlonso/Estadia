<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\Tratamiento;
use App\Models\Diagnostico;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->admin();
        } elseif ($user->isMedico()) {
            return $this->medico();
        } else {
            return $this->paciente();
        }
    }

    public function admin()
    {
        $stats = [
            'total_users' => User::count(),
            'total_medicos' => User::where('rol', 'medico')->count(),
            'total_pacientes' => User::where('rol', 'paciente')->count(),
            'active_users' => User::where('activo', 1)->count(),
            'recent_citas' => Cita::with(['paciente.usuario', 'medico.usuario'])
                                 ->orderBy('fecha', 'desc')
                                 ->limit(5)
                                 ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function medico()
    {
        $user = Auth::user();
        $medico = $user->medico;

        // Si no existe el médico, crear uno temporal o manejar el caso
        if (!$medico) {
            // Crear un médico temporal para usuarios que no tienen registro en la tabla medicos
            $medico = new Medico();
            $medico->id_medico = 0; // ID temporal
            $medico->id_usuario = $user->id_usuario;
        }

        $stats = [
            'total_citas' => $medico->id_medico > 0 ? Cita::where('id_medico', $medico->id_medico)->count() : 0,
            'citas_pendientes' => $medico->id_medico > 0 ? Cita::where('id_medico', $medico->id_medico)
                                     ->where('estado', 'pendiente')
                                     ->count() : 0,
            'citas_hoy' => $medico->id_medico > 0 ? Cita::where('id_medico', $medico->id_medico)
                              ->whereDate('fecha', today())
                              ->count() : 0,
            'tratamientos_activos' => $medico->id_medico > 0 ? Tratamiento::where('id_medico', $medico->id_medico)
                                                ->where('activo', 1)
                                                ->count() : 0,
        ];

        $citasHoy = $medico->id_medico > 0 ? Cita::with(['paciente.usuario'])
                        ->where('id_medico', $medico->id_medico)
                        ->whereDate('fecha', today())
                        ->orderBy('fecha')
                        ->get() : collect();

        $citasPendientes = $medico->id_medico > 0 ? Cita::with(['paciente.usuario'])
                               ->where('id_medico', $medico->id_medico)
                               ->where('estado', 'pendiente')
                               ->orderBy('fecha')
                               ->limit(5)
                               ->get() : collect();

        return view('medico.dashboard', compact('stats', 'citasHoy', 'citasPendientes'));
    }

    public function paciente()
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        // Si no existe el paciente, crear uno temporal o manejar el caso
        if (!$paciente) {
            // Crear un paciente temporal para usuarios que no tienen registro en la tabla pacientes
            $paciente = new Paciente();
            $paciente->id_paciente = 0; // ID temporal
            $paciente->id_usuario = $user->id_usuario;
        }

        $stats = [
            'total_citas' => $paciente->id_paciente > 0 ? Cita::where('id_paciente', $paciente->id_paciente)->count() : 0,
            'citas_pendientes' => $paciente->id_paciente > 0 ? Cita::with(['medico.usuario'])
                                     ->where('id_paciente', $paciente->id_paciente)
                                     ->where('estado', 'pendiente')
                                     ->count() : 0,
            'citas_proximas' => $paciente->id_paciente > 0 ? Cita::with(['medico.usuario'])
                                   ->where('id_paciente', $paciente->id_paciente)
                                   ->where('fecha', '>', now())
                                   ->where('estado', '!=', 'cancelada')
                                   ->count() : 0,
            'tratamientos_activos' => $paciente->id_paciente > 0 ? Tratamiento::where('id_paciente', $paciente->id_paciente)
                                                ->where('activo', 1)
                                                ->count() : 0,
            'total_diagnosticos' => $paciente->id_paciente > 0 ? Diagnostico::where('id_paciente', $paciente->id_paciente)->count() : 0,
            'historial_clinico' => $paciente->id_paciente > 0 ? $paciente->historialClinico()->count() : 0,
        ];

        $citasProximas = $paciente->id_paciente > 0 ? Cita::with(['medico.usuario'])
                             ->where('id_paciente', $paciente->id_paciente)
                             ->where('fecha', '>', now())
                             ->where('estado', '!=', 'cancelada')
                             ->orderBy('fecha')
                             ->limit(5)
                             ->get() : collect();

        $tratamientosActivos = $paciente->id_paciente > 0 ? Tratamiento::with(['medico.usuario'])
                                         ->where('id_paciente', $paciente->id_paciente)
                                         ->where('activo', 1)
                                         ->orderBy('fecha_inicio', 'desc')
                                         ->limit(5)
                                         ->get() : collect();

        // Datos para gráficas
        $datosGraficas = $this->obtenerDatosGraficas($paciente);

        return view('paciente.dashboard', compact('stats', 'citasProximas', 'tratamientosActivos', 'datosGraficas', 'paciente'));
    }

    private function obtenerDatosGraficas($paciente)
    {
        // Si no hay paciente real, retornar datos vacíos
        if ($paciente->id_paciente <= 0) {
            return [
                'citas_ultimos_meses' => collect(),
                'tratamientos_por_tipo' => collect(),
                'diagnosticos_por_mes' => collect(),
            ];
        }

        // Obtener citas de los últimos 6 meses para gráfica de actividad
        $citasUltimosMeses = Cita::where('id_paciente', $paciente->id_paciente)
                                 ->where('fecha', '>=', now()->subMonths(6))
                                 ->selectRaw('MONTH(fecha) as mes, COUNT(*) as total')
                                 ->groupBy('mes')
                                 ->get()
                                 ->keyBy('mes');

        // Obtener tratamientos por tipo
        $tratamientosPorTipo = Tratamiento::where('id_paciente', $paciente->id_paciente)
                                         ->selectRaw('nombre, COUNT(*) as total')
                                         ->groupBy('nombre')
                                         ->get();

        // Obtener diagnósticos por mes
        $diagnosticosPorMes = Diagnostico::where('id_paciente', $paciente->id_paciente)
                                        ->where('fecha', '>=', now()->subMonths(6))
                                        ->selectRaw('MONTH(fecha) as mes, COUNT(*) as total')
                                        ->groupBy('mes')
                                        ->get()
                                        ->keyBy('mes');

        return [
            'citas_ultimos_meses' => $citasUltimosMeses,
            'tratamientos_por_tipo' => $tratamientosPorTipo,
            'diagnosticos_por_mes' => $diagnosticosPorMes,
        ];
    }
} 