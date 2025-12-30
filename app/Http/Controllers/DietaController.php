<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class DietaController extends Controller
{
    public function index()
    {
        return Inertia::render('Chatbot');
    }

    /**
     * Verifica la conexión a la base de datos y guarda el estado en Redis.
     */
    public function verificarEstado()
    {
        try {
            // Obtenemos información de la base de datos
            $driver = DB::connection()->getDriverName(); // Debería ser 'sqlite'
            $databaseName = DB::connection()->getDatabaseName();
            $totalAlimentos = Alimento::count();

            $statusData = [
                'driver' => $driver,
                'db_path' => $databaseName,
                'total_alimentos' => $totalAlimentos,
                'verified_at' => now()->toDateTimeString(),
            ];

            // Guardamos la verificación en Redis (clave válida por 10 minutos)
            Redis::setex('app_db_status', 600, json_encode($statusData));

            return response()->json([
                'success' => true,
                'data' => $statusData,
                'source' => 'Fresh Check'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generar(Request $request)
    {
        try {
            $request->validate([
                'calorias' => 'required|integer|min:500|max:5000',
            ]);

            $metaCalorica = $request->calorias;
            $alimentosRaw = Alimento::all();
            
            if ($alimentosRaw->isEmpty()) {
                return response()->json([
                    'error' => 'La base de conocimiento está vacía. Ejecuta: php artisan db:seed --class=AlimentoSeeder'
                ], 400);
            }

            $db = [
                'desayuno' => $alimentosRaw->where('tipo', 'desayuno')->values(),
                'comida'   => $alimentosRaw->where('tipo', 'comida')->values(),
                'cena'     => $alimentosRaw->where('tipo', 'cena')->values(),
                'snack'    => $alimentosRaw->where('tipo', 'snack')->values(),
            ];

            // ... (resto del código del algoritmo genético igual que antes)
            $poblacionTamano = 40;
            $generaciones = 100;
            $tasaMutacion = 0.2;

            $poblacion = [];
            for ($i = 0; $i < $poblacionTamano; $i++) {
                $poblacion[] = $this->crearIndividuo($db);
            }

            $g = 0;
            for (; $g < $generaciones; $g++) {
                usort($poblacion, function($a, $b) use ($metaCalorica) {
                    return $this->calcularFitness($a, $metaCalorica) <=> $this->calcularFitness($b, $metaCalorica);
                });
                if ($this->calcularFitness($poblacion[0], $metaCalorica) < 5) break;
                $nuevaPoblacion = array_slice($poblacion, 0, 8);
                while (count($nuevaPoblacion) < $poblacionTamano) {
                    $p1 = $nuevaPoblacion[rand(0, count($nuevaPoblacion) - 1)];
                    $p2 = $nuevaPoblacion[rand(0, count($nuevaPoblacion) - 1)];
                    $hijo = $this->crossover($p1, $p2);
                    $hijo = $this->mutar($hijo, $db, $tasaMutacion);
                    $nuevaPoblacion[] = $hijo;
                }
                $poblacion = $nuevaPoblacion;
            }

            return response()->json([
                'dieta' => $poblacion[0],
                'analisis' => [
                    'total_calorias' => $this->sumarCalorias($poblacion[0]),
                    'error' => $this->calcularFitness($poblacion[0], $metaCalorica),
                    'generaciones_procesadas' => $g
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error en el Agente: " . $e->getMessage());
            return response()->json(['error' => 'Error interno en el motor evolutivo.'], 500);
        }
    }

    private function crearIndividuo($db) {
        return [
            'desayuno' => $db['desayuno']->random(),
            'comida'   => $db['comida']->random(),
            'cena'     => $db['cena']->random(),
            'snack1'   => $db['snack']->random(),
            'snack2'   => $db['snack']->random(),
        ];
    }

    private function calcularFitness($ind, $meta) {
        return abs($meta - $this->sumarCalorias($ind));
    }

    private function sumarCalorias($ind) {
        return collect($ind)->sum('calorias');
    }

    private function crossover($p1, $p2) {
        return [
            'desayuno' => rand(0, 1) ? $p1['desayuno'] : $p2['desayuno'],
            'comida'   => rand(0, 1) ? $p1['comida'] : $p2['comida'],
            'cena'     => rand(0, 1) ? $p1['cena'] : $p2['cena'],
            'snack1'   => rand(0, 1) ? $p1['snack1'] : $p2['snack1'],
            'snack2'   => rand(0, 1) ? $p1['snack2'] : $p2['snack2'],
        ];
    }

    private function mutar($ind, $db, $tasa) {
        if (rand(0, 100) / 100 < $tasa) {
            $comidas = ['desayuno', 'comida', 'cena', 'snack1', 'snack2'];
            $clave = $comidas[array_rand($comidas)];
            $tipoBusqueda = str_contains($clave, 'snack') ? 'snack' : $clave;
            $ind[$clave] = $db[$tipoBusqueda]->random();
        }
        return $ind;
    }
}