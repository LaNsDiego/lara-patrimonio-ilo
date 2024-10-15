<?php

namespace App\Http\Controllers;

use App\Models\MovementType;
use Illuminate\Http\Request;

class MovementTypeController extends Controller
{
    public function list()
    {
        $movement_types = MovementType::all();
        return response()->json($movement_types);
    }

    public function listInput()
    {
        $movement_types = MovementType::where('name', 'ENTRADA')->orderBy('id','DESC')->get();
        return response()->json($movement_types);
    }

    public function listOutput()
    {
        $movement_types = MovementType::where('name', 'SALIDA')->orderBy('id','DESC')->get();
        return response()->json($movement_types);
    }
}
