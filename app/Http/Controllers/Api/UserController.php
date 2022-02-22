<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
 
class UserController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'id_rol' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = new User();
        $user->id_rol = $request->id_rol;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "result" => true,
            "message" => "Registro de usuario exitoso!"
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Se usa el first() porque solo se compara un resultado, no todos como con get()
        $user = User::where("email", "=", $request->email)->first();

        // Verifica si una variable está definida y no es null
        if(isset($user->id)) {
            if(Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    "result" => true,
                    "data" => ([
                        "access_token" => $token,
                    ]),
                    "message" => "Inicio de sesión exitoso!"
                ]);
            } else {
                return response()->json([
                    "result" => false,
                    "message" => "Contraseña incorrecta."
                ]);
            }
        } else {
            return response()->json([
                "result" => false,
                "message" => "Usuario no registrado."
            ]);
        }
    }

    public function userProfile() {
        return response()->json([
            "result" => true,
            "data" => auth()->user(),
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json([
            "result" => true,
            "message" => "Cierre de sesión exitoso."
        ]);
    }
}
