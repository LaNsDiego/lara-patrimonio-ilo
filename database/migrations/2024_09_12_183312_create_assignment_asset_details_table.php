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
        Schema::create('assignment_asset_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_asset_id')->comment('Identificador de la asignación del bien');
            $table->unsignedBigInteger('asset_id')->comment('Identificador del bien');

            $table->foreign('assignment_asset_id')->references('id')->on('assignment_assets');
            $table->foreign('asset_id')->references('id')->on('products');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_asset_details');
    }
};
