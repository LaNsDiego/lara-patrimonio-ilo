<?php

namespace App\Http\Controllers;

use App\Models\DetailKardex;
use App\Models\DetailProductRental;
use App\Models\Incident;
use App\Models\Product;
use App\Models\ProductRental;
use App\Models\ProductType;
use App\Models\Programation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function data(){

        // 1Obtener los vehículos en el establecimiento
        $product_type_vehicles_ids = ProductType::
            whereJsonContains('tags', "VEHICULOS")
            ->get()
            ->pluck('id');
        $vehicles = Product::
            where('establishment_id', 1)
            ->whereIn('product_type_id', $product_type_vehicles_ids)
            ->get();

        // 2Obtener el consumo de combustible en el establecimiento
        $product_type_fuels_ids = ProductType::
            whereJsonContains('tags', "COMBUSTIBLE")
            ->get()
            ->pluck('id');

        $fuels_ids_in_target_establishment = Product::
            where('establishment_id', 1)
            ->whereIn('product_type_id', $product_type_fuels_ids)
            ->get()
            ->pluck('id');

        $fuels = DetailKardex::
            whereIn('product_id', $fuels_ids_in_target_establishment)
            ->where('movement_type_id',2)
            ->get();

        // 3Obtener las programaciones de patrullaje
        $programations = Programation::all()->count();

        // 4Obtener los servicios de patrullaje de recorrido(PARTE DIARIO)
        $vehicle_rentals = DetailProductRental::all()->count();

        // 5Obtener los incidentes ocurridos en los últimos 12 meses
        $monthly_incidents = [];
        Carbon::setLocale('es');
        // Iterar sobre los últimos 12 meses, comenzando por el mes actual
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i); // Obtener la fecha correspondiente

            $month = $date->month;
            $year = $date->year;

            $count = Incident::whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->whereNull('deleted_at')
                            ->count();

            array_push(
                $monthly_incidents,
                [
                    'count'=> $count,
                    'month_name'=> ucfirst($date->translatedFormat ('F-Y')),
                ]
            );
        }
        // 6 LOS 5 PRODUCTOS CON MAYOR MOVIMIENTO EN EL ESTABLECIMIENTO

        $topProducts = DetailKardex::select('product_types.name', DB::raw('SUM(detail_kardex.quantity) as total_movement'))
            ->join('products', 'detail_kardex.product_id', '=', 'products.id')
            ->join('product_types', 'products.product_type_id', '=', 'product_types.id')
            ->where('detail_kardex.establishment_id', 1)
            ->whereNull('detail_kardex.deleted_at')  // Considera solo los registros que no han sido eliminados
            ->groupBy('product_types.name')
            ->orderByDesc('total_movement')
            ->limit(5)
            ->get();

        return response()->json([
            'vehicles' => $vehicles->count(),
            'fuel_consumption' => $fuels->sum('quantity'),
            'programations' => $programations,
            'service_patrols' => $vehicle_rentals,
            'monthly_incidents' => array_reverse($monthly_incidents),
            'top_products' => $topProducts
        ]);
    }
}
