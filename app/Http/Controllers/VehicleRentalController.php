<?php

namespace App\Http\Controllers;

use App\Models\VehicleRental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VehicleRentalController extends Controller
{
    public function list()
    {
        $vehicle_retals = VehicleRental::with('vehicle','provider')->get();
        return response()->json($vehicle_retals);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'vehicle_id' => 'required',
            'provider_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'daily_cost' => 'required',
            'total_cost' => 'required',
            'mileage' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $vehicle_retal = VehicleRental::create([
            'vehicle_id' => (int) $request->vehicle_id,
            'provider_id' => (int) $request->provider_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'daily_cost' => $request->daily_cost,
            'total_cost' => $request->total_cost,
            'mileage' => $request->mileage,
        ]);

        $vehicle_retal->load('vehicle');
        $vehicle_retal->load('provider');
        return response()->json(['message' => 'El vehiculo rentado fue registrado exitosamente','created' => $vehicle_retal ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'vehicle_id' => 'required',
            'provider_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'daily_cost' => 'required',
            'total_cost' => 'required',
            'mileage' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $vehicle_retal = VehicleRental::find($request->id);
        $vehicle_retal->vehicle_id = (int)$request->vehicle_id;
        $vehicle_retal->provider_id = (int)$request->provider_id;
        $vehicle_retal->start_date = $request->start_date;
        $vehicle_retal->end_date = $request->end_date;
        $vehicle_retal->daily_cost = $request->daily_cost;
        $vehicle_retal->total_cost = $request->total_cost;
        $vehicle_retal->mileage = $request->mileage;
        $vehicle_retal->save();

        return response()->json(['message' => 'El vehiculo rentado fue actualizado exitosamente' ], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'vehicle_rental_id' => 'required|integer|exists:vehicle_rentals,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $vehicle_rental = VehicleRental::find($request->vehicle_rental_id);

        $vehicle_rental->delete();
        return response()->json(['message' => 'El vehiculo rentado fue eliminada exitosamente' ], 200);
    }
}
