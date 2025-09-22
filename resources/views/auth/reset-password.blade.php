@extends('layouts.app')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Restablecer Contraseña
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ingresa tu nueva contraseña
            </p>
        </div>
        
        @if ($errors->any())
            <div class="alert-error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <div>
                <label for="codigo" class="sr-only">Código de verificación</label>
                <input id="codigo" name="codigo" type="text" required 
                       class="form-input" 
                       placeholder="Código de verificación (6 dígitos)"
                       maxlength="6"
                       pattern="[0-9]{6}">
            </div>
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="contrasena" class="sr-only">Nueva contraseña</label>
                    <input id="contrasena" name="contrasena" type="password" required 
                           class="form-input rounded-t-md" 
                           placeholder="Nueva contraseña">
                </div>
                <div>
                    <label for="contrasena_confirmation" class="sr-only">Confirmar contraseña</label>
                    <input id="contrasena_confirmation" name="contrasena_confirmation" type="password" required 
                           class="form-input rounded-b-md" 
                           placeholder="Confirmar contraseña">
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full">
                    Restablecer Contraseña
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    ← Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 