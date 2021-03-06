<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorias;

class CategoriasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function obtenerCategorias() {
        $categorias = Categorias::select('id', 'nombre')->get();

        return response()->json([
            'result' => true,
            'datos' => $categorias
        ]);
    }
}
