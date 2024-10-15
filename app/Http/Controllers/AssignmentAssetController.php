<?php

namespace App\Http\Controllers;

use App\Models\AssignmentAsset;
use Illuminate\Http\Request;

class AssignmentAssetController extends Controller
{
    public function list(){
        $assignments = AssignmentAsset::with('old_responsible', 'new_responsible')->orderBy('id','asc')->get();
        return response()->json($assignments);
    }

    public function store(Request $request){

        $request->validate([
            'old_responsible_id' => 'required',
            'new_responsible_id' => 'required',
            'details.*.asset_id' => 'required',
        ]);

        $assignment = AssignmentAsset::create();
        $assignment->old_responsible_id = $request->old_responsible_id;
        $assignment->new_responsible_id = $request->new_responsible_id;
        $assignment->save();

        foreach ($request->details as $detail) {
            $assignment->details()->create($detail);
        }

        return response()->json(['message' => 'Asignación creada correctamente']);

    }


}
