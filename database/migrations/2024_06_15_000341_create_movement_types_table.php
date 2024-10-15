<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movement_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nombre del tipo de movimiento');
            $table->string('description')->nullable()->comment('Descripción del tipo de movimiento');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Tabla de tipos de movimientos');
        });
        $this->seedMovementTypes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_types');
    }

    private function seedMovementTypes()
    {
        DB::table('movement_types')->insert([
            [
                'name' => 'ENTRADA',
                'description' => 'ENTRADA DE BIENES EN EL INVENTARIO',
            ],
            [
                'name' => 'SALIDA',
                'description' => 'SALIDA DE BIENES DEL INVENTARIO',
            ],
            [
                'name' => 'SALIDA PAPELETA',
                'description' => 'PAPELETA DE SALIDA DE BIENES',
            ],
            [
                'name' => 'PRESTAMO',
                'description' => 'PARTE DIARIO DE PRESTAMO DE BIENES',
            ],
        ]);
    }
};
