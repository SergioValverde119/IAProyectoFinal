import pandas as pd
import random
import numpy as np

# Configuración del Agente
#POPULATION_SIZE = 60       
#GENERATIONS = 50           
#MUTATION_RATE = 0.1
# Configuración del Agente (Mejorada para precisión)
POPULATION_SIZE = 100       # Más variedad inicial
GENERATIONS = 100           # Más tiempo para perfeccionar
MUTATION_RATE = 0.15        # Un poco más alto para evitar estancamiento
    

# NOTA: Ya no usamos MEAL_PLAN_SIZE global fija

class AgenteNutricional:
    def __init__(self, data_path='data/nutrition_cleaned.csv'):
        # Cargar base de conocimientos
        try:
            self.df_completo = pd.read_csv(data_path)
            self.df_completo['Nombre_Lower'] = self.df_completo['Nombre'].str.lower()
            self.food_database = self.df_completo.to_dict('records')
            print(f"Agente inicializado con {len(self.food_database)} alimentos.")
        except Exception as e:
            print(f"Error cargando datos: {e}")
            self.food_database = []

    def calcular_requerimiento(self, edad, genero, peso, altura, objetivo):
        # Harris-Benedict
        if genero.lower() == 'hombre':
            tmb = 88.36 + (13.4 * peso) + (4.8 * altura) - (5.7 * edad)
        else:
            tmb = 447.6 + (9.2 * peso) + (3.1 * altura) - (4.3 * edad)
        
        gasto_total = tmb * 1.375
        
        if objetivo == 'deficit':
            calorias_finales = gasto_total * 0.85
        elif objetivo == 'volumen':
            calorias_finales = gasto_total * 1.15
        else:
            calorias_finales = gasto_total
            
        return int(calorias_finales)

    def filtrar_alimentos(self, restricciones):
        if not restricciones:
            return self.food_database
        
        pool_filtrado = []
        for alimento in self.food_database:
            nombre = alimento['Nombre_Lower']
            if not any(r.lower() in nombre for r in restricciones):
                pool_filtrado.append(alimento)
        return pool_filtrado

    # --- AG ADAPTADO A TAMAÑO VARIABLE ---
    
    def crear_individuo(self, pool_alimentos, n_comidas):
        # Crea un menú con 'n_comidas' items
        return random.choices(pool_alimentos, k=n_comidas)

    def crear_poblacion(self, pool_alimentos, n_comidas):
        return [self.crear_individuo(pool_alimentos, n_comidas) for _ in range(POPULATION_SIZE)]

    #def calcular_fitness(self, individuo, target_cal):
    #    total_cal = sum(item['Calorias'] for item in individuo)
    #    error = abs(target_cal - total_cal)
    #    return 1 / (error + 1)
    def calcular_fitness(self, individuo, target_cal):
        total_cal = sum(item['Calorias'] for item in individuo)
        error = abs(target_cal - total_cal)
        
        # 1. Puntaje base (Precisión calórica)
        # Usamos error cuadrático para castigar desviaciones grandes
        fitness = 1 / (error**2 + 1)
        
        # 2. Penalización por Repetición (NUEVO)
        nombres = [item['Nombre'] for item in individuo]
        # Si hay menos nombres únicos que comidas totales, es que algo se repite
        n_unicos = len(set(nombres))
        
        if n_unicos < len(individuo):
            # CASTIGO SEVERO: Reducimos el fitness drásticamente
            # Si se repite todo, fitness se divide entre 100
            # Si se repite poco, se divide entre 10
            factor_castigo = 10 * (len(individuo) - n_unicos + 1)
            fitness = fitness / factor_castigo
            
        return fitness



    def seleccion_torneo(self, poblacion, fitness_scores):
        seleccionados = []
        for _ in range(len(poblacion)):
            aspirantes = random.choices(list(zip(poblacion, fitness_scores)), k=3)
            mejor = max(aspirantes, key=lambda x: x[1])
            seleccionados.append(mejor[0])
        return seleccionados

    def cruce(self, padre1, padre2):
        # El tamaño es dinámico, así que lo leemos del padre
        size = len(padre1) 
        if size > 1:
            punto = random.randint(1, size - 1)
            hijo1 = padre1[:punto] + padre2[punto:]
            hijo2 = padre2[:punto] + padre1[punto:]
            return hijo1, hijo2
        else:
            # Si es solo 1 comida (raro), no hay cruce real
            return padre1, padre2

    def mutacion(self, individuo, pool_alimentos):
        if random.random() < MUTATION_RATE:
            size = len(individuo)
            idx = random.randint(0, size - 1)
            individuo[idx] = random.choice(pool_alimentos)
        return individuo

    # --- Método Principal ---
    def generar_plan_personalizado(self, perfil_usuario):
        """
        Genera un plan nutricional adaptado.
        Ajusta dinámicamente la intensidad de búsqueda según la dificultad del problema.
        """
        # 1. Calcular Calorías Objetivo
        target = self.calcular_requerimiento(
            perfil_usuario['edad'], perfil_usuario['genero'],
            perfil_usuario['peso'], perfil_usuario['altura'],
            perfil_usuario['objetivo']
        )
        
        # 2. Filtrar Alimentos (Restricciones)
        pool_actual = self.filtrar_alimentos(perfil_usuario.get('restricciones', []))
        if len(pool_actual) < 10:
            return {"error": "Demasiadas restricciones, no hay alimentos suficientes."}

        # 3. Leer número de comidas (Validación estricta)
        n_comidas = perfil_usuario.get('numero_comidas', 3)
        if n_comidas < 1: n_comidas = 3
        if n_comidas > 6: n_comidas = 6

        # --- ESTRATEGIA ADAPTATIVA (Corrección del error de 900 kcal) ---
        # Si hay pocas comidas, es más difícil "atinar" a la meta exacta.
        # Aumentamos agresivamente la búsqueda para compensar.
        if n_comidas <= 3:
            poblacion_dinamica = POPULATION_SIZE * 3  # Triplicar población (ej. 300 individuos)
            generaciones_dinamicas = GENERATIONS * 2  # Duplicar tiempo (ej. 200 gens)
        else:
            poblacion_dinamica = POPULATION_SIZE
            generaciones_dinamicas = GENERATIONS

        # 4. Iniciar AG con parámetros dinámicos
        # Nota: crear_poblacion debe generar la cantidad 'poblacion_dinamica'
        poblacion = [self.crear_individuo(pool_actual, n_comidas) for _ in range(poblacion_dinamica)]
        
        mejor_global = None
        mejor_fitness_global = -1

        for gen in range(generaciones_dinamicas):
            fitness_scores = [self.calcular_fitness(ind, target) for ind in poblacion]
            
            # --- Elitismo ---
            max_fit_gen = max(fitness_scores)
            idx_mejor = fitness_scores.index(max_fit_gen)
            mejor_gen = poblacion[idx_mejor]
            
            # Actualizar mejor histórico
            if max_fit_gen > mejor_fitness_global:
                mejor_fitness_global = max_fit_gen
                mejor_global = mejor_gen
            
            # --- Selección y Reproducción ---
            padres = self.seleccion_torneo(poblacion, fitness_scores)
            
            # Elitismo: El mejor SIEMPRE pasa a la siguiente generación
            nueva_poblacion = [mejor_global] 
            
            # Llenar el resto de la población
            while len(nueva_poblacion) < poblacion_dinamica:
                # Selección de padres aleatoria dentro de los ganadores del torneo
                p1, p2 = random.sample(padres, 2)
                h1, h2 = self.cruce(p1, p2)
                
                # Mutación y agregar
                nueva_poblacion.append(self.mutacion(h1, pool_actual))
                if len(nueva_poblacion) < poblacion_dinamica:
                    nueva_poblacion.append(self.mutacion(h2, pool_actual))
            
            poblacion = nueva_poblacion

        # Retornar resultado final
        total_cal = sum(i['Calorias'] for i in mejor_global)
        
        # Opcional: Calcular error porcentual para debug
        # error_pct = abs(target - total_cal) / target * 100
        # print(f"DEBUG: Meta {target} | Logrado {total_cal} | Error {error_pct:.2f}%")

        return {
            "meta_calculada": target,
            "total_menu": round(total_cal, 2),
            "numero_comidas": n_comidas,
            "menu": mejor_global
        }
