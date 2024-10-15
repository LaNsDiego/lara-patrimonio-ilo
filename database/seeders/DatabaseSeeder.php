<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Brand;
use App\Models\Campaing;
use App\Models\Construction;
use App\Models\DetailProductRental;
use App\Models\Employee;
use App\Models\Establishment;
use App\Models\EstablishmentLocation;
use App\Models\EstablishmentType;
use App\Models\JobTitle;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\ModuleGroup;
use App\Models\SystemModule;
use App\Models\PermissionSystem;
use App\Models\Product;
use App\Models\ProductRental;
use App\Models\ProductType;
use App\Models\Programation;
use App\Models\ProgramationSchedule;
use App\Models\RoadWay;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\Tag;
use App\Models\User;
use App\Models\WorkActivity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private const ADMIN_NAME_ROLE = 'ADMINISTRADOR';
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Tag::insert([
            ['name' => 'VEHICULOS'], //INDISPENSABLE
            ['name' => 'CONTENEDORES'], //INDISPENSABLE

            ['name' => 'MAQUINARIAS'],
            ['name' => 'HERRAMIENTAS'],
            ['name' => 'EQUIPOS'],
            ['name' => 'REPUESTOS'],
        ]);

        JobTitle::insert([
            ['name' => 'Gerente'],
            ['name' => 'Sub Gerente'],
            ['name' => 'Supervisor de Seguridad'],
        ]);

        $job_titles = JobTitle::pluck('id');

        DB::table('suppliers')->insert([
            [
                'type' => 'EMPRESA',
                'name' => 'Gasoline Provider Inc.',
                'document_type' => 'RUC',
                'document_number' => '1234567890',
                'phone_number' => '123-456-7890',
                'email' => 'contact@gasolineprovider.com',
            ],
            [
                'type' => 'EMPRESA',
                'name' => 'Heavy Machinery Ltd.',
                'document_type' => 'RUC',
                'document_number' => '0987654321',
                'phone_number' => '098-765-4321',
                'email' => 'info@heavymachinery.com',
            ],
            [
                'type' => 'EMPRESA',
                'name' => 'Auto Parts Co.',
                'document_type' => 'RUC',
                'document_number' => '1122334455',
                'phone_number' => '111-222-3333',
                'email' => 'support@autoparts.com',
            ],
        ]);

        DB::table('measurement_units')->insert([
            [
                'acronym' => 'UND',
                'name' => 'UNIDADES',
            ],
            [
                'acronym' => 'L',
                'name' => 'LITROS',
            ],
            [
                'acronym' => 'KG',
                'name' => 'KILOGRAMOS',
            ],
            [
                'acronym' => 'M',
                'name' => 'METROS',
            ],
            [
                'acronym' => 'GAL',
                'name' => 'GALONES',
            ],

        ]);

        LocationType::insert([
            ['name' => 'INSTITUCIONES'],
            ['name' => 'OBRAS'],
            ['name' => 'PLAZAS'],
            ['name' => 'PARQUES'],
            ['name' => 'JARDINES'],
            ['name' => 'COLEGIOS'],
            ['name' => 'FABRICAS'],
            ['name' => 'ALMACENES'],
            ['name' => 'PLANTAS'],
            ['name' => 'PROYECTOS'],
            ['name' => 'BIBLIOTECAS'],
        ]);

        Brand::insert([
            ['name' => 'CATARPILLAR'],
            ['name' => 'VOLVO'],
            ['name' => 'SCANIA'],
            ['name' => 'DAF'],
            ['name' => 'IVECO'],
            ['name' => 'NISAN'],
            ['name' => 'TOYOTA'],
            ['name' => 'HONDA'],
            ['name' => 'KIA'],
            ['name' => 'HYUNDAI'],
            ['name' => 'FORD'],
            ['name' => 'CHEVROLET'],
            ['name' => 'SUZUKI'],
            ['name' => 'MAZDA'],
            ['name' => 'VOLKSWAGEN'],
            ['name' => 'ASTON MARTIN'],

        ]);

        EstablishmentType::insert([
            ['name' => 'AREA'],
            ['name' => 'ALMACEN'],
            ['name' => 'PLANTA'],
            ['name' => 'OBRA'],
            ['name' => 'PROYECTOS'],
        ]);



        Location::insert([
            [
                'name' => 'SEDE PRINCIPAL',
                'description' => 'Sede principal de la municipalidad.',
                'acronym' => 'SDPR',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.24512848303736,-18.003077989774297]},"properties":{}}',
                'location_type_id' => 1
            ],
            [
                'name' => 'Parque Residencial',
                'description' => 'Un gran parque en el área residencial.',
                'acronym' => 'PQRD',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.24615867322592,-18.003282432555537]},"properties":{}}',
                'location_type_id' => 5
            ],
            [
                'name' => 'Complejo Industrial',
                'description' => 'Una fábrica importante en la zona industrial.',
                'acronym' => 'CPIT',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.24941908928002,-18.00976939403901]},"properties":{}}',
                'location_type_id' => 8
            ],
            [
                'name' => 'Antiguo Ayuntamiento',
                'description' => 'Un edificio histórico en el distrito histórico.',
                'acronym' => 'AGAT',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.25485316175428,-18.00431438001631]},"properties":{}}',
                'location_type_id' => 1
            ],
            [
                'name' => 'Biblioteca Universitaria',
                'description' => 'La biblioteca principal de la universidad.',
                'acronym' => 'BTUT',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.25619247240604,-18.005391970093584]},"properties":{}}',
                'location_type_id' => 11
            ],
            [
                'name' => 'Estadio cachipucara',
                'description' => 'plaza de tierra estadi cachipucara.',
                'acronym' => 'BTUT',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.25619247240604,-18.005391970093584]},"properties":{}}',
                'location_type_id' => 3
            ],
            [
                'name' => 'Terreno de proyecto de construccion',
                'description' => 'Un terreno en el que se construirá un nuevo edificio.',
                'acronym' => 'BTUT',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.24615867322592,-18.003282432555537]},"properties":{}}',
                'location_type_id' => 2
            ],
            [
                'name' => 'Terreno de proyecto de construccion 2',
                'description' => 'Un terreno en el que se construirá un nuevo edificio.',
                'acronym' => 'BTUT',
                'geojson' => '{"type":"Feature","geometry":{"type":"Point","coordinates":[-70.25619247240604,-18.005391970093584]},"properties":{}}',
                'location_type_id' => 2

            ]

        ]);



        $establishment_target = Establishment::create([
            'name' => Establishment::NAME_ESTABLISHMENT_TARGET,
            'acronym' => 'LPAV',
            'code' => 'LPAV001',
            'establishment_type_id' => 1,
            'location_id' => 1,
            'responsible_id' => 3,
        ]);


        // Crear áreas principales
        $municipalidad = Establishment::create([
            'name' => 'ALCALDIA',
            'acronym' => 'ALC',
            'code' => 'ALC01',
            'establishment_type_id' => 1,
            'location_id' => 1,
            'responsible_id' => 2,
        ]);

        // Crear gerencias dependientes de la Municipalidad
        $gerenciaGeneral = Establishment::create([
            'name' => 'GERENCIA GENERAL',
            'acronym' => 'GG',
            'code' => 'GG001',
            'establishment_type_id' => 1,
            'location_id' => 1,
            'responsible_id' => 2,

        ]);

        $gerenciaFinanciera = Establishment::create([
            'name' => 'GERENCIA FINANCIERA',
            'acronym' => 'GF',
            'code' => 'GF001',
            'establishment_type_id' => 1,
            'location_id' => 1,
            'responsible_id' => 3,

        ]);

        // Crear subgerencias dependientes de las Gerencias
        $subgerenciaAdministrativa = Establishment::create([
            'name' => 'SUBGERENCIA ADMINISTRATIVA',
            'acronym' => 'SA',
            'code' => 'SA001',
            'establishment_type_id' => 1,
            'location_id' => 1,
            'responsible_id' => 2,

        ]);

        $subgerenciaContable = Establishment::create([
            'name' => 'SUBGERENCIA CONTABLE',
            'acronym' => 'SC',
            'code' => 'SC001',
            'establishment_type_id' => 1,
            'location_id' => 1,
            'responsible_id' => 3,
        ]);

        $establish_location_1_target = EstablishmentLocation::create([
            'name' => 'ALMACEN PRINCIPAL',
            'code' => '44CP01',
            'establishment_id' => $establishment_target->id,
        ]);

        Employee::factory()->count(10)->create();



        $productTypeRecommendation = [
            [
                'name' => 'CARRO 4X4',
            ],
            [
                'name' => 'CAMIONETA',
            ],
            [
                'name' => 'TRACTOR',
            ],
            [
                'name' => 'MOTOCICLETA',
            ],
            [
                'name' => 'CAMION',
            ],
            [
                'name' => 'RETROEXCAVADORA',
            ],
            [
                'name' => 'MOTONIVELADORA',
            ],
            [
                'name' => 'COMPRESORA',
            ],
            [
                'name' => 'GENERADOR',
            ],
            [
                'name' => 'MOTOBOMBA',
            ],
            [
                'name' => 'CISTERNA',
            ],
            [
                'name' => 'TANQUE DE COMBUSTIBLE',
            ],
            [
                'name' => 'BOMBA DE AGUA',
            ],
            [
                'name' => 'COMPRESORA DE AIRE',
            ],
            [
                'name' => 'MOTOCOMPRESORA',
            ],
            [
                'name' => 'MOTOCARGADOR',
            ],
            [
                'name' => 'MOTOCULTIVADOR',
            ],
        ];
        DB::table('product_type_recommendations')->insert($productTypeRecommendation);

        $productTypes = [
            [
                'name' => 'CAMIONETA',
                'model' => 'HILUX',
                'tags' => ['VEHICULOS'],
                'brand_id' => 1,
                'measurement_unit_id' => 1,
                'description' => 'CAMIONETA HILUX 4X4 - UNIDADES',
            ],
            [
                'name' => 'TRACTOR',
                'model' => 'D8T',
                'tags' => ['VEHICULOS'],
                'brand_id' => 1,
                'measurement_unit_id' => 1,
                'description' => 'TRACTOR CATARPILLAR D8T - UNIDADES',
            ],
            [
                'name' => 'TANQUE DE COMBUSTIBLE',
                'model' => 'TANQ-001',
                'tags' => ['MAQUINARIAS'],
                'brand_id' => 3,
                'measurement_unit_id' => 2,
                'description' => 'TANQUE DE COMBUSTIBLE - LITROS',
            ]


        ];
        foreach ($productTypes as $type) {
            ProductType::create($type);
        }

        $products = [
            [
                'barcode' => '123456789',
                'image' => '',
                'serial_number' => 'ABC123',
                'product_type_id' => 1,
                'employee_id' => 1,
                'establishment_location_id' => $establish_location_1_target->id,
                'acquisition_cost' => 1500.00,
                'rental_price' => 100.00,
                'siga_code' => 'SIGA123',
                'accounting_account' => 'ACC123',
                'order_number' => 'ORD456',
                'pecosa_number' => 'PEC789',
                'dimensions' => '10x5x2',
                'license_plate' => 'ABC-123',
                'manufacture_year' => 2012,
                'color' => 'Red',
                'chassis' => 'CHS456',
                'engine' => 'ENG789',
                'historical_value' => 2000.00,
                'status' => 'NUEVO',
                'responsible_employee_id' => 1,
            ],
            [
                'barcode' => '345678901234',
                'image' => '',
                'serial_number' => 'SN345678',
                'product_type_id' => 2,
                'employee_id' => 2,
                'establishment_location_id' => $establish_location_1_target->id,
                'acquisition_cost' => 200.00,
                'rental_price' => 80.00,
                'license_plate' => '',
                'manufacture_year' => 2021,
                'siga_code' => 'XYZ789',
                'accounting_account' => '5678-9012-34',
                'order_number' => '',
                'pecosa_number' => '',
                'dimensions' => '',
                'color' => 'Verde',
                'chassis' => '',
                'engine' => '',
                'historical_value' => 250.00,
                'status' => 'ALQUILADO',
                'responsible_employee_id' => 3,

            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $employees = Employee::pluck('id');


        // MODULE GROUPS
        $grupo_home = ModuleGroup::create(['name' => 'Home']);
        $grupo_configuracion = ModuleGroup::create(['name' => 'Configuración']);
        $grupo_mantenimiento = ModuleGroup::create(['name' => 'Mantenimiento']);
        $grupo_gestion = ModuleGroup::create(['name' => 'Gestión']);
        $grupo_servicios = ModuleGroup::create(['name' => 'Servicios']);
        $grupo_reportes_estadisticos = ModuleGroup::create(['name' => 'Reportes Estadísticos']);


        // GENERATING MODULE SYSTEMS
        $modulos = [
            // Home
            ['name' => 'Reporte estadistico', 'description' => 'Módulo de administración de dashboard', 'module_group_id' => $grupo_home->id],

            // Configuración
            ['name' => 'Roles y permisos', 'description' => 'Módulo de administración de permisos', 'module_group_id' => $grupo_configuracion->id],
            ['name' => 'Usuarios', 'description' => 'Módulo de administración de usuarios', 'module_group_id' => $grupo_configuracion->id],

            // Mantenimiento
            ['name' => 'Cargo profesional', 'description' => 'Módulo de administración de títulos de trabajo', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Actividades laborales', 'description' => 'Módulo de administración de actividades laborales', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Zonas por sector', 'description' => 'Módulo de administración de sectores', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Caminos', 'description' => 'Módulo de administración de sectores', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Mapa de recorrido', 'description' => 'Módulo de administración de caminos', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Ubicaciones', 'description' => 'Módulo de administración de ubicaciones', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Centro de costo', 'description' => 'Módulo de administración de establecimientos', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Personal', 'description' => 'Módulo de administración de empleados', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Proveedores', 'description' => 'Módulo de administración de proveedores', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Tipo de productos', 'description' => 'Módulo de administración de tipos de productos', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Productos', 'description' => 'Módulo de administración de productos', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Vehiculos', 'description' => 'Módulo de administración de productos', 'module_group_id' => $grupo_mantenimiento->id],
            ['name' => 'Kardex', 'description' => 'Módulo de administración de productos', 'module_group_id' => $grupo_mantenimiento->id],

            // Servicios
            ['name' => 'Programacion por turnos', 'description' => 'Módulo de administración de intervenciones mecánicas', 'module_group_id' => $grupo_servicios->id],
            ['name' => 'Consumo combustible', 'description' => 'Módulo de administración de intervenciones mecánicas', 'module_group_id' => $grupo_servicios->id],
            ['name' => 'Intervenciones y ocurrencias', 'description' => 'Módulo de administración de entrada diaria botadero', 'module_group_id' => $grupo_servicios->id],


            // Reportes Detallados
            ['name' => 'Reporte de Actividades', 'description' => 'Módulo de administración de reporte de actividades', 'module_group_id' => $grupo_reportes_estadisticos->id],
            ['name' => 'Reporte de Intervenciones y ocurrencias', 'description' => 'Módulo de administración de reporte de intervenciones', 'module_group_id' => $grupo_reportes_estadisticos->id],
            ['name' => 'Reporte de Consumo de combustible', 'description' => 'Módulo de administración de reporte de intervenciones', 'module_group_id' => $grupo_reportes_estadisticos->id],

        ];

        // Create modules and permissions
        $permissions = [];
        foreach ($modulos as $modulo) {
            $createdModule = SystemModule::create($modulo);
            $permission = PermissionSystem::create([
                'action' => "$createdModule->name-ver",
                'system_module_id' => $createdModule->id,
            ]);
            $permissions[] = $permission;
        }

        // Create admin role
        $adminRole =  Role::insert([
            ['name' => DatabaseSeeder::ADMIN_NAME_ROLE],
        ]);

        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123123123'),
            'role_id' => 1
        ]);

        // Assign all permissions to admin role
        foreach ($permissions as $permission) {
            RoleHasPermission::create([
                'permission_id' => $permission->id,
                'role_id' => 1,
                'has_access' => true,
            ]);
        }


        DB::table('kardex')->insert([
            [
                'date' => '2024-01-01',
                'product_type_id' => 1,
                'quantity' => 100,
                'unit_cost' => 50,
                'total_cost' => 5000,
                'establishment_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'date' => '2024-01-02',
                'product_type_id' => 2,
                'quantity' => 30,
                'unit_cost' => 30,
                'total_cost' => 900,
                'establishment_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('detail_kardex')->insert([
            [
                'kardex_id' => 1,
                'establishment_id' => 1,
                'product_id' => 1,
                'quantity' => 50,
                'unit_price' => 50,
                'total_price' => 2500,
                'responsible_employee_id' => 1,
                'movement_type_id' => 1,
                'comment' => 'Initial stock',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kardex_id' => 1,
                'establishment_id' => 1,
                'product_id' => 1,
                'quantity' => 50,
                'unit_price' => 50,
                'total_price' => 2500,
                'responsible_employee_id' => 1,
                'movement_type_id' => 1,
                'comment' => 'Initial stock',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kardex_id' => 2,
                'establishment_id' => 2,
                'product_id' => 2,
                'quantity' => 30,
                'unit_price' => 30,
                'total_price' => 900,
                'responsible_employee_id' => 3,
                'movement_type_id' => 1,
                'comment' => 'Initial stock',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);


    }

}
