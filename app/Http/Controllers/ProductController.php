<?php

namespace App\Http\Controllers;

use App\Application\UseCases\Product\ExistBarcodeUserCase;
use App\Application\UseCases\Product\ExistSerialNumberUseCase;
use App\Models\Establishment;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function list()
    {

        $products = Product::with('employee','product_type.brand','establishment_location')->orderBy('id')->get();
        foreach ($products as $product) {
            $product->stock = $product->stock();
        }
        return response()->json($products);
    }

    public function store(Request $request){
        // $request->validate()

    }

    public function listByEstablishment(Request $request)
    {
        $products = Product::with('employee','product_type.brand','establishment')->where('establishment_id', $request->establishment_id)->orderBy('id')->get();
        foreach ($products as $product) {
            $product->stock = $product->stock();
        }
        return response()->json($products);
    }

    public function listBySerialNumber()
    {
        $products = Product::with('employee','product_type.brand','establishment')->whereNotNull('serial_number')->orderBy('id')->get();
        return response()->json($products);
    }

    public function listBySerialNumberAndEstablishment(Request $request)
    {
        $products = Product::with('employee','product_type.brand','establishment')->whereNotNull('serial_number')->where('establishment_id', $request->establishment_id)->orderBy('id')->get();
        return response()->json($products);
    }

    //OBTIENE LOS PRODUCTOS QUE SON TIPO CONTENEDORES Y QUE NO TIENEN ASIGNADO UNA UBICACIÓN
    public function byProductType(Request $request)
    {
        $productId = $request->product_id; // excluye el producto para editar
        $productTypeIds = ProductType::whereJsonContains('tags',  $request->product_type_name)->pluck('id');

        $products = Product::with('product_type')
        ->whereIn('product_type_id', $productTypeIds)
        ->where(function ($query) use ($productId) {
            $query->whereDoesntHave('product_location')
                  ->orWhere('id', $productId);
        })
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json($products);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products,id',
            // 'barcode' => 'required|unique:products,barcode,' . $request->id,
            'serial_number' => 'nullable|string|max:255',
            // 'product_type_id' => 'nullable|exists:product_types,id',
            // 'employee_id' => 'nullable|exists:employees,id',
            // 'establishment_id' => 'nullable|exists:establishments,id',
            // 'acquisition_cost' => 'nullable|numeric|min:0',
            'siga_code' => 'nullable|string|max:255',
            'accounting_account' => 'nullable|string|max:255',
            'order_number' => 'nullable|string|max:255',
            'rental_price' => 'nullable|numeric|min:0',
            'pecosa_number' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'license_plate' => 'nullable|unique:products,license_plate,' . $request->id,
            'manufacture_year' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'chassis' => 'nullable|string|max:255',
            'engine' => 'nullable|string|max:255',
            'historical_value' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = Product::findOrFail($request->id);

        $path = '';

        Log::info($request->all());

        if($request->hasFile('image') && $request->file('image')->getClientMimeType() !== 'image/*'){
            $image = $request->file('image');
            $path = $image->store('Products', 'public');
            // if exist image delete
            if($product->image != null || $product->image != ""){
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $path;
        }

        $product->update([
            'serial_number' => $request->serial_number ?? '',
            'license_plate' => $request->license_plate ?? '',
            'rental_price' => (double)$request->rental_price ?? 0,
            'manufacture_year' => $request->manufacture_year ?? '',
            'color' => $request->color ?? '',
            'chassis' => $request->chassis ?? '',
            'engine' => $request->engine ?? '',
            'historical_value' => (double)$request->historical_value ?? 0,
            'siga_code' => $request->siga_code ?? '',
            'accounting_account' => $request->accounting_account ?? '',
            'order_number' => $request->order_number ?? '',
            'pecosa_number' => $request->pecosa_number ?? '',
            'dimensions' => $request->dimensions ?? '',
            'status' => $request->status ?? '',
        ]);

        return response()->json(['message' => 'Producto actualizado exitosamente' ], 200);
    }


    public function destroy(Request $request){

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = Product::findOrFail($request->product_id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return response()->json(['message' => 'Producto eliminado exitosamente' ], 200);
    }

    public function check_serial_number(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|min:8',
        ]);
        return response()->json([
            'exists' => ExistSerialNumberUseCase::execute($request->serial_number),
        ]);
    }
    public function check_barcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|min:8',
        ]);
        return response()->json(ExistBarcodeUserCase::execute($request->barcode));
    }

    public function by_establishment_and_product_type(Request $request)
    {
        $products = Product::
            where('establishment_id', $request->establishment_id)
            ->where('product_type_id', $request->product_type_id)
            ->get();

        foreach ($products as $product) {
            $product->stock = $product->stock();
        }

        return response()->json($products);
    }

    public function vehicles_on_target_establishment(){
        $product_type_vehicle_ids = ProductType::whereJsonContains('tags',  ProductType::VEHICLE)->pluck('id');
        $vehicles = Product::with(['product_type','establishment','employee'])
            ->whereIn('product_type_id', $product_type_vehicle_ids)
            ->where('establishment_id', Establishment::ESTABLISHMENT_TARGET_ID)
            ->get();
        foreach ($vehicles as $vehicle) {
            $vehicle->stock = $vehicle->stock();
        }
        return response()->json($vehicles);
    }

    public function update_vehicles(Request $request){
        Validator::make($request->all(), [
           'id' => 'required|integer|exists:products,id',
           'serial_number' => 'nullable|string|max:255',
           'siga_code' => 'nullable|string|max:255',
           'accounting_account' => 'nullable|string|max:255',
           'order_number' => 'nullable|string|max:255',
           'rental_price' => 'nullable|numeric|min:0',
           'pecosa_number' => 'nullable|string|max:255',
           'dimensions' => 'nullable|string|max:255',
           'license_plate' => 'nullable|unique:products,license_plate,' . $request->id,
           'manufacture_year' => 'nullable|integer|digits:4|min:1900|max:' . (date('Y') + 1),
           'color' => 'nullable|string|max:255',
           'chassis' => 'nullable|string|max:255',
           'engine' => 'nullable|string|max:255',
           'historical_value' => 'nullable|numeric|min:0',
           'status' => 'nullable|string|max:255',
           'type_machinery' => 'required|string|max:255',
       ])->validate();

       $product = Product::findOrFail($request->id);

       $path = '';


       if($request->hasFile('image') && $request->file('image')->getClientMimeType() !== 'image/*'){
           $image = $request->file('image');
           $path = $image->store('Products', 'public');
           // if exist image delete
           if($product->image != null || $product->image != ""){
               Storage::disk('public')->delete($product->image);
           }
           $product->image = $path;
       }

       $product->update([
           'serial_number' => $request->serial_number ?? '',
           'license_plate' => $request->license_plate ?? '',
           'rental_price' => (double)$request->rental_price ?? 0,
           'manufacture_year' => $request->manufacture_year ?? '',
           'color' => $request->color ?? '',
           'chassis' => $request->chassis ?? '',
           'engine' => $request->engine ?? '',
           'historical_value' => (double)$request->historical_value ?? 0,
           'siga_code' => $request->siga_code ?? '',
           'accounting_account' => $request->accounting_account ?? '',
           'order_number' => $request->order_number ?? '',
           'pecosa_number' => $request->pecosa_number ?? '',
           'dimensions' => $request->dimensions ?? '',
           'status' => $request->status ?? '',
           'type_machinery' => $request->type_machinery,
       ]);

       return response()->json(['message' => 'Producto actualizado exitosamente' ], 200);
   }

    public function by_establishment_and_tag(Request $request)
    {
        $tag = Tag::where('name',$request->tag)->first();
        $product_type_vehicle_ids = ProductType::whereJsonContains('tags',  $tag->name)->pluck('id');
        $vehicles = Product::with(['product_type'])
            ->where('establishment_id', $request->establishment_id)
            ->whereIn('product_type_id', $product_type_vehicle_ids)
            ->get();

        return response()->json($vehicles);
    }


    public function list_by_responsible(Request $request){
        $products = Product::with('product_type','establishment_location','responsible')
            ->where('responsible_employee_id', $request->responsible_employee_id)
            ->get();

        return response()->json($products);
    }
}
