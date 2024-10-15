<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use Illuminate\Http\Request;

class MeasurementUnitController extends Controller
{
    public function list()
    {
        $measurement_units = MeasurementUnit::orderBy('id','DESC')->get();
        return response()->json($measurement_units);
    }

}
