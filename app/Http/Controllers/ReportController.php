<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\FuelConsumption;
use App\Models\ProductRental;
use App\Models\Vehicle;
use App\Models\WantedPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function fuel_consumption_data()
    {
        $vehicles = Vehicle::with(['employee'])->get();
        return response()->json($vehicles);
    }

    public function fuel_consumption_report(Request $request)
    {
        Log::info($request->all());
        $vehicle = Vehicle::find($request->vehicle_id);

        $query = FuelConsumption::where('vehicle_id', $request->vehicle_id);
        if($request->has('start_date') && $request->start_date != null){
            $query->where('date', '>=', $request->start_date);
        }
        if($request->has('end_date') && $request->end_date != null){
            $query->where('date', '<=', $request->end_date);
        }
        $fuel_consumptions = $query->get();
        return response()->json($fuel_consumptions);
    }

    public function wanted_people_by_date_range(Request $request)
    {
        Log::info($request->all());
        Log::info(Carbon::parse($request->start_date)->toIso8601String());
        Log::info(Carbon::parse($request->end_date)->toIso8601String());
        // $query = WantedPerson::with(['incidents.staff_security','incidents.photos']);
        $query = WantedPerson::whereHas('incidents', function($subquery) use ($request) {
            $subquery->whereBetween('created_at', [
                Carbon::parse($request->start_date)->toIso8601String(),
                Carbon::parse($request->end_date)->toIso8601String()
            ]);
        })->with([
            'incidents' => fn($subquery) => $subquery->whereBetween('created_at', [
                Carbon::parse($request->start_date)->toIso8601String(),
                Carbon::parse($request->end_date)->toIso8601String()
            ]),
            'incidents.staff_security', 'incidents.photos']);
        // if($request->has('start_date') && $request->start_date != null){
        //     $query->where('date', '>=', $request->start_date);
        // }
        // if($request->has('end_date') && $request->end_date != null){
        //     $query->where('date', '<=', $request->end_date);
        // }
        $result = $query->get();
        return response()->json($result);
    }

    public function wanted_person(Request $request){
        Log::info($request->all());
        $wanted_person = WantedPerson::with(['incidents.staff_security','incidents.photos','incidents.vehicle.product_type'])->where('id',$request->wanted_person_id)->first();
        $wanted_person->incidents->each(function($incident){
            $incident->photos->each(function($photo){
                if (Storage::disk('public')->exists($photo->path)) {
                    $fileContent = Storage::disk('public')->get($photo->path);
                    $mimeType = Storage::disk('public')->mimeType($photo->path);
                    $base64File = base64_encode($fileContent);
                    $photo->base64 = "data:$mimeType;base64,$base64File";
                }
            });
        });
        return response()->json($wanted_person);
    }


    public function product_rental(Request $request){

        Log::info($request->all());
        $establishment_id = $request->establishment_id;
        $range_dates = $request->range_dates;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $product_id = $request->product_id;
        $employee_id = $request->employee_id;
        $is_external = $request->is_external;

        $query = ProductRental::with(['product.product_type','employee','establishment']);
        if ($establishment_id) {
            $query->where('establishment_id', Establishment::ESTABLISHMENT_TARGET_ID);
        }
        if ($is_external !== null) {
            if (boolval($is_external) === true) {
                $query->where('is_external', true);
            } elseif (boolval($is_external)  === false) {
                $query->where('is_external', false);
                if($employee_id) {
                    $query->where('employee_id', $employee_id);
                }

            }
        }
        if($range_dates){
            if($start_date){
                $query->where('start_date', '>=', $start_date);
            }

            if($end_date){
                $query->where('end_date', '<=', $end_date);
            }
        }

        if($product_id) {
            $query->where('product_id', $product_id);
        }



        $product_rentals = $query->orderBy('id','DESC')->get();
        // Calcular la suma total
        $sum_mileage_traveled = $product_rentals->sum('mileage_traveled');
        $sum_total_hours = $product_rentals->sum('total_hours');
        $sum_total_price = $product_rentals->sum('total_price');

        return response()->json([
            'product_rentals' => $product_rentals,
            'totals' => [
                'sum_mileage_traveled' => $sum_mileage_traveled,
                'sum_total_hours' => $sum_total_hours,
                'sum_total_price' => $sum_total_price
            ]
        ]);
    }

}
