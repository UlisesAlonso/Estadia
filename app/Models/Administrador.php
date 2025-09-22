<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'Administrador';
    protected $primaryKey = 'idAdministrador';
    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'contrasena',
    ];
} 