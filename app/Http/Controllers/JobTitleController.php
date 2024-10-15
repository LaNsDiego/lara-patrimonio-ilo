<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobTitleController extends Controller
{
    public function list()
    {
        $job_titles = JobTitle::all();
        return response()->json($job_titles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $job_title = JobTitle::create([
            'name' => $request->name,
        ]);
        
        return response()->json(['message' => 'El cargo fue registrado exitosamente','created' => $job_title ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $job_title = JobTitle::find($request->id);
        $job_title->name = $request->name;
        $job_title->save();
        
        return response()->json(['message' => 'El cargo fue actualizada exitosamente' ], 200);
    }
    
    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'job_title_id' => 'required|integer|exists:job_titles,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $job_title = JobTitle::find($request->job_title_id);

        $job_title->delete();
        return response()->json(['message' => 'El cargo fue eliminada exitosamente' ], 200);
    }
}
