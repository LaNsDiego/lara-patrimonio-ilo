<?php

namespace App\Http\Controllers;

use App\Models\FuelConsumption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FuelConsumptionController extends Controller
{
    public function list()
    {
        $fuel_consumptions = FuelConsumption::with('vehicle')->get();
        return response()->json($fuel_consumptions);
    }

    public function store(Request $request)
    {
        Log::info($request->all());

        $validator = Validator::make($request->all(),[
            'vehicle_id' => 'required',
            'date' => 'required',
            'gallons' => 'required',
            'gallon_cost' => 'required',
            'total_cost' => 'required',  
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $fuel_consumption = FuelConsumption::create([
            'vehicle_id' => (int)$request->vehicle_id,
            'date' => $request->date,
            'gallons' => $request->gallons,
            'gallon_cost' => $request->gallon_cost,
            'total_cost' => $request->total_cost,
        ]);

        $fuel_consumption->load('vehicle');
        return response()->json(['message' => 'El consumo de combustible fue registrado exitosamente','created' => $fuel_consumption ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'vehicle_id' => 'required',
            'date' => 'required',
            'gallons' => 'required',
            'gallon_cost' => 'required',
            'total_cost' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $fuel_consumption = FuelConsumption::find($request->id);
        $fuel_consumption->vehicle_id = $request->vehicle_id;
        $fuel_consumption->date = $request->date;
        $fuel_consumption->gallons = $request->gallons;
        $fuel_consumption->gallon_cost = $request->gallon_cost;
        $fuel_consumption->total_cost = $request->total_cost;
        $fuel_consumption->save();
        
        return response()->json(['message' => 'El consumo de combustible actualizado exitosamente' ], 200);
    }

    public function destroy(Request $request){

        Log::info($request->all());
        $validator = Validator::make($request->all(),[
            'fuel_consumption_id' => 'required|integer|exists:fuel_consumptions,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $fuel_consumption = FuelConsumption::find($request->fuel_consumption_id);

        $fuel_consumption->delete();
        return response()->json(['message' => 'El consumo de combustible fue eliminado exitosamente' ], 200);
    }
}
