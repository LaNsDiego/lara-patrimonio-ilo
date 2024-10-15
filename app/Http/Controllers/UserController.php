<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserEmployee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list()
    {
        $users = User::with(['role','employees'])->where('id','!=',1)->orderBy('id','DESC')->get();
        return response()->json($users, 200);
    }

    public function searchById($user_id)
    {
        Log::info($user_id);
        $user = User::with(['role','employees.job_title','employees.establishment'])->where('id',$user_id)->first();
        Log::info($user);
        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255',
            'email' => 'required|email|unique:users',
            'role_id' => 'required|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('123123123'),
            'role_id' => $request->role_id
        ]);

        $user->employees()->attach($request->employee_id);

        $user->load('employees');
        $user->load('role');
        return response()->json(['message' => 'El usuario fue registrado exitosamente','created' => $user ], Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {
        Validator::make($request->all(),[
            'id' => 'required',
            'name' => "required|string|unique:users,name,$request->id,id|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255",
            'email' => 'required|email|unique:users,email,' . $request->id . ',id',
            'role_id' => 'required|numeric',
        ])->validate();

        $user = User::find($request->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id
        ]);

        if($request->employee_id > 0){
            $relations = UserEmployee::where('user_id',$user->id)->get();
            if($relations->count() > 0){
                $user_employee = $relations->first();
                $user_employee->employee_id = $request->employee_id;
                $user_employee->save();
            }else{
                $user->employees()->attach($request->employee_id);
            }


        }

        return response()->json(['message' => "El usuario fue actualizado exitosamente."], 200);
    }

    public function destroy(Request $request){

        Validator::make($request->all(),[
            'user_id' => 'required|integer|exists:users,id',
        ])->validate();

        $user = User::find($request->user_id);

        $user->employees()->detach();
        $user->delete();
        return response()->json(['message' => 'El usuario fue eliminada exitosamente' ], 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer|exists:users,id',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ])->validate();

        $user = User::find($request->user_id);
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => "La contraseña fue actualizada exitosamente."], 201);
    }
}
