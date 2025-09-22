@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <!-- Logo placeholder - Reemplazar src con tu imagen -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Cardio Vida Logo" 
                     class="h-16 w-auto"
                     onerror="this.style.display='none'">
            </div>
            
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Cardio Vida
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Inicia sesión en tu cuenta
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

        @if (session('timeout'))
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Sesión Expirada
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>{{ session('timeout') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="correo" class="sr-only">Correo electrónico</label>
                    <input id="correo" name="correo" type="email" required 
                           class="form-input rounded-t-md" 
                           placeholder="Correo electrónico"
                           value="{{ old('correo') }}">
                </div>
                <div>
                    <label for="contrasena" class="sr-only">Contraseña</label>
                    <input id="contrasena" name="contrasena" type="password" required 
                           class="form-input rounded-b-md" 
                           placeholder="Contraseña">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Recordarme
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 