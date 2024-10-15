<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\WantedPerson;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WantedPersonController extends Controller
{

    public function next_code_incident(Request $request){
        // PR-SC-00001-2024 formato de pre-recibo
        $count_rows = Incident::count();
        $count_rows++;

        $code = "INC-".str_pad($count_rows, 5, '0', STR_PAD_LEFT).'-'.date('Y');
        return response()->json($code);

    }

    public function person_by_dni($dni)
    {
        $respuesta = array();
        $client = new Client();
        $res = $client->get('http://facturae-garzasoft.com/facturacion/buscaCliente/BuscaCliente2.php?' . 'dni=' . $dni . '&fe=N&token=qusEj_w7aHEpX');
        if ($res->getStatusCode() == 200) { // 200 OK
            $response_data = $res->getBody()->getContents();
            $respuesta = json_decode($response_data);
        }
        return json_encode($respuesta);
    }

    public function list(){
        return response()->json(WantedPerson::with(['incidents.staff_security','incidents.photos','incidents.vehicle.product_type'])->get());
    }

    public function destroy(Request $request){
        Log::info('Eliminando persona buscada');
        Validator::make($request->all(),[
            'wanted_person_id' => 'required|integer|exists:wanted_person,id',
        ])->validate();

        $wanted_person = WantedPerson::find($request->wanted_person_id);

        $wanted_person->delete();
        return response()->json(['message' => 'El proveedor fue eliminado exitosamente' ], 200);
    }
}
