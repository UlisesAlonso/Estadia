<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    use HasFactory;

    protected $table = 'historial_clinico';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
        'id_diagnostico',
        'id_tratamiento',
        'observaciones',
        'fecha_registro',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    // La tabla historial_clinico no tiene id_medico, se relaciona a través de diagnósticos y tratamientos
    public function medico()
    {
        // Relación a través del diagnóstico
        if ($this->id_diagnostico) {
            return $this->belongsTo(Medico::class, 'id_medico', 'id_medico')->through(Diagnostico::class);
        }
        // Relación a través del tratamiento
        if ($this->id_tratamiento) {
            return $this->belongsTo(Medico::class, 'id_medico', 'id_medico')->through(Tratamiento::class);
        }
        return null;
    }

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class, 'id_diagnostico', 'id_diagnostico');
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'id_tratamiento', 'id_tratamiento');
    }
} 