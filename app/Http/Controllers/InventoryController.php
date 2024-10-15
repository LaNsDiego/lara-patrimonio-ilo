<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function list(){
        $entities = Inventory::all();
        return response()->json($entities);
    }

    public function store(Request $request){

        $request->validate([
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required',
            'state' => 'required',
            // 'progress' => 'required',
        ]);
        Inventory::create([
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'state' => $request->state,
            'progress' => 0,
        ]);
        return response()->json(['message' => 'Inventario fisico creado correctamente']);
    }

    public function update(Request $request){

        $request->validate([
            'id' => 'required|exists:inventories,id',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required',
            'state' => 'required',
            'progress' => 'required',
        ]);
        $entity = Inventory::find($request->id);
        $entity->update($request->all());
        return response()->json(['message' => 'Inventario fisico creado correctamente']);
    }

    public function delete(Request $request){

            $request->validate([
                'id' => 'required|exists:inventories,id',
            ]);
            $entity = Inventory::find($request->id);
            $entity->delete();
            return response()->json(['message' => 'Inventario fisico eliminado correctamente']);
    }
}
