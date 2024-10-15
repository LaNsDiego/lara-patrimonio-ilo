<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function list()
    {
        $locations = Location::with('sector','location_type')->orderBy('id','DESC')->get();
        return response()->json($locations);
    }

    //OBTIENE TODAS LAS UBICACIONES QUE SON TIPO CONTENEDOR Y QUE NO TIENEN ASIGNADO UNA UBICACIÓN
    public function byLocationType(Request $request)
    {
        switch ($request->location_type_name) {
            case LocationType::CONTAINER:
                $locationTypeIds = LocationType::where('name', LocationType::CONTAINER)->pluck('id');
                break;
            default:
                break;
        }
        
        $locations = Location::with('location_type')
        ->whereIn('location_type_id', $locationTypeIds)
        ->orderBy('id', 'DESC')
        ->get();
                    
        return response()->json($locations);
    }

        
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'sector_id' => 'required',
            'location_type_id' => 'required',
            'geojson' => 'required|min:5',
            'acronym' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $location = Location::create([
            'location_type_id' => $request->location_type_id,
            'name' => $request->name,
            'description' => $request->description,
            'sector_id' => $request->sector_id,
            'geojson' => $request->geojson,
            'acronym' => $request->acronym,
        ]);

        return response()->json(['message' => 'La ubicación fue registrada exitosamente','created' => $location ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer|exists:locations,id',
            'name' => 'required',
            'description' => 'required',
            'sector_id' => 'required',
            'location_type_id' => 'required',
            'geojson' => 'required|min:5',
            'acronym' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $locations = Location::find($request->id);
        $locations->location_type_id = (int)$request->location_type_id;
        $locations->name = $request->name;
        $locations->description = $request->description;
        $locations->sector_id = $request->sector_id;
        $locations->geojson = $request->geojson;
        $locations->acronym = $request->acronym;
        $locations->update();

        return response()->json(['message' => 'La ubicación fue actualizada exitosamente' ], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'location_id' => 'required|integer|exists:locations,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $location = Location::find($request->location_id);

        $location->delete();
        return response()->json(['message' => 'La ubicación fue eliminada exitosamente' ], 200);
    }
}
