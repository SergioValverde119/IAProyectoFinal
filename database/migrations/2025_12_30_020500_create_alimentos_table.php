<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alimentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['desayuno', 'comida', 'cena', 'snack']);
            $table->integer('calorias');
            $table->decimal('proteinas', 8, 2)->default(0);
            $table->decimal('carbohidratos', 8, 2)->default(0);
            $table->decimal('grasas', 8, 2)->default(0);
            $table->string('porcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alimentos');
    }
};