@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Usuario</h1>
            <a href="{{ route('admin.users.index') }}" class="btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información básica -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
                    </div>
                    
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre *
                        </label>
                        <input type="text" name="nombre" id="nombre" 
                               value="{{ old('nombre') }}" required
                               class="form-input @error('nombre') border-red-500 @enderror">
                        @error('nombre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="apPaterno" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Paterno
                        </label>
                        <input type="text" name="apPaterno" id="apPaterno" 
                               value="{{ old('apPaterno') }}"
                               class="form-input @error('apPaterno') border-red-500 @enderror">
                        @error('apPaterno')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="apMaterno" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido Materno
                        </label>
                        <input type="text" name="apMaterno" id="apMaterno" 
                               value="{{ old('apMaterno') }}"
                               class="form-input @error('apMaterno') border-red-500 @enderror">
                        @error('apMaterno')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico *
                        </label>
                        <input type="email" name="correo" id="correo" 
                               value="{{ old('correo') }}" required
                               class="form-input @error('correo') border-red-500 @enderror">
                        @error('correo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña *
                        </label>
                        <input type="password" name="contrasena" id="contrasena" required
                               class="form-input @error('contrasena') border-red-500 @enderror">
                        @error('contrasena')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contrasena_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Contraseña *
                        </label>
                        <input type="password" name="contrasena_confirmation" id="contrasena_confirmation" required
                               class="form-input">
                    </div>

                    <div>
                        <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                            Rol *
                        </label>
                        <select name="rol" id="rol" required
                                class="form-select @error('rol') border-red-500 @enderror">
                            <option value="">Seleccionar rol</option>
                            <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>
                                Administrador
                            </option>
                            <option value="medico" {{ old('rol') == 'medico' ? 'selected' : '' }}>
                                Médico
                            </option>
                            <option value="paciente" {{ old('rol') == 'paciente' ? 'selected' : '' }}>
                                Paciente
                            </option>
                        </select>
                        @error('rol')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="activo" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" name="activo" id="activo" value="1" 
                                   {{ old('activo', 1) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="activo" class="ml-2 block text-sm text-gray-900">
                                Usuario activo
                            </label>
                        </div>
                    </div>

                    <!-- Campos específicos por rol -->
                    <div id="campos-medico" class="md:col-span-2 hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Médico</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                                    Especialidad
                                </label>
                                <input type="text" name="especialidad" id="especialidad" 
                                       value="{{ old('especialidad') }}"
                                       class="form-input @error('especialidad') border-red-500 @enderror"
                                       placeholder="Ej: Cardiología">
                                @error('especialidad')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="cedula_profesional" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cédula Profesional
                                </label>
                                <input type="text" name="cedula_profesional" id="cedula_profesional" 
                                       value="{{ old('cedula_profesional') }}"
                                       class="form-input @error('cedula_profesional') border-red-500 @enderror"
                                       placeholder="Ej: CARD001">
                                @error('cedula_profesional')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="fecha_nacimiento_medico" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" name="fecha_nacimiento_medico" id="fecha_nacimiento_medico" 
                                       value="{{ old('fecha_nacimiento_medico') }}"
                                       class="form-input @error('fecha_nacimiento_medico') border-red-500 @enderror">
                                @error('fecha_nacimiento_medico')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="campos-paciente" class="md:col-span-2 hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Paciente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                       value="{{ old('fecha_nacimiento') }}"
                                       class="form-input @error('fecha_nacimiento') border-red-500 @enderror">
                                @error('fecha_nacimiento')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sexo
                                </label>
                                <select name="sexo" id="sexo"
                                        class="form-select @error('sexo') border-red-500 @enderror">
                                    <option value="">Seleccionar</option>
                                    <option value="masculino" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="femenino" {{ old('sexo') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="otro" {{ old('sexo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('sexo')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('admin.users.index') }}" class="btn-outline">Cancelar</a>
                    <button type="submit" class="btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('rol').addEventListener('change', function() {
    const camposMedico = document.getElementById('campos-medico');
    const camposPaciente = document.getElementById('campos-paciente');
    
    camposMedico.classList.add('hidden');
    camposPaciente.classList.add('hidden');
    
    if (this.value === 'medico') {
        camposMedico.classList.remove('hidden');
    } else if (this.value === 'paciente') {
        camposPaciente.classList.remove('hidden');
    }
});
</script>
@endsection 