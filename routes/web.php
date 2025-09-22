<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\HistorialClinicoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicoPacienteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta de prueba para CSS
Route::get('/test', function () {
    return view('test');
})->name('test');

// Rutas de autenticación
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Recuperación de contraseña
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Rutas para manejo de sesión
Route::get('/check-session', function() {
    if (Auth::check()) {
        return response()->json(['active' => true]);
    }
    return response()->json(['active' => false], 401);
})->name('session.check');

Route::post('/extend-session', function() {
    if (Auth::check()) {
        // Actualizar la última actividad
        Session::put('last_activity', time());
        
        // Forzar el guardado de la sesión
        Session::save();
        
        return response()->json([
            'success' => true,
            'message' => 'Sesión extendida exitosamente',
            'timestamp' => time()
        ]);
    }
    return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
})->name('session.extend');

// Rutas protegidas
Route::middleware(['auth', 'session.timeout'])->group(function () {
    
    // Dashboard según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de administrador
    Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    // Rutas de médico
    Route::middleware(['auth', 'role:medico'])->prefix('medico')->name('medico.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'medico'])->name('dashboard');
        Route::resource('citas', CitaController::class)->middleware('cita.availability');
        Route::post('/citas/{id}/confirmar', [CitaController::class, 'confirmar'])->name('citas.confirmar');
        Route::post('/citas/{id}/completar', [CitaController::class, 'completar'])->name('citas.completar');
        
        Route::resource('tratamientos', TratamientoController::class);
        Route::resource('diagnosticos', DiagnosticoController::class);
        Route::resource('historial-clinico', HistorialClinicoController::class);
        Route::resource('pacientes', MedicoPacienteController::class);
        Route::post('/pacientes/{id}/toggle-status', [MedicoPacienteController::class, 'toggleStatus'])->name('pacientes.toggle-status');
    });
    
    // Rutas de paciente
    Route::middleware(['auth', 'role:paciente'])->prefix('paciente')->name('paciente.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'paciente'])->name('dashboard');
        Route::get('/citas', [CitaController::class, 'paciente'])->name('citas.index');
        Route::resource('citas', CitaController::class)->except(['index'])->middleware('cita.availability');
        Route::get('/historial-clinico', [HistorialClinicoController::class, 'paciente'])->name('historial-clinico.index');
        Route::get('/tratamientos', [TratamientoController::class, 'paciente'])->name('tratamientos.index');
    });
    
    // Rutas compartidas
    Route::resource('citas', CitaController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/citas/disponibilidad', [CitaController::class, 'disponibilidad'])->name('citas.disponibilidad');
});

// Rutas de API para AJAX
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/medicos', function () {
        return \App\Models\Medico::with('usuario')->get();
    })->name('medicos');
    
    Route::get('/especialidades', function () {
        return \App\Models\Medico::distinct()->pluck('especialidad');
    })->name('especialidades');
});
