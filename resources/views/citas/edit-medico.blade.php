@extends('layouts.app')

@section('title', 'Editar Cita - Médico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Editar Cita</h1>
            <a href="{{ route('medico.citas.index') }}" class="btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('medico.citas.update', $cita->id_cita) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Selección de Paciente -->
                    <div class="md:col-span-2">
                        <label for="id_paciente" class="block text-sm font-medium text-gray-700 mb-2">
                            Paciente *
                        </label>
                        <select name="id_paciente" id="id_paciente" required
                                class="form-select @error('id_paciente') border-red-500 @enderror">
                            <option value="">Selecciona un paciente</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id_paciente }}" 
                                        {{ (old('id_paciente', $cita->id_paciente) == $paciente->id_paciente) ? 'selected' : '' }}>
                                    {{ $paciente->usuario->nombre_completo }} 
                                    @if($paciente->fecha_nacimiento)
                                        ({{ $paciente->fecha_nacimiento->age }} años)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('id_paciente')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha *
                        </label>
                        <input type="date" name="fecha" id="fecha" required
                               value="{{ old('fecha', $cita->fecha->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="form-input @error('fecha') border-red-500 @enderror">
                        @error('fecha')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora -->
                    <div>
                        <label for="hora" class="block text-sm font-medium text-gray-700 mb-2">
                            Hora *
                        </label>
                        <select name="hora" id="hora" required
                                class="form-select @error('hora') border-red-500 @enderror">
                            <option value="">Selecciona una hora</option>
                            @for($h = 8; $h <= 18; $h++)
                                @for($m = 0; $m < 60; $m += 30)
                                    @php
                                        $time = sprintf('%02d:%02d', $h, $m);
                                        $selected = (old('hora', $cita->fecha->format('H:i')) === $time) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $time }}" {{ $selected }}>
                                        {{ $time }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                        @error('hora')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado *
                        </label>
                        <select name="estado" id="estado" required
                                class="form-select @error('estado') border-red-500 @enderror">
                            <option value="pendiente" {{ old('estado', $cita->estado) === 'pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>
                            <option value="confirmada" {{ old('estado', $cita->estado) === 'confirmada' ? 'selected' : '' }}>
                                Confirmada
                            </option>
                            <option value="completada" {{ old('estado', $cita->estado) === 'completada' ? 'selected' : '' }}>
                                Completada
                            </option>
                            <option value="cancelada" {{ old('estado', $cita->estado) === 'cancelada' ? 'selected' : '' }}>
                                Cancelada
                            </option>
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Especialidad (solo lectura) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Especialidad
                        </label>
                        <input type="text" value="{{ $cita->especialidad_medica }}" 
                               class="form-input bg-gray-100" readonly>
                        <p class="text-xs text-gray-500 mt-1">La especialidad se asigna automáticamente</p>
                    </div>

                    <!-- Motivo -->
                    <div class="md:col-span-2">
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo de la cita *
                        </label>
                        <textarea name="motivo" id="motivo" rows="4" required
                                  placeholder="Describe el motivo de la consulta..."
                                  class="form-textarea @error('motivo') border-red-500 @enderror">{{ old('motivo', $cita->motivo) }}</textarea>
                        @error('motivo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observaciones Clínicas -->
                    <div class="md:col-span-2">
                        <label for="observaciones_clinicas" class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones Clínicas
                        </label>
                        <textarea name="observaciones_clinicas" id="observaciones_clinicas" rows="3"
                                  placeholder="Observaciones médicas adicionales..."
                                  class="form-textarea @error('observaciones_clinicas') border-red-500 @enderror">{{ old('observaciones_clinicas', $cita->observaciones_clinicas) }}</textarea>
                        @error('observaciones_clinicas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Información del médico -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Información de la Cita</h3>
                    <div class="text-sm text-blue-700">
                        <p><strong>Médico:</strong> {{ auth()->user()->nombre_completo }}</p>
                        <p><strong>Especialidad:</strong> {{ auth()->user()->medico->especialidad }}</p>
                        <p><strong>Creada:</strong> {{ $cita->created_at->format('d/m/Y H:i') }}</p>
                        @if($cita->updated_at)
                            <p><strong>Última modificación:</strong> {{ $cita->updated_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('medico.citas.index') }}" class="btn-outline">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Cita
                    </button>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 bg-yellow-50 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Información importante
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Puedes cambiar el estado de la cita según sea necesario</li>
                            <li>Las observaciones clínicas son visibles solo para ti</li>
                            <li>El paciente será notificado de los cambios importantes</li>
                            <li>Los cambios se registrarán con fecha y hora</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha');
    const horaSelect = document.getElementById('hora');
    const estadoSelect = document.getElementById('estado');
    
    // Función para verificar disponibilidad
    function verificarDisponibilidad() {
        const fecha = fechaInput.value;
        
        if (fecha) {
            console.log('Verificando disponibilidad para:', fecha);
        }
    }
    
    // Event listeners
    fechaInput.addEventListener('change', verificarDisponibilidad);
    
    // Mostrar/ocultar campos según el estado
    function toggleFields() {
        const estado = estadoSelect.value;
        const observacionesField = document.getElementById('observaciones_clinicas').closest('div');
        
        if (estado === 'completada') {
            observacionesField.style.display = 'block';
        } else {
            observacionesField.style.display = 'block'; // Siempre visible para médicos
        }
    }
    
    estadoSelect.addEventListener('change', toggleFields);
    toggleFields(); // Ejecutar al cargar
});
</script>
@endsection
