<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;

class UsuariosController extends Controller
{
    public function index() {
        $usuarios = Usuarios::all();

        return response()->json([
            'result' => true,
            'datos' => $usuarios
        ]);
        
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        $usuarios = new Usuarios();

        $usuarios->id_rol = $request->id_rol;
        $usuarios->nombre = $request->nombre;
        $usuarios->apellido = $request->apellido;
        $usuarios->correo = $request->correo;
        $usuarios->password = $request->password;
        
        $usuarios->save();
    }

    public function show(Usuarios $usuarios) {
        return response()->json([
            'result' => true,
            'datos' => $usuarios
        ]);
    }

    public function update(Request $request) {
        $usuarios = Usuarios::findOrFail($request->id);

        $usuarios->id_rol = $request->id_rol;
        $usuarios->nombre = $request->nombre;
        $usuarios->apellido = $request->apellido;
        $usuarios->correo = $request->correo;
        $usuarios->password = $request->password;

        $usuarios->save();

        return $usuarios;
    }

    public function destroy(Request $request) {
        $usuarios = Usuarios::destroy($request->id);
        
        return $usuarios;
    }
}
