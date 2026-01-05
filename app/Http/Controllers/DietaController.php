<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class DietaController extends Controller
{
    /**
     * Muestra la vista del Chatbot.
     */
    public function index()
    {
        return Inertia::render('Chatbot');
    }

    /**
     * Motor de Inteligencia Artificial (Algoritmo Genético).
     * Traducido y mejorado desde la versión de Python.
     */
    public function generar(Request $request)
    {
        try {
            // 1. Validación de los datos del perfil del usuario
            $request->validate([
                'edad' => 'required|integer|min:15|max:100',
                'genero' => 'required|string|in:hombre,mujer',
                'peso' => 'required|numeric|min:30|max:250',
                'altura' => 'required|numeric|min:100|max:250',
                'objetivo' => 'required|string|in:deficit,volumen,mantenimiento',
                'numero_comidas' => 'required|integer|min:3|max:6',
                'restricciones' => 'nullable|array'
            ]);

            // 2. Cálculo de Requerimiento Calórico (Fórmula de Harris-Benedict)
            $metaCalorica = $this->calcularRequerimiento(
                $request->edad, 
                $request->genero, 
                $request->peso, 
                $request->altura, 
                $request->objetivo
            );

            // 3. Carga de Base de Conocimientos (Dataset)
            $alimentosRaw = Alimento::all();
            if ($alimentosRaw->isEmpty()) {
                return response()->json([
                    'error' => 'La base de conocimiento está vacía. Ejecuta el seeder primero.'
                ], 400);
            }

            // Filtrado por tipo para facilitar la creación de individuos
            $db = [
                'desayuno' => $alimentosRaw->where('tipo', 'desayuno')->values(),
                'comida'   => $alimentosRaw->where('tipo', 'comida')->values(),
                'cena'     => $alimentosRaw->where('tipo', 'cena')->values(),
                'snack'    => $alimentosRaw->where('tipo', 'snack')->values(),
            ];

            // 4. Estrategia Adaptativa (Configuración del Agente)
            $n_comidas = $request->numero_comidas;
            // Si hay pocas comidas, es más difícil ajustar calorías, por lo que aumentamos la búsqueda
            $poblacionTamano = ($n_comidas <= 3) ? 150 : 60; 
            $generacionesMax = ($n_comidas <= 3) ? 200 : 100;
            $tasaMutacion = 0.15;

            // 5. Inicialización de Población
            $poblacion = [];
            for ($i = 0; $i < $poblacionTamano; $i++) {
                $poblacion[] = $this->crearIndividuo($db, $n_comidas);
            }

            // 6. Bucle Evolutivo
            $g = 0;
            $mejorIndividuo = null;
            $mejorFitness = INF;

            for (; $g < $generacionesMax; $g++) {
                // Evaluar y ordenar por fitness (menor error es mejor)
                usort($poblacion, function($a, $b) use ($metaCalorica) {
                    return $this->calcularFitness($a, $metaCalorica) <=> $this->calcularFitness($b, $metaCalorica);
                });

                $actualMejor = $poblacion[0];
                $actualFitness = $this->calcularFitness($actualMejor, $metaCalorica);

                // Guardar el mejor histórico (Elitismo)
                if ($actualFitness < $mejorFitness) {
                    $mejorFitness = $actualFitness;
                    $mejorIndividuo = $actualMejor;
                }

                // Criterio de parada: error menor a 5 kcal
                if ($mejorFitness < 5) break;

                // Reproducción (Selección por Torneo simplificada + Elitismo)
                $nuevaPoblacion = array_slice($poblacion, 0, 10); // Los 10 mejores pasan directo
                
                while (count($nuevaPoblacion) < $poblacionTamano) {
                    // Selección
                    $p1 = $poblacion[rand(0, 15)]; 
                    $p2 = $poblacion[rand(0, 15)];
                    
                    // Crossover
                    $hijo = $this->crossover($p1, $p2);
                    
                    // Mutación
                    $hijo = $this->mutar($hijo, $db, $tasaMutacion);
                    
                    $nuevaPoblacion[] = $hijo;
                }
                $poblacion = $nuevaPoblacion;
            }

            // 7. Respuesta al Cliente
            return response()->json([
                'dieta' => $mejorIndividuo,
                'meta_calculada' => $metaCalorica,
                'analisis' => [
                    'total_calorias' => $this->sumarCalorias($mejorIndividuo),
                    'error' => round($mejorFitness, 2),
                    'generaciones_procesadas' => $g,
                    'poblacion_utilizada' => $poblacionTamano
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error en Agente Nutricional: " . $e->getMessage());
            return response()->json(['error' => 'Error interno en el motor de IA.'], 500);
        }
    }

    /**
     * Calcula TMB y gasto total usando Harris-Benedict.
     */
    private function calcularRequerimiento($edad, $genero, $peso, $altura, $objetivo) {
        if ($genero === 'hombre') {
            $tmb = 88.36 + (13.4 * $peso) + (4.8 * $altura) - (5.7 * $edad);
        } else {
            $tmb = 447.6 + (9.2 * $peso) + (3.1 * $altura) - (4.3 * $edad);
        }

        $gastoTotal = $tmb * 1.375; // Factor de actividad moderada

        if ($objetivo === 'deficit') return intval($gastoTotal * 0.85);
        if ($objetivo === 'volumen') return intval($gastoTotal * 1.15);
        
        return intval($gastoTotal);
    }

    /**
     * Función de Fitness con Penalización por Repetición.
     */
    private function calcularFitness($individuo, $meta) {
        $totalCal = $this->sumarCalorias($individuo);
        $error = abs($meta - $totalCal);
        
        // --- Penalización por Repetición (Mudar lógica de Python) ---
        $nombres = array_map(fn($item) => $item['nombre'], $individuo);
        $nUnicos = count(array_unique($nombres));
        $totalItems = count($individuo);

        if ($nUnicos < $totalItems) {
            // Castigo drástico por cada alimento repetido
            $error += 400 * ($totalItems - $nUnicos);
        }
        
        return $error;
    }

    private function crearIndividuo($db, $n_comidas) {
        $plan = [];
        $plan['desayuno'] = $db['desayuno']->random();
        $plan['comida']   = $db['comida']->random();
        $plan['cena']     = $db['cena']->random();

        // Rellenar snacks adicionales dinámicamente
        for ($i = 1; $i <= ($n_comidas - 3); $i++) {
            $plan['snack_' . $i] = $db['snack']->random();
        }
        return $plan;
    }

    private function sumarCalorias($individuo) {
        return array_sum(array_map(fn($item) => $item['calorias'], $individuo));
    }

    private function crossover($p1, $p2) {
        $hijo = [];
        foreach ($p1 as $key => $value) {
            $hijo[$key] = (rand(0, 1)) ? $p1[$key] : $p2[$key];
        }
        return $hijo;
    }

    private function mutar($individuo, $db, $tasa) {
        if ((rand(0, 100) / 100) < $tasa) {
            $llaves = array_keys($individuo);
            $targetKey = $llaves[array_rand($llaves)];
            $tipo = str_contains($targetKey, 'snack') ? 'snack' : $targetKey;
            $individuo[$targetKey] = $db[$tipo]->random();
        }
        return $individuo;
    }
}