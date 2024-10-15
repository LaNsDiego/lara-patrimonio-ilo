<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function list()
    {
        $tags = Tag::orderBy('id','DESC')->get();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $tag = Tag::create([
            'name' => $request->name,
        ]);
        
        return response()->json(['message' => 'El tag fue registrado exitosamente','created' => $tag ], 201);
    }
}
