<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Cita;
use App\Models\HistorialClinico;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apPaterno',
        'apMaterno',
        'correo',
        'contrasena',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'id_usuario', 'id_usuario');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'id_usuario', 'id_usuario');
    }

    public function mensajesEnviados()
    {
        return $this->hasMany(Mensaje::class, 'remitente_id', 'id_usuario');
    }

    public function mensajesRecibidos()
    {
        return $this->hasMany(Mensaje::class, 'destinatario_id', 'id_usuario');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'id_usuario', 'id_usuario');
    }

    public function tokensRecuperacion()
    {
        return $this->hasMany(TokenRecuperacion::class, 'id_usuario', 'id_usuario');
    }

    // Relaciones para citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_paciente', 'id_usuario');
    }

    public function citasMedico()
    {
        return $this->hasMany(Cita::class, 'id_medico', 'id_usuario');
    }

    // Relaciones para historial clínico
    public function historialClinico()
    {
        return $this->hasMany(HistorialClinico::class, 'id_paciente', 'id_usuario');
    }

    // Relaciones para tratamientos
    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'id_paciente', 'id_usuario');
    }

    // No existe historialClinicoMedico porque la tabla historial_clinico no tiene id_medico

    // Métodos helper para roles
    public function isAdmin()
    {
        return $this->rol === 'administrador';
    }

    public function isMedico()
    {
        return $this->rol === 'medico';
    }

    public function isPaciente()
    {
        return $this->rol === 'paciente';
    }

    // Método helper para obtener el nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apPaterno . ' ' . $this->apMaterno);
    }
}
