@extends('layouts.app')

@section('title', 'Gestión de Pacientes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Pacientes</h1>
        <a href="{{ route('medico.pacientes.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nuevo Paciente
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('medico.pacientes.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" 
                       class="form-input" placeholder="Nombre o correo...">
            </div>
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-secondary mr-2">Filtrar</button>
                <a href="{{ route('medico.pacientes.index') }}" class="btn-outline">Limpiar</a>
            </div>
        </form>
    </div>

    <!-- Tabla de pacientes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paciente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Información
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pacientes as $paciente)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-medium text-sm">
                                            {{ strtoupper(substr($paciente->nombre, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $paciente->nombre_completo }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $paciente->correo }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                                         @if($paciente->paciente && $paciente->paciente->fecha_nacimiento)
                                 <div class="text-sm text-gray-900">
                                     <div>Edad: {{ \Carbon\Carbon::parse($paciente->paciente->fecha_nacimiento)->age }} años</div>
                                     <div>Sexo: {{ ucfirst($paciente->paciente->sexo) }}</div>
                                 </div>
                             @else
                                 <span class="text-gray-500 text-sm">Sin información adicional</span>
                             @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($paciente->activo) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $paciente->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('medico.pacientes.show', $paciente->id_usuario) }}" 
                                   class="text-blue-600 hover:text-blue-900">Ver</a>
                                <a href="{{ route('medico.pacientes.edit', $paciente->id_usuario) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                <form method="POST" action="{{ route('medico.pacientes.toggle-status', $paciente->id_usuario) }}" 
                                      class="inline" onsubmit="return confirm('¿Estás seguro?')">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                        {{ $paciente->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('medico.pacientes.destroy', $paciente->id_usuario) }}" 
                                      class="inline" onsubmit="return confirm('¿Estás seguro de que quieres ELIMINAR PERMANENTEMENTE este paciente y todos sus datos? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron pacientes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($pacientes->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $pacientes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 