@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Recuperar Contraseña
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña
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

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div>
                <label for="correo" class="sr-only">Correo electrónico</label>
                <input id="correo" name="correo" type="email" required 
                       class="form-input" 
                       placeholder="Correo electrónico"
                       value="{{ old('correo') }}">
            </div>

            <div>
                <button type="submit" class="btn-primary w-full">
                    Enviar Enlace de Recuperación
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

@if(session('redirect_to_reset'))
<script>
    // Redirigir automáticamente después de 5 segundos
    setTimeout(function() {
        window.location.href = '{{ route("password.reset") }}';
    }, 5000);
    
    // Mostrar contador regresivo
    let countdown = 5;
    const countdownElement = document.createElement('div');
    countdownElement.className = 'mt-4 text-center text-sm text-blue-600';
    countdownElement.innerHTML = `Redirigiendo en <span id="countdown">${countdown}</span> segundos...`;
    
    // Insertar después del mensaje de éxito
    const successMessage = document.querySelector('.bg-green-100');
    if (successMessage) {
        successMessage.parentNode.insertBefore(countdownElement, successMessage.nextSibling);
    }
    
    // Actualizar contador
    const countdownSpan = document.getElementById('countdown');
    const timer = setInterval(function() {
        countdown--;
        if (countdownSpan) {
            countdownSpan.textContent = countdown;
        }
        if (countdown <= 0) {
            clearInterval(timer);
        }
    }, 1000);
</script>
@endif
@endsection 