<?php

namespace App\Http\Controllers;

use App\Models\HistorialClinico;
use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HistorialClinicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isMedico()) {
            // Médicos ven historiales de sus pacientes
            $historiales = HistorialClinico::with(['paciente.usuario', 'diagnostico', 'tratamiento'])
                ->whereHas('paciente', function($query) use ($user) {
                    $query->whereHas('medico', function($q) use ($user) {
                        $q->where('id_medico', $user->medico->id_medico);
                    });
                })
                ->when($request->filled('paciente'), function($query) use ($request) {
                    return $query->whereHas('paciente.usuario', function($q) use ($request) {
                        $q->where('nombre', 'like', '%' . $request->paciente . '%');
                    });
                })
                ->when($request->filled('fecha'), function($query) use ($request) {
                    return $query->whereDate('fecha_registro', $request->fecha);
                })
                ->orderBy('fecha_registro', 'desc')
                ->paginate(15);
        } else {
            // Pacientes ven su propio historial
            $historiales = HistorialClinico::with(['diagnostico', 'tratamiento'])
                ->where('id_paciente', $user->paciente->id_paciente)
                ->orderBy('fecha_registro', 'desc')
                ->paginate(15);
        }

        return view('historial-clinico.index', compact('historiales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('historial-clinico.index')->with('error', 'Solo los médicos pueden crear registros de historial clínico.');
        }

        $pacientes = Paciente::with('usuario')->get();
        $diagnosticos = Diagnostico::where('id_medico', $user->medico->id_medico)->get();
        $tratamientos = Tratamiento::where('id_medico', $user->medico->id_medico)->get();

        return view('historial-clinico.create', compact('pacientes', 'diagnosticos', 'tratamientos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('historial-clinico.index')->with('error', 'Solo los médicos pueden crear registros de historial clínico.');
        }

        $validator = Validator::make($request->all(), [
            'id_paciente' => 'required|exists:pacientes,id_paciente',
            'id_diagnostico' => 'nullable|exists:diagnosticos,id_diagnostico',
            'id_tratamiento' => 'nullable|exists:tratamientos,id_tratamiento',
            'observaciones' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $historial = HistorialClinico::create([
            'id_paciente' => $request->id_paciente,
            'id_diagnostico' => $request->id_diagnostico,
            'id_tratamiento' => $request->id_tratamiento,
            'observaciones' => $request->observaciones,
            'fecha_registro' => now(),
        ]);

        return redirect()->route('historial-clinico.index')->with('success', 'Registro de historial clínico creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $historial = HistorialClinico::with(['paciente.usuario', 'diagnostico', 'tratamiento'])->findOrFail($id);

        // Verificar permisos
        if ($user->isPaciente() && $historial->id_paciente !== $user->paciente->id_paciente) {
            return redirect()->route('historial-clinico.index')->with('error', 'No tienes permisos para ver este registro.');
        }

        if ($user->isMedico() && !$historial->paciente->medico || $historial->paciente->medico->id_medico !== $user->medico->id_medico) {
            return redirect()->route('historial-clinico.index')->with('error', 'No tienes permisos para ver este registro.');
        }

        return view('historial-clinico.show', compact('historial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('historial-clinico.index')->with('error', 'Solo los médicos pueden editar registros de historial clínico.');
        }

        $historial = HistorialClinico::findOrFail($id);
        
        if (!$historial->paciente->medico || $historial->paciente->medico->id_medico !== $user->medico->id_medico) {
            return redirect()->route('historial-clinico.index')->with('error', 'No tienes permisos para editar este registro.');
        }

        $pacientes = Paciente::with('usuario')->get();
        $diagnosticos = Diagnostico::where('id_medico', $user->medico->id_medico)->get();
        $tratamientos = Tratamiento::where('id_medico', $user->medico->id_medico)->get();

        return view('historial-clinico.edit', compact('historial', 'pacientes', 'diagnosticos', 'tratamientos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('historial-clinico.index')->with('error', 'Solo los médicos pueden editar registros de historial clínico.');
        }

        $historial = HistorialClinico::findOrFail($id);
        
        if (!$historial->paciente->medico || $historial->paciente->medico->id_medico !== $user->medico->id_medico) {
            return redirect()->route('historial-clinico.index')->with('error', 'No tienes permisos para editar este registro.');
        }

        $validator = Validator::make($request->all(), [
            'id_diagnostico' => 'nullable|exists:diagnosticos,id_diagnostico',
            'id_tratamiento' => 'nullable|exists:tratamientos,id_tratamiento',
            'observaciones' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $historial->update($request->all());

        return redirect()->route('historial-clinico.index')->with('success', 'Registro de historial clínico actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('historial-clinico.index')->with('error', 'Solo los médicos pueden eliminar registros de historial clínico.');
        }

        $historial = HistorialClinico::findOrFail($id);
        
        if (!$historial->paciente->medico || $historial->paciente->medico->id_medico !== $user->medico->id_medico) {
            return redirect()->route('historial-clinico.index')->with('error', 'No tienes permisos para eliminar este registro.');
        }

        $historial->delete();

        return redirect()->route('historial-clinico.index')->with('success', 'Registro de historial clínico eliminado exitosamente.');
    }

    /**
     * Método para pacientes ver su historial clínico
     */
    public function paciente()
    {
        $user = Auth::user();
        
        if (!$user->isPaciente()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $historiales = HistorialClinico::with(['diagnostico', 'tratamiento'])
            ->where('id_paciente', $user->paciente->id_paciente)
            ->orderBy('fecha_registro', 'desc')
            ->paginate(15);

        return view('paciente.historial-clinico.index', compact('historiales'));
    }
}
