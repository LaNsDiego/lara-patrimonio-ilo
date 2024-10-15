<?php

namespace App\Http\Controllers;

use App\Models\DailyTrashEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DailyTrashEntryController extends Controller
{
    public function list()
    {
        $entries = DailyTrashEntry::with(['construction','product.product_type','product.establishment','employee.establishment','employee.job_title'])->get();
        return response()->json($entries);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'construction_id' => 'required|integer|exists:constructions,id',
            'entry_date' => 'required',
            'trash_amount' => 'required',
            'product_id' => 'required|integer|exists:products,id',
            'employee_id' => 'required|integer|exists:employees,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        Log::info('DailyTrashEntryController@store: Creando entrada de basura diaria');
        $entry = DailyTrashEntry::create([
            'construction_id' => $request->construction_id,
            'entry_date' => Carbon::parse($request->entry_date),
            'trash_amount' => $request->trash_amount,
            'product_id' => $request->product_id,
            'employee_id' => $request->employee_id,
        ]);

        return response()->json(['message' => 'La entrada de basura fue registrado exitosamente','created' => $entry ], 201);
    }

    public function update (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer|exists:daily_trash_entries,id',
            'construction_id' => 'required|integer|exists:constructions,id',
            'entry_date' => 'required|date',
            'trash_amount' => 'required',
            'product_id' => 'required|integer|exists:products,id',
            'employee_id' => 'required|integer|exists:employees,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $entry = DailyTrashEntry::find($request->id);
        $entry->construction_id = $request->construction_id;
        $entry->entry_date = Carbon::parse($request->entry_date);
        $entry->trash_amount = $request->trash_amount;
        $entry->product_id = $request->product_id;
        $entry->employee_id = $request->employee_id;
        $entry->save();

        return response()->json(['message' => 'La entrada de basura fue actualizado exitosamente'], 200);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'daily_trash_entry_id' => 'required|integer|exists:daily_trash_entries,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $daily_trash_entry = DailyTrashEntry::find($request->daily_trash_entry_id);

        $daily_trash_entry->delete();
        return response()->json(['message' => 'El botadero fue eliminada exitosamente' ], 200);
    }
}
