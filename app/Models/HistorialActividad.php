<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialActividad extends Model
{
    use HasFactory;

    protected $table = 'historial_actividades';
    protected $primaryKey = 'id_historial_actividad';
    public $timestamps = false;

    protected $fillable = [
        'id_historial',
        'id_actividad',
        'fecha_asignacion',
        'fecha_cumplimiento',
        'cumplida',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_cumplimiento' => 'date',
        'cumplida' => 'boolean',
    ];

    // Relaciones
    public function historialClinico()
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial', 'id_historial');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id_actividad');
    }
} 