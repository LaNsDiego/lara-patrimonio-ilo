<?php

namespace App\Http\Controllers;

use App\Models\RoadWay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoadWayController extends Controller
{
    public function list(){
        $road_ways = RoadWay::all();
        return response()->json($road_ways);
    }

    public function store(Request $request){
        RoadWay::create([
            'name' => $request->name,
            'geojson' => $request->geojson,
            'point_center' => $request->point_center,
            'point_a' => $request->point_a,
            'point_b' => $request->point_b,
        ]);

        return response()->json(['message' => 'Camino creado correctamente.']);
    }

    public function destroy(Request $request){

        $road_way = Validator::make($request->all(),[
            'road_way_id' => 'required|integer|exists:road_ways,id',
        ])->validate();
        $road_way = RoadWay::find($request->road_way_id);
        $road_way->delete();

        return response()->json(['message' => 'El camino fue eliminado exitosamente' ], 200);
    }
}
