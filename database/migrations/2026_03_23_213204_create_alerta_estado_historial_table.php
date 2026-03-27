<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alerta_estado_historial', function (Blueprint $table) {
            $table->id('id_historial');

            $table->unsignedBigInteger('id_alerta');
            $table->unsignedInteger('id_usuario')->nullable();

            $table->enum('estado_anterior', [
                'nueva',
                'en_revision',
                'falsa_positiva',
                'resuelta'
            ])->nullable();

            $table->enum('estado_nuevo', [
                'nueva',
                'en_revision',
                'falsa_positiva',
                'resuelta'
            ]);

            $table->string('comentario', 255)->nullable();

            $table->dateTime('fecha_cambio')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerta_estado_historial');
    }
};
