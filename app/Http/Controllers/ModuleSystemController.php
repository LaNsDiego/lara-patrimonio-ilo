<?php

namespace App\Http\Controllers;

use App\Models\SystemModule;
use Illuminate\Http\Request;

class SystemModuleController extends Controller
{
    public function index()
    {
        $modules_system = SystemModule::all();
        return response()->json($modules_system, 200);
    }
}
