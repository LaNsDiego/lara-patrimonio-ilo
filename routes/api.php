<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AssignmentAssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CampaingController;
use App\Http\Controllers\ConstructionControlller;
use App\Http\Controllers\DailyTrashEntryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\EstablishmentTypeController;
use App\Http\Controllers\FuelConsumptionController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTaskController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocationTypeController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\MechanicalInterventionController;
use App\Http\Controllers\ModuleGroupController;
use App\Http\Controllers\MovementTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductAllocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLocationController;
use App\Http\Controllers\ProductRentalController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductTypeRecommendationController;
use App\Http\Controllers\ProgramationController;
use App\Http\Controllers\ProgramationScheduleController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoadWayController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteMapController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\SectorTypeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SystemModuleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TransferTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleRentalController;
use App\Http\Controllers\WantedPersonController;
use App\Http\Controllers\WorkActivityController;

use Illuminate\Support\Facades\Route;

Route::get("sapi", [AuthController::class, "sapi"]);
Route::get("group", [ModuleGroupController::class, "list"]);
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::post("register", [AuthController::class, "register"]);

Route::group(
    [
        'middleware' => 'auth:api'
    ],
    function () {

        // DASHBOARD DATA
        Route::get('dashboard/data', [DashboardController::class, 'data']);

        //ACTIVITIES LOGS
        Route::post('/activities/user', [ActivityController::class, 'getUserActivities']);

        // SYSTEM MODULES
        Route::get('system-modules/list', [SystemModuleController::class, 'list']);
        // USERS
        Route::get('users/list', [UserController::class, 'list']);
        Route::post('users/store', [UserController::class, 'store']);
        Route::post('users/update', [UserController::class, 'update']);
        Route::post('users/update-password', [UserController::class, 'updatePassword']);
        Route::post('users/delete', [UserController::class, 'destroy']);
        Route::get('users/search/{user_id}', [UserController::class, 'searchById']);

        Route::get('roles/list', [RoleController::class, 'list']);
        Route::post('roles/store', [RoleController::class, 'store']);

        // JOB TITLES
        Route::get('job-titles/list', [JobTitleController::class, 'list']);
        Route::post('job-titles/store', [JobTitleController::class, 'store']);
        Route::post('job-titles/update', [JobTitleController::class, 'update']);
        Route::post('job-titles/delete', [JobTitleController::class, 'destroy']);

        // WORK ACTIVITIES
        Route::get('work-activities/list', [WorkActivityController::class, 'list']);
        Route::post('work-activities/store', [WorkActivityController::class, 'store']);
        Route::post('work-activities/update', [WorkActivityController::class, 'update']);
        Route::post('work-activities/delete', [WorkActivityController::class, 'destroy']);

        // BRANDS
        Route::get('brands/list', [BrandController::class, 'list']);
        Route::post('brands/store', [BrandController::class, 'store']);
        Route::post('brands/update', [BrandController::class, 'update']);
        Route::post('brands/delete', [BrandController::class, 'destroy']);

        // MEASUREMENT
        Route::get('measurement-units/list', [MeasurementUnitController::class, 'list']);

        // PRODUCTO TYPE RECOMMENDATION
        Route::get('product-type-recommendations/list', [ProductTypeRecommendationController::class, 'list']);
        Route::post('product-type-recommendations/store', [ProductTypeRecommendationController::class, 'store']);

        // TAG
        Route::get('tags/list', [TagController::class, 'list']);
        Route::post('tags/store', [TagController::class, 'store']);

        // MOVEMENT TYPES
        Route::get('movement-types/list', [MovementTypeController::class, 'list']);
        Route::get('movement-types/list-input', [MovementTypeController::class, 'listInput']);
        Route::get('movement-types/list-output', [MovementTypeController::class, 'listOutput']);

        //ESTABLISHMENTS TYPES
        Route::get('establishment-types/list', [EstablishmentTypeController::class, 'list']);

        //ESTABLISHMENTS
        Route::get('establishments/list', [EstablishmentController::class, 'list']);
        Route::post('establishments/store', [EstablishmentController::class, 'store']);
        Route::post('establishments/update', [EstablishmentController::class, 'update']);
        Route::post('establishments/delete', [EstablishmentController::class, 'destroy']);

        //KARDEX
        // Route::get('establishments/list', [EstablishmentController::class, 'list']);
        Route::get('kardex/list-establishments', [KardexController::class, 'list_establishment']);
        Route::post('kardex/list-by-establishments', [KardexController::class, 'list_by_establishment']);
        Route::get('kardex/list-by-target', [KardexController::class, 'list_by_target_establishment']);
        Route::post('kardex/vehicles/store', [KardexController::class, 'kardex_vechicles_store']);
        Route::post('products/vehicles/update', [ProductController::class, 'update_vehicles']);
        // Route::post('establishments/store', [EstablishmentController::class, 'store']);
        // Route::post('establishments/update', [EstablishmentController::class, 'update']);
        // Route::post('establishments/delete', [EstablishmentController::class, 'destroy']);

        // EMPLOYEES
        Route::get('employees/list', [EmployeesController::class, 'list']);
        Route::post('employees/store', [EmployeesController::class, 'store']);
        Route::post('employees/update', [EmployeesController::class, 'update']);
        Route::post('employees/delete', [EmployeesController::class, 'destroy']);
        Route::get('employees/managers/list', [EmployeesController::class, 'managers']);
        Route::get('employees/list/by-stablishment/{stablishment_id}', [EmployeesController::class, 'employees_by_stablishment']);
        Route::get('employees/list/stablishment-target', [EmployeesController::class, 'employees_by_stablishment_target']);
        Route::get('employees/list/establishment-except-output/{establishment_id}', [EmployeesController::class, 'employees_by_establishment_except_output']);

        // LOCATION TYPES
        Route::get('location-types/list', [LocationTypeController::class, 'list']);

        // LOCATION
        Route::get('locations/list', [LocationController::class, 'list']);
        Route::post('locations/by-location-type', [LocationController::class, 'byLocationType']);
        Route::post('locations/store', [LocationController::class, 'store']);
        Route::post('locations/update', [LocationController::class, 'update']);
        Route::post('locations/delete', [LocationController::class, 'destroy']);

        // TYPE PRODUCTS
        Route::get('product-types/list', [ProductTypeController::class, 'list']);
        Route::post('product-types/store', [ProductTypeController::class, 'store']);
        Route::post('product-types/update', [ProductTypeController::class, 'update']);
        Route::post('product-types/delete', [ProductTypeController::class, 'destroy']);

        Route::get('product-types/list-without-vehicle-tag', [ProductTypeController::class, 'listWithoutVehicleTag']);
        Route::get('product-types/list-with-vehicle-tag', [ProductTypeController::class, 'listWithVehicleTag']);


        // PRODUCTS
        Route::get('products/list', [ProductController::class, 'list']);
        Route::get('products/list-by-serial-number', [ProductController::class, 'listBySerialNumber']);
        Route::post('products/list-by-establishment', [ProductController::class, 'listByEstablishment']);
        Route::post('products/list-by-serialnumber-and-establishemnt', [ProductController::class, 'listBySerialNumberAndEstablishment']);
        Route::post('products/by-product-type', [ProductController::class, 'byProductType']);
        Route::post('products/store', [ProductController::class, 'store']);
        Route::post('products/update', [ProductController::class, 'update']);
        Route::post('products/delete', [ProductController::class, 'destroy']);
        Route::post('products/check-serial-number', [ProductController::class, 'check_serial_number']);
        Route::post('products/check-barcode', [ProductController::class, 'check_barcode']);
        Route::get('products/establishment/{establishment_id}/product-type/{product_type_id}', [ProductController::class, 'by_establishment_and_product_type']);
        Route::get('products/establishment/{establishment_id}/tag/{tag}', [ProductController::class, 'by_establishment_and_tag']);
        Route::get('products/vehicles/establishment-target', [ProductController::class, 'vehicles_on_target_establishment']);
        Route::get('products/by/responsible/{responsible_employee_id}', [ProductController::class, 'list_by_responsible']);

        // SERCTOR TYPES
        Route::get('sector-types/list', [SectorTypeController::class, 'list']);

        // SERCTORS
        Route::get('sectors/list', [SectorController::class, 'list']);
        Route::post('sectors/store', [SectorController::class, 'store']);
        Route::post('sectors/update', [SectorController::class, 'update']);
        Route::post('sectors/delete', [SectorController::class, 'destroy']);

        // ROAD WAYS(CAMINOS)
        Route::get('road-ways/list', [RoadWayController::class, 'list']);
        Route::post('road-ways/store', [RoadWayController::class, 'store']);
        Route::post('road-ways/delete', [RoadWayController::class, 'destroy']);


        //CONTAINER LOCATION
        Route::get('product-locations/list', [ProductLocationController::class, 'list']);
        Route::post('product-locations/by-location-type', [ProductLocationController::class,'byLocationType']);
        Route::post('product-locations/store', [ProductLocationController::class, 'store']);
        Route::post('product-locations/update', [ProductLocationController::class, 'update']);
        Route::post('product-locations/delete', [ProductLocationController::class, 'destroy']);

        // ALLOCATIONS
        Route::post('product-allocations/list', [ProductAllocationController::class, 'list']);
        Route::post('product-allocations/store', [ProductAllocationController::class, 'store']);

        // CAMPAINGS
        Route::get('campaings/list', [CampaingController::class, 'list']);
        Route::post('campaings/store', [CampaingController::class, 'store']);
        Route::post('campaings/update', [CampaingController::class, 'update']);
        Route::post('campaings/delete', [CampaingController::class, 'delete']);

        // PROGRAMATIONS
        Route::post('programations/store', [ProgramationController::class, 'store']);
        Route::post('programations/update', [ProgramationController::class, 'update']);

        // PROGRAMATIONS
        Route::post('programation-schedules/store', [ProgramationScheduleController::class, 'store']);
        Route::post('programation-schedules/update-map', [ProgramationScheduleController::class, 'update_map']);

        // KARDEX
        Route::get('kardex/list', [KardexController::class, 'list']);
        Route::post('kardex/store', [KardexController::class, 'store']);
        Route::post('kardex/store-output', [KardexController::class, 'storeOutput']);
        Route::post('kardex/update', [KardexController::class, 'update']);
        Route::post('kardex/delete', [KardexController::class, 'destroy']);

        // SERCTORS
        Route::get('sectors/list', [SectorController::class, 'list']);
        Route::post('sectors/store', [SectorController::class, 'store']);
        Route::post('sectors/update', [SectorController::class, 'update']);
        Route::post('sectors/delete', [SectorController::class, 'destroy']);

        // PERMISSIONS (PERMISSIONS , HAS_PERMISSIONS)
        Route::get('permissions/list', [PermissionController::class, 'index']);
        Route::post('permissions/update', [PermissionController::class, 'update']);
        Route::post('permissions/update-permissions', [PermissionController::class, 'update_permissions']);
        Route::get('permissions/list-by-role/{role_id}', [PermissionController::class, 'permissions_by_role']);


        // MECHANICAL INTERVENTIONS
        Route::get('mechanical-interventions/list', [MechanicalInterventionController::class, 'list']);
        Route::post('mechanical-interventions/store', [MechanicalInterventionController::class, 'store']);
        Route::post('mechanical-interventions/update', [MechanicalInterventionController::class, 'update']);
        Route::post('mechanical-interventions/delete', [MechanicalInterventionController::class, 'destroy']);

        //FUEL CONSUMPTIONS
        Route::get('fuel-consumptions/list', [FuelConsumptionController::class, 'list']);
        Route::post('fuel-consumptions/store', [FuelConsumptionController::class, 'store']);
        Route::post('fuel-consumptions/update', [FuelConsumptionController::class, 'update']);
        Route::post('fuel-consumptions/delete', [FuelConsumptionController::class, 'destroy']);

        //VEHICLE RENTALS
        Route::get('vehicle-rentals/list', [VehicleRentalController::class, 'list']);
        Route::post('vehicle-rentals/store', [VehicleRentalController::class, 'store']);
        Route::post('vehicle-rentals/update', [VehicleRentalController::class, 'update']);
        Route::post('vehicle-rentals/delete', [VehicleRentalController::class, 'destroy']);

        //PRODUCT RENTALS
        Route::get('product-rentals/list', [ProductRentalController::class, 'list']);
        Route::post('product-rentals/store', [ProductRentalController::class, 'store']);
        Route::post('product-rentals/delete', [ProductRentalController::class, 'destroy']);
        Route::post('product-rentals/list-by-employee', [ProductRentalController::class, 'list_by_employee_id']);

        // MAPS ROUTE
        Route::get('route-maps/list', [RouteMapController::class, 'list']);
        Route::post('route-maps/store', [RouteMapController::class, 'store']);
        Route::post('route-maps/delete', [RouteMapController::class, 'destroy']);

        // PROVIDERS
        Route::get('suppliers/list', [SupplierController::class, 'list']);
        Route::post('suppliers/store', [SupplierController::class, 'store']);
        Route::post('suppliers/update', [SupplierController::class, 'update']);
        Route::post('suppliers/delete', [SupplierController::class, 'destroy']);

        // REPORTS
        Route::get('reports/fuel-consumption-data', [ReportController::class, 'fuel_consumption_data']);
        Route::post('reports/fuel-consumption', [ReportController::class, 'fuel_consumption_report']);
        Route::post('reports/wanted-people-date-range', [ReportController::class, 'wanted_people_by_date_range']);
        Route::post('reports/wanted-person', [ReportController::class, 'wanted_person']);
        Route::post('reports/product-rental', [ReportController::class, 'product_rental']);


        Route::post('providers/delete', [ProviderController::class, 'destroy']);

        // WANTED PEOPLE
        Route::get('wanted-people/person/{dni}', [WantedPersonController::class, 'person_by_dni']);
        Route::get('wanted-people/list', [WantedPersonController::class, 'list']);
        Route::post('wanted-people/delete', [WantedPersonController::class, 'destroy']);

        // INCIDENTS
        Route::post('incidents/store', [IncidentController::class, 'store']);
        Route::post('incidents/update', [IncidentController::class, 'update']);
        Route::get('wanted-people/next-incident-code', [WantedPersonController::class, 'next_code_incident']);
        Route::get('incidents/list-by-wanted-person/{wanted_person_id}', [IncidentController::class, 'list_by_wanted_person']);

        // CONSTRUCTIONS
        Route::get('constructions/list', [ConstructionControlller::class, 'list']);
        Route::post('constructions/store', [ConstructionControlller::class, 'store']);
        Route::post('constructions/update', [ConstructionControlller::class, 'update']);
        Route::post('constructions/delete', [ConstructionControlller::class, 'destroy']);

        // DAILY TRASH ENTRIES
        Route::get('daily-trash-entries/list', [DailyTrashEntryController::class, 'list']);
        Route::post('daily-trash-entries/store', [DailyTrashEntryController::class, 'store']);
        Route::post('daily-trash-entries/update', [DailyTrashEntryController::class, 'update']);
        Route::post('daily-trash-entries/delete', [DailyTrashEntryController::class, 'destroy']);

        // #############################################
        // transfer ticket
        Route::get('transfer-tickets/list', [TransferTicketController::class, 'list']);
        Route::post('transfer-tickets/store', [TransferTicketController::class, 'store']);
        Route::post('transfer-tickets/update', [TransferTicketController::class, 'update']);
        Route::post('transfer-tickets/delete', [TransferTicketController::class, 'destroy']);


        // asset-assignments
        Route::get('asset-assignments/list', [AssignmentAssetController::class, 'list']);
        Route::post('asset-assignments/store', [AssignmentAssetController::class, 'store']);
        Route::post('asset-assignments/update', [AssignmentAssetController::class, 'update']);
        Route::post('asset-assignments/delete', [AssignmentAssetController::class, 'destroy']);

        // inventory(inventario fisico)
        Route::get('inventories/list', [InventoryController::class, 'list']);
        Route::post('inventories/store', [InventoryController::class, 'store']);
        Route::post('inventories/update', [InventoryController::class, 'update']);
        Route::post('inventories/delete', [InventoryController::class, 'delete']);


        // inventory-tasks
        Route::get('inventory-tasks/list/by-inventory/{inventory_id}', [InventoryTaskController::class, 'list_by_inventory']);
        Route::post('inventory-tasks/store', [InventoryTaskController::class, 'store']);
        Route::post('inventory-tasks/update', [InventoryTaskController::class, 'update']);
        Route::post('inventory-tasks/delete', [InventoryTaskController::class, 'delete']);

    }
);
