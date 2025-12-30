<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, nextTick } from 'vue';
import axios from 'axios';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card } from '@/components/ui/card';
// Se eliminó la importación de ScrollArea para evitar dependencias externas
import { Utensils, Send, Bot, User, ChevronRight } from 'lucide-vue-next';

interface Message {
    role: 'bot' | 'user';
    text?: string;
    dieta?: any;
    analisis?: any;
}

const inputCalorias = ref('');
const messages = ref<Message[]>([
    { role: 'bot', text: '¡Hola! Soy tu asistente nutricional inteligente. ¿Cuántas calorías deseas consumir hoy para que el algoritmo evolutivo genere tu dieta óptima?' }
]);
const loading = ref(false);
const scrollRef = ref<HTMLDivElement | null>(null);

// Función de scroll automático ajustada para un div nativo
const scrollToBottom = () => {
    nextTick(() => {
        if (scrollRef.value) {
            scrollRef.value.scrollTop = scrollRef.value.scrollHeight;
        }
    });
};

const enviar = async () => {
    if (!inputCalorias.value || loading.value) return;

    const calorias = parseInt(inputCalorias.value);
    messages.value.push({ role: 'user', text: `Necesito un plan de ${calorias} kcal.` });
    
    const meta = inputCalorias.value;
    inputCalorias.value = '';
    loading.value = true;
    scrollToBottom();

    try {
        const response = await axios.post('/generar-dieta', { calorias: meta });
        messages.value.push({
            role: 'bot',
            text: 'Tras procesar múltiples generaciones de individuos, he seleccionado la combinación con mayor aptitud calórica:',
            dieta: response.data.dieta,
            analisis: response.data.analisis
        });
    } catch (error) {
        messages.value.push({ 
            role: 'bot', 
            text: 'Error en la percepción de datos. Por favor, ingresa un valor numérico entre 500 y 5000 calorías.' 
        });
    } finally {
        loading.value = false;
        scrollToBottom();
    }
};
</script>

<template>
    <Head title="Chatbot Inteligente" />

    <AppLayout>
        <div class="flex flex-col h-[calc(100vh-140px)] max-w-4xl mx-auto p-4 md:p-6">
            <header class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <Utensils class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">Optimización Nutricional</h1>
                        <p class="text-xs text-muted-foreground">Agente Inteligente Basado en Algoritmos Genéticos</p>
                    </div>
                </div>
            </header>

            <Card class="flex-1 flex flex-col overflow-hidden border-2 shadow-xl bg-background/50 backdrop-blur-sm">
                <!-- Se reemplazó ScrollArea por un div nativo con scroll de Tailwind -->
                <div 
                    ref="scrollRef" 
                    class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 scroll-smooth custom-scrollbar"
                >
                    <div class="space-y-6">
                        <div v-for="(msg, idx) in messages" :key="idx" 
                             :class="['flex w-full animate-in fade-in slide-in-from-bottom-2 duration-300', msg.role === 'user' ? 'justify-end' : 'justify-start']">
                            
                            <div :class="['flex gap-4 max-w-[85%] md:max-w-[70%] group', 
                                         msg.role === 'user' ? 'flex-row-reverse' : 'flex-row']">
                                
                                <div :class="['shrink-0 w-8 h-8 rounded-full flex items-center justify-center border shadow-sm',
                                             msg.role === 'user' ? 'bg-primary border-primary' : 'bg-muted border-border']">
                                    <User v-if="msg.role === 'user'" class="w-4 h-4 text-primary-foreground" />
                                    <Bot v-else class="w-4 h-4 text-primary" />
                                </div>

                                <div :class="['flex flex-col gap-2', msg.role === 'user' ? 'items-end' : 'items-start']">
                                    <div :class="['p-4 rounded-2xl text-sm leading-relaxed shadow-sm', 
                                                 msg.role === 'user' ? 'bg-primary text-primary-foreground rounded-tr-none' : 'bg-card text-card-foreground rounded-tl-none border']">
                                        {{ msg.text }}
                                    </div>

                                    <!-- Resultado del Agente (Dieta) -->
                                    <div v-if="msg.dieta" class="w-full mt-2 space-y-3">
                                        <div class="grid grid-cols-1 gap-2">
                                            <div v-for="(item, key) in msg.dieta" :key="key" 
                                                 class="flex items-center justify-between p-3 rounded-xl bg-muted/50 border border-border/40 hover:bg-muted transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <ChevronRight class="w-3 h-3 text-primary opacity-50" />
                                                    <div>
                                                        <span class="text-[10px] font-bold uppercase text-primary block leading-none mb-1">{{ key }}</span>
                                                        <span class="text-xs font-medium">{{ item.nombre }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs font-mono font-bold">{{ item.calorias }}</span>
                                                    <span class="text-[10px] ml-1 opacity-60">kcal</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="p-3 rounded-xl bg-primary/5 border border-primary/20 flex justify-between items-center shadow-inner">
                                            <span class="text-xs font-bold">Aptitud Final (Calorías Totales):</span>
                                            <span class="text-primary font-black text-lg">{{ msg.analisis.total_calorias }} <span class="text-[10px]">kcal</span></span>
                                        </div>

                                        <div class="px-2 flex justify-between items-center text-[10px] text-muted-foreground italic">
                                            <span>Generaciones: {{ msg.analisis.generaciones_procesadas }}</span>
                                            <span>Error de Fitness: {{ msg.analisis.error }} kcal</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Indicador de pensamiento del agente -->
                        <div v-if="loading" class="flex justify-start animate-in fade-in duration-300 pb-4">
                            <div class="flex gap-4">
                                <div class="shrink-0 w-8 h-8 rounded-full bg-muted border flex items-center justify-center animate-pulse">
                                    <Bot class="w-4 h-4 text-primary" />
                                </div>
                                <div class="bg-card p-4 rounded-2xl border flex items-center gap-3 shadow-sm rounded-tl-none">
                                    <span class="flex gap-1.5">
                                        <span class="w-2 h-2 bg-primary rounded-full animate-bounce [animation-duration:0.8s]"></span>
                                        <span class="w-2 h-2 bg-primary rounded-full animate-bounce [animation-duration:0.8s] [animation-delay:0.2s]"></span>
                                        <span class="w-2 h-2 bg-primary rounded-full animate-bounce [animation-duration:0.8s] [animation-delay:0.4s]"></span>
                                    </span>
                                    <span class="text-xs font-medium italic opacity-70">El agente está evolucionando poblaciones...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-muted/50 border-t backdrop-blur-md">
                    <form @submit.prevent="enviar" class="flex gap-3">
                        <div class="relative flex-1">
                            <Input 
                                v-model="inputCalorias" 
                                type="number" 
                                placeholder="Ingresa tu meta calórica (ej. 2200)..." 
                                class="pr-10 h-11 rounded-xl border-2 focus-visible:ring-primary"
                                :disabled="loading"
                                required
                            />
                        </div>
                        <Button type="submit" class="h-11 px-6 rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-95" :disabled="loading">
                            <Send v-if="!loading" class="w-4 h-4 mr-2" />
                            <span v-if="!loading">Generar Plan</span>
                            <span v-else>Procesando...</span>
                        </Button>
                    </form>
                    <p class="text-center text-[10px] mt-3 text-muted-foreground">
                        El sistema generará una combinación óptima basada en los alimentos disponibles en el dataset.
                    </p>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Estilos personalizados para que el scroll se vea más limpio */
.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(var(--primary), 0.1);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(var(--primary), 0.2);
}
</style>