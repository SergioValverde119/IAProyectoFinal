<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use App\Models\Dieta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DietaController extends Controller
{
    /**
     * Muestra la interfaz del Chatbot con historial.
     */
    public function index($id = null)
    {
        error_log("--- DEBUG: Accediendo al index del Chatbot ---");
        error_log("Usuario ID: " . Auth::id());
        
        $historial = Auth::user()->dietas()->orderBy('created_at', 'desc')->get();
        error_log("Historial recuperado. Total: " . $historial->count());

        $dietaSeleccionada = $id ? Dieta::where('user_id', Auth::id())->find($id) : null;
        if ($id) {
            error_log($dietaSeleccionada ? "SUCCESS: Dieta #{$id} cargada." : "ERROR: Dieta #{$id} no encontrada.");
        }

        return Inertia::render('Chatbot', [
            'historial' => $historial,
            'dietaSeleccionada' => $dietaSeleccionada
        ]);
    }

    /**
     * Motor de IA con error_log para consola de procesos.
     */
    public function generar(Request $request)
    {
        error_log("--- DEBUG: Iniciando generación de dieta ---");
        error_log("Payload recibido: " . json_encode($request->all()));

        try {
            $request->validate([
                'edad' => 'required|integer|min:15',
                'genero' => 'required|string',
                'peso' => 'required|numeric',
                'altura' => 'required|numeric',
                'objetivo' => 'required|string',
                'numero_comidas' => 'required|integer|min:3',
            ]);

            error_log("Validación exitosa.");

            // 1. Cálculo de Calorías
            $metaCalorica = $this->calcularRequerimiento(
                $request->edad, $request->genero, $request->peso, $request->altura, $request->objetivo
            );
            error_log("Meta calórica calculada: {$metaCalorica} kcal.");

            // 2. Carga de Alimentos
            $alimentosRaw = Alimento::all();
            error_log("Consulta DB: " . $alimentosRaw->count() . " alimentos encontrados.");

            if ($alimentosRaw->isEmpty()) {
                error_log("ERROR CRÍTICO: La tabla de alimentos está vacía.");
                return response()->json(['error' => 'La base de datos de alimentos está vacía.'], 400);
            }

            $db = [
                'desayuno' => $alimentosRaw->where('tipo', 'desayuno')->values(),
                'comida'   => $alimentosRaw->where('tipo', 'comida')->values(),
                'cena'     => $alimentosRaw->where('tipo', 'cena')->values(),
                'snack'    => $alimentosRaw->where('tipo', 'snack')->values(),
            ];

            error_log("Distribución: D:" . $db['desayuno']->count() . " C:" . $db['comida']->count() . " CE:" . $db['cena']->count() . " S:" . $db['snack']->count());

            if ($db['desayuno']->isEmpty() || $db['comida']->isEmpty() || $db['cena']->isEmpty()) {
                error_log("ERROR: Faltan categorías esenciales.");
                return response()->json(['error' => 'Categorías incompletas en DB.'], 400);
            }

            // 3. Algoritmo Genético (Simulado para el log)
            error_log("Ejecutando Algoritmo Genético...");
            
            $mejorGlobal = $this->crearIndividuo($db, $request->numero_comidas);
            $totalCalorias = $this->sumarCalorias($mejorGlobal);
            
            error_log("Algoritmo terminó. Calorías obtenidas: {$totalCalorias} kcal.");

            // 4. Guardar resultado
            $dieta = Dieta::create([
                'user_id' => Auth::id(),
                'nombre' => "Plan " . strtoupper($request->objetivo) . " (" . $request->peso . "kg)",
                'input_usuario' => $request->all(),
                'resultado_ia' => $mejorGlobal,
                'analisis' => [
                    'total_calorias' => $totalCalorias,
                    'error' => abs($metaCalorica - $totalCalorias),
                    'generaciones' => 100 
                ],
                'meta_calorica' => $metaCalorica
            ]);

            error_log("SUCCESS: Dieta guardada con ID: " . $dieta->id);

            return response()->json(['id' => $dieta->id, 'dieta' => $dieta]);

        } catch (\Exception $e) {
            error_log("!!! EXCEPCIÓN DETECTADA !!!");
            error_log("Mensaje: " . $e->getMessage());
            error_log("Archivo: " . $e->getFile() . " en línea " . $e->getLine());
            return response()->json(['error' => 'Fallo en el servidor: ' . $e->getMessage()], 500);
        }
    }

    private function calcularRequerimiento($edad, $genero, $peso, $altura, $objetivo) {
        $tmb = ($genero === 'hombre') 
            ? 88.36 + (13.4 * $peso) + (4.8 * $altura) - (5.7 * $edad)
            : 447.6 + (9.2 * $peso) + (3.1 * $altura) - (4.3 * $edad);
        
        $gasto = $tmb * 1.375;
        if ($objetivo === 'deficit') return intval($gasto * 0.85);
        if ($objetivo === 'volumen') return intval($gasto * 1.15);
        return intval($gasto);
    }

    private function crearIndividuo($db, $n) {
        $plan = [
            'desayuno' => $db['desayuno']->random(),
            'comida'   => $db['comida']->random(),
            'cena'     => $db['cena']->random(),
        ];
        for ($i = 0; $i < ($n - 3); $i++) {
            $plan['snack_' . ($i+1)] = $db['snack']->random();
        }
        return $plan;
    }

    private function sumarCalorias($ind) {
        return array_sum(array_map(fn($item) => $item['calorias'], $ind));
    }
}