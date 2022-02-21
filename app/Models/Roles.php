<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'id_usuario', 'id_categoria', 'id_estado', 'id_tecnico', 'categoria_otro', 'descripcion', 'fecha_de_cita', 'imagen', 'active'];
}
