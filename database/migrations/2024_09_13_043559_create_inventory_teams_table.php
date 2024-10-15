<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id')->comment('Identificador del inventario');
            $table->string('name')->comment('Nombre del equipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_teams');
    }
};
