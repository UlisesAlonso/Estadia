@extends('layouts.app')

@section('title', 'Reporte de Salud')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Reporte de Salud</h1>
        <p class="text-gray-600 mt-2">Bienvenido, {{ Auth::user()->nombre }}</p>
        
        @if($paciente->id_paciente <= 0)
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            ¡Bienvenido a tu primera visita!
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Tu historial médico se creará automáticamente cuando tengas tu primera cita con un médico. Por ahora, puedes explorar las funciones del sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Citas</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total_citas'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Diagnósticos</p>
                    <p class="text-2xl font-bold text-green-900">{{ $stats['total_diagnosticos'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-600">Tratamientos Activos</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['tratamientos_activos'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-indigo-600">Historial Clínico</p>
                    <p class="text-2xl font-bold text-indigo-900">{{ $stats['historial_clinico'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas de salud -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Gráfica de actividad de citas -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actividad de Citas (Últimos 6 meses)</h3>
            </div>
            <div class="p-6">
                @if($paciente->id_paciente <= 0)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p class="text-blue-600 font-medium mt-2">¡Bienvenido!</p>
                        <p class="text-gray-500 mt-1">Tu historial médico se creará cuando tengas tu primera cita</p>
                    </div>
                @elseif($datosGraficas['citas_ultimos_meses']->count() > 0)
                    <div class="h-64 flex items-end justify-between space-x-2">
                        @php
                            $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                            $maxCitas = $datosGraficas['citas_ultimos_meses']->max('total') ?: 1;
                        @endphp
                        @for($i = 1; $i <= 12; $i++)
                            @php
                                $citasMes = $datosGraficas['citas_ultimos_meses']->get($i, (object)['total' => 0]);
                                $altura = ($citasMes->total / $maxCitas) * 100;
                            @endphp
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-blue-200 rounded-t" style="height: {{ $altura }}%">
                                    <div class="bg-blue-600 h-full rounded-t"></div>
                                </div>
                                <span class="text-xs text-gray-600 mt-2">{{ $meses[$i-1] }}</span>
                                <span class="text-xs font-medium text-gray-900">{{ $citasMes->total }}</span>
                            </div>
                        @endfor
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="text-gray-500 mt-2">No hay datos de citas para mostrar</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Gráfica de tratamientos por tipo -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Tratamientos por Tipo</h3>
            </div>
            <div class="p-6">
                @if($paciente->id_paciente <= 0)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <p class="text-blue-600 font-medium mt-2">Sin tratamientos</p>
                        <p class="text-gray-500 mt-1">Los tratamientos aparecerán aquí cuando tu médico los registre</p>
                    </div>
                @elseif($datosGraficas['tratamientos_por_tipo']->count() > 0)
                    <div class="space-y-4">
                        @foreach($datosGraficas['tratamientos_por_tipo'] as $tratamiento)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">{{ $tratamiento->nombre }}</span>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($tratamiento->total / $datosGraficas['tratamientos_por_tipo']->sum('total')) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $tratamiento->total }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <p class="text-gray-500 mt-2">No hay tratamientos registrados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Información de historial clínico -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Estado del Historial Clínico</h3>
        </div>
        <div class="p-6">
            @if($paciente->id_paciente <= 0)
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Bienvenido al Sistema</p>
                        <p class="text-sm text-gray-600">Aún no te hemos generado un historial médico. Esto se creará automáticamente cuando tengas tu primera cita con un médico.</p>
                    </div>
                </div>
            @elseif($stats['historial_clinico'] > 0)
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Historial Clínico Actualizado</p>
                        <p class="text-sm text-gray-600">Tu médico ha registrado {{ $stats['historial_clinico'] }} entradas en tu historial clínico.</p>
                    </div>
                </div>
            @else
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Historial Clínico Pendiente</p>
                        <p class="text-sm text-gray-600">Tu historial clínico aún no ha sido creado por tu médico. Esto es normal si es tu primera visita.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Próximas citas y tratamientos activos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Próximas citas -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Próximas Citas</h3>
            </div>
            <div class="p-6">
                @if($paciente->id_paciente <= 0)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-blue-600 font-medium mt-2">Sin citas programadas</p>
                        <p class="text-gray-500 mt-1">Agenda tu primera cita para comenzar tu historial médico</p>
                    </div>
                @elseif($citasProximas->count() > 0)
                    <div class="space-y-4">
                        @foreach($citasProximas as $cita)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $cita->medico->usuario->nombre }}</p>
                                    <p class="text-sm text-gray-600">{{ $cita->fecha->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($cita->estado === 'confirmada') bg-green-100 text-green-800
                                    @elseif($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No tienes citas próximas</p>
                @endif
            </div>
        </div>

        <!-- Tratamientos activos -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Tratamientos Activos</h3>
            </div>
            <div class="p-6">
                @if($paciente->id_paciente <= 0)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <p class="text-blue-600 font-medium mt-2">Sin tratamientos activos</p>
                        <p class="text-gray-500 mt-1">Los tratamientos aparecerán aquí cuando tu médico los prescriba</p>
                    </div>
                @elseif($tratamientosActivos->count() > 0)
                    <div class="space-y-4">
                        @foreach($tratamientosActivos as $tratamiento)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="font-medium text-gray-900">{{ $tratamiento->nombre }}</p>
                                <p class="text-sm text-gray-600">{{ $tratamiento->descripcion }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Inicio: {{ $tratamiento->fecha_inicio->format('d/m/Y') }}
                                    @if($tratamiento->fecha_fin)
                                        | Fin: {{ $tratamiento->fecha_fin->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No tienes tratamientos activos</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="#" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Agendar Cita
            </a>
            <a href="#" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Ver Mis Citas
            </a>
            <a href="#" class="btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Historial Clínico
            </a>
        </div>
    </div>
</div>
@endsection 