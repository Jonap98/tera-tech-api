<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('registro', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

// Rutas protegidas por token con Sanctum
Route::group(['middleware' => ["auth:sanctum"]], function() {
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('logout', [UserController::class, 'logout']);
});

// Obtener todos los usuarios
Route::get('/usuarios', 'App\Http\Controllers\UsuariosController@index');
