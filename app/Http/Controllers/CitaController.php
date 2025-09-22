<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Cita::with(['paciente.usuario', 'medico.usuario']);

        if ($user->isPaciente()) {
            // Pacientes ven solo sus citas
            $query->where('id_paciente', $user->paciente->id_paciente);
        } elseif ($user->isMedico()) {
            // Médicos ven citas donde son el médico asignado
            $query->where('id_medico', $user->medico->id_medico);
        }

        // Filtros mejorados
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('especialidad')) {
            $query->where('especialidad_medica', $request->especialidad);
        }

        if ($request->filled('paciente') && $user->isMedico()) {
            $busqueda = trim($request->paciente);
            $query->whereHas('paciente.usuario', function($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('apPaterno', 'like', "%{$busqueda}%")
                  ->orWhere('apMaterno', 'like', "%{$busqueda}%");
            });
        }

        // Ordenamiento
        $orden = $request->get('orden', 'fecha');
        $direccion = $request->get('direccion', 'asc');
        $query->orderBy($orden, $direccion);

        $citas = $query->paginate(15);

        // Datos para filtros
        $estados = ['pendiente', 'confirmada', 'completada', 'cancelada'];
        $especialidades = Cita::distinct()->pluck('especialidad_medica')->filter();

        return view('citas.index', compact('citas', 'estados', 'especialidades'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->isPaciente()) {
            // Pacientes pueden crear citas
            $medicos = Medico::with('usuario')->get();
            return view('citas.create', compact('medicos'));
        } elseif ($user->isMedico()) {
            // Médicos pueden crear citas para sus pacientes
            $pacientes = Paciente::with('usuario')->get();
            return view('citas.create-medico', compact('pacientes'));
        }

        return redirect()->route('citas.index')
                        ->with('error', 'No tienes permisos para crear citas.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isPaciente()) {
            return $this->storePaciente($request, $user);
        } elseif ($user->isMedico()) {
            return $this->storeMedico($request, $user);
        }

        return redirect()->route('citas.index')
                        ->with('error', 'No tienes permisos para crear citas.');
    }

    private function storePaciente(Request $request, $user)
    {
        $request->validate([
            'id_medico' => 'required|exists:medicos,id_medico',
            'fecha' => 'required|date|after:now',
            'hora' => 'required|string',
            'motivo' => 'required|string|max:500'
        ]);

        // Obtener información del médico
        $medico = Medico::with('usuario')->findOrFail($request->id_medico);

        // Combinar fecha y hora
        $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

        // Verificar disponibilidad del médico
        $citaExistente = Cita::where('id_medico', $request->id_medico)
                            ->where('fecha', $fechaHora)
                            ->where('estado', '!=', 'cancelada')
                            ->first();

        if ($citaExistente) {
            return back()->withErrors([
                'fecha' => 'El médico no está disponible en esa fecha y hora.'
            ])->withInput();
        }

        Cita::create([
            'id_paciente' => $user->paciente->id_paciente,
            'id_medico' => $request->id_medico,
            'fecha' => $fechaHora,
            'motivo' => $request->motivo,
            'estado' => 'pendiente',
            'especialidad_medica' => $medico->especialidad
        ]);

        return redirect()->route('paciente.citas.index')
                        ->with('success', 'Cita creada exitosamente.');
    }

    private function storeMedico(Request $request, $user)
    {
        $request->validate([
            'id_paciente' => 'required|exists:pacientes,id_paciente',
            'fecha' => 'required|date|after:now',
            'hora' => 'required|string',
            'motivo' => 'required|string|max:500',
            'observaciones_clinicas' => 'nullable|string|max:1000'
        ]);

        // Combinar fecha y hora
        $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

        // Verificar disponibilidad del médico
        $citaExistente = Cita::where('id_medico', $user->medico->id_medico)
                            ->where('fecha', $fechaHora)
                            ->where('estado', '!=', 'cancelada')
                            ->first();

        if ($citaExistente) {
            return back()->withErrors([
                'fecha' => 'No estás disponible en esa fecha y hora.'
            ])->withInput();
        }

        Cita::create([
            'id_paciente' => $request->id_paciente,
            'id_medico' => $user->medico->id_medico,
            'fecha' => $fechaHora,
            'motivo' => $request->motivo,
            'estado' => 'confirmada', // Los médicos crean citas ya confirmadas
            'especialidad_medica' => $user->medico->especialidad,
            'observaciones_clinicas' => $request->observaciones_clinicas
        ]);

        return redirect()->route('medico.citas.index')
                        ->with('success', 'Cita creada y confirmada exitosamente.');
    }

    public function show($id)
    {
        $cita = Cita::with(['paciente.usuario', 'medico.usuario'])->findOrFail($id);
        $user = Auth::user();

        // Verificar permisos
        if ($user->isPaciente() && $cita->id_paciente !== $user->paciente->id_paciente) {
            return redirect()->route('citas.index')
                            ->with('error', 'No tienes permisos para ver esta cita.');
        }

        if ($user->isMedico() && $cita->id_medico !== $user->medico->id_medico) {
            return redirect()->route('citas.index')
                            ->with('error', 'No tienes permisos para ver esta cita.');
        }

        return view('citas.show', compact('cita'));
    }

    public function edit($id)
    {
        $cita = Cita::with(['paciente.usuario', 'medico.usuario'])->findOrFail($id);
        $user = Auth::user();

        // Verificar permisos
        if ($user->isPaciente()) {
            if ($cita->id_paciente !== $user->paciente->id_paciente || !$cita->puedeSerModificada()) {
                return redirect()->route('citas.index')
                                ->with('error', 'No puedes editar esta cita.');
            }
            $medicos = Medico::with('usuario')->get();
            return view('citas.edit', compact('cita', 'medicos'));
        } elseif ($user->isMedico()) {
            if ($cita->id_medico !== $user->medico->id_medico || !$cita->puedeSerModificada()) {
                return redirect()->route('citas.index')
                                ->with('error', 'No puedes editar esta cita.');
            }
            $pacientes = Paciente::with('usuario')->get();
            return view('citas.edit-medico', compact('cita', 'pacientes'));
        }

        return redirect()->route('citas.index')
                        ->with('error', 'No tienes permisos para editar esta cita.');
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        $user = Auth::user();

        if ($user->isPaciente()) {
            return $this->updatePaciente($request, $cita, $user);
        } elseif ($user->isMedico()) {
            return $this->updateMedico($request, $cita, $user);
        }

        return redirect()->route('citas.index')
                        ->with('error', 'No tienes permisos para actualizar esta cita.');
    }

    private function updatePaciente(Request $request, $cita, $user)
    {
        if ($cita->id_paciente !== $user->paciente->id_paciente || !$cita->puedeSerModificada()) {
            return redirect()->route('citas.index')
                            ->with('error', 'No puedes actualizar esta cita.');
        }

        $request->validate([
            'id_medico' => 'required|exists:medicos,id_medico',
            'fecha' => 'required|date|after:now',
            'hora' => 'required|string',
            'motivo' => 'required|string|max:500'
        ]);

        // Obtener información del médico
        $medico = Medico::with('usuario')->findOrFail($request->id_medico);

        // Combinar fecha y hora
        $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

        // Verificar disponibilidad del médico
        $citaExistente = Cita::where('id_medico', $request->id_medico)
                            ->where('fecha', $fechaHora)
                            ->where('estado', '!=', 'cancelada')
                            ->where('id_cita', '!=', $cita->id_cita)
                            ->first();

        if ($citaExistente) {
            return back()->withErrors([
                'fecha' => 'El médico no está disponible en esa fecha y hora.'
            ])->withInput();
        }

        $cita->update([
            'id_medico' => $request->id_medico,
            'fecha' => $fechaHora,
            'motivo' => $request->motivo,
            'especialidad_medica' => $medico->especialidad
        ]);

        return redirect()->route('paciente.citas.index')
                        ->with('success', 'Cita actualizada exitosamente.');
    }

    private function updateMedico(Request $request, $cita, $user)
    {
        if ($cita->id_medico !== $user->medico->id_medico || !$cita->puedeSerModificada()) {
            return redirect()->route('citas.index')
                            ->with('error', 'No puedes actualizar esta cita.');
        }

        $request->validate([
            'id_paciente' => 'required|exists:pacientes,id_paciente',
            'fecha' => 'required|date|after:now',
            'hora' => 'required|string',
            'motivo' => 'required|string|max:500',
            'observaciones_clinicas' => 'nullable|string|max:1000',
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada'
        ]);

        // Combinar fecha y hora
        $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

        // Verificar disponibilidad del médico
        $citaExistente = Cita::where('id_medico', $user->medico->id_medico)
                            ->where('fecha', $fechaHora)
                            ->where('estado', '!=', 'cancelada')
                            ->where('id_cita', '!=', $cita->id_cita)
                            ->first();

        if ($citaExistente) {
            return back()->withErrors([
                'fecha' => 'No estás disponible en esa fecha y hora.'
            ])->withInput();
        }

        $cita->update([
            'id_paciente' => $request->id_paciente,
            'fecha' => $fechaHora,
            'motivo' => $request->motivo,
            'estado' => $request->estado,
            'observaciones_clinicas' => $request->observaciones_clinicas
        ]);

        return redirect()->route('medico.citas.index')
                        ->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $user = Auth::user();

        if ($user->isPaciente()) {
            // Pacientes solo pueden cancelar sus citas
            if ($cita->id_paciente !== $user->paciente->id_paciente) {
                return redirect()->route('citas.index')
                                ->with('error', 'No puedes cancelar esta cita.');
            }
            $cita->update(['estado' => 'cancelada']);
            return redirect()->route('paciente.citas.index')
                            ->with('success', 'Cita cancelada exitosamente.');
        } elseif ($user->isMedico()) {
            // Médicos pueden eliminar citas completadas o canceladas
            if ($cita->id_medico !== $user->medico->id_medico) {
                return redirect()->route('citas.index')
                                ->with('error', 'No puedes eliminar esta cita.');
            }
            
            if (!$cita->puedeSerEliminada()) {
                return redirect()->route('citas.index')
                                ->with('error', 'Solo puedes eliminar citas completadas o canceladas.');
            }

            $cita->delete();
            return redirect()->route('medico.citas.index')
                            ->with('success', 'Cita eliminada exitosamente.');
        }

        return redirect()->route('citas.index')
                        ->with('error', 'No tienes permisos para realizar esta acción.');
    }

    public function confirmar($id)
    {
        $cita = Cita::findOrFail($id);
        $user = Auth::user();

        // Solo el médico puede confirmar citas
        if (!$user->isMedico() || $cita->id_medico !== $user->medico->id_medico) {
            return redirect()->route('citas.index')
                            ->with('error', 'No puedes confirmar esta cita.');
        }

        $cita->update(['estado' => 'confirmada']);

        return redirect()->route('citas.index')
                        ->with('success', 'Cita confirmada exitosamente.');
    }

    public function completar($id)
    {
        $cita = Cita::findOrFail($id);
        $user = Auth::user();

        // Solo el médico puede marcar como completada
        if (!$user->isMedico() || $cita->id_medico !== $user->medico->id_medico) {
            return redirect()->route('citas.index')
                            ->with('error', 'No puedes completar esta cita.');
        }

        $cita->update(['estado' => 'completada']);

        return redirect()->route('citas.index')
                        ->with('success', 'Cita marcada como completada.');
    }

    public function disponibilidad(Request $request)
    {
        $request->validate([
            'id_medico' => 'required|exists:medicos,id_medico',
            'fecha' => 'required|date|after:now'
        ]);

        $citasOcupadas = Cita::where('id_medico', $request->id_medico)
                             ->whereDate('fecha', $request->fecha)
                             ->where('estado', '!=', 'cancelada')
                             ->pluck('fecha')
                             ->toArray();

        return response()->json([
            'disponible' => empty($citasOcupadas),
            'citas_ocupadas' => $citasOcupadas
        ]);
    }

    /**
     * Método específico para pacientes ver sus citas organizadas
     */
    public function paciente()
    {
        $user = Auth::user();
        
        if (!$user->isPaciente()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $hoy = now();
        $proximos7Dias = $hoy->copy()->addDays(7);

        // Citas próximas (próximos 7 días)
        $citasProximas = Cita::with(['medico.usuario'])
            ->where('id_paciente', $user->paciente->id_paciente)
            ->where('fecha', '>=', $hoy)
            ->where('fecha', '<=', $proximos7Dias)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->get();

        // Citas futuras (después de 7 días)
        $citasFuturas = Cita::with(['medico.usuario'])
            ->where('id_paciente', $user->paciente->id_paciente)
            ->where('fecha', '>', $proximos7Dias)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha')
            ->get();

        // Citas pasadas
        $citasPasadas = Cita::with(['medico.usuario'])
            ->where('id_paciente', $user->paciente->id_paciente)
            ->where('fecha', '<', $hoy)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('paciente.citas', compact('citasProximas', 'citasFuturas', 'citasPasadas'));
    }

    
} 