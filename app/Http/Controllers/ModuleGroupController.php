<?php

namespace App\Http\Controllers;

use App\Models\ModuleGroup;
use Illuminate\Http\Request;

class ModuleGroupController extends Controller
{
    public function list(){
        return response()->json(ModuleGroup::with(['system_modules.module_permissions'])->get(), 200);
    }
}
