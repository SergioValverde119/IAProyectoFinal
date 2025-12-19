# IAProyectoFinal
Proyecto final para la materia de inteligencia artifical, que se basa en un chatbot que recomienda dietas personalizadas, será desarrollado en un entorno web, Dios nos ayude.


🥗 NutriGenius: Agente Inteligente para Dietas Optimizadas

Implementación de un sistema de optimización nutricional mediante algoritmos evolutivos.

NutriGenius es un agente inteligente diseñado para resolver el complejo problema de la planificación alimenticia personalizada. A diferencia de las herramientas convencionales basadas en reglas estáticas, este sistema utiliza un Algoritmo Genético (AG) para encontrar la combinación óptima de alimentos que satisfaga los requerimientos biológicos de un usuario, maximizando la precisión nutricional y promoviendo la variedad alimentaria.

🧠 Concepto del Proyecto

El corazón de este proyecto es un Agente Basado en Objetivos. El sistema opera bajo la premisa de que una dieta no es solo una suma de calorías, sino un problema de optimización combinatoria. El agente utiliza principios de selección natural para "hacer evolucionar" un plan alimenticio hasta alcanzar la meta establecida.

El Cerebro del Agente: Algoritmo Genético

El proceso de inteligencia sigue un flujo evolutivo digital:

Población Inicial: El agente genera un conjunto diverso de menús aleatorios que cumplen con las restricciones de estructura (desayuno, comida, cena).

Función de Aptitud (Fitness): Cada menú es evaluado matemáticamente. ¿Qué tan cerca está del objetivo calórico y de macronutrientes? Los individuos con menor error tienen una mayor "probabilidad de supervivencia".

Cruce (Crossover): Las dietas más aptas intercambian "genes" (platos específicos) para crear descendencia que combine las mejores características nutricionales de sus progenitores.

Mutación: El agente introduce cambios aleatorios controlados en la población para explorar nuevas combinaciones y evitar caer en "óptimos locales" (dietas repetitivas o estancadas).

📊 Especificaciones del Agente (Modelo PEAS)

Para garantizar un diseño robusto bajo los estándares de la Inteligencia Artificial, el agente se define mediante el marco PEAS:

Performance (Rendimiento): Precisión calórica (error < 1%), equilibrio de macronutrientes, tiempo de convergencia del algoritmo.

Environment (Entorno): Base de datos de información nutricional, perfiles de usuario y metas biológicas.

Actuators (Actuadores): Interfaz de usuario interactiva (Chatbot) y visualizador de planes dietéticos.

Sensors (Sensores): Datos de entrada del usuario (edad, peso, actividad física, objetivos de salud).

🎯 Objetivos Estratégicos

Optimización Multiobjetivo: No solo busca calorías, sino un equilibrio entre proteínas, carbohidratos y grasas.

Coherencia Alimenticia: El agente es capaz de distinguir entre tipos de alimentos para asegurar que los menús sean lógicos (ej. no sugerir una cena pesada como desayuno).

Escalabilidad: Diseñado para manejar bases de datos de alimentos en crecimiento sin degradar la precisión de la búsqueda.

Interacción Fluida: Implementación de un entorno web que facilita la comunicación entre el humano y el agente inteligente.

🏗️ Estructura del Sistema

El proyecto se articula en tres capas fundamentales:

Motor de Optimización: Donde reside la lógica del Algoritmo Genético.

Capa de Conocimiento: Un conjunto de datos estructurado con perfiles nutricionales validados.

Interfaz de Usuario: Un entorno web reactivo que permite la interacción con el chatbot.

📅 Estado de Desarrollo

Actualmente el proyecto se encuentra en la fase de refinamiento del motor lógico y estructuración del modelo de datos inicial.

Este proyecto es parte integral de la asignatura de Agentes Inteligentes - Ciclo 2025.
