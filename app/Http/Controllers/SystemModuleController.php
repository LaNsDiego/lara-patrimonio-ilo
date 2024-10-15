<?php

namespace App\Http\Controllers;

use App\Models\ModuleGroup;
use App\Models\SystemModule;
use Illuminate\Http\Request;

class SystemModuleController extends Controller
{
    //
    public function list(){
        return response()->json(ModuleGroup::with(['system_modules.module_permissions'])->get());
    }
}
