<?php

namespace App\Http\Controllers;

use App\Models\Construction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConstructionControlller extends Controller
{
    public function list()
    {   
        $constructions = Construction::with('location','resident','establishment')->orderBy('id','DESC')->get();
        return response()->json($constructions);
    }
        
    public function store(Request $request)
    {
        Log::info('Creating construction', $request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location_id' => 'required|integer|exists:locations,id',
            'resident_id' => 'required|integer|exists:employees,id',
            'establishment_id' => 'required|integer|exists:establishments,id',
            'mount' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        Log::info('------------- pasoo ---------------');

        $construction = Construction::create([
            'name' => $request->name,
            'establishment_id' => (int)$request->establishment_id,
            'location_id' => $request->location_id,
            'resident_id' => $request->resident_id,
            'mount' => $request->mount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json(['message' => 'El botadero fue registrada exitosamente','created' => $construction ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:constructions,id',
            'name' => 'required|string|max:255',
            'establishment_id' => 'required|integer|exists:establishments,id',
            'location_id' => 'required|integer|exists:locations,id',
            'resident_id' => 'required|integer|exists:employees,id',
            'mount' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $construction = Construction::find($request->id);
        if (!$construction) {
            return response()->json(['error' => 'Construction not found'], 404);
        }

        $construction->update([
            'name' => $request->name,
            'establishment_id' => $request->establishment_id,
            'location_id' => $request->location_id,
            'resident_id' => $request->resident_id,
            'mount' => $request->mount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json(['message' => 'El botadero fue actualizada exitosamente' ], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'construction_id' => 'required|integer|exists:constructions,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $construction = Construction::find($request->construction_id);

        $construction->delete();
        return response()->json(['message' => 'El botadero fue eliminada exitosamente' ], 200);
    }
}
