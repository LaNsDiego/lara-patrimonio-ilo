<?php

namespace App\Http\Controllers;

use App\Models\ProgramationSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ProgramationScheduleController extends Controller
{
    public function store(Request $request){

        $request->validate([
            'programation_id' => 'required',
            'name' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255',
            'shift' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        ProgramationSchedule::create([
            'programation_id' => $request->programation_id,
            'name' => $request->name,
            'shift' => $request->shift,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        return response()->json(['message' => 'Horario de programacion creada.'], Response::HTTP_CREATED);
    }


    public function update_map(Request $request){
        $programation_schedule = ProgramationSchedule::find($request->programation_schedule_id);
        $programation_schedule->map_route_id = $request->map_route_id;
        $programation_schedule->save();
        return response()->json(['message' => 'Mapa de ruta asignado.'], Response::HTTP_OK);
    }
}
