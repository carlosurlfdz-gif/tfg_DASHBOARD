<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bloqueo_ip', function (Blueprint $table) {
            $table->id('id_bloqueo');

            $table->unsignedBigInteger('id_alerta')->nullable();
            $table->unsignedInteger('id_usuario')->nullable();

            $table->string('ip_bloqueada', 45);

            $table->string('motivo', 255)->nullable();

            $table->enum('origen_bloqueo', ['manual', 'automatico'])->default('manual');

            $table->enum('estado', ['activo', 'retirado'])->default('activo');

            $table->dateTime('fecha_bloqueo')->useCurrent();
            $table->dateTime('fecha_desbloqueo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bloqueo_ip');
    }
};
