@extends('layouts.app')

@section('title', 'Crear Nuevo Paciente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Paciente</h1>
            <a href="{{ route('medico.pacientes.index') }}" class="btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('medico.pacientes.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre *
                        </label>
                        <input type="text" name="nombre" id="nombre" 
                               value="{{ old('nombre') }}" required
                               class="form-input @error('nombre') border-red-500 @enderror"
                               placeholder="Nombre del paciente">
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
                               class="form-input @error('apPaterno') border-red-500 @enderror"
                               placeholder="Apellido paterno">
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
                               class="form-input @error('apMaterno') border-red-500 @enderror"
                               placeholder="Apellido materno">
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
                               class="form-input @error('correo') border-red-500 @enderror"
                               placeholder="correo@ejemplo.com">
                        @error('correo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña *
                        </label>
                        <input type="password" name="contrasena" id="contrasena" required
                               class="form-input @error('contrasena') border-red-500 @enderror"
                               placeholder="Mínimo 8 caracteres">
                        @error('contrasena')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Nacimiento *
                        </label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento') }}" required
                               class="form-input @error('fecha_nacimiento') border-red-500 @enderror">
                        @error('fecha_nacimiento')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                            Sexo *
                        </label>
                        <select name="sexo" id="sexo" required
                                class="form-select @error('sexo') border-red-500 @enderror">
                            <option value="">Seleccionar sexo</option>
                            <option value="masculino" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="femenino" {{ old('sexo') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="otro" {{ old('sexo') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('sexo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('medico.pacientes.index') }}" class="btn-outline">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Crear Paciente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 