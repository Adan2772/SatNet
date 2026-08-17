<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enlaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suscripcion_id')->unique()->constrained('suscripciones')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('ip_asignada')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('tipo_antena')->nullable();
            $table->string('nodo')->nullable();
            $table->string('numero_serie')->nullable();
            $table->date('fecha_instalacion');
            $table->string('estado')->default('activo');
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enlaces');
    }
};
