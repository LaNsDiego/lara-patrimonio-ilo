<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EstablishmentController extends Controller
{
    public function list()
    {
        $establishment = Establishment::with('childrenRecursive')->with(['establishment_type','parent','location','responsible'])->withCount('employees')->orderBy('id')->get();
        return response()->json($establishment);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'parent_id' => 'nullable',
            'code' => 'required|string|unique:establishments,code|max:255',
            'acronym' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'establishment_type_id' => 'required|exists:establishment_types,id',
            'responsible_id' => 'required|exists:employees,id',
            'location_id' => 'required|exists:locations,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $establishment = Establishment::create([
            'parent_id' => ($request->parent_id)?(int)$request->parent_id:null,
            'code' => $request->code,
            'acronym' => $request->acronym,
            'name' => $request->name,
            'establishment_type_id' => (int)$request->establishment_type_id,
            'responsible_id' => (int)$request->responsible_id,
            'location_id' => (int)$request->location_id
        ]);

        return response()->json(['message' => 'El establecimiento fue registrado exitosamente','created' => $establishment ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'parent_id' => 'nullable',
            'code' => 'required|string|unique:establishments,code,' . $request->id . ',id',
            'acronym' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'establishment_type_id' => 'required|exists:establishment_types,id',
            'responsible_id' => 'required|exists:employees,id',
            'location_id' => 'required|exists:locations,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $establishment = Establishment::find($request->id);
        $establishment->update([
            'id' => $request->id,
            'parent_id' => ($request->parent_id)?(int)$request->parent_id:null,
            'code' => $request->code,
            'acronym' => $request->acronym,
            'name' => $request->name,
            'establishment_type_id' => (int)$request->establishment_type_id,
            'responsible_id' => (int)$request->responsible_id,
            'location_id' => (int)$request->location_id
        ]);

        return response()->json(['message' => 'El establecimiento fue actualizado exitosamente' ], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'establishment_id' => 'required|integer|exists:establishments,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $establishment = Establishment::find($request->establishment_id);
        $establishment->delete();

        return response()->json(['message' => 'El establecimiento fue eliminado exitosamente' ], 200);
    }
}
