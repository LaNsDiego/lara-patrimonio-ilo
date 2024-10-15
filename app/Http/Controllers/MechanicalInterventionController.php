<?php

namespace App\Http\Controllers;

use App\Models\MechanicalIntervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MechanicalInterventionController extends Controller
{
    public function list(){
        $mechanical_interventions = MechanicalIntervention::with(['vehicle','responsible'])->get();
        return response()->json($mechanical_interventions);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'date' => 'required',
            'description' => 'required',
            'cost' => 'required|numeric',
            'status' => 'required',
            'vehicle_id' => 'required|exists:vehicles,id',
            'responsible_employee_id' => 'required|exists:employees,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $mechanical_intervention = new MechanicalIntervention();
        $mechanical_intervention->date = $request->date;
        $mechanical_intervention->description = $request->description;
        $mechanical_intervention->cost = $request->cost;
        $mechanical_intervention->status = $request->status;
        $mechanical_intervention->vehicle_id = $request->vehicle_id;
        $mechanical_intervention->responsible_employee_id = $request->responsible_employee_id;
        $mechanical_intervention->save();
        return response()->json(['message' => 'La intervención mecánica fue registrada exitosamente'],201);
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
            'date' => 'required',
            'description' => 'required',
            'cost' => 'required|numeric',
            'status' => 'required',
            'vehicle_id' => 'required|exists:vehicles,id',
            'responsible_employee_id' => 'required|exists:employees,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $mechanical_intervention = MechanicalIntervention::find($request->id);
        $mechanical_intervention->date = $request->date;
        $mechanical_intervention->description = $request->description;
        $mechanical_intervention->cost = $request->cost;
        $mechanical_intervention->status = $request->status;
        $mechanical_intervention->vehicle_id = $request->vehicle_id;
        $mechanical_intervention->responsible_employee_id = $request->responsible_employee_id;
        $mechanical_intervention->save();
        return response()->json(['message' => 'La intervención mecánica fue actualizada exitosamente'],200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'mechanical_intervention_id' => 'required|integer|exists:mechanical_interventions,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $mechanical_intervention = MechanicalIntervention::find($request->mechanical_intervention_id);

        $mechanical_intervention->delete();
        return response()->json(['message' => 'La intervención mecánica fue eliminada exitosamente' ], 200);
    }
}
