<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_rol' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'Ya existe una cuenta registrada con ese email.',
            'password.confirmed' => 'Las contraseñas deben ser iguales.',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validate(),
            ['password' => bcrypt($request->password)]
        ));

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        return $this->respondWithToken('Usuario registrado correctamente!', $token);
    }
    
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Favor de revisar sus credenciales'], 401);
        }

        return $this->respondWithToken('Inicio de sesión exitoso!', $token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(String $text, $token)
    {
        return response()->json([
            'result' => true,
            'data' => ([
                'current_user' => auth()->user(),
                'access_token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60
            ]),
            'message' => $text,
        ]);
    }
}
