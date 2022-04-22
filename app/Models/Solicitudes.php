<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitudes extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_usuario', 'id_categoria', 'id_estado', 'id_tecnico', 'descripcion', 'fecha_cita', 'imagen', 'comentario', 'fecha_listo', 'fecha_real', 'active'];
}
