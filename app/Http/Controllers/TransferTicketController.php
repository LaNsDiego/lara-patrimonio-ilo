<?php

namespace App\Http\Controllers;

use App\Models\TransferTicket;
use Illuminate\Http\Request;

class TransferTicketController extends Controller
{
    public function list()
    {
        $transfer_tickets = TransferTicket::
            with([
                'approver_employee',
                'requester_employee',
                'origin_establishment',
                'destination_establishment'
            ])->orderBy('id', 'asc')
            ->get();
        return response()->json($transfer_tickets);
    }

    public function store(Request $request){

        $request->validate([
            'date' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required|max:255',
            'observation' => 'required',
            'approver_employee_id' => 'required|exists:employees,id',
            'requester_employee_id' => 'required|exists:employees,id',
            'origin_establishment_id' => 'required|exists:establishments,id',
            'destination_establishment_id' => 'required|exists:establishments,id',
        ]);

        $transfer_ticket = new TransferTicket();
        $transfer_ticket->date = $request->date;
        $transfer_ticket->start_date = $request->start_date;
        $transfer_ticket->end_date = $request->end_date;
        $transfer_ticket->reason = $request->reason;
        $transfer_ticket->observation = $request->observation;
        $transfer_ticket->approver_employee_id = $request->approver_employee_id;
        $transfer_ticket->requester_employee_id = $request->requester_employee_id;
        $transfer_ticket->origin_establishment_id = $request->origin_establishment_id;
        $transfer_ticket->destination_establishment_id = $request->destination_establishment_id;
        $transfer_ticket->save();

        return response()->json(['message' => 'Papeleta de traslado creada correctamente']);
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required|exists:transfer_tickets,id',
            'date' => 'required',
            'start_date' => 'required|format:Y-m-d',
            'end_date' => 'required|format:Y-m-d',
            'reason' => 'required|max:255',
            'observation' => 'required',
            'approver_employee_id' => 'required|exists:employees,id',
            'requester_employee_id' => 'required|exists:employees,id',
            'origin_establishment_id' => 'required|exists:establishments,id',
            'destination_establishment_id' => 'required|exists:establishments,id',
        ]);

        $transfer_ticket = TransferTicket::find($request->id);
        $transfer_ticket->date = $request->date;
        $transfer_ticket->start_date = $request->start_date;
        $transfer_ticket->end_date = $request->end_date;
        $transfer_ticket->reason = $request->reason;
        $transfer_ticket->observation = $request->observation;
        $transfer_ticket->approver_employee_id = $request->approver_employee_id;
        $transfer_ticket->requester_employee_id = $request->requester_employee_id;
        $transfer_ticket->origin_establishment_id = $request->origin_establishment_id;
        $transfer_ticket->destination_establishment_id = $request->destination_establishment_id;
        $transfer_ticket->save();

        return response()->json(['message' => 'Papeleta de traslado actualizada correctamente']);
    }
}
