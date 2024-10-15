<?php

namespace App\Http\Controllers;

use App\Models\ProductAllocation;
use App\Models\ProductAllocationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductAllocationController extends Controller
{
    public function list(Request $request){
        $product_allocations = ProductAllocation::with(['employee','product_allocation_details.product.product_type.measurement_unit'])->where('programation_schedule_id', $request->programation_schedule_id)->get();
        return response()->json($product_allocations);
    }

    public function store(Request $request){
        // $product_allocation = ProductAllocation::create($request->all());
        Log::info($request->all());
        $product_allocation = ProductAllocation::create([
            'programation_schedule_id' => $request->programation_schedule_id,
            'employee_id' => $request->employee_id,
        ]);

        foreach ($request->allocation_product_details as $new_allocation) {
            ProductAllocationDetail::create([
                'product_allocation_id' => $product_allocation->id,
                'product_id' => $new_allocation['id'],
                'quantity' => $new_allocation['stock'],
            ]);
        }
        return response()->json(['message' => 'Asignación de producto creada correctamente']);
    }

}
