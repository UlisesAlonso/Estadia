@extends('layouts.app')

@section('title', 'Prueba CSS')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Prueba de CSS</h1>
        <p class="text-gray-600">Esta es una página de prueba para verificar que Tailwind CSS funciona correctamente.</p>
    </div>

    <!-- Botones de prueba -->
    <div class="space-y-4 mb-8">
        <button class="btn-primary">Botón Primario</button>
        <button class="btn-secondary">Botón Secundario</button>
        <button class="btn-danger">Botón Peligro</button>
    </div>

    <!-- Tarjeta de prueba -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Tarjeta de Prueba</h3>
        </div>
        <div class="card-body">
            <p class="text-gray-600">Esta es una tarjeta de prueba para verificar que los estilos funcionan correctamente.</p>
        </div>
    </div>

    <!-- Alertas de prueba -->
    <div class="space-y-4 mt-8">
        <div class="alert-success">
            <strong>Éxito!</strong> Esta es una alerta de éxito.
        </div>
        <div class="alert-error">
            <strong>Error!</strong> Esta es una alerta de error.
        </div>
        <div class="alert-warning">
            <strong>Advertencia!</strong> Esta es una alerta de advertencia.
        </div>
        <div class="alert-info">
            <strong>Información!</strong> Esta es una alerta de información.
        </div>
    </div>

    <!-- Formulario de prueba -->
    <div class="card mt-8">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Formulario de Prueba</h3>
        </div>
        <div class="card-body">
            <form class="space-y-4">
                <div>
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" id="name" class="form-input" placeholder="Ingresa tu nombre">
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-input" placeholder="Ingresa tu email">
                </div>
                <div>
                    <button type="submit" class="btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 