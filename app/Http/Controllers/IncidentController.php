<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentPhoto;
use App\Models\WantedPerson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class IncidentController extends Controller
{
    public function list_by_wanted_person(Request $request){
        $incidents = Incident::with('photos','staff_security','vehicle.product_type')
        ->where('wanted_person_id',$request->wanted_person_id)
        ->get();
        return response()->json($incidents);
    }

    public function store(Request $request){
        $request->validate([
            'photos' => 'required',
            'staff_security_id' => 'required',
            'reason' => 'required',
            'time' => 'required',
            'action_to_take' => 'required',
            'vehicle_id' => 'required|integer|exists:products,id',
        ]);


        $wanted_person = WantedPerson::where('dni', $request->dni)->first();
        if($wanted_person == null){
            $path_photo = '';
            if($request->hasFile('photo')){
                $path_photo = $request->file('photo')->store('requisitoriados','public');
            }
            $wanted_person = WantedPerson::create([
                'dni' => $request->dni,
                'photo' => $path_photo,
                'first_name' => $request->first_name,
                'time' => $request->time,
                'last_name' => $request->last_name,
            ]);
        }

        $incident = new Incident();
        $incident->wanted_person_id = $wanted_person->id;
        $incident->code = $request->code;
        $incident->staff_security_id = $request->staff_security_id;
        $incident->time = $request->time;
        $incident->action_to_take = $request->action_to_take;
        $incident->reason = $request->reason;
        $incident->sector_id = $request->sector_id;
        $incident->vehicle_id = $request->vehicle_id;
        $incident->vehicle_plate = $request->vehicle_plate;

        $incident->save();

        if($request->hasFile('photos')){
            foreach($request->file('photos') as $photo){
                $path_photo = $photo->store('incidents','public');
                $incident->photos()->create([
                    'path' => $path_photo
                ]);
            }
        }

        return response()->json([
            'message' => 'Incidente registrado correctamente'
        ],Response::HTTP_CREATED);

    }

    public function update(Request $request){

        $request->validate([
            'photos' => 'required',
            'staff_security_id' => 'required',
            'reason' => 'required',
            'time' => 'required',
            'action_to_take' => 'required',
            'vehicle_id' => 'required|integer|exists:products,id',
        ]);

        $incident = Incident::find($request->id);

        if($incident == null){
            return response()->json([
                'message' => 'Incidente no encontrado'
            ],500);
        }

        foreach($request->file('photos') as $photo){
            if($photo->getSize()){
                $path_photo = $photo->store('incidents','public');
                $incident->photos()->create([
                    'path' => $path_photo
                ]);
            }
        }

        // Log::info($request->deleted_photos);
        foreach(json_decode($request->deleted_photos) as $deleted_photo){
            // Log::info($deleted_photo);
            IncidentPhoto::where('path',$deleted_photo)->delete();
        }

        $incident->staff_security_id = $request->staff_security_id;
        $incident->reason = $request->reason;
        $incident->time = $request->time;
        $incident->save();

        return response()->json([
            'message' => 'Incidente registrado correctamente'
        ]);

    }
}
