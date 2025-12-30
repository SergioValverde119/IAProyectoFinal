<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\DietaController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::get('/chatbot', [DietaController::class, 'index'])->middleware(['auth', 'verified'])->name('chatbot');
Route::post('/generar-dieta', [DietaController::class, 'generar'])->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
