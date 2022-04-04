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

    // Este si jala, hay que probar otra implementación
    // public function crearSolicitud(Request $request) {
    //     // $currentUser = auth()->user();

    //     $solicitud = new Solicitudes();

    //     $solicitud->id_usuario = $request->id_usuario;
    //     $solicitud->id_categoria = $request->id_categoria;
    //     $solicitud->id_estado = $request->id_estado;
    //     $solicitud->id_tecnico = $request->id_tecnico;
    //     $solicitud->descripcion = $request->descripcion;
    //     $solicitud->fecha_cita = $request->fecha_cita;

    //     if($request->imagen) {
    //         $solicitud->imagen = $request->imagen->store('');
    //     }
    
    //     $request->validate([
    //         'imagen' => 'image|max:102400'
    //     ]);
    //     if($request->hasFile('image')) {

    //         Solicitudes::create([
    //             'id_usuario' => $solicitud->id_usuario,
    //             'id_categoria' => $solicitud->id_categoria,
    //             'id_estado' => $solicitud->id_estado,
    //             'id_tecnico' => $solicitud->id_tecnico,
    //             'descripcion' => $solicitud->descripcion,
    //             'fecha_cita' => $solicitud->fecha_cita,
    //             'imagen' => $solicitud->imagen
    //             // 'imagen' => $data['image']
    //             // 'imagen' => $solicitud->imagen
    //         ]);
    //     }
        
    //     $solicitud->save();

    //     return response()->json([
    //         'result' => true,
    //         'message' => 'Solicitud creada con éxito!'
    //     ]);
    // }

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
                    'categorias.nombre',
                    'estados.estado',
                    'users.name',
                    'users.last_name'
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
        $cliente = null;
        $estado = null;
        $tecnico = null;
        $currentUser = auth()->user();

        $idCliente = $request->input('idCliente');
        $idEstado = $request->input('idEstado');
        $idTecnico = $request->input('idTecnico');


        // Consultas dependiendo de los parametros encontrados
        if($idCliente && $idEstado && $idTecnico) {
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
            $solicitudes = Solicitudes::where('id_estado', $idEstado)->get();
        } 
        else {
            $solicitudes = Solicitudes::all();
        }

        return response()->json([
            'result' => true,
            'solicitudes_count' => $solicitudes->count(),
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
