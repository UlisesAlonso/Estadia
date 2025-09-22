@extends('layouts.app')

@section('title', 'Detalles de la Cita')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Detalles de la Cita</h1>
            <a href="{{ route('paciente.citas.index') }}" class="btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header con estado -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            Cita del {{ $cita->fecha->format('d/m/Y') }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            {{ $cita->fecha->format('H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        @if($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                        @elseif($cita->estado === 'confirmada') bg-green-100 text-green-800
                        @elseif($cita->estado === 'completada') bg-blue-100 text-blue-800
                        @elseif($cita->estado === 'cancelada') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Información del Paciente -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Paciente</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd class="text-sm text-gray-900">{{ $cita->paciente->usuario->nombre_completo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Correo</dt>
                                <dd class="text-sm text-gray-900">{{ $cita->paciente->usuario->correo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $cita->paciente->fecha_nacimiento ? $cita->paciente->fecha_nacimiento->format('d/m/Y') : 'No especificada' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($cita->paciente->sexo ?? 'No especificado') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Información del Médico -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Médico</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd class="text-sm text-gray-900">{{ $cita->medico->usuario->nombre_completo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Especialidad</dt>
                                <dd class="text-sm text-gray-900">{{ $cita->medico->especialidad }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cédula Profesional</dt>
                                <dd class="text-sm text-gray-900">{{ $cita->medico->cedula_profesional }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Especialidad de la Cita</dt>
                                <dd class="text-sm text-gray-900">{{ $cita->especialidad_medica }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Motivo de la cita -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Motivo de la Cita</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $cita->motivo }}</p>
                    </div>
                </div>

                <!-- Observaciones Clínicas (si las hay) -->
                @if($cita->observaciones_clinicas)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Observaciones Clínicas</h3>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $cita->observaciones_clinicas }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Acciones -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Creada el {{ $cita->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex space-x-3">
                        @if($cita->estado === 'pendiente')
                        <a href="{{ route('paciente.citas.edit', $cita->id_cita) }}" class="btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                        @endif
                        <a href="{{ route('paciente.citas.index') }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a Mis Citas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


