<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, nextTick } from 'vue';
import axios from 'axios';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { 
    Utensils, Send, Bot, User, 
    ChevronRight, Calculator, RefreshCw 
} from 'lucide-vue-next';

interface Message {
    role: 'bot' | 'user';
    text?: string;
    dieta?: any;
    analisis?: any;
    meta?: number;
}

// Estado del Formulario (Perfil de Usuario)
const perfil = ref({
    edad: 25,
    genero: 'hombre',
    peso: 70,
    altura: 170,
    objetivo: 'mantenimiento',
    numero_comidas: 3
});

const step = ref('form'); // 'form' o 'chat'
const messages = ref<Message[]>([]);
const loading = ref(false);
const scrollRef = ref<HTMLDivElement | null>(null);

const scrollToBottom = () => {
    nextTick(() => {
        if (scrollRef.value) {
            scrollRef.value.scrollTop = scrollRef.value.scrollHeight;
        }
    });
};

const iniciarCalculo = async () => {
    loading.value = true;
    step.value = 'chat';
    
    messages.value.push({ 
        role: 'user', 
        text: `Hola, soy ${perfil.value.genero}, tengo ${perfil.value.edad} años. Mi peso es ${perfil.value.peso}kg y mido ${perfil.value.altura}cm. Mi objetivo es ${perfil.value.objetivo} con ${perfil.value.numero_comidas} comidas al día.` 
    });

    try {
        const response = await axios.post('/generar-dieta', perfil.value);
        messages.value.push({
            role: 'bot',
            text: `Basado en la fórmula de Harris-Benedict, tu meta diaria es de ${response.data.meta_calculada} kcal. He ejecutado el Algoritmo Genético para encontrar tu menú ideal:`,
            dieta: response.data.dieta,
            analisis: response.data.analisis,
            meta: response.data.meta_calculada
        });
    } catch (error: any) {
        messages.value.push({ 
            role: 'bot', 
            text: 'Lo siento, hubo un error en el motor evolutivo. Verifica que la base de datos de alimentos esté cargada.' 
        });
    } finally {
        loading.value = false;
        scrollToBottom();
    }
};

const reiniciar = () => {
    messages.value = [];
    step.value = 'form';
};
</script>

<template>
    <Head title="Agente Nutricional IA" />

    <AppLayout>
        <div class="flex flex-col h-[calc(100vh-140px)] max-w-4xl mx-auto p-4 md:p-6">
            
            <!-- HEADER -->
            <header class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <Bot class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">Agente Nutricional Inteligente</h1>
                        <p class="text-xs text-muted-foreground italic">Powered by Genetic Algorithms & Harris-Benedict</p>
                    </div>
                </div>
                <Button v-if="step === 'chat'" variant="outline" size="sm" @click="reiniciar">
                    <RefreshCw class="w-4 h-4 mr-2" /> Reiniciar
                </Button>
            </header>

            <!-- FORMULARIO INICIAL -->
            <Card v-if="step === 'form'" class="p-6 border-2 shadow-2xl animate-in fade-in zoom-in duration-300">
                <div class="flex items-center gap-2 mb-6 text-primary">
                    <Calculator class="w-5 h-5" />
                    <h2 class="font-bold">Configuración del Perfil</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <Label>Edad</Label>
                        <Input v-model="perfil.edad" type="number" />
                    </div>
                    <div class="space-y-2">
                        <Label>Género</Label>
                        <select v-model="perfil.genero" class="w-full p-2 rounded-md border bg-background">
                            <option value="hombre">Hombre</option>
                            <option value="mujer">Mujer</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <Label>Peso (kg)</Label>
                        <Input v-model="perfil.peso" type="number" />
                    </div>
                    <div class="space-y-2">
                        <Label>Altura (cm)</Label>
                        <Input v-model="perfil.altura" type="number" />
                    </div>
                    <div class="space-y-2">
                        <Label>Objetivo</Label>
                        <select v-model="perfil.objetivo" class="w-full p-2 rounded-md border bg-background">
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="deficit">Déficit Calórico</option>
                            <option value="volumen">Volumen (Ganar Masa)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <Label>Comidas al día</Label>
                        <Input v-model="perfil.numero_comidas" type="number" min="3" max="6" />
                    </div>
                </div>

                <Button class="w-full mt-8 h-12 text-lg font-bold shadow-lg" @click="iniciarCalculo">
                    <Calculator class="w-5 h-5 mr-2" /> Calcular y Generar Dieta
                </Button>
            </Card>

            <!-- CHAT DE RESULTADOS -->
            <Card v-else class="flex-1 flex flex-col overflow-hidden border-2 shadow-xl bg-background/50 backdrop-blur-sm">
                <div ref="scrollRef" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 scroll-smooth custom-scrollbar">
                    <div v-for="(msg, idx) in messages" :key="idx" 
                         :class="['flex w-full animate-in fade-in slide-in-from-bottom-2 duration-300', msg.role === 'user' ? 'justify-end' : 'justify-start']">
                        
                        <div :class="['flex gap-4 max-w-[90%] md:max-w-[80%]', msg.role === 'user' ? 'flex-row-reverse' : 'flex-row']">
                            <div :class="['shrink-0 w-8 h-8 rounded-full flex items-center justify-center border shadow-sm',
                                         msg.role === 'user' ? 'bg-primary text-primary-foreground' : 'bg-muted text-primary']">
                                <User v-if="msg.role === 'user'" class="w-4 h-4" />
                                <Bot v-else class="w-4 h-4" />
                            </div>

                            <div :class="['flex flex-col gap-2', msg.role === 'user' ? 'items-end' : 'items-start']">
                                <div :class="['p-4 rounded-2xl text-sm shadow-sm leading-relaxed', 
                                             msg.role === 'user' ? 'bg-primary text-primary-foreground rounded-tr-none' : 'bg-card border rounded-tl-none']">
                                    {{ msg.text }}
                                </div>

                                <!-- DIETA GENERADA -->
                                <div v-if="msg.dieta" class="w-full mt-4 space-y-3">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div v-for="(item, key) in msg.dieta" :key="key" 
                                             class="p-4 rounded-xl bg-muted/30 border border-border/50 hover:border-primary/30 transition-all">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-[10px] font-black uppercase text-primary">{{ key.replace('_', ' ') }}</span>
                                                <span class="text-xs font-mono font-bold">{{ item.calorias }} kcal</span>
                                            </div>
                                            <p class="text-sm font-medium leading-tight">{{ item.nombre }}</p>
                                        </div>
                                    </div>

                                    <div class="p-4 rounded-xl bg-primary/10 border-2 border-primary/20 flex justify-between items-center shadow-inner">
                                        <div>
                                            <p class="text-[10px] uppercase font-bold opacity-60">Aptitud lograda</p>
                                            <p class="text-2xl font-black text-primary">{{ msg.analisis.total_calorias }} <span class="text-xs">kcal</span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] uppercase font-bold opacity-60">Error de Fitness</p>
                                            <p class="text-sm font-mono font-bold text-orange-500">± {{ msg.analisis.error }} kcal</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-[10px] text-muted-foreground px-2">
                                        <span>Generaciones: {{ msg.analisis.generaciones_procesadas }}</span>
                                        <span>Población: {{ msg.analisis.poblacion_utilizada }} individuos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LOADING STATE -->
                    <div v-if="loading" class="flex justify-start pb-4">
                        <div class="flex gap-4">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-muted border flex items-center justify-center animate-pulse">
                                <Bot class="w-4 h-4 text-primary" />
                            </div>
                            <div class="bg-card p-4 rounded-2xl border flex items-center gap-3 shadow-sm rounded-tl-none">
                                <span class="flex gap-1.5">
                                    <span class="w-2 h-2 bg-primary rounded-full animate-bounce"></span>
                                    <span class="w-2 h-2 bg-primary rounded-full animate-bounce [animation-delay:0.2s]"></span>
                                    <span class="w-2 h-2 bg-primary rounded-full animate-bounce [animation-delay:0.4s]"></span>
                                </span>
                                <span class="text-xs font-medium italic opacity-70 italic font-mono">Evolucionando dieta óptima...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(var(--primary), 0.1); border-radius: 10px; }
</style>