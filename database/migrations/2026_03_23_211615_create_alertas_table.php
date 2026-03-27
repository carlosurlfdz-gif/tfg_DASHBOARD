<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id('id_alerta');

            $table->dateTime('timestamp_evento', 6);

            $table->unsignedBigInteger('flow_id')->nullable();

            $table->string('src_ip', 45);
            $table->unsignedInteger('src_port')->nullable();

            $table->string('dest_ip', 45);
            $table->unsignedInteger('dest_port')->nullable();

            $table->string('proto', 10)->nullable();
            $table->string('app_proto', 20)->nullable();

            $table->enum('direction', ['to_server', 'to_client'])->nullable();

            $table->string('accion', 20)->nullable();

            $table->unsignedInteger('firma_id')->nullable();
            $table->string('firma', 255);

            $table->string('categoria', 255)->nullable();

            $table->unsignedTinyInteger('severidad');

            $table->enum('estado', [
                'nueva',
                'en_revision',
                'falsa_positiva',
                'resuelta'
            ])->default('nueva');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
