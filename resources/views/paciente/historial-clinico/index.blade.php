@extends('layouts.app')

@section('title', 'Mi Historial Clínico')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mi Historial Clínico</h1>
        <a href="{{ route('paciente.dashboard') }}" class="btn-outline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al Dashboard
        </a>
    </div>

    @if($historiales->count() > 0)
        <!-- Lista de historiales clínicos -->
        <div class="space-y-6">
            @foreach($historiales as $historial)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Registro del {{ $historial->fecha_registro->format('d/m/Y') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $historial->fecha_registro->format('H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        @if($historial->diagnostico) bg-blue-100 text-blue-800
                        @elseif($historial->tratamiento) bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        @if($historial->diagnostico) Diagnóstico
                        @elseif($historial->tratamiento) Tratamiento
                        @else Registro General @endif
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($historial->diagnostico)
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Diagnóstico</h4>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 mb-2">
                                <span class="font-medium">Condición:</span> {{ $historial->diagnostico->condicion }}
                            </p>
                            @if($historial->diagnostico->descripcion)
                            <p class="text-sm text-gray-700 mb-2">
                                <span class="font-medium">Descripción:</span> {{ $historial->diagnostico->descripcion }}
                            </p>
                            @endif
                            @if($historial->diagnostico->medico)
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Diagnosticado por:</span> {{ $historial->diagnostico->medico->usuario->nombre_completo }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($historial->tratamiento)
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Tratamiento</h4>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 mb-2">
                                <span class="font-medium">Medicamento:</span> {{ $historial->tratamiento->medicamento }}
                            </p>
                            @if($historial->tratamiento->dosis)
                            <p class="text-sm text-gray-700 mb-2">
                                <span class="font-medium">Dosis:</span> {{ $historial->tratamiento->dosis }}
                            </p>
                            @endif
                            @if($historial->tratamiento->frecuencia)
                            <p class="text-sm text-gray-700 mb-2">
                                <span class="font-medium">Frecuencia:</span> {{ $historial->tratamiento->frecuencia }}
                            </p>
                            @endif
                            @if($historial->tratamiento->medico)
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Prescrito por:</span> {{ $historial->tratamiento->medico->usuario->nombre_completo }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                @if($historial->notas)
                <div class="mt-4">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Notas Adicionales</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $historial->notas }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Paginación -->
        @if($historiales->hasPages())
        <div class="mt-8">
            {{ $historiales->links() }}
        </div>
        @endif

    @else
        <!-- Estado vacío -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay historial clínico</h3>
            <p class="mt-1 text-sm text-gray-500">
                Aún no tienes registros en tu historial clínico. Consulta con tu médico para obtener más información.
            </p>
        </div>
    @endif
</div>
@endsection


