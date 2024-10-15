<?php

namespace App\Http\Controllers;

use App\Models\Campaing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CampaingController extends Controller
{
    public function list(){
        $campaings = Campaing::with(['programations.responsible_employee','programations.programation_schedules'])->get();
        return response()->json($campaings);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z0-9\sÑñáéíóúÁÉÍÓÚ]+$/|max:255'
        ]);

        $campaing = new Campaing();
        $campaing->name = $request->name;
        $campaing->save();

        return response()->json(['message' => 'Campaña de Patrullaje creada correctamente'], Response::HTTP_CREATED);
    }

    public function update(Request $request){
        $campaing = Campaing::find($request->id);
        $campaing->name = $request->name;
        $campaing->save();

        return response()->json(['message' => 'Campaña de Patrullaje actualizada correctamente']);
    }

    public function delete(Request $request){
        $campaing = Campaing::find($request->id);
        $campaing->delete();

        return response()->json(['message' => 'Campaña de Patrullaje eliminada correctamente']);
    }
}
