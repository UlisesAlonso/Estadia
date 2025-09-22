<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';
    protected $primaryKey = 'id_cita';
    public $timestamps = true;

    protected $fillable = [
        'id_paciente',
        'id_medico',
        'fecha',
        'motivo',
        'estado',
        'observaciones_clinicas',
        'especialidad_medica',
    ];

    protected $casts = [
        'fecha' => 'datetime',
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

    // Scopes para filtros
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopePorEspecialidad($query, $especialidad)
    {
        return $query->where('especialidad_medica', $especialidad);
    }

    public function scopeProximas($query, $dias = 7)
    {
        return $query->where('fecha', '>=', now())
                    ->where('fecha', '<=', now()->addDays($dias));
    }

    // Métodos de estado
    public function estaPendiente()
    {
        return $this->estado === 'pendiente';
    }

    public function estaConfirmada()
    {
        return $this->estado === 'confirmada';
    }

    public function estaCompletada()
    {
        return $this->estado === 'completada';
    }

    public function estaCancelada()
    {
        return $this->estado === 'cancelada';
    }

    public function puedeSerModificada()
    {
        return in_array($this->estado, ['pendiente', 'confirmada']);
    }

    public function puedeSerEliminada()
    {
        return in_array($this->estado, ['completada', 'cancelada']);
    }

    // Accessor para el estado formateado
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'confirmada' => 'Confirmada',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }
} 