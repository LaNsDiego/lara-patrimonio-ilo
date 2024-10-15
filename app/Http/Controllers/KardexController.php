<?php

namespace App\Http\Controllers;

use App\Models\DetailKardex;
use App\Models\Employee;
use App\Models\Establishment;
use App\Models\Kardex;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KardexController extends Controller
{
/*     public function list()
    {
        $kardex = Kardex::with(['product_type','establishment'])->orderBy('id')->get();
        return response()->json($kardex);
    }
 */
    public function list_establishment()
    {
        $establishment = Establishment::with(['establishment_type','responsible'])->withCount('kardex')->orderBy('id')->get();
        return response()->json($establishment);
    }

    public function list_by_establishment(Request $request)
    {
        $establishment_id = (int)$request->establishment_id;
        $kardex = Kardex::with(['product_type.brand','detail_kardex.establishment','detail_kardex.product','detail_kardex.movement_type'])->withCount('detail_kardex')->where('establishment_id', $establishment_id)->orderBy('id','DESC')->get();
        return response()->json($kardex);
    }


    public function kardex_vechicles_store(Request $request)
    {
        // Validator::make($request->all(), [
        //     'product_type_id' => 'required|integer|exists:product_types,id',
        //     'quantity' => 'required|integer',
        //     'unit_price' => 'required|numeric',
        //     'rental_price' => 'required|numeric',
        //     'license_plate' => 'required|string',
        //     'manufacture_year' => 'nullable|string',
        //     'color' => 'nullable|string',
        //     'chassis' => 'nullable|string',
        //     'engine' => 'nullable|string',
        //     'historical_value' => 'nullable|numeric',
        //     'siga_code' => 'nullable|string',
        //     'accounting_account' => 'nullable|string',
        //     'order_number' => 'nullable|string',
        //     'pecosa_number' => 'nullable|string',
        //     'dimensions' => 'nullable|string',
        //     'status' => 'required|string',
        //     'comment' => 'required|string',
        // ])->validate();


        Log::info($request->all());
        $product_type_id = $request->product_type_id;
        $establishment_id = Establishment::ESTABLISHMENT_TARGET_ID;

        $kardex = Kardex::firstOrCreate(
            [
                'establishment_id' => $establishment_id,
                'product_type_id' => $product_type_id
            ],
            [
                'date' => now(),
                'quantity' => 0,
                'total_cost' => 0
            ]
        );

        if ($kardex->exists) {
            $kardex->increment('quantity', (int)$request->quantity);
            $kardex->increment('total_cost', (double)$request->unit_price);
        } else {
            $kardex->quantity = (int)$request->quantity;
            $kardex->total_cost = (double)$request->unit_price;
            $kardex->save();
        }

        $path = '';

        if($request->hasFile('image') && $request->file('image')->getClientMimeType() !== 'image/*'){
            $image = $request->file('image');
            $path = $image->store('Products', 'public');
        }
        $product = Product::create([
            'barcode' => Product::generateUniqueBarcode(),
            'serial_number' => $request->serial_number,
            'product_type_id' => $product_type_id,
            'establishment_id' => $establishment_id,
            'employee_id' => $request->employee_id,
            'rental_price' => (double)$request->rental_price,
            'acquisition_cost' => (double)$request->unit_price,
            'image' => $path,
            'license_plate' => $request->license_plate,
            'manufacture_year' => $request->manufacture_year,
            'color' => $request->color,
            'chassis' => $request->chassis,
            'engine' => $request->engine,
            'historical_value' => (double)$request->historical_value,
            'siga_code' => $request->siga_code,
            'accounting_account' => $request->accounting_account,
            'order_number' => $request->order_number,
            'pecosa_number' => $request->pecosa_number,
            'dimensions' => $request->dimensions,
            'status' => $request->status,
            'type_machinery' => $request->type_machinery,
            'responsible_employee_id' => ($request->responsible_employee_id)?$request->responsible_employee_id:null,
        ]);



        DetailKardex::create([
            'kardex_id' => $kardex->id,
            'product_id' => $product->id,
            'responsible_employee_id' => ($request->responsible_employee_id)?$request->responsible_employee_id:null,
            'movement_type_id' => 1,
            'establishment_id' => $establishment_id,
            'quantity' => 1,
            'unit_price' => (double)$request->unit_price,
            'total_price' => (double)$request->unit_price,
            'comment' => $request->comment
        ]);

        return response()->json(['message' => 'El vehículo fue registrado exitosamente'], 201);
    }

    public function store(Request $request)
    {
        // Log::info($request->all());
        $kardex_obj = (object)$request->values;
        $establishment_id = (int)$kardex_obj->establishment_id;
        $details = $request->details;

        if ($details) {
            foreach ($details as $detail) {
                $obj_detail = (object)$detail;
                $product_type_id = (int)$obj_detail->product_type_id;

                $kardex = Kardex::firstOrCreate(
                    [
                        'establishment_id' => $establishment_id,
                        'product_type_id' => $product_type_id
                    ],
                    [
                        'date' => $kardex_obj->date,
                        'quantity' => 0,
                        'total_cost' => 0
                    ]
                );

                if ($kardex->exists) {
                    $kardex->increment('quantity', (int)$obj_detail->quantity);
                    $kardex->increment('total_cost', (double)$obj_detail->total_price);
                } else {
                    $kardex->quantity = (int)$obj_detail->quantity;
                    $kardex->total_cost = (double)$obj_detail->total_price;
                    $kardex->save();
                }

                $product = $this->findOrCreateProduct($obj_detail, $kardex_obj, $establishment_id);

                $this->createDetailKardex($product, $kardex, $obj_detail, $kardex_obj);
            }
        }
    }

    public function storeOutput(Request $request){

        $assigned_employee = Employee::find($request->employee_id);
        $establishment_id = $request->establishment_id;
        $movement_type_id = $request->movement_type_id;
        $responsible_employee_id = ($request->responsible_employee_id)? $request->responsible_employee_id : null;

        foreach($request->allocation_product_details as $new_allocation){

            $product = Product::find($new_allocation['id']);

            $kardex = Kardex::where(
                [
                    ['establishment_id', $establishment_id],
                    ['product_type_id', $product->product_type_id]
                ]
            )->first();


            if($kardex){
                $kardex->quantity = $kardex->quantity - $new_allocation['stock'];
                $kardex->total_cost = $kardex->total_cost - ($new_allocation['stock'] * $product->acquisition_cost);
                $kardex->update();

                $new_detail_kardex = new DetailKardex();
                $new_detail_kardex->create([
                    'kardex_id' => $kardex->id,
                    'product_id' => $product->id,
                    'responsible_employee_id' => $responsible_employee_id,
                    'establishment_id' => $assigned_employee->establishment_id,
                    'movement_type_id' => $movement_type_id,
                    'quantity' => $new_allocation['stock'],
                    // 'unit_price' => $product->acquisition_cost,
                    'unit_price' => 0,
                    // 'total_price' => $new_allocation['stock'] * $product->acquisition_cost,
                    'total_price' => 0,
                ]);


            }

            $recept_kardex = Kardex::where(
                [
                    ['establishment_id', $assigned_employee->establishment_id],
                    ['product_type_id', $product->product_type_id]
                ]
            )->first();


            if($recept_kardex){
                $recept_kardex->quantity = $recept_kardex->quantity + $new_allocation['stock'];
                $recept_kardex->total_cost = $recept_kardex->total_cost + ($new_allocation['stock'] * $product->acquisition_cost);
                $recept_kardex->update();

            }else{
                $recept_kardex = new Kardex();
                $recept_kardex->establishment_id = $assigned_employee->establishment_id;
                $recept_kardex->product_type_id = $product->product_type_id;
                $recept_kardex->date = now();
                $recept_kardex->quantity = $new_allocation['stock'];
                $recept_kardex->total_cost = $new_allocation['stock'] * $product->acquisition_cost;
                $recept_kardex->save();
            }



            $new_product = Product::where('barcode', $product->barcode)
            ->where('employee_id', $assigned_employee->id)
            ->where('establishment_id', $assigned_employee->establishment_id)->first();

            if($new_product){
                $new_product->employee_id = $assigned_employee->id;
                $new_product->establishment_id = $assigned_employee->establishment_id;
                $new_product->update();

            }else{
                $new_product = new Product();
                $new_product->barcode = $product->barcode;
                $new_product->employee_id = $assigned_employee->id;
                $new_product->establishment_id = $assigned_employee->establishment_id;

                $fillableAttributes = collect($product->getAttributes())->except([
                    'barcode',
                    'employee_id',
                    'establishment_id',
                ])->toArray();

                $new_product->fill($fillableAttributes);
                $new_product->save();
            }

            $new_detail_kardex = new DetailKardex();
            $new_detail_kardex->create([
                'kardex_id' => $recept_kardex->id,
                'product_id' => $new_product->id,
                'responsible_employee_id' => $responsible_employee_id,
                'establishment_id' => $establishment_id,
                'movement_type_id' => 1, //ENTRADA
                'quantity' => $new_allocation['stock'],
                // 'unit_price' => $new_product->acquisition_cost,
                'unit_price' => 0,
                // 'total_price' => $new_allocation['stock'] * $new_product->acquisition_cost,
                'total_price' => 0,
            ]);


        }

        return response()->json(['message' => 'La salida fue registrado exitosamente'], 201);
    }

    private function findOrCreateProduct($obj_detail, $kardex_obj, $establishment_id)
    {
        $establishment = Establishment::find($establishment_id);
        $establishment_employee_id =($establishment->responsible_id)? (int)$establishment->responsible_id : null;
        $accion_employee = ($kardex_obj->responsible_employee_id)? (int)$kardex_obj->responsible_employee_id : null;
        if ($obj_detail->serial_number) {
            Log::info('no existe producto con serie');

            $path = null;
            if ($obj_detail->image) {
                $path = $this->createPathFile($obj_detail->image);
            }
            Log::info($path);


            return Product::create([
                'barcode' => Product::generateUniqueBarcode(),
                'serial_number' => $obj_detail->serial_number ?? '',
                'product_type_id' => (int)$obj_detail->product_type_id,
                'establishment_id' => $establishment_id,
                'employee_id' => $establishment_employee_id,
                'rental_price' => (double)$obj_detail->rental_price,
                'acquisition_cost' => (double)$obj_detail->unit_price,
                'image' => $path,
                'license_plate' => $obj_detail->license_plate ?? '',
                'manufacture_year' => $obj_detail->manufacture_year ?? '',
                'color' => $obj_detail->color ?? '',
                'chassis' => $obj_detail->chassis ?? '',
                'engine' => $obj_detail->engine ?? '',
                'historical_value' => (double)$obj_detail->historical_value ?? 0,
                'siga_code' => $obj_detail->siga_code ?? '',
                'accounting_account' => $obj_detail->accounting_account ?? '',
                'order_number' => $obj_detail->order_number ?? '',
                'pecosa_number' => $obj_detail->pecosa_number ?? '',
                'dimensions' => $obj_detail->dimensions ?? '',
                'status' => $obj_detail->status ?? '',
                'responsible_employee_id' => $accion_employee,

            ]);
        } else {
            Log::info('existe producto sin serie');
            $existing_product_without_series = Product::where([
                ['establishment_id', $establishment_id],
                ['product_type_id', $obj_detail->product_type_id],
                ['serial_number', null]
            ])->first();

            if ($existing_product_without_series) {
                Log::info('existe producto sin serie');
                return $existing_product_without_series;
            } else {
                Log::info('no existe producto sin serie');

                $path = null;
                if ($obj_detail->image) {
                    $path = $this->createPathFile($obj_detail->image);
                }


                return Product::create([
                    'barcode' => Product::generateUniqueBarcode(),
                    'product_type_id' => (int)$obj_detail->product_type_id,
                    'establishment_id' => $establishment_id,
                    'employee_id' => ($establishment->responsible_id)? (int)$establishment->responsible_id : null,
                    'rental_price' => (double)$obj_detail->rental_price,
                    'acquisition_cost' => (double)$obj_detail->unit_price,
                    'image' => $path,
                    'license_plate' => $obj_detail->license_plate ?? '',
                    'manufacture_year' => $obj_detail->manufacture_year ?? '',
                    'color' => $obj_detail->color ?? '',
                    'chassis' => $obj_detail->chassis ?? '',
                    'engine' => $obj_detail->engine ?? '',
                    'historical_value' => (double)$obj_detail->historical_value ?? 0,
                    'siga_code' => $obj_detail->siga_code ?? '',
                    'accounting_account' => $obj_detail->accounting_account ?? '',
                    'order_number' => $obj_detail->order_number ?? '',
                    'pecosa_number' => $obj_detail->pecosa_number ?? '',
                    'dimensions' => $obj_detail->dimensions ?? '',
                    'status' => $obj_detail->status ?? '',
                    'responsible_employee_id' => $accion_employee,
                ]);
            }
        }
    }

    private function createDetailKardex($product, $kardex, $obj_detail, $kardex_obj)
    {
        DetailKardex::create([
            'kardex_id' => $kardex->id,
            'product_id' => $product->id,
            'responsible_employee_id' => ($kardex_obj->responsible_employee_id)? (int)$kardex_obj->responsible_employee_id : null,
            'movement_type_id' => (int)$kardex_obj->movement_type_id,
            'establishment_id' => (int)$kardex->establishment_id,
            'quantity' => (int)$obj_detail->quantity,
            'unit_price' => (double)$obj_detail->unit_price,
            'total_price' => (double)$obj_detail->total_price,
            'comment' => $obj_detail->comment
        ]);
    }

    private function createPathFile($file)
    {
            $imageData = $file;
            list($type, $imageData) = explode(';', $imageData);
            list(,$imageData)  = explode(',', $imageData);
            $imageData = base64_decode($imageData);
            $imageName = 'products/image_' . time() . '.' . explode('/', $type)[1];
            Storage::disk('public')->put($imageName, $imageData);

            return $imageName;
    }

    public function listByLimpieza(Request $request)
    {
        $establishment = Establishment::where('name', Establishment::NAME_ESTABLISHMENT_TARGET)->first();
        $kardex = Kardex::with(['product_type.brand','detail_kardex.product.product_type.measurement_unit'])->withCount('detail_kardex')->where('establishment_id', $establishment->id)->orderBy('id')->get();
        return response()->json($kardex);
    }
}
