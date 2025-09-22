<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenRecuperacion extends Model
{
    protected $table = 'tokens_recuperacion';
    protected $primaryKey = 'id_token';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'token',
        'expiracion',
        'usado'
    ];

    protected $casts = [
        'expiracion' => 'datetime',
        'usado' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
} 