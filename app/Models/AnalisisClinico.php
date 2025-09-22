<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisisClinico extends Model
{
    use HasFactory;

    protected $table = 'analisis_clinicos';
    protected $primaryKey = 'id_analisis';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
        'id_medico',
        'tipo_analisis',
        'resultado',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'id_medico', 'id_medico');
    }
} 