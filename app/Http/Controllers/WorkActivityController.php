<?php

namespace App\Http\Controllers;

use App\Models\WorkActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WorkActivityController extends Controller
{
    public function list(){
        $work_activitys = WorkActivity::orderBy('id','DESC')->get();
        return response()->json($work_activitys);
    }

    public function store(Request $request){
        Validator::make($request->all(),[
            'name' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        WorkActivity::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json(['message' => 'Actividad laboral creado correctamente'],Response::HTTP_CREATED);
    }

    public function update(Request $request){
        Validator::make($request->all(),[
            'id' => 'required|integer|exists:work_activities,id',
            'name' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        $work_activity = WorkActivity::find($request->id);
        $work_activity->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json(['message' => 'Actividad laboral atualizado correctamente'],Response::HTTP_OK);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'work_activity_id' => 'required|integer|exists:work_activities,id',
        ])->validate();

        $work_activity = WorkActivity::find($request->work_activity_id);

        $work_activity->delete();
        return response()->json(['message' => 'La marca fue eliminada exitosamente' ], Response::HTTP_OK);
    }
}
