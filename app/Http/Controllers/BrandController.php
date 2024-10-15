<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{

    public function list()
    {
        $brands = Brand::orderBy('id')->get();
        return response()->json($brands);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(),[
            'name' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255',
        ])->validate();

        Brand::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'La marca fue registrado exitosamente'], Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->save();

        return response()->json(['message' => 'La marca fue actualizada exitosamente' ], Response::HTTP_OK);
    }

    public function destroy(Request $request){

        Validator::make($request->all(),[
            'brand_id' => 'required|integer|exists:brands,id',
        ])->validate();
        $brand = Brand::find($request->brand_id);
        $brand->delete();
        return response()->json(['message' => 'La marca fue eliminada exitosamente' ], Response::HTTP_OK);
    }
}
