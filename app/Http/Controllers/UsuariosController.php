<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUsuarios() {
        return response()->json(auth()->user());
    }
    public function index() {
        $usuarios = User::all();

        return response()->json([
            'result' => true,
            'users_count' => $usuarios->count(),
            'datos' => $usuarios
        ]);
    }

    public function obtenerClientes() {
        $usuarios = DB::table('users')
            ->select(
                'id',
                'id_rol',
                'name',
                'last_name',
                'email'
            )
            ->where('users.id_rol', 3)
            ->get();

        return response()->json([
            'result' => true,
            'users_count' => $usuarios->count(),
            'datos' => $usuarios
        ]);
    }

    public function obtenerTecnicos(Request $request) {
        $idUser = null;

        $idUser = $request->input('idUser');

        if($idUser) {
            $usuarios = DB::table('users')
            ->join('especialidades', 'users.id_rol', '=', 'especialidades.id')
            ->select(
                'users.id',
                'users.id_rol',
                'users.id_especialidad',
                'users.name',
                'users.last_name',
                'users.email',
                'especialidades.especialidad'
            )
            ->where('users.id_rol', 2)
            ->where('users.id', $idUser)
            ->get();
        } else {
            $usuarios = DB::table('users')
                ->join('especialidades', 'users.id_rol', '=', 'especialidades.id')
                ->select(
                    'users.id',
                    'users.id_rol',
                    'users.id_especialidad',
                    'users.name',
                    'users.last_name',
                    'users.email',
                    'especialidades.especialidad'
                )
                ->where('users.id_rol', 2)
                ->get();
        }

        
        return response()->json([
            'result' => true,
            'users_count' => $usuarios->count(),
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
