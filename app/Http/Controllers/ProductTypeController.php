<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductTypeController extends Controller
{
    public function list()
    {
        $product_types = ProductType::with('brand','measurement_unit','product_without_serie')->orderBy('id','DESC')->get();
        return response()->json($product_types);
    }

    public function listWithoutVehicleTag()
    {
        $excludedTags = 'VEHICULOS';

        $product_types = ProductType::with('brand', 'measurement_unit')
        ->whereJsonDoesntContain('tags', $excludedTags)
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json($product_types);
    }

    // lista los tipos de vehiculos
    public function listWithVehicleTag()
    {
        $excludedTags = 'VEHICULOS';

        $product_types = ProductType::with('brand', 'measurement_unit')
        ->whereJsonContains('tags', $excludedTags)
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json($product_types);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'nullable',
            'model' => 'required',
            'tags' => 'nullable|string',
            'brand_id' => 'required|integer|exists:brands,id',
            'measurement_unit_id' => 'required|integer|exists:measurement_units,id',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $tagsArray = $request->input('tags') ? explode(',', $request->input('tags')) : [];
        $product_type = ProductType::create([
            'name' => $request->name,
            'description' => $request->description,
            'model' => $request->model,
            'tags' => $tagsArray,
            'brand_id' => (int)$request->brand_id,
            'measurement_unit_id' => (int)$request->measurement_unit_id,
        ]);

        $product_type->load('brand');
        return response()->json(['message' => 'Tipo de producto registrado exitosamente','created' => $product_type ], 201);
    }


    public function update(Request $request){
        Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:product_types,id',
            'name' => 'required',
            'description' => 'nullable',
            'model' => 'required',
            'tags' => 'nullable|string',
            'brand_id' => 'required|integer|exists:brands,id',
            'measurement_unit_id' => 'required|integer|exists:measurement_units,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $tagsArray = $request->input('tags') ? explode(',', $request->input('tags')) : [];
        $product_type = ProductType::findOrFail($request->id);

        $product_type->update([
            'name' => $request->name,
            'description' => $request->description,
            'model' => $request->model,
            'tags' => $tagsArray,
            'brand_id' => (int)$request->brand_id,
            'measurement_unit_id' => (int)$request->measurement_unit_id,
        ]);

        return response()->json(['message' => 'Producto actualizado exitosamente' ], 200);
    }


    public function destroy(Request $request){

        $validator = Validator::make($request->all(), [
            'product_type_id' => 'required|integer|exists:product_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product_type = ProductType::findOrFail($request->product_type_id);
        $product_type->delete();

        return response()->json(['message' => 'Tipo de producto eliminado exitosamente' ], 200);
    }


}
