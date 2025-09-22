@extends('layouts.app')

@section('title', 'Nueva Cita')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Nueva Cita</h1>
            <a href="{{ route('paciente.citas.index') }}" class="btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('paciente.citas.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Selección de Médico -->
                    <div class="md:col-span-2">
                        <label for="id_medico" class="block text-sm font-medium text-gray-700 mb-2">
                            Médico *
                        </label>
                        <select name="id_medico" id="id_medico" required
                                class="form-select @error('id_medico') border-red-500 @enderror">
                            <option value="">Selecciona un médico</option>
                            @foreach($medicos as $medico)
                                <option value="{{ $medico->id_medico }}" 
                                        {{ old('id_medico') == $medico->id_medico ? 'selected' : '' }}>
                                    {{ $medico->usuario->nombre_completo }} - {{ $medico->especialidad }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_medico')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha *
                        </label>
                        <input type="date" name="fecha" id="fecha" required
                               value="{{ old('fecha') }}"
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
                                        $selected = old('hora') === $time ? 'selected' : '';
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

                    <!-- Motivo -->
                    <div class="md:col-span-2">
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo de la cita *
                        </label>
                        <textarea name="motivo" id="motivo" rows="4" required
                                  placeholder="Describe brevemente el motivo de tu consulta..."
                                  class="form-textarea @error('motivo') border-red-500 @enderror">{{ old('motivo') }}</textarea>
                        @error('motivo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('paciente.citas.index') }}" class="btn-outline">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Crear Cita
                    </button>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 bg-blue-50 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Información importante
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Las citas deben programarse con al menos 24 horas de anticipación</li>
                            <li>Puedes cancelar o modificar tu cita hasta 2 horas antes de la hora programada</li>
                            <li>Recibirás una confirmación por correo electrónico</li>
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
    const medicoSelect = document.getElementById('id_medico');
    
    // Función para verificar disponibilidad
    function verificarDisponibilidad() {
        const fecha = fechaInput.value;
        const medico = medicoSelect.value;
        
        if (fecha && medico) {
            // Aquí podrías hacer una llamada AJAX para verificar disponibilidad
            console.log('Verificando disponibilidad para:', fecha, medico);
        }
    }
    
    // Event listeners
    fechaInput.addEventListener('change', verificarDisponibilidad);
    medicoSelect.addEventListener('change', verificarDisponibilidad);
});
</script>
@endsection


