<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import  AdministrativeLook from '@/Components/AdministrativeLook.vue';
import { Head } from '@inertiajs/vue3';
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

    <Head title="Documentos Administrativos ARCHIVO CENTRAL" />

    <AuthenticatedLayout>
        <div class="my-8">
            <v-card class="mx-auto" max-width="1000">
                <v-toolbar color="blue-darken-3">
                    <v-btn icon="mdi-arrow-left" @click="redirectTo('return')" class="text-none" density="compact"></v-btn>
                    <v-toolbar-title class="text-gray-100">
                        Buscar
                        <span class="text-sm text-gray-300 ml-2">Resoluciones</span>
                    </v-toolbar-title>
                </v-toolbar>
            <v-list>
                <AdministrativeLook></AdministrativeLook>
            </v-list>
        </v-card>
        </div>

    </AuthenticatedLayout>
</template>