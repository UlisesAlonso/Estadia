@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mis Citas</h1>
        <div class="flex space-x-3">
            <a href="{{ route('paciente.citas.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Cita
            </a>
            <a href="{{ route('paciente.dashboard') }}" class="btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Citas Próximas (7 días) -->
    @if($citasProximas->count() > 0)
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <div class="bg-orange-100 p-2 rounded-lg mr-3">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Próximas Citas (7 días)</h2>
            <span class="ml-3 bg-orange-100 text-orange-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                {{ $citasProximas->count() }} cita{{ $citasProximas->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($citasProximas as $cita)
            <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500 p-6 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $cita->fecha->format('d/m/Y') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $cita->fecha->format('H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                        @elseif($cita->estado === 'confirmada') bg-green-100 text-green-800
                        @elseif($cita->estado === 'completada') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Médico:</span> {{ $cita->medico->usuario->nombre_completo }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Especialidad:</span> {{ $cita->especialidad_medica ?? $cita->medico->especialidad }}
                    </p>
                </div>
                
                <p class="text-sm text-gray-700 mb-4">{{ $cita->motivo }}</p>
                
                <div class="flex space-x-2">
                    <a href="{{ route('paciente.citas.show', $cita->id_cita) }}" class="btn-outline text-xs">
                        Ver Detalles
                    </a>
                    @if($cita->estado === 'pendiente')
                    <a href="{{ route('paciente.citas.edit', $cita->id_cita) }}" class="btn-outline text-xs">
                        Editar
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Citas Futuras (después de 7 días) -->
    @if($citasFuturas->count() > 0)
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Citas Futuras</h2>
            <span class="ml-3 bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                {{ $citasFuturas->count() }} cita{{ $citasFuturas->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($citasFuturas as $cita)
            <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $cita->fecha->format('d/m/Y') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $cita->fecha->format('H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                        @elseif($cita->estado === 'confirmada') bg-green-100 text-green-800
                        @elseif($cita->estado === 'completada') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Médico:</span> {{ $cita->medico->usuario->nombre_completo }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Especialidad:</span> {{ $cita->especialidad_medica ?? $cita->medico->especialidad }}
                    </p>
                </div>
                
                <p class="text-sm text-gray-700 mb-4">{{ $cita->motivo }}</p>
                
                <div class="flex space-x-2">
                    <a href="{{ route('paciente.citas.show', $cita->id_cita) }}" class="btn-outline text-xs">
                        Ver Detalles
                    </a>
                    @if($cita->estado === 'pendiente')
                    <a href="{{ route('paciente.citas.edit', $cita->id_cita) }}" class="btn-outline text-xs">
                        Editar
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Citas Pasadas -->
    @if($citasPasadas->count() > 0)
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <div class="bg-gray-100 p-2 rounded-lg mr-3">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Citas Pasadas</h2>
            <span class="ml-3 bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                {{ $citasPasadas->count() }} cita{{ $citasPasadas->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($citasPasadas as $cita)
            <div class="bg-white rounded-lg shadow-md border-l-4 border-gray-400 p-6 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $cita->fecha->format('d/m/Y') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $cita->fecha->format('H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($cita->estado === 'completada') bg-green-100 text-green-800
                        @elseif($cita->estado === 'cancelada') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-1">
                        <span class="font-medium">Médico:</span> {{ $cita->medico->usuario->nombre_completo }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Especialidad:</span> {{ $cita->especialidad_medica ?? $cita->medico->especialidad }}
                    </p>
                </div>
                
                <p class="text-sm text-gray-700 mb-4">{{ $cita->motivo }}</p>
                
                <div class="flex space-x-2">
                    <a href="{{ route('paciente.citas.show', $cita->id_cita) }}" class="btn-outline text-xs">
                        Ver Detalles
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Estado vacío si no hay citas -->
    @if($citasProximas->count() === 0 && $citasFuturas->count() === 0 && $citasPasadas->count() === 0)
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes citas programadas</h3>
        <p class="mt-1 text-sm text-gray-500">
            Comienza programando tu primera cita médica.
        </p>
        <div class="mt-6">
            <a href="{{ route('paciente.citas.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Programar Nueva Cita
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
