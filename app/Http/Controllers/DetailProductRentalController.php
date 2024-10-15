<?php

namespace App\Http\Controllers;

use App\Models\DetailProductRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailProductRentalController extends Controller
{
    public function index()
    {
        $details = DetailProductRental::with('work_activity', 'construction')->get();
        return response()->json($details);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_rental_id' => 'required|exists:product_rentals,id',
            'work_activity_id' => 'required|exists:work_activities,id',
            'start_hour' => 'nullable|date_format:H:i',
            'end_hour' => 'nullable|date_format:H:i',
            'total_hours' => 'nullable|numeric',
            'construction_id' => 'required|exists:constructions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $detail = DetailProductRental::create($request->all());

        return response()->json(['message' => 'El detalle de alquiler de producto fue registrada exitosamente', 'data' => $detail], 201);
    }

    public function show($id)
    {
        $detail = DetailProductRental::with('product_rental', 'work_activity', 'construction')->find($id);
        if (!$detail) {
            return response()->json(['error' => 'Detail product rental not found'], 404);
        }
        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = DetailProductRental::find($id);
        if (!$detail) {
            return response()->json(['error' => 'El detalle de alquiler de producto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_rental_id' => 'required|exists:product_rentals,id',
            'work_activity_id' => 'required|exists:work_activities,id',
            'start_hour' => 'nullable|date_format:H:i',
            'end_hour' => 'nullable|date_format:H:i',
            'total_hours' => 'nullable|numeric',
            'construction_id' => 'required|exists:constructions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $detail->update($request->all());

        return response()->json(['message' => 'El detalle de alquiler de producto fue actualizada exitosamente', 'data' => $detail], 200);
    }

    public function destroy($id)
    {
        $detail = DetailProductRental::find($id);
        if (!$detail) {
            return response()->json(['error' => 'El detalle de alquiler de producto no encontrado'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'El detalle de alquiler de producto fue eliminada exitosamente'], 200);
    }
}
