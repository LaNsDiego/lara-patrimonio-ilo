<?php

namespace App\Http\Controllers;

use App\Models\ResourceMapAssignment;
use App\Models\RouteMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RouteMapController extends Controller
{

    public function list(){
        $routeMaps = RouteMap::with(['road_ways.road_way','sectors.sector','locations.location'])->get();
        return response()->json($routeMaps);
    }

    public function store(Request $request){

        // Log::info($request->all());
        $route_map = RouteMap::create([
            'name' => $request->name,
            'point_center' => json_encode($request->point_center),
        ]);

        foreach ($request->resources as $resource){
            // Log::info("RESOURCE => " . $resource['value']);
            if($resource['type'] == ResourceMapAssignment::MAP_RESOURCE_SECTOR){
                ResourceMapAssignment::create([
                    'route_map_id' => $route_map->id,
                    'type_map_resource' => ResourceMapAssignment::MAP_RESOURCE_SECTOR,
                    'resource_id' => $resource['value'],
                ]);
            }
            if($resource['type'] == ResourceMapAssignment::MAP_RESOURCE_LOCATION){
                ResourceMapAssignment::create([
                    'route_map_id' => $route_map->id,
                    'type_map_resource' => ResourceMapAssignment::MAP_RESOURCE_LOCATION,
                    'resource_id' => $resource['value'],
                ]);
            }
            if($resource['type'] == ResourceMapAssignment::MAP_RESOURCE_ROAD){
                ResourceMapAssignment::create([
                    'route_map_id' => $route_map->id,
                    'type_map_resource' => ResourceMapAssignment::MAP_RESOURCE_ROAD,
                    'resource_id' => $resource['value'],
                ]);
            }
        }

        return response()->json(['message' => 'El mapa creado exitosamente'], 201);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'route_map_id' => 'required|integer|exists:route_maps,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $route_map = RouteMap::find($request->route_map_id);
        $route_map->delete();

        return response()->json(['message' => 'El mapa fue eliminado exitosamente' ], 200);
    }
}
