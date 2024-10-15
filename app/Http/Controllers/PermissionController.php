<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleHasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $roles_with_permissions = Role::with('permissions.permission.system_module')->get();
        return response()->json($roles_with_permissions);
    }


    public function permissions_by_role(Request $request)
    {
        $permissions = RoleHasPermission::with(['permission.system_module'])->where('role_id',$request->role_id)->get();
        return response()->json($permissions);
    }




    public function update(Request $request){
        $role_has_permission = RoleHasPermission::find($request->role_has_permission_id);
        $role_has_permission->update([
            'has_access' => (boolean)$request->has_access
        ]);

        $action = strtoupper($request->action);
        return response()->json(['message' => "Permiso $action actualizado."]);
    }

    public function update_permissions(Request $request){
        // return response()->json(['message' => "Error al actualizar los permisos."],500);
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->permission_ids as $permission) {
                    $roles_has_permission = RoleHasPermission::where('role_id', $request->role_id)
                        ->where('permission_id', $permission['permission_id'])
                        ->lockForUpdate()
                        ->first();

                    if ($roles_has_permission) {
                        $roles_has_permission->update([
                            'has_access' => $permission['has_access']
                        ]);
                    } else {
                        RoleHasPermission::create([
                            'role_id' => $request->role_id,
                            'permission_id' => $permission['permission_id'],
                            'has_access' => $permission['has_access']
                        ]);
                    }
                }
            });
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['message' => "Error al actualizar los permisos."],500);
        }

        return response()->json(['message' => "Permisos actualizados."]);
    }
}
