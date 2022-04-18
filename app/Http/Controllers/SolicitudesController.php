<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitudes;
use App\Models\Estados;
use App\Models\User;
// use App\Models\Categorias;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        if($request->imagen) {
            $solicitud->imagen = $request->imagen->store('images');
        }
    
        $request->validate([
            'imagen' => 'image|max:102400'
        ]);
        if($request->hasFile('image')) {
            $path = $request->file('images')->store('images');

            Solicitudes::create([
                'id_usuario' => $solicitud->id_usuario,
                'id_categoria' => $solicitud->id_categoria,
                'id_estado' => $solicitud->id_estado,
                'id_tecnico' => $solicitud->id_tecnico,
                'descripcion' => $solicitud->descripcion,
                'fecha_cita' => $solicitud->fecha_cita,
                'imagen' => $path
                // 'imagen' => $solicitud->imagen
            ]);
        }
        
        $solicitud->save();

        return response()->json([
            'result' => true,
            'message' => 'Solicitud creada con éxito!'
        ]);
    }

    public function verificarDisponibilidad(Request $request) {

        $fecha = $request->input('fecha_cita');

        if($fecha) {
            $solicitud = Solicitudes::select('id')
                ->where('fecha_cita', $fecha)
                ->get();
        }

        return response()->json([
            'result' => true,
            'solicitudes_count' => $solicitud->count(),
            'solicitud' => $solicitud,
        ]);
        

    }
    
    // Solicitud CurrentUser, se usa por el usuario para buscar sus propias
    // solicitudes
    public function solicitudPorUsuario(Request $request) {
        // $currentUser = auth()->user();

        $idUser = $request->input('idUser');


        if($idUser) {
            $user = User::select('name', 'last_name')
                ->where('id', $idUser)->first();

            // V2
            $solicitudes = DB::table('solicitudes')
                ->join('categorias', 'solicitudes.id_categoria', '=', 'categorias.id')
                ->join('estados', 'solicitudes.id_estado', '=', 'estados.id')
                ->join('users', 'solicitudes.id_usuario', '=', 'users.id')
                ->select(
                    'solicitudes.id',
                    'solicitudes.id_usuario',
                    'solicitudes.id_categoria',
                    'solicitudes.id_estado',
                    'solicitudes.id_tecnico',
                    'solicitudes.descripcion',
                    'solicitudes.fecha_cita',
                    'solicitudes.imagen',
                    'solicitudes.comentario',
                    'solicitudes.fecha_listo',
                    'solicitudes.fecha_real',
                    'categorias.nombre as nombre_categoria',
                    'estados.estado as nombre_estado',
                    'users.name as nombre_usuario',
                    'users.last_name as apellido'
                )
                ->where('solicitudes.id_usuario', $idUser)
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

    // Se usa por admin o técnico. Permite filtrar por técnico y cliente
    public function obtenerSolicitudesPorFiltro(Request $request) {
        $id = null;
        $cliente = null;
        $estado = null;
        $tecnico = null;
        $currentUser = auth()->user();

        $id = $request->input('id');
        $idCliente = $request->input('idCliente');
        $idEstado = $request->input('idEstado');
        $idTecnico = $request->input('idTecnico');


        // Consultas dependiendo de los parametros encontrados
        if($id && $idEstado) {
            $estadoObj = Estados::select('estado')
                ->where('id', $idEstado)->first();
            $estado = $estadoObj->estado;
            // $solicitudes = Solicitudes::where('id_estado', $idEstado)->get();
            $solicitudes = DB::table('solicitudes')
                ->join('categorias', 'solicitudes.id_categoria', '=', 'categorias.id')
                ->join('estados', 'solicitudes.id_estado', '=', 'estados.id')
                ->join('users', 'solicitudes.id_usuario', '=', 'users.id')
                ->select(
                    'solicitudes.id',
                    'solicitudes.id_usuario',
                    'solicitudes.id_categoria',
                    'solicitudes.id_estado',
                    'solicitudes.id_tecnico',
                    'solicitudes.descripcion',
                    'solicitudes.fecha_cita',
                    'solicitudes.imagen',
                    'solicitudes.comentario',
                    'solicitudes.fecha_listo',
                    'solicitudes.fecha_real',
                    'categorias.nombre as nombre_categoria',
                    'estados.estado as nombre_estado',
                    'users.name as nombre_usuario',
                    'users.last_name as apellido'
                )
                // ->where('solicitudes.id_usuario', $idUser)
                ->where('solicitudes.id', $id)
                ->get();
        } else if($idEstado && $idTecnico) {
            $estadoObj = Estados::select('estado')
                ->where('id', $idEstado)->first();
            $estado = $estadoObj->estado;
            // $solicitudes = Solicitudes::where('id_estado', $idEstado)->get();
            $solicitudes = DB::table('solicitudes')
                ->join('categorias', 'solicitudes.id_categoria', '=', 'categorias.id')
                ->join('estados', 'solicitudes.id_estado', '=', 'estados.id')
                ->join('users', 'solicitudes.id_usuario', '=', 'users.id')
                ->select(
                    'solicitudes.id',
                    'solicitudes.id_usuario',
                    'solicitudes.id_categoria',
                    'solicitudes.id_estado',
                    'solicitudes.id_tecnico',
                    'solicitudes.descripcion',
                    'solicitudes.fecha_cita',
                    'solicitudes.imagen',
                    'solicitudes.comentario',
                    'solicitudes.fecha_listo',
                    'solicitudes.fecha_real',
                    'categorias.nombre as nombre_categoria',
                    'estados.estado as nombre_estado',
                    'users.name as nombre_usuario',
                    'users.last_name as apellido',
                    )
                    ->where('solicitudes.id_tecnico', $idTecnico)
                    ->where('solicitudes.id_estado', $idEstado)
                    ->get();
        } else if($idCliente && $idEstado && $idTecnico) {
            $estadoObj = Estados::select('estado')
                ->where('id', $idEstado)->first();
            $estado = $estadoObj->estado;

            $cliente = User::select('name', 'last_name')
                ->where('id', $idCliente)->first();
            
            $tecnico = User::select('name', 'last_name')
                ->where('id', $idTecnico)->first();

            $solicitudes = Solicitudes::select('*')
            ->where('id_usuario', $idCliente)
            ->where('id_estado', $idEstado)
            ->where('id_tecnico', $idTecnico)
                ->get();
        } else if ($idCliente && $idTecnico) {
            $cliente = User::select('name', 'last_name')
                ->where('id', $idCliente)->first();
            
            $tecnico = User::select('name', 'last_name')
                ->where('id', $idTecnico)->first();

            $solicitudes = Solicitudes::select('*')
            ->where('id_usuario', $idCliente)
            ->where('id_tecnico', $idTecnico)
                ->get();
        }
        else if($idCliente) {
            $cliente = User::select('name', 'last_name')
                ->where('id', $idCliente)->first();

            $solicitudes = Solicitudes::where('id_usuario', $idCliente)->get();

        } else if($idTecnico) {
            $tecnico = User::select('name', 'last_name')
                ->where('id', $idTecnico)->first();
            
            $solicitudes = Solicitudes::where('id_tecnico', $idTecnico)->get();
        } else if($idEstado) {
            $estadoObj = Estados::select('estado')
                ->where('id', $idEstado)->first();
            $estado = $estadoObj->estado;
            // $solicitudes = Solicitudes::where('id_estado', $idEstado)->get();
            $solicitudes = DB::table('solicitudes')
                ->join('categorias', 'solicitudes.id_categoria', '=', 'categorias.id')
                ->join('estados', 'solicitudes.id_estado', '=', 'estados.id')
                ->join('users', 'solicitudes.id_usuario', '=', 'users.id')
                ->select(
                    'solicitudes.id',
                    'solicitudes.id_usuario',
                    'solicitudes.id_categoria',
                    'solicitudes.id_estado',
                    'solicitudes.id_tecnico',
                    'solicitudes.descripcion',
                    'solicitudes.fecha_cita',
                    'solicitudes.imagen',
                    'solicitudes.comentario',
                    'solicitudes.fecha_listo',
                    'solicitudes.fecha_real',
                    'categorias.nombre as nombre_categoria',
                    'estados.estado as nombre_estado',
                    'users.name as nombre_usuario',
                    'users.last_name as apellido',
                    )
                    ->where('id_estado', $idEstado)
                    ->get();
        } else {
            $solicitudes = Solicitudes::all();
        }

        return response()->json([
            'result' => true,
            'solicitudes_count' => $solicitudes->count(),
            'user' => ([
                'id_user' => null,
                'user' => ([
                    'name' => null,
                    'last_name' => null,
                ]),
            ]),
            'datos' => $solicitudes,
        ]);
    }


    // Query Parameters - Query Strings
    public function obtenerSolicitudes(Request $request) {
        $user = null;
        $estado = null;
        $currentUser = auth()->user();

        $idUser = $request->input('idUser');
        $idEstado = $request->input('idEstado');

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

    public function asignarTecnico(Request $request, $idSolicitud) {

        $solicitud = Solicitudes::find($idSolicitud);
        
        $solicitud->id_tecnico = $request->id_tecnico;

        $solicitud->update([
            'id_tecnico' => $solicitud->id_tecnico,
            'id_estado' => 3,
        ]);

        $solicitud->save();

        return response()->json([
            'result' => true,
            'message' => 'Técnico asignado con éxito!'
        ]);
    }

    public function atenderSolicitud(Request $request, $idSolicitud) {

        $solicitud = Solicitudes::find($idSolicitud);
        
        if($request->detalle == 1) {
            $solicitud->id_estado = 5;
        } else {
            $solicitud->id_estado = 4;
        }
        
        $solicitud->comentario = $request->comentario;
        $solicitud->fecha_listo = $request->fecha_listo;

        $solicitud->update([
            'id_estado' => $solicitud->id_estado,
            'comentario' => $solicitud->comentario,
            'fecha_listo' => $solicitud->fecha_listo,

        ]);

        // Solicitudes::create([
        //     'id_usuario' => $solicitud->id_usuario,
        //     'id_categoria' => $solicitud->id_categoria,
        //     'id_estado' => $solicitud->id_estado,
        //     'id_tecnico' => $solicitud->id_tecnico,
        //     'descripcion' => $solicitud->descripcion,
        //     'fecha_cita' => $solicitud->fecha_cita,
        //     'imagen' => $path
        //     // 'imagen' => $solicitud->imagen
        // ]);


        $solicitud->save();

        return response()->json([
            'result' => true,
            'message' => 'Solicitud actualizada con éxito!'
        ]);
    }

    public function cerrarSolicitud($idSolicitud) {

        $solicitud = Solicitudes::find($idSolicitud);
        $solicitud->id_estado = 6;

        $solicitud->save();

        return response()->json([
            'result' => true,
            'message' => 'Solicitud cerrada con éxito!'
        ]);
    }
}
