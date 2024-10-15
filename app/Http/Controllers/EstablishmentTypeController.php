<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentType;
use Illuminate\Http\Request;

class EstablishmentTypeController extends Controller
{
    public function list()
    {
        $establishment_type = EstablishmentType::orderBy('id','DESC')->get();
        return response()->json($establishment_type);
    }
}
