<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MedicoPacienteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $query = User::where('rol', 'paciente');

        // Filtros
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apPaterno', 'like', "%{$search}%")
                  ->orWhere('apMaterno', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado);
        }

        $pacientes = $query->with('paciente')->paginate(15);

        return view('medico.pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        return view('medico.pacientes.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apPaterno' => 'nullable|string|max:100',
            'apMaterno' => 'nullable|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|min:8',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:masculino,femenino,otro',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Crear usuario paciente
        $userPaciente = User::create([
            'nombre' => $request->nombre,
            'apPaterno' => $request->apPaterno,
            'apMaterno' => $request->apMaterno,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
            'rol' => 'paciente',
            'activo' => 1
        ]);

        // Crear registro de paciente
        Paciente::create([
            'id_usuario' => $userPaciente->id_usuario,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => (string) $request->sexo
        ]);

        // Recargar el usuario para asegurar que la relación se cargue
        $userPaciente->load('paciente');

        return redirect()->route('medico.pacientes.index')
                        ->with('success', 'Paciente creado exitosamente.');
    }

    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $paciente = User::with(['paciente'])->where('rol', 'paciente')->findOrFail($id);
        
        return view('medico.pacientes.show', compact('paciente'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $paciente = User::with(['paciente'])->where('rol', 'paciente')->findOrFail($id);
        
        return view('medico.pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $paciente = User::where('rol', 'paciente')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apPaterno' => 'nullable|string|max:100',
            'apMaterno' => 'nullable|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:masculino,femenino,otro',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Actualizar usuario
        $paciente->update([
            'nombre' => $request->nombre,
            'apPaterno' => $request->apPaterno,
            'apMaterno' => $request->apMaterno,
            'correo' => $request->correo,
        ]);

        // Actualizar información del paciente
        if ($paciente->paciente) {
            $paciente->paciente->update([
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => (string) $request->sexo
            ]);
            
            // Recargar la relación para asegurar que los cambios se vean
            $paciente->load('paciente');
        } else {
            // Si no existe el registro de paciente, crearlo
            Paciente::create([
                'id_usuario' => $paciente->id_usuario,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => (string) $request->sexo
            ]);
            
            // Recargar la relación para asegurar que los cambios se vean
            $paciente->load('paciente');
        }

        return redirect()->route('medico.pacientes.index')
                        ->with('success', 'Paciente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $paciente = User::where('rol', 'paciente')->findOrFail($id);
        
        // Eliminar registros relacionados
        if ($paciente->paciente) {
            $paciente->paciente->delete();
        }
        
        // Eliminar tokens de recuperación
        $paciente->tokensRecuperacion()->delete();
        
        // Eliminar mensajes enviados y recibidos
        $paciente->mensajesEnviados()->delete();
        $paciente->mensajesRecibidos()->delete();
        
        // Eliminar respuestas
        $paciente->respuestas()->delete();
        
        // Eliminar citas relacionadas
        $paciente->citas()->delete();
        
        // Eliminar historial clínico
        $paciente->historialClinico()->delete();
        
        // Finalmente eliminar el usuario
        $paciente->delete();

        return redirect()->route('medico.pacientes.index')
                        ->with('success', 'Paciente eliminado exitosamente.');
    }

    public function toggleStatus($id)
    {
        $user = Auth::user();
        
        if (!$user->isMedico()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        $paciente = User::where('rol', 'paciente')->findOrFail($id);
        $paciente->update(['activo' => !$paciente->activo]);
        
        // Recargar el modelo para obtener el nuevo estado
        $paciente->refresh();

        $status = $paciente->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Paciente {$status} exitosamente.");
    }
}
