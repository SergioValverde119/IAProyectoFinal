<?php

namespace Database\Seeders;

use App\Models\Alimento;
use Illuminate\Database\Seeder;

class AlimentoSeeder extends Seeder
{
    public function run(): void
    {
        $alimentos = [
            // DESAYUNOS
            ['nombre' => 'Huevos a la mexicana (2 pzas)', 'tipo' => 'desayuno', 'calorias' => 210, 'proteinas' => 13.0, 'carbohidratos' => 4.0, 'grasas' => 16.0, 'porcion' => '1 plato'],
            ['nombre' => 'Avena con leche y plátano', 'tipo' => 'desayuno', 'calorias' => 320, 'proteinas' => 12.0, 'carbohidratos' => 52.0, 'grasas' => 7.0, 'porcion' => '1 tazón'],
            ['nombre' => 'Yogurt natural con granola', 'tipo' => 'desayuno', 'calorias' => 250, 'proteinas' => 8.0, 'carbohidratos' => 35.0, 'grasas' => 9.0, 'porcion' => '200g'],
            ['nombre' => 'Pan tostado con aguacate', 'tipo' => 'desayuno', 'calorias' => 280, 'proteinas' => 6.0, 'carbohidratos' => 28.0, 'grasas' => 18.0, 'porcion' => '2 rebanadas'],
            ['nombre' => 'Omelette de claras y champiñones', 'tipo' => 'desayuno', 'calorias' => 150, 'proteinas' => 18.0, 'carbohidratos' => 2.0, 'grasas' => 8.0, 'porcion' => '1 plato'],
            ['nombre' => 'Batido de proteína y fresas', 'tipo' => 'desayuno', 'calorias' => 210, 'proteinas' => 25.0, 'carbohidratos' => 15.0, 'grasas' => 4.0, 'porcion' => '500ml'],
            ['nombre' => 'Chilaquiles sencillos (sin crema)', 'tipo' => 'desayuno', 'calorias' => 400, 'proteinas' => 10.0, 'carbohidratos' => 45.0, 'grasas' => 22.0, 'porcion' => '1 plato'],
            ['nombre' => 'Molletes con frijol y queso', 'tipo' => 'desayuno', 'calorias' => 350, 'proteinas' => 15.0, 'carbohidratos' => 40.0, 'grasas' => 14.0, 'porcion' => '2 piezas'],
            ['nombre' => 'Hotcakes de avena y clara de huevo', 'tipo' => 'desayuno', 'calorias' => 290, 'proteinas' => 14.0, 'carbohidratos' => 48.0, 'grasas' => 6.0, 'porcion' => '3 piezas'],
            ['nombre' => 'Enchiladas verdes de pollo (2)', 'tipo' => 'desayuno', 'calorias' => 380, 'proteinas' => 22.0, 'carbohidratos' => 35.0, 'grasas' => 16.0, 'porcion' => '2 piezas'],
            ['nombre' => 'Cereal integral con leche descremada', 'tipo' => 'desayuno', 'calorias' => 220, 'proteinas' => 9.0, 'carbohidratos' => 42.0, 'grasas' => 3.0, 'porcion' => '1 tazón'],
            ['nombre' => 'Quesadilla de flor de calabaza', 'tipo' => 'desayuno', 'calorias' => 180, 'proteinas' => 8.0, 'carbohidratos' => 20.0, 'grasas' => 9.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Tamal de pollo (pequeño)', 'tipo' => 'desayuno', 'calorias' => 350, 'proteinas' => 10.0, 'carbohidratos' => 45.0, 'grasas' => 18.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Fruta picada con queso cottage', 'tipo' => 'desayuno', 'calorias' => 190, 'proteinas' => 12.0, 'carbohidratos' => 25.0, 'grasas' => 4.0, 'porcion' => '250g'],
            ['nombre' => 'Tostadas de jamón de pavo (2)', 'tipo' => 'desayuno', 'calorias' => 240, 'proteinas' => 14.0, 'carbohidratos' => 26.0, 'grasas' => 8.0, 'porcion' => '2 piezas'],

            // COMIDAS
            ['nombre' => 'Pechuga de pollo a la plancha', 'tipo' => 'comida', 'calorias' => 165, 'proteinas' => 31.0, 'carbohidratos' => 0.0, 'grasas' => 3.6, 'porcion' => '150g'],
            ['nombre' => 'Filete de pescado al vapor', 'tipo' => 'comida', 'calorias' => 140, 'proteinas' => 25.0, 'carbohidratos' => 0.0, 'grasas' => 4.0, 'porcion' => '150g'],
            ['nombre' => 'Arroz blanco cocido', 'tipo' => 'comida', 'calorias' => 200, 'proteinas' => 4.0, 'carbohidratos' => 44.0, 'grasas' => 0.4, 'porcion' => '1 taza'],
            ['nombre' => 'Lentejas con verduras', 'tipo' => 'comida', 'calorias' => 230, 'proteinas' => 18.0, 'carbohidratos' => 35.0, 'grasas' => 1.0, 'porcion' => '1 tazón'],
            ['nombre' => 'Ensalada César con pollo asado', 'tipo' => 'comida', 'calorias' => 350, 'proteinas' => 28.0, 'carbohidratos' => 12.0, 'grasas' => 22.0, 'porcion' => '1 plato'],
            ['nombre' => 'Pasta integral a la boloñesa', 'tipo' => 'comida', 'calorias' => 450, 'proteinas' => 22.0, 'carbohidratos' => 55.0, 'grasas' => 15.0, 'porcion' => '1 plato'],
            ['nombre' => 'Bistec de res con nopales', 'tipo' => 'comida', 'calorias' => 280, 'proteinas' => 26.0, 'carbohidratos' => 5.0, 'grasas' => 18.0, 'porcion' => '150g'],
            ['nombre' => 'Tacos de canasta (papa/frijol - 3)', 'tipo' => 'comida', 'calorias' => 390, 'proteinas' => 12.0, 'carbohidratos' => 45.0, 'grasas' => 18.0, 'porcion' => '3 piezas'],
            ['nombre' => 'Salmón al horno con espárragos', 'tipo' => 'comida', 'calorias' => 320, 'proteinas' => 28.0, 'carbohidratos' => 2.0, 'grasas' => 20.0, 'porcion' => '150g'],
            ['nombre' => 'Pechuga rellena de espinacas', 'tipo' => 'comida', 'calorias' => 280, 'proteinas' => 35.0, 'carbohidratos' => 4.0, 'grasas' => 14.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Fajitas de pollo con pimiento', 'tipo' => 'comida', 'calorias' => 240, 'proteinas' => 28.0, 'carbohidratos' => 8.0, 'grasas' => 10.0, 'porcion' => '200g'],
            ['nombre' => 'Garbanzos con espinacas', 'tipo' => 'comida', 'calorias' => 210, 'proteinas' => 12.0, 'carbohidratos' => 30.0, 'grasas' => 4.0, 'porcion' => '1 tazón'],
            ['nombre' => 'Ceviche de pescado', 'tipo' => 'comida', 'calorias' => 180, 'proteinas' => 25.0, 'carbohidratos' => 10.0, 'grasas' => 2.0, 'porcion' => '200g'],
            ['nombre' => 'Caldo Tlalpeño', 'tipo' => 'comida', 'calorias' => 260, 'proteinas' => 18.0, 'carbohidratos' => 20.0, 'grasas' => 12.0, 'porcion' => '1 plato grande'],
            ['nombre' => 'Torta de jamón y aguacate', 'tipo' => 'comida', 'calorias' => 420, 'proteinas' => 18.0, 'carbohidratos' => 48.0, 'grasas' => 18.0, 'porcion' => '1 pieza'],

            // CENAS
            ['nombre' => 'Sándwich de pavo y pan integral', 'tipo' => 'cena', 'calorias' => 250, 'proteinas' => 15.0, 'carbohidratos' => 32.0, 'grasas' => 6.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Atún en agua con galletas saladas', 'tipo' => 'cena', 'calorias' => 210, 'proteinas' => 26.0, 'carbohidratos' => 18.0, 'grasas' => 2.0, 'porcion' => '1 lata'],
            ['nombre' => 'Quesadillas de maíz (2 piezas)', 'tipo' => 'cena', 'calorias' => 320, 'proteinas' => 14.0, 'carbohidratos' => 30.0, 'grasas' => 16.0, 'porcion' => '2 piezas'],
            ['nombre' => 'Ensalada de atún con lechuga', 'tipo' => 'cena', 'calorias' => 150, 'proteinas' => 24.0, 'carbohidratos' => 4.0, 'grasas' => 4.0, 'porcion' => '1 plato'],
            ['nombre' => 'Yogurt griego con nueces', 'tipo' => 'cena', 'calorias' => 220, 'proteinas' => 15.0, 'carbohidratos' => 12.0, 'grasas' => 12.0, 'porcion' => '150g'],
            ['nombre' => 'Cereal de caja con leche', 'tipo' => 'cena', 'calorias' => 280, 'proteinas' => 8.0, 'carbohidratos' => 50.0, 'grasas' => 5.0, 'porcion' => '1 tazón'],
            ['nombre' => 'Sopa de verduras clara', 'tipo' => 'cena', 'calorias' => 90, 'proteinas' => 2.0, 'carbohidratos' => 15.0, 'grasas' => 1.0, 'porcion' => '1 tazón'],
            ['nombre' => 'Tostada de pollo (1 pieza)', 'tipo' => 'cena', 'calorias' => 180, 'proteinas' => 12.0, 'carbohidratos' => 18.0, 'grasas' => 8.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Huevo duro (2 piezas)', 'tipo' => 'cena', 'calorias' => 140, 'proteinas' => 12.0, 'carbohidratos' => 1.0, 'grasas' => 10.0, 'porcion' => '2 piezas'],
            ['nombre' => 'Rollitos de jamón y queso (3)', 'tipo' => 'cena', 'calorias' => 190, 'proteinas' => 18.0, 'carbohidratos' => 2.0, 'grasas' => 12.0, 'porcion' => '3 piezas'],
            ['nombre' => 'Pan dulce (pieza pequeña)', 'tipo' => 'cena', 'calorias' => 250, 'proteinas' => 4.0, 'carbohidratos' => 40.0, 'grasas' => 10.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Ensalada de nopales', 'tipo' => 'cena', 'calorias' => 80, 'proteinas' => 2.0, 'carbohidratos' => 12.0, 'grasas' => 2.0, 'porcion' => '1 tazón'],
            ['nombre' => 'Tacos de nopal con queso (2)', 'tipo' => 'cena', 'calorias' => 220, 'proteinas' => 10.0, 'carbohidratos' => 18.0, 'grasas' => 12.0, 'porcion' => '2 piezas'],
            ['nombre' => 'Vaso de leche tibia', 'tipo' => 'cena', 'calorias' => 120, 'proteinas' => 8.0, 'carbohidratos' => 12.0, 'grasas' => 4.0, 'porcion' => '250ml'],
            ['nombre' => 'Pechuga de pavo asada (fria)', 'tipo' => 'cena', 'calorias' => 130, 'proteinas' => 24.0, 'carbohidratos' => 0.0, 'grasas' => 3.0, 'porcion' => '100g'],

            // SNACKS
            ['nombre' => 'Manzana verde', 'tipo' => 'snack', 'calorias' => 52, 'proteinas' => 0.3, 'carbohidratos' => 14.0, 'grasas' => 0.2, 'porcion' => '1 pieza'],
            ['nombre' => 'Almendras (10 piezas)', 'tipo' => 'snack', 'calorias' => 70, 'proteinas' => 2.5, 'carbohidratos' => 2.5, 'grasas' => 6.0, 'porcion' => '10 piezas'],
            ['nombre' => 'Barrita de granola', 'tipo' => 'snack', 'calorias' => 120, 'proteinas' => 2.0, 'carbohidratos' => 22.0, 'grasas' => 4.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Plátano dominico', 'tipo' => 'snack', 'calorias' => 90, 'proteinas' => 1.0, 'carbohidratos' => 23.0, 'grasas' => 0.3, 'porcion' => '1 pieza'],
            ['nombre' => 'Gelatina light', 'tipo' => 'snack', 'calorias' => 10, 'proteinas' => 1.0, 'carbohidratos' => 0.0, 'grasas' => 0.0, 'porcion' => '1 taza'],
            ['nombre' => 'Palomitas naturales (1 taza)', 'tipo' => 'snack', 'calorias' => 31, 'proteinas' => 1.0, 'carbohidratos' => 6.0, 'grasas' => 0.4, 'porcion' => '1 taza'],
            ['nombre' => 'Nueces de la india (5 piezas)', 'tipo' => 'snack', 'calorias' => 80, 'proteinas' => 2.0, 'carbohidratos' => 4.0, 'grasas' => 7.0, 'porcion' => '5 piezas'],
            ['nombre' => 'Jicama con chile y limón', 'tipo' => 'snack', 'calorias' => 45, 'proteinas' => 1.0, 'carbohidratos' => 10.0, 'grasas' => 0.1, 'porcion' => '1 taza'],
            ['nombre' => 'Zanahoria baby', 'tipo' => 'snack', 'calorias' => 35, 'proteinas' => 1.0, 'carbohidratos' => 8.0, 'grasas' => 0.1, 'porcion' => '10 piezas'],
            ['nombre' => 'Galletas integrales (2 piezas)', 'tipo' => 'snack', 'calorias' => 90, 'proteinas' => 2.0, 'carbohidratos' => 18.0, 'grasas' => 2.0, 'porcion' => '2 piezas'],
            ['nombre' => 'Huevo cocido solo', 'tipo' => 'snack', 'calorias' => 70, 'proteinas' => 6.0, 'carbohidratos' => 0.5, 'grasas' => 5.0, 'porcion' => '1 pieza'],
            ['nombre' => 'Queso panela en cubos', 'tipo' => 'snack', 'calorias' => 80, 'proteinas' => 6.0, 'carbohidratos' => 1.0, 'grasas' => 6.0, 'porcion' => '30g'],
            ['nombre' => 'Arándanos secos', 'tipo' => 'snack', 'calorias' => 110, 'proteinas' => 0.0, 'carbohidratos' => 30.0, 'grasas' => 0.0, 'porcion' => '30g'],
            ['nombre' => 'Pistaches (15 piezas)', 'tipo' => 'snack', 'calorias' => 85, 'proteinas' => 3.0, 'carbohidratos' => 4.0, 'grasas' => 7.0, 'porcion' => '15 piezas'],
            ['nombre' => 'Hummus con apio', 'tipo' => 'snack', 'calorias' => 120, 'proteinas' => 4.0, 'carbohidratos' => 12.0, 'grasas' => 8.0, 'porcion' => '2 cucharadas'],
        ];

        foreach ($alimentos as $alimento) {
            Alimento::updateOrCreate(['nombre' => $alimento['nombre']], $alimento);
        }
    }
}