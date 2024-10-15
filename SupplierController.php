<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function list()
    {
        $suppliers = Supplier::orderBy('id','DESC')->get();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' => 'required',
            'name' => 'required',
            'document_type' => 'required | string',
            'document_number' => 'required | string',
            'phone_number' => 'required | string',
            'email' => 'required | email',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $supplier = Supplier::create([
            'type' => $request->type,
            'name' => $request->name,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'El proveedor fue registrado exitosamente','created' => $supplier ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'type' => 'required',
            'name' => 'required',
            'document_type' => 'required | string',
            'document_number' => 'required | string',
            'phone_number' => 'required | string',
            'email' => 'required | email',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $supplier = Supplier::find($request->id);
        $supplier->update([
            'type' => $request->type,
            'name' => $request->name,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'El proveedor fue actualizado exitosamente' ], 200);
    }

    public function destroy(Request $request){
        Log::info($request->all());
        Validator::make($request->all(),[
            'supplier_id' => 'required|integer|exists:suppliers,id',
        ])->validate();
        
        $supplier = Supplier::find($request->supplier_id);

        $supplier->delete();
        return response()->json(['message' => 'El proveedor fue eliminado exitosamente' ], 200);
    }
}
