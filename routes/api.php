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
    'prefix' => 'auth'
], function ($router) {

    Route::post('registro', 'App\Http\Controllers\AuthController@register');
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');

});

// Obtener todos los usuarios
Route::get('/usuarios', 'App\Http\Controllers\UsuariosController@index');
