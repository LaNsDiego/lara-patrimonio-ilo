<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectorController extends Controller
{
    public function list(){
        return response()->json(Sector::with(['sector_type'])->orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'geojson' => 'required',
            'sector_type_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $sector = Sector::create([
            'name' => $request->name,
            'description' => $request->description,
            'geojson' => $request->geojson,
            'sector_type_id' => $request->sector_type_id,
            'parent_sector_id' => $request->parent_sector_id > 0  ? $request->parent_sector_id : null
        ]);

        return response()->json(['message' => 'El sector fue registrado exitosamente','created' => $sector ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $sector = Sector::find($request->id);
        $sector->name = $request->name;
        $sector->description = $request->description;
        $sector->save();

        return response()->json(['message' => 'El sector fue actualizado exitosamente' ], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'sector_id' => 'required|integer|exists:sectors,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $sector = Sector::find($request->sector_id);

        $sector->delete();
        return response()->json(['message' => 'El sector fue eliminada exitosamente' ], 200);
    }

}
