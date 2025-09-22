<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensajes';
    protected $primaryKey = 'id_mensaje';
    public $timestamps = false;

    protected $fillable = [
        'remitente_id',
        'destinatario_id',
        'mensaje',
        'fecha_envio'
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
    ];

    public function remitente()
    {
        return $this->belongsTo(User::class, 'remitente_id', 'id_usuario');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id', 'id_usuario');
    }
} 