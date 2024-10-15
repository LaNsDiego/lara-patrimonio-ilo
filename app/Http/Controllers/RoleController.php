<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function list(Request $request)
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|string',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return response()->json(['message' => 'Rol creado exitosamente'], 201);
    }
}
