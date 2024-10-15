<?php

namespace App\Http\Controllers;

use App\Models\SectorType;
use Illuminate\Http\Request;

class SectorTypeController extends Controller
{
    public function list(){
        $sector_types = SectorType::orderBy('id','DESC')->get();
        return response()->json($sector_types);
    }
}
