<?php

namespace App\Http\Controllers;

use App\Models\AssignedProductRental;
use App\Models\DetailKardex;
use App\Models\DetailProductRental;
use App\Models\Employee;
use App\Models\Kardex;
use App\Models\Product;
use App\Models\ProductRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductRentalController extends Controller
{
    public function list()
    {
        $product_rental = ProductRental::with(['product.product_type','employee','establishment','detail_product_rentals.work_activity','detail_product_rentals.construction','assigned_product_rentals.detail_kardex.product.product_type.measurement_unit'])->orderBy('id','DESC')->get();
        return response()->json($product_rental);
    }
    public function list_by_employee_id(Request $request)
    {
        $employee = Employee::find($request->employee_id);
        $product_rentals = ProductRental::
        with([
            'product.product_type',
            'employee',
            'establishment',
            'detail_product_rentals.work_activity',
            'detail_product_rentals.construction',
            'assigned_product_rentals.detail_kardex.product.product_type.measurement_unit'
        ])
        ->where('employee_id', $request->employee_id)
        ->orderBy('id','DESC')
        ->get();
        return response()->json(['product_rentals' => $product_rentals , 'employee' => $employee]);
    }

    public function store(Request $request)
    {
        Log::info('------------------------------ Aqui -------------------------------');
        Log::info($request->all());

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'price_per_hour' => 'required|numeric',
            'employee_id' => 'nullable|exists:employees,id',
            'establishment_id' => 'nullable|exists:establishments,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'total_hours' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'is_external' => 'boolean',
            'number_dni' => 'nullable|string',
            'full_name' => 'nullable|string',
            'mileage_traveled' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $is_external = $request->is_external;

        $product_rental = new ProductRental();
        $product_rental->product_id = $request->product_id;
        $product_rental->price_per_hour = $request->price_per_hour;
        $product_rental->is_external = $is_external;
        if($is_external){
            $product_rental->employee_id = 0;
            $product_rental->establishment_id = 0;
            $product_rental->number_dni = $request->number_dni;
            $product_rental->full_name = $request->full_name;
        }else{
            $product_rental->employee_id = $request->employee_id;
            $product_rental->establishment_id = $request->establishment_id;
            $product_rental->number_dni = '';
            $product_rental->full_name = '';
        }
        $product_rental->start_date = $request->start_date;
        $product_rental->end_date = $request->end_date;
        $product_rental->total_hours = $request->total_hours;
        $product_rental->total_price = $request->total_price;
        $product_rental->mileage_traveled = $request->mileage_traveled;
        $product_rental->status = $request->status;
        $product_rental->save();

        $year = date('Y');
        $uniqueCode = 'PR-' . $year . '-' . str_pad($product_rental->id, 6, '0', STR_PAD_LEFT);
        $product_rental->update(['code' => $uniqueCode]);

        foreach ($request->detail as $detail) {
            $detail_product_rental = new DetailProductRental([
                'product_rental_id' => $product_rental->id,
                'work_activity_id' => $detail['work_activity_id'],
                'date' => $detail['date'],
                'start_hour' => $detail['start_hour'],
                'end_hour' => $detail['end_hour'],
                'total_hours' => $detail['total_hours'],
                'total_price' => $detail['total_price'],
                'construction_id' => $is_external ? null : $detail['construction_id'],
                'location_detail' => $is_external ? $detail['location_detail']:''
            ]);

            $detail_product_rental->save();
        }

        //ASSIGNED PRODUCT RENTAL
        Log::info('------------------------------ Aqui se encontro assigned_products -------------------------------');
        // Log::info($request->assigned_products);

        foreach ($request->assigned_products as $assigned_product) {
            // Log::info($assigned_product['id']);

            $product = Product::find(intval($assigned_product['id']));

            Log::info($product);
            Log::info('------------------------------ PÁSO HASTA EL PRODUCTO-------------------------------');

            $kardex = Kardex::where(
                [
                    ['establishment_id', $product->establishment_id],
                    ['product_type_id', $product->product_type_id]
                ]
            )->first();

            Log::info('------------------------------ Aqui se encontro kardex -------------------------------');
            Log::info($kardex);

            if($kardex){
                $kardex->update(['stock' => $kardex->stock - $assigned_product['stock']]);

                $detail_kardex = new DetailKardex();
                $detail_kardex->kardex_id = $kardex->id;
                $detail_kardex->product_id = $assigned_product['id'];
                $detail_kardex->responsible_employee_id = Auth::user()->id;
                $detail_kardex->movement_type_id = 2; // 2 = SALIDA $
                $detail_kardex->establishment_id = $product->establishment_id;
                $detail_kardex->quantity = $assigned_product['stock'];
                // $detail_kardex->unit_price = $product['acquisition_cost'];
                $detail_kardex->unit_price = 0;
                // $detail_kardex->total_price = $assigned_product['stock'] * $product['acquisition_cost'];
                $detail_kardex->total_price = 0;
                $detail_kardex->save();
                Log::info('------------------------------ Se agrego detalle kardex -------------------------------');
                // Log::info($detail_kardex);

                if ($detail_kardex) {
                    $assigned_product_rental = new AssignedProductRental([
                        'product_rental_id' => $product_rental->id,
                        'detail_kardex_id' => $detail_kardex->id,
                    ]);
                    $assigned_product_rental->save();

                }
            }

        }

        return response()->json(['message' => 'El alquiler de producto fue registrada exitosamente', 'data' => $product_rental], 201);
    }

    public function update(Request $request, $id)
    {
        $product_rental = ProductRental::find($id);
        if (!$product_rental) {
            return response()->json(['error' => 'Product rental not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20',
            'product_id' => 'required|exists:products,id',
            'employee_id' => 'required|exists:employees,id',
            'establishment_id' => 'required|exists:establishments,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'total_hours' => 'nullable|numeric',
            'mileage_traveled' => 'nullable|numeric',
            'status' => 'required|in:PENDIENTE,ALQUILADO,RETORNADO,CANCELADO',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product_rental->update($request->all());

        return response()->json(['message' => 'El alquiler de producto fue actualizada exitosamente', 'data' => $product_rental], 200);
    }

    public function destroy(Request $request)
    {
        Validator::make($request->all(), [
            'product_rental_id' => 'required|integer|exists:product_rentals,id',
        ])->validate();

        $product_rental = ProductRental::find($request->product_rental_id);
        if (!$product_rental) {
            return response()->json(['error' => 'El alquiler de producto no encontrado'], 404);
        }

        $product_rental->delete();

        return response()->json(['message' => 'El alquiler de producto fue eliminada exitosamente'], 200);
    }
}
