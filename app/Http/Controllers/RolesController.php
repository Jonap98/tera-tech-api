<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function obtenerRoles() {
        $roles = Roles::select('id', 'nombre')->get();

        return response()->json([
            'result' => true,
            'datos' => $roles
        ]);
    }
}
