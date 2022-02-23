<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitudes;
use App\Models\Estados;
use App\Models\User;
// use App\Models\Categorias;
use Illuminate\Support\Facades\DB;

class SolicitudesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function crearSolicitud(Request $request) {
        // $currentUser = auth()->user();

        $solicitud = new Solicitudes();

        $solicitud->id_usuario = $request->id_usuario;
        $solicitud->id_categoria = $request->id_categoria;
        $solicitud->id_estado = $request->id_estado;
        $solicitud->id_tecnico = $request->id_tecnico;
        $solicitud->descripcion = $request->descripcion;
        $solicitud->fecha_cita = $request->fecha_cita;

        $solicitud->save();

        return response()->json([
            'result' => true,
            'message' => 'Solicitud creada con éxito!'
        ]);
    }

    // Solicitud CurrentUser
    public function solicitudPorUsuario(Request $request) {
        // $currentUser = auth()->user();

        $idUser = $request->input('idUser');


        if($idUser) {
            $user = User::select('name', 'last_name')
                ->where('id', $idUser)->first();

            // Select Join con id para obtener los nombres de categorias y estados
            // V1
            // $solicitudes = DB::table('solicitudes')
            //     ->join('categorias', 'solicitudes.id_categoria', '=', 'categorias.id')
            //     ->join('estados', 'solicitudes.id_estado', '=', 'estados.id')
            //     // Crear una tabla empleados?
            //     // ->where('solicitudes.id_tecnico', '=', null)
            //     // ->join('usuarios', 'solicitudes.id', '=', 'solicitudes.id_tecnico')
            //     // ->select('name')
            //     ->join('users', 'solicitudes.id_tecnico', '=', 'users.id')
            //     ->select('*')
            //     ->where('solicitudes.id_usuario', $idUser)
            //     // ->where('solicitudes.id_tecnico', '<>', null)
            //     ->get();

            // V2
            $solicitudes = DB::table('solicitudes')
                ->join('categorias', 'solicitudes.id_categoria', '=', 'categorias.id')
                ->join('estados', 'solicitudes.id_estado', '=', 'estados.id')
                // Crear una tabla empleados?
                // ->where('solicitudes.id_tecnico', '=', null)
                // ->join('usuarios', 'solicitudes.id', '=', 'solicitudes.id_tecnico')
                // ->select('name')
                // ->where('solicitudes.id_tecnico', '<>', null)
                ->join('users', 'solicitudes.id_tecnico', '=', 'users.id')
                ->select(
                    'solicitudes.id_usuario',
                    'solicitudes.id_categoria',
                    'solicitudes.id_estado',
                    'solicitudes.id_tecnico',
                    'solicitudes.categoria_otro',
                    'solicitudes.descripcion',
                    'solicitudes.fecha_cita',
                    'solicitudes.imagen',
                    'categorias.nombre',
                    'estados.estado',
                    'users.name',
                    'users.last_name'
                )
                ->where('solicitudes.id_usuario', $idUser)
                // ->where([
                //     ['solicitudes.id_usuario', $idUser],
                //     // ['solicitudes.id_tecnico', '=', 'null']
                // ])
                // ->where('solicitudes.id_tecnico', '<>', null)
                ->get();

        }
        
        return response()->json([
            'result' => true,
            'solicitudes_count' => $solicitudes->count(),
            'user' => ([
                'id_user' => $idUser,
                'user' => $user,
            ]),
            'datos' => $solicitudes
        ]);
    }


    // Query Parameters - Query Strings
    public function obtenerSolicitudes(Request $request) {
        $user = null;
        $estado = null;
        $currentUser = auth()->user();

        $idUser = $request->input('idUser');
        $idEstado = $request->input('idEstado');

        // $query = $request->all();

        // $id = Input::get('idUser');
        // $query = Input::all();

        // $query = $request->all();

        // Treae todas las solicitudes
        // $query = Solicitudes::all();

        // if($idUser && $idEstado) {
        //     $solicitudes = Solicitudes::where(
        //         ['id_usuario', $idUser],
        //         ['id_estado', $idEstado],
        //     )->get();

        // Consultas dependiendo de los parametros encontrados
        if($idUser && $idEstado) {
            $estadoObj = Estados::select('estado')
                ->where('id', $idEstado)->first();
            $estado = $estadoObj->estado;
            $user = User::select('name', 'last_name')
                ->where('id', $idUser)->first();

            $solicitudes = Solicitudes::select('*')
            ->where('id_usuario', $idUser)
            ->where('id_estado', $idEstado)
                ->get();
        }
        else if($idUser) {
            $user = User::select('name', 'last_name')
                ->where('id', $idUser)->first();

            $solicitudes = Solicitudes::where('id_usuario', $idUser)->get();

        } else if($idEstado) {
            $estadoObj = Estados::select('estado')
                ->where('id', $idEstado)->first();
            $estado = $estadoObj->estado;
            $solicitudes = Solicitudes::where('id_estado', $idEstado)->get();

        } else {
            $solicitudes = Solicitudes::all();
        }


        // $solicitudes = Solicitudes::where()
        //     ->where('id_usuario', $idUser);
        // $solicitudes = DB::table('solicitudes')
        //     ->where('id_usuario', '=', $idUser)
        //     ->fisrt();

        // $solicitudes = Solicitudes::where('id_usuario', 3);

        // return $solicitudes->json();
        // Response solo ids
        // return response()->json([
        //     'result' => true,
        //     'solicitudes_count' => $solicitudes->count(),
        //     'id_user' => $idUser,
        //     'id_estado' => $idEstado,
        //     'datos' => $solicitudes
        // ]);

        // Response más completa
        return response()->json([
            'result' => true,
            'solicitudes_count' => $solicitudes->count(),
            'user' => ([
                'id_user' => $idUser,
                'user' => $user,
            ]),
            'estado' => ([
                'id_estado' => $idEstado,
                'estado' => $estado,
            ]),
            // 'id_estado' => $idEstado,
            'datos' => $solicitudes
        ]);
    }
}
