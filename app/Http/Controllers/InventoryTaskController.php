<?php

namespace App\Http\Controllers;

use App\Models\InventoryTask;
use Illuminate\Http\Request;

class InventoryTaskController extends Controller
{
    public function list_by_inventory($inventory_id){

        $entities = InventoryTask::where('inventory_id', $inventory_id)->orderBy('id','desc')->get();
        return response()->json($entities);
    }

    public function store(Request $request){

        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'headquarter_id' => 'required|exists:establishments,id',
            'responsible_headquarter_id' => 'required|exists:employees,id',
            'inventory_manager_id' => 'required|exists:employees,id',
        ]);
        $entity = new InventoryTask();
        $entity->inventory_id = $request->inventory_id;
        $entity->headquarter_id = $request->headquarter_id;
        $entity->responsible_headquarter_id = $request->responsible_headquarter_id;
        $entity->inventory_manager_id = $request->inventory_manager_id;
        $entity->save();

        return response()->json(['message' => 'Tarea programada correctamente']);
    }
}
