<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarGroup,
    SidebarGroupLabel,
} from '@/components/ui/sidebar';
import { chatbot, dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage, router } from '@inertiajs/vue3';
import { 
    Bot, 
    Folder, 
    LayoutGrid, 
    Utensils, 
    Pencil, 
    Trash, 
    Plus 
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Asistente IA',
        href: chatbot(),
        icon: Bot,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/SergioValverde119/IAProyectoFinal.git',
        icon: Folder,
    },
];

// Funciones para gestionar el historial desde la barra lateral
const confirmarEliminar = (id: number) => {
    if (confirm('¿Deseas eliminar este plan permanentemente?')) {
        router.delete(`/chatbot/conversacion/${id}`);
    }
};

const renombrarPlan = (item: any) => {
    const nuevoNombre = prompt('Nuevo nombre para el plan:', item.nombre);
    if (nuevoNombre && nuevoNombre !== item.nombre) {
        router.patch(`/chatbot/conversacion/${item.id}`, { nombre: nuevoNombre });
    }
};
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <!-- Navegación Principal -->
            <NavMain :items="mainNavItems" />

            <!-- Sección de Historial (Estilo Gemini/ChatGPT) -->
            <SidebarGroup v-if="page.props.historial && (page.props.historial as any[]).length > 0">
                <SidebarGroupLabel>Planes Recientes</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem v-for="item in (page.props.historial as any[])" :key="item.id" class="group/item relative">
                        <SidebarMenuButton as-child :active="page.url === `/chatbot/${item.id}`">
                            <Link :href="`/chatbot/${item.id}`" class="flex items-center gap-2 pr-12">
                                <Utensils class="w-4 h-4 opacity-50 shrink-0" />
                                <span class="truncate text-xs">{{ item.nombre }}</span>
                            </Link>
                        </SidebarMenuButton>
                        
                        <!-- Botones de Acción (Visibles al hacer Hover) -->
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 hidden group-hover/item:flex items-center gap-1 bg-background/80 backdrop-blur-sm pl-2">
                            <button 
                                @click.stop.prevent="renombrarPlan(item)" 
                                class="p-1 hover:text-primary transition-colors"
                                title="Renombrar"
                            >
                                <Pencil class="w-3 h-3" />
                            </button>
                            <button 
                                @click.stop.prevent="confirmarEliminar(item.id)" 
                                class="p-1 hover:text-destructive transition-colors"
                                title="Eliminar"
                            >
                                <Trash class="w-3 h-3" />
                            </button>
                        </div>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

<style scoped>
/* Ajuste para que el texto no se encime con los botones de acción */
.truncate {
    max-width: 140px;
}
</style>