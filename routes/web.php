<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\DietaController;

/**
 * PÁGINA PRINCIPAL
 * Redirige al chatbot para que sea lo primero que vea el usuario.
 */
Route::get('/', [DietaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

/**
 * DASHBOARD Y OTROS
 */
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * MOTOR DE LA IA (CHATBOT) E HISTORIAL
 * Agrupamos bajo el prefijo 'chatbot' para que coincida con el frontend.
 */
Route::middleware(['auth', 'verified'])->prefix('chatbot')->group(function () {
    
    // 1. Ruta para generar la dieta (POST)
    // Debe ir ANTES de la ruta con parámetro {id?} para evitar conflictos
    Route::post('/generar-dieta', [DietaController::class, 'generar'])->name('chatbot.generar');

    // 2. Ruta para cargar el formulario o una dieta específica (GET)
    Route::get('/{id?}', [DietaController::class, 'index'])->name('chatbot');
    
    // 3. Rutas de gestión de historial
    Route::patch('/conversacion/{id}', [DietaController::class, 'rename'])->name('chatbot.rename');
    Route::delete('/conversacion/{id}', [DietaController::class, 'destroy'])->name('chatbot.destroy');
});

require __DIR__.'/settings.php';