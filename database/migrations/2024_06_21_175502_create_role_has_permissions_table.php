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
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id')->comment('permiso asignado al rol');
            $table->unsignedBigInteger('role_id')->comment('rol al que se le asigna el permiso');
            $table->foreign('permission_id')->references('id')->on('permission_systems');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->boolean('has_access')->comment('permiso activo o inactivo');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
    }
};
