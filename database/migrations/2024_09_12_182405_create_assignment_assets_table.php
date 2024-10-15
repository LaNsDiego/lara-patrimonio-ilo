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
        Schema::create('assignment_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_responsible_id')->comment('Identificador del responsable anterior');
            $table->unsignedBigInteger('new_responsible_id')->comment('Identificador del nuevo responsable');

            $table->foreign('old_responsible_id')->references('id')->on('employees');
            $table->foreign('new_responsible_id')->references('id')->on('employees');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_assets');
    }
};
