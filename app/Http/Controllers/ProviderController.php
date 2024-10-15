<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function list()
    {
        $provider = Provider::all();
        return response()->json($provider);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' => 'required',
            'name' => 'required',
            'document_type' => 'required | string',
            'document_number' => 'required | string',
            'phone_number' => 'required | string',
            'email' => 'required | email',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $provider = Provider::create([
            'type' => $request->type,
            'name' => $request->name,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'El proveedor fue registrado exitosamente','created' => $provider ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'type' => 'required',
            'name' => 'required',
            'document_type' => 'required | string',
            'document_number' => 'required | string',
            'phone_number' => 'required | string',
            'email' => 'required | email',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $provider = Provider::find($request->id);
        $provider->update([
            'type' => $request->type,
            'name' => $request->name,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'El proveedor fue actualizado exitosamente' ], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'provider_id' => 'required|integer|exists:providers,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $provider = Provider::find($request->provider_id);

        $provider->delete();
        return response()->json(['message' => 'El proveedor fue eliminado exitosamente' ], 200);
    }
}
