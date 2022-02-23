<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estados;

class EstadosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function obtenerEstados() {
        $estados = Estados::select('id', 'estado')->get();

        return response()->json([
            'result' => true,
            'datos' => $estados
        ]);
    }
}
