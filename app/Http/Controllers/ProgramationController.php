<?php

namespace App\Http\Controllers;

use App\Models\Programation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProgramationController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255',
            'description' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/',
            'start_date' => 'required',
            'end_date' => 'required',
            'responsible_employee_id' => 'required|exists:employees,id',
            'campaing_id' => 'required|exists:campaings,id',
        ]);

        Programation::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'responsible_employee_id' => $request->responsible_employee_id,
            'campaing_id' => $request->campaing_id,

        ]);
        return response()->json(['message' => 'Programacion creada correctamente.'],Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {

        $request->validate([
            'id' => 'required|integer|exists:programations,id',
            'name' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255',
            'description' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/',
            'start_date' => 'required',
            'end_date' => 'required',
            'responsible_employee_id' => 'required|exists:employees,id',
        ]);

        $programation = Programation::find($request->id);
        $programation->update([
            'name' => $request->name,
            'description' => $request->description,
            'responsible_employee_id' => $request->responsible_employee_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return response()->json(['message' => 'Programacion actualizada correctamente.'],Response::HTTP_CREATED);
    }

}
