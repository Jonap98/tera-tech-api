<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SolicitudesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Autenticación Santcum
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('registro', [UserController::class, 'register']);
// Route::post('login', [UserController::class, 'login']);

// // Rutas protegidas por token con Sanctum
// Route::group(['middleware' => ["auth:sanctum"]], function() {
//     Route::get('user-profile', [UserController::class, 'userProfile']);
//     Route::get('logout', [UserController::class, 'logout']);
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 
// Autenticación JWT
Route::group([
    'middleware' => 'api',
    // 'prefix' => 'auth'
], function ($router) {

    Route::post('registro', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');

    // Obtener roles
    Route::get('roles', 'App\Http\Controllers\RolesController@obtenerRoles');
    // Obtener categorias
    Route::get('categorias', 'App\Http\Controllers\CategoriasController@obtenerCategorias');
    // Obtener estados
    Route::get('estados', 'App\Http\Controllers\EstadosController@obtenerEstados');
    // Crear solicitud
    Route::post('crear-solicitud', 'App\Http\Controllers\SolicitudesController@crearSolicitud');
    // Route::get('mis-solicitudes/{idUser?}', 'App\Http\Controllers\SolicitudesController@solicitudCurrentUser')->name('solicitudes.id_usuario');
    Route::get('solicitudes-usuario/{idUser?}', 'App\Http\Controllers\SolicitudesController@solicitudPorUsuario');
    Route::get('solicitudes/{id?}/{id_user?}/{id_estado?}/{id_tecnico?}', 'App\Http\Controllers\SolicitudesController@obtenerSolicitudesporFiltro');
    // Cerrar solicitud
    Route::put('cerrar-solicitud/{idSolicitud}', 'App\Http\Controllers\SolicitudesController@cerrarSolicitud');
    // Atender solicitud
    Route::post('atender-solicitud/{idSolicitud}', 'App\Http\Controllers\SolicitudesController@atenderSolicitud');
    // Asignar técnico a solicitud
    Route::post('asignar-tecnico/{idSolicitud}', 'App\Http\Controllers\SolicitudesController@asignarTecnico');
    // Route::get('solicitudes')
    // Obtener usuarios
    Route::get('usuarios', 'App\Http\Controllers\UsuariosController@index');
    Route::get('clientes', 'App\Http\Controllers\UsuariosController@obtenerClientes');
    Route::get('tecnicos/{idUser?}', 'App\Http\Controllers\UsuariosController@obtenerTecnicos');
    // Obtener fechas citas
    Route::post('citas', 'App\Http\Controllers\SolicitudesController@verificarDisponibilidad');
    
});

// Obtener todos los usuarios
// Route::get('/usuarios', 'App\Http\Controllers\UsuariosController@getUsuarios');
