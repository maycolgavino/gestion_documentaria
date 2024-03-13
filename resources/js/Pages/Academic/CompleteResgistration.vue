<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdministrativeData from '@/Components/completecomponent.vue';
import { Head } from '@inertiajs/vue3';
import completecomponent from '@/Components/completecomponent.vue';
function redirectTo(routeName) {
    // Código de la función aquí...
    console.log('Redirigiendo a:', routeName);
    switch (routeName) {
        case 'return':
            window.location.href = '/dashboard'; // Reemplaza con la ruta de Laravel
            break;
    }
}

</script>

<template>
    
    <Head title="Registro Completo" />

    <AuthenticatedLayout>
        <div class="my-8">
            <v-card class="mx-auto" max-width="1000">
                <v-card-item class="bg-dark dark:bg-purple-700" >
                    <v-card-title>
                        <v-btn class="text-none"  variant="text" density="compact" icon="mdi-arrow-left" @click="redirectTo('return')"></v-btn>
                        <span class="text-gray-200">  Documentos Administrativos</span>
                    </v-card-title>
                </v-card-item>
                <v-list>
                    <completecomponent></completecomponent>
                </v-list>
            </v-card>
        </div>

    </AuthenticatedLayout>
</template>