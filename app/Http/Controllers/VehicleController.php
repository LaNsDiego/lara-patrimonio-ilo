<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function list()
    {
        $vehicles = Vehicle::with('employee','location.sector')->get();
        Log::info($vehicles);
        return response()->json($vehicles);
    }

    public function store(Request $request)
    {
        Log::info($request->all());

        if($request->hasFile('image')){
            Log::info('----------------S----------------');
            Log::info($request->file('image'));
            Log::info('----------------E----------------');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // $extension = $image->getClientOriginalExtension();
            // $fileName = now()->format('dmY_siH') .".{$extension}";
            // $path = $image->storeAs('vehicles', $fileName, 'public');
            $path = $image->store('', 'public');
            Log::info($path);
        }

         $validator = Validator::make($request->all(),[
            'license_plate' => 'required | unique:vehicles',
            'name' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
            'brand' => 'required',
            'acquisition_date' => 'required',
            'manufacture_year' => 'required',
            'acquisition_cost' => 'required',
            'status' => 'required',
            'location_id' => 'required',
            'description' => 'required',
            'responsible_employee_id' => 'required | integer',
            'is_heavy_machinery' => 'required',
        ]);



        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $vehicles = Vehicle::create([
            'license_plate' => $request->license_plate,
            'name' => $request->name,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'brand' => $request->brand,
            'acquisition_date' => Carbon::createFromFormat('m/d/Y', $request->acquisition_date)->toIso8601String(),
            'manufacture_year' => $request->manufacture_year,
            'acquisition_cost' => $request->acquisition_cost,
            'status' => $request->status,
            'location_id' => (int)$request->location_id,
            'description' => $request->description,
            'responsible_employee_id' => (int)$request->responsible_employee_id,
            'is_heavy_machinery' => (boolean)$request->is_heavy_machinery,
            'image' => $path,
        ]);

        $vehicles->load('employee');
        $vehicles->load('location.sector');
        return response()->json(['message' => 'Vehiculo registrado exitosamente','created' => $vehicles ], 201);
    }


    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'license_plate' => 'required',
            'name' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
            'brand' => 'required',
            'acquisition_date' => 'required',
            'manufacture_year' => 'required',
            'acquisition_cost' => 'required',
            'status' => 'required',
            'location_id' => 'required',
            'description' => 'required',
            'responsible_employee_id' => 'required | integer',
            'is_heavy_machinery' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $vehicles = Vehicle::find($request->id);

        $path = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('vehicles', 'public');
            if($vehicles->image != null || $vehicles->image != ""){
                Storage::disk('public')->delete($vehicles->image);
            }
        }

        $vehicles->license_plate = $request->license_plate;
        $vehicles->name = $request->name;
        $vehicles->model = $request->model;
        $vehicles->serial_number = $request->serial_number;
        $vehicles->brand = $request->brand;
        $vehicles->acquisition_date = $request->acquisition_date;
        $vehicles->manufacture_year = $request->manufacture_year;
        $vehicles->acquisition_cost = $request->acquisition_cost;
        $vehicles->status = $request->status;
        $vehicles->location_id = (int)$request->location_id;
        $vehicles->description = $request->description;
        $vehicles->responsible_employee_id = (int)$request->responsible_employee_id;
        $vehicles->is_heavy_machinery = (boolean)$request->is_heavy_machinery;
        $vehicles->image = $path == "" ? $vehicles->image : $path;
        $vehicles->save();

        return response()->json(['message' => 'Vehiculo actualizado exitosamente' ], 200);
    }


    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'vehicle_id' => 'required|integer|exists:vehicles,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $vehicle = Vehicle::find($request->vehicle_id);
        if($vehicle->image != null || $vehicle->image != ""){
            Storage::disk('public')->delete($vehicle->image);
        }

        $vehicle->delete();
        return response()->json(['message' => 'Vehiculo eliminado exitosamente' ], 200);
    }


}
