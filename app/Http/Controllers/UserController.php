<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado);
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apPaterno', 'like', "%{$search}%")
                  ->orWhere('apMaterno', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apPaterno' => 'nullable|string|max:100',
            'apMaterno' => 'nullable|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'rol' => 'required|in:administrador,medico,paciente',
            'contrasena' => 'required|min:8',
            'especialidad' => 'required_if:rol,medico|nullable|string|max:100',
            'cedula_profesional' => 'required_if:rol,medico|nullable|string|max:50',
            'fecha_nacimiento_medico' => 'required_if:rol,medico|nullable|date',
            'fecha_nacimiento' => 'required_if:rol,paciente|nullable|date',
            'sexo' => 'required_if:rol,paciente|nullable|in:masculino,femenino,otro',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Crear usuario
        $user = User::create([
            'nombre' => $request->nombre,
            'apPaterno' => $request->apPaterno,
            'apMaterno' => $request->apMaterno,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
            'rol' => $request->rol,
            'activo' => 1
        ]);

        // Crear registro específico según el rol
        if ($request->rol === 'medico') {
            Medico::create([
                'id_usuario' => $user->id_usuario,
                'especialidad' => $request->especialidad,
                'cedula_profesional' => $request->cedula_profesional,
                'fecha_nacimiento' => $request->fecha_nacimiento_medico
            ]);
        } elseif ($request->rol === 'paciente') {
            Paciente::create([
                'id_usuario' => $user->id_usuario,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario creado exitosamente.');
    }

    public function show($id)
    {
        $user = User::with(['paciente', 'medico'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with(['paciente', 'medico'])->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apPaterno' => 'nullable|string|max:100',
            'apMaterno' => 'nullable|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario',
            'rol' => 'required|in:administrador,medico,paciente',
            'especialidad' => 'required_if:rol,medico|nullable|string|max:100',
            'cedula_profesional' => 'required_if:rol,medico|nullable|string|max:50',
            'fecha_nacimiento_medico' => 'required_if:rol,medico|nullable|date',
            'fecha_nacimiento' => 'required_if:rol,paciente|nullable|date',
            'sexo' => 'required_if:rol,paciente|nullable|in:masculino,femenino,otro',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Actualizar usuario
        $user->update([
            'nombre' => $request->nombre,
            'apPaterno' => $request->apPaterno,
            'apMaterno' => $request->apMaterno,
            'correo' => $request->correo,
            'rol' => $request->rol,
        ]);

        // Actualizar información específica según el rol
        if ($request->rol === 'medico') {
            if ($user->medico) {
                $user->medico->update([
                    'especialidad' => $request->especialidad,
                    'cedula_profesional' => $request->cedula_profesional,
                    'fecha_nacimiento' => $request->fecha_nacimiento_medico
                ]);
            } else {
                Medico::create([
                    'id_usuario' => $user->id_usuario,
                    'especialidad' => $request->especialidad,
                    'cedula_profesional' => $request->cedula_profesional,
                    'fecha_nacimiento' => $request->fecha_nacimiento_medico
                ]);
            }
        } elseif ($request->rol === 'paciente') {
            if ($user->paciente) {
                $user->paciente->update([
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'sexo' => $request->sexo
                ]);
            } else {
                Paciente::create([
                    'id_usuario' => $user->id_usuario,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'sexo' => $request->sexo
                ]);
            }
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();
        
        // Verificar que no se elimine a sí mismo
        if ($user->id_usuario === $currentUser->id_usuario) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        
        // Verificar que no se elimine el último administrador
        if ($user->rol === 'administrador') {
            $adminCount = User::where('rol', 'administrador')->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.users.index')
                                ->with('error', 'No se puede eliminar el último administrador del sistema.');
            }
        }
        
        // Eliminar registros relacionados primero
        if ($user->medico) {
            $user->medico->delete();
        }
        
        if ($user->paciente) {
            $user->paciente->delete();
        }
        
        // Eliminar tokens de recuperación
        $user->tokensRecuperacion()->delete();
        
        // Eliminar mensajes enviados y recibidos
        $user->mensajesEnviados()->delete();
        $user->mensajesRecibidos()->delete();
        
        // Eliminar respuestas
        $user->respuestas()->delete();
        
        // Eliminar citas relacionadas
        $user->citas()->delete();
        $user->citasMedico()->delete();
        
        // Eliminar historial clínico (solo como paciente, no como médico)
        $user->historialClinico()->delete();
        
        // Finalmente eliminar el usuario
        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario eliminado exitosamente de la base de datos.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = Str::random(8);
        
        $user->update([
            'contrasena' => Hash::make($newPassword)
        ]);

        return back()->with('success', "Contraseña restablecida. Nueva contraseña: {$newPassword}");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $oldStatus = $user->activo;
        $user->update(['activo' => !$user->activo]);
        
        // Recargar el modelo para obtener el nuevo estado
        $user->refresh();

        $status = $user->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$status} exitosamente.");
    }
} 