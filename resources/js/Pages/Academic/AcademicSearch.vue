<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import  AlumnoLook from '@/Components/AlumnoLook.vue';
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

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <v-card class="mx-auto" max-width="1000">
            <v-card-item class="bg-cyan-darken-1">
                <v-card-title>
                    <v-btn density="compact" icon="mdi-arrow-left" @click="redirectTo('return')"></v-btn>
                    <span>Buscar Registros Academicos</span>
                </v-card-title>
            </v-card-item>
            <v-list>
                <AlumnoLook></AlumnoLook>
            </v-list>
        </v-card>
    </AuthenticatedLayout>
</template>