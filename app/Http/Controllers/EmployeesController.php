<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
{
    public function list()
    {
        $employee = Employee::with(['job_title','establishment'])->orderBy('id','DESC')->get();
        return response()->json($employee);
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'document_type' => 'required',
            'document_number' => 'required|unique:employees,document_number',
            'job_title_id' => 'required|numeric',
            'establishment_id' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $employee = Employee::create([
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'name' => $request->name,
            'job_title_id' => (int) $request->job_title_id,
            'establishment_id' => (int) $request->establishment_id,
            'email' => ($request->email)?$request->email:null,
            'phone_number' => ($request->phone_number)?$request->phone_number:null,
        ]);

        $employee->load('job_title');
        $employee->load('establishment');
        return response()->json(['message' => 'El personal fue registrado exitosamente','created' => $employee ], Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'name' => 'required',
            'document_type' => 'required',
            'document_number' => 'required|unique:employees,document_number,' . $request->id . ',id',
            'job_title_id' => 'required|numeric',
            'establishment_id' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $employee = Employee::find($request->id);
        $employee->update([
            'id' => $request->id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'name' => $request->name,
            'job_title_id' => (int)$request->job_title_id,
            'establishment_id' => (int)$request->establishment_id,
            'email' => ($request->email)?$request->email:null,
            'phone_number' => ($request->phone_number)?$request->phone_number:null,
        ]);

        return response()->json(['message' => 'El personal fue actualizado exitosamente' ], 200);
    }

    public function managers(){
        $managers = Employee::where('job_title_id', 2)->get();
        return response()->json($managers);
    }

    public function destroy(Request $request){

        $validator = Validator::make($request->all(),[
            'employee_id' => 'required|integer|exists:employees,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }
        $employee = Employee::find($request->employee_id);

        $employee->delete();
        return response()->json(['message' => 'El personal fue eliminado exitosamente' ], 200);
    }


    public function employees_by_stablishment(Request $request){
        Log::info($request->all());
        $validator = Validator::make($request->all(),[
            'stablishment_id' => 'required|integer|exists:establishments,id',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $employees = Employee::where('establishment_id', $request->establishment_id)->get();
        return response()->json($employees);
    }

    public function employees_by_stablishment_target(){
        $establishment = Establishment::where('name', Establishment::NAME_ESTABLISHMENT_TARGET)->first();
        $employees = Employee::where('establishment_id',$establishment->id)->get();
        return response()->json($employees);
    }

    public function employees_by_establishment_except_output($establishment_id){
        $employees = Employee::with(['job_title','establishment'])->where('establishment_id', '<>', $establishment_id)->orderBy('id','DESC')->get();
        return response()->json($employees);
    }
}
