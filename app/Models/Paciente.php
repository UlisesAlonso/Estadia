<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';
    protected $primaryKey = 'id_paciente';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'fecha_nacimiento',
        'sexo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'sexo' => 'string',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_paciente', 'id_paciente');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'id_paciente', 'id_paciente');
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'id_paciente', 'id_paciente');
    }

    public function historialClinico()
    {
        return $this->hasMany(HistorialClinico::class, 'id_paciente', 'id_paciente');
    }
} 