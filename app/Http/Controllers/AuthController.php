<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TokenRecuperacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        $user = User::where('correo', $request->correo)
                   ->where('activo', 1)
                   ->first();

        if ($user && Hash::check($request->contrasena, $user->contrasena)) {
            Auth::login($user);
            
            // Actualizar último acceso
            $user->update(['ultimo_acceso' => now()]);

            // Redirigir según el rol
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isMedico()) {
                return redirect()->route('medico.dashboard');
            } else {
                return redirect()->route('paciente.dashboard');
            }
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|exists:usuarios,correo'
        ]);

        $user = User::where('correo', $request->correo)->first();
        
        if ($user) {
            // Generar código de 6 dígitos
            $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Eliminar tokens anteriores del usuario
            TokenRecuperacion::where('id_usuario', $user->id_usuario)->delete();
            
            // Guardar código en la base de datos
            TokenRecuperacion::create([
                'id_usuario' => $user->id_usuario,
                'token' => $codigo,
                'expiracion' => now()->addMinutes(5), // 5 minutos de expiración
                'usado' => 0
            ]);

            // Enviar email con el código
            Mail::to($user->correo)->send(new ResetPasswordMail($codigo, $user));

            return back()->with('success', 'Se ha enviado un código de verificación de 6 dígitos a tu correo electrónico. Serás redirigido automáticamente en 5 segundos.')
                ->with('redirect_to_reset', true);
        }

        return back()->withErrors([
            'correo' => 'No encontramos una cuenta con esa dirección de correo electrónico.',
        ]);
    }

    public function showResetPassword()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|size:6',
            'contrasena' => 'required|min:8|confirmed',
        ]);

        $tokenRecord = TokenRecuperacion::where('token', $request->codigo)
                                       ->where('expiracion', '>', now())
                                       ->where('usado', 0)
                                       ->first();

        if (!$tokenRecord) {
            return back()->withErrors([
                'codigo' => 'El código de verificación es inválido, ha expirado o ya fue utilizado.',
            ]);
        }

        // Actualizar contraseña
        $user = User::find($tokenRecord->id_usuario);
        $user->update([
            'contrasena' => Hash::make($request->contrasena)
        ]);

        // Marcar token como usado
        $tokenRecord->update(['usado' => 1]);

        return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida exitosamente.');
    }
} 