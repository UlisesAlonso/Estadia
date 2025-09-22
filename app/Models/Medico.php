<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medicos';
    protected $primaryKey = 'id_medico';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'especialidad',
        'cedula_profesional',
        'fecha_nacimiento',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_medico', 'id_medico');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'id_medico', 'id_medico');
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'id_medico', 'id_medico');
    }

    // La tabla historial_clinico no tiene id_medico, se relaciona a través de diagnósticos y tratamientos
    public function historialClinico()
    {
        // Relación a través de diagnósticos
        return $this->hasManyThrough(HistorialClinico::class, Diagnostico::class, 'id_medico', 'id_diagnostico', 'id_medico', 'id_diagnostico');
    }
} 