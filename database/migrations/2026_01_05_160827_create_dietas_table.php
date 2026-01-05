<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dietas', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->string('nombre'); // Ej: "Dieta para 70kg"
            $blueprint->json('input_usuario'); // Edad, peso, altura, etc.
            $blueprint->json('resultado_ia'); // El menú generado
            $blueprint->json('analisis'); // Calorías totales, error, etc.
            $blueprint->integer('meta_calorica');
            $blueprint->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('dietas'); }
};
