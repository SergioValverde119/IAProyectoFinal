<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { 
    Bot, Calculator, Flame, CheckCircle, 
    History, Plus, Utensils, User as UserIcon,
    Pencil, Trash2, Scale, Info, ArrowRight
} from 'lucide-vue-next';

// Props enviadas desde el controlador (DietaController)
const props = defineProps<{
    historial: any[];
    dietaSeleccionada?: any;
}>();

const loading = ref(false);
const errorMsg = ref('');

// Estado inicial del formulario para el Algoritmo Genético
const form = ref({
    edad: 25,
    genero: 'hombre',
    peso: 70,
    altura: 170,
    objetivo: 'mantenimiento',
    numero_comidas: 3
});

// Si se selecciona una dieta del historial, cargamos sus datos para referencia
onMounted(() => {
    if (props.dietaSeleccionada && props.dietaSeleccionada.input_usuario) {
        form.value = { ...props.dietaSeleccionada.input_usuario };
    }
});

// Función para enviar los datos al motor de IA en el backend
const generarPlan = async () => {
    loading.value = true;
    errorMsg.value = '';
    try {
        const response = await axios.post('/chatbot/generar-dieta', form.value);
        // Al recibir la nueva dieta, navegamos a su URL específica para ver el resultado
        router.visit(`/chatbot/${response.data.id}`);
    } catch (e: any) {
        errorMsg.value = e.response?.data?.error || 'Error al conectar con el servidor de IA.';
    } finally {
        loading.value = false;
    }
};

// Navegar de vuelta al formulario de "Nueva Consulta"
const nuevaConsulta = () => {
    router.visit('/chatbot');
};

// Borrar dieta del historial
const eliminarPlan = (id: number) => {
    if (confirm('¿Deseas eliminar este plan permanentemente?')) {
        router.delete(`/chatbot/conversacion/${id}`);
    }
};

// Renombrar dieta del historial
const renombrarPlan = (id: number) => {
    const nuevoNombre = prompt('Ingresa el nuevo nombre para este plan:', props.dietaSeleccionada?.nombre);
    if (nuevoNombre && nuevoNombre !== props.dietaSeleccionada?.nombre) {
        router.patch(`/chatbot/conversacion/${id}`, { nombre: nuevoNombre });
    }
};
</script>

<template>
    <Head :title="dietaSeleccionada ? dietaSeleccionada.nombre : 'Nueva Dieta IA'" />

    <AppLayout>
        <div class="max-w-5xl mx-auto p-4 md:p-10">
            
            <!-- ESTADO 1: FORMULARIO DE GENERACIÓN (Si no hay dieta seleccionada) -->
            <div v-if="!dietaSeleccionada" class="animate-in fade-in slide-in-from-top-4 duration-700">
                <div class="text-center mb-12 space-y-3">
                    <div class="inline-flex p-4 bg-primary/10 rounded-3xl mb-2 shadow-inner">
                        <Bot class="w-12 h-12 text-primary" />
                    </div>
                    <h1 class="text-4xl font-black tracking-tighter text-slate-900">Nueva Dieta Evolutiva</h1>
                    <p class="text-slate-500 max-w-lg mx-auto text-lg leading-relaxed">
                        Nuestro Agente IA simulará miles de combinaciones de alimentos para encontrar la que mejor se adapte a tus necesidades.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-8 md:p-12 rounded-[3rem] border border-slate-200 shadow-2xl shadow-slate-200/40 relative overflow-hidden">
                    <!-- Decoración de fondo -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="space-y-6 relative z-10">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Edad del Usuario</label>
                            <input v-model="form.edad" type="number" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Peso Actual (kg)</label>
                            <input v-model="form.peso" type="number" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Estatura (cm)</label>
                            <input v-model="form.altura" type="number" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold" />
                        </div>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Objetivo Fitness</label>
                            <select v-model="form.objetivo" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold appearance-none">
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="deficit">Déficit Calórico</option>
                                <option value="volumen">Aumento de Masa</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400 ml-1">Comidas Diarias</label>
                            <input v-model="form.numero_comidas" type="number" min="3" max="6" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold" />
                        </div>

                        <div class="pt-6">
                            <button 
                                @click="generarPlan"
                                :disabled="loading"
                                class="w-full bg-slate-900 text-white p-5 rounded-2xl font-black text-lg shadow-xl shadow-slate-900/20 hover:bg-slate-800 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group"
                            >
                                <template v-if="loading">
                                    <div class="w-6 h-6 border-2 border-white/20 border-t-white rounded-full animate-spin" />
                                    <span>Simulando Evolución...</span>
                                </template>
                                <template v-else>
                                    <Calculator class="w-6 h-6" />
                                    <span>Comenzar Cálculo IA</span>
                                    <ArrowRight class="w-5 h-5 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all" />
                                </template>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="errorMsg" class="mt-8 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-center text-sm font-bold flex items-center justify-center gap-2">
                    <Info class="w-4 h-4" /> {{ errorMsg }}
                </div>
            </div>

            <!-- ESTADO 2: VISTA DE RESULTADO (Cuando hay una dieta seleccionada) -->
            <div v-else class="animate-in fade-in slide-in-from-bottom-8 duration-700">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-primary/10 rounded-2xl">
                            <History class="w-8 h-8 text-primary" />
                        </div>
                        <div>
                            <h2 class="text-3xl font-black tracking-tight text-slate-900">{{ dietaSeleccionada.nombre }}</h2>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Generado con Algoritmo Genético</span>
                                <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                                <span class="text-xs font-bold text-primary">{{ new Date(dietaSeleccionada.created_at).toLocaleDateString() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button @click="nuevaConsulta" class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold shadow-sm hover:bg-slate-50 transition-all">
                            <Plus class="w-4 h-4" /> Nuevo Plan
                        </button>
                        <button @click="renombrarPlan(dietaSeleccionada.id)" class="p-3 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-2xl transition-all">
                            <Pencil class="w-5 h-5" />
                        </button>
                        <button @click="eliminarPlan(dietaSeleccionada.id)" class="p-3 text-red-400 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all">
                            <Trash2 class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <!-- Métricas de la IA -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-blue-200 transition-all">
                        <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl group-hover:scale-110 transition-transform"><Flame class="w-7 h-7"/></div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-1">Requerimiento</p>
                            <p class="text-2xl font-black text-slate-900">{{ dietaSeleccionada.meta_calorica }} <span class="text-xs opacity-50">kcal</span></p>
                        </div>
                    </div>
                    <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-emerald-200 transition-all">
                        <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:scale-110 transition-transform"><CheckCircle class="w-7 h-7"/></div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-1">Calculado</p>
                            <p class="text-2xl font-black text-emerald-600">{{ dietaSeleccionada.analisis.total_calorias }} <span class="text-xs opacity-50">kcal</span></p>
                        </div>
                    </div>
                    <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-amber-200 transition-all">
                        <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl group-hover:scale-110 transition-transform"><Scale class="w-7 h-7"/></div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-1">Margen Error</p>
                            <p class="text-2xl font-black text-amber-600">± {{ Math.round(dietaSeleccionada.analisis.error) }} <span class="text-xs opacity-50">kcal</span></p>
                        </div>
                    </div>
                </div>

                <!-- Lista de Alimentos (El Menú) -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 px-2">
                        <Utensils class="w-6 h-6 text-primary" />
                        <h3 class="font-black text-slate-900 uppercase tracking-tighter text-xl">Plan de Alimentación Sugerido</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div v-for="(data, comida) in dietaSeleccionada.resultado_ia" :key="comida" 
                             class="group p-7 bg-white border border-slate-200 rounded-[2rem] hover:border-primary/30 hover:shadow-xl hover:shadow-primary/5 transition-all duration-500">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-[10px] font-black text-primary bg-primary/5 px-3 py-1.5 rounded-xl uppercase tracking-widest">
                                    {{ comida.replace('_', ' ') }}
                                </span>
                                <span class="text-xs font-mono font-bold text-slate-300 group-hover:text-primary transition-colors">{{ data.calorias }} kcal</span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-lg group-hover:translate-x-1 transition-all leading-snug">
                                {{ data.nombre }}
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Footer del Reporte -->
                <div class="mt-16 pt-8 border-t border-dashed border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">
                    <span class="flex items-center gap-2"><Bot class="w-3 h-3"/> Motor Evolutivo v2.5.4</span>
                    <span class="bg-slate-50 px-3 py-1 rounded-full border border-slate-100">Generaciones Calculadas: {{ dietaSeleccionada.analisis.generaciones }}</span>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

<style scoped>
/* Transiciones de entrada suave */
.fade-enter-active, .fade-leave-active { transition: all 0.5s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(10px); }

/* Personalización del select para quitar la flecha por defecto y usar el diseño limpio */
select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1.25rem center;
    background-size: 1rem;
}
</style>