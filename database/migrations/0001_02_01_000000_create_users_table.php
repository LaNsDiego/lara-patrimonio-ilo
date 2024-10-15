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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('Identificador único del usuario.');
            $table->string('name')->comment('Nombre del usuario.');
            $table->string('email')->unique()->comment('Correo electrónico del usuario.');
            $table->timestamp('email_verified_at')->nullable()->comment('Fecha de verificación del correo electrónico.');
            $table->string('password')->comment('Contraseña del usuario.');
            $table->rememberToken()->comment('Token de recordar sesión.');
            $table->unsignedBigInteger('role_id')->comment('Identificador del rol del usuario.');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->comment('Relación con el rol del usuario.');
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->comment('Esta tabla almacena los usuarios del sistema.');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary()->comment('Correo electrónico del usuario.');
            $table->string('token')->comment('Token de restablecimiento de contraseña.');
            $table->timestamp('created_at')->nullable();
            $table->comment('Esta tabla almacena los tokens de restablecimiento de contraseña.');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary()->comment('Identificador de la sesión.');
            $table->foreignId('user_id')->nullable()->index()->comment('Identificador del usuario.');
            $table->string('ip_address', 45)->nullable()->comment('Dirección IP del usuario.');
            $table->text('user_agent')->nullable()->comment('Agente de usuario del usuario.');
            $table->longText('payload')->comment('Carga de la sesión.');
            $table->integer('last_activity')->index()->comment('Última actividad del usuario.');
            $table->comment('Esta tabla almacena las sesiones de los usuarios.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
