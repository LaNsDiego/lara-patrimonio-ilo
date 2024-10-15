<?php

namespace App\Http\Controllers;

use App\Models\ProductTypeRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductTypeRecommendationController extends Controller
{
    public function list()
    {
        $product_type_recommendations = ProductTypeRecommendation::all();
        return response()->json($product_type_recommendations);
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product_type_recommendation = ProductTypeRecommendation::create([
            'name' => $request->name,
        ]);
        
        return response()->json(['message' => 'La recomendacion de tipo de producto fue registrado exitosamente','created' => $product_type_recommendation ], 201);
    }
}
