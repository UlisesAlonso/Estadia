@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Detalles del Usuario</h1>
                         <div class="flex space-x-2">
                 <a href="{{ route('admin.users.edit', $user->id_usuario) }}" class="btn-secondary">
                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                     </svg>
                     Editar
                 </a>
                 <form method="POST" action="{{ route('admin.users.destroy', $user->id_usuario) }}" 
                       class="inline" onsubmit="return confirm('¿Estás seguro de que quieres ELIMINAR PERMANENTEMENTE este usuario y todos sus datos? Esta acción no se puede deshacer.')">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn-danger">
                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                         </svg>
                         Eliminar
                     </button>
                 </form>
                 <a href="{{ route('admin.users.index') }}" class="btn-outline">
                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                     </svg>
                     Volver
                 </a>
             </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Información básica -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-medium text-xl">
                                {{ strtoupper(substr($user->nombre, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->nombre_completo }}</h2>
                        <p class="text-gray-600">{{ $user->correo }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                @if($user->rol === 'administrador') bg-purple-100 text-purple-800
                                @elseif($user->rol === 'medico') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($user->rol) }}
                            </span>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                @if($user->activo) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $user->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles específicos -->
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información General</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd class="text-sm text-gray-900">{{ $user->nombre_completo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                                <dd class="text-sm text-gray-900">{{ $user->correo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rol</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($user->rol) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="text-sm text-gray-900">{{ $user->activo ? 'Activo' : 'Inactivo' }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($user->rol === 'medico' && $user->medico)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Médico</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Especialidad</dt>
                                <dd class="text-sm text-gray-900">{{ $user->medico->especialidad }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cédula Profesional</dt>
                                <dd class="text-sm text-gray-900">{{ $user->medico->cedula_profesional }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif

                    @if($user->rol === 'paciente' && $user->paciente)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Paciente</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                                <dd class="text-sm text-gray-900">{{ $user->paciente->fecha_nacimiento->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($user->paciente->sexo) }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif
                </div>

                <!-- Estadísticas -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($user->rol === 'medico')
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-blue-600">Citas Asignadas</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $user->citasMedico ? $user->citasMedico->count() : 0 }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($user->rol === 'paciente')
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-green-600">Historial Clínico</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $user->historialClinico ? $user->historialClinico->count() : 0 }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 