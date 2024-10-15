<?php

namespace App\Http\Controllers;

use App\Models\ProductLocation;
use App\Models\LocationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductLocationController extends Controller
{
    public function list()
    {
        return response()->json(ProductLocation::with('product.product_type','location.location_type')->orderBy('id','DESC')->get());
    }

    public function byLocationType(Request $request)
    {
        $location_type_id = LocationType::where('name', $request->location_type)->pluck('id')->first();
        $product_locations = ProductLocation::with('product.product_type', 'location.location_type')
        ->whereHas('location', function ($query) use ($location_type_id) {
            $query->where('location_type_id', $location_type_id);
        })
        ->orderBy('id', 'DESC')
        ->get();
        return response()->json($product_locations);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'product_id' => 'required',
            'location_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product_location = ProductLocation::create([
            'product_id' => $request->product_id,
            'location_id' => $request->location_id,
        ]);

        return response()->json(['message' => 'La ubicación del contenedor fue registrada exitosamente','created' => $product_location ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer|exists:product_locations,id',
            'product_id' => 'required',
            'location_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product_location = ProductLocation::find($request->id);
        $product_location->product_id = $request->product_id;
        $product_location->location_id = $request->location_id;
        $product_location->update();

        return response()->json(['message' => 'La ubicación del contenedor fue actualizada exitosamente' ], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'location_id' => 'required|integer|exists:product_locations,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $product_location = ProductLocation::find($request->product_location_id);

        $product_location->delete();
        return response()->json(['message' => 'La ubicación del contenedor fue eliminada exitosamente' ], 200);
    }
}
