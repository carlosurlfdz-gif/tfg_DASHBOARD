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
        Schema::create('log_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users');
            $table->string('username_intentado')->nullable();
            $table->string('ip_origen');
            $table->text('user_agent')->nullable();
            $table->boolean('resultado')->comment('0 fallo, 1 éxito');
            $table->string('detalle')->nullable();
            $table->dateTime('fecha_acceso')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_acceso');
    }
};
