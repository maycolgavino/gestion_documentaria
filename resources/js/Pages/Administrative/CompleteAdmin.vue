<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Head } from '@inertiajs/vue3';


function redirectTo(routeName) {
    // Código de la función aquí...
    console.log('Redirigiendo a:', routeName);
    switch (routeName) {
        case 'return':
            window.location.href = '/dashboard'; // Reemplaza con la ruta de Laravel
            break;
        case 'busqueda':
            window.location.href = '/look_ad'; // Reemplaza con la ruta de Laravel
            break;
        case 'emitir':
            window.location.href = '/add_ad'; // Reemplaza con la ruta de Laravel
            break;
    }
}
</script>

<template>

    <Head title="Registro Completo" />

    <AuthenticatedLayout>
        <div class="my-8">
            <v-card class="mx-auto" max-width="1000">
                <v-toolbar color="blue-darken-3">
                    <v-btn icon @click="redirectTo('return')">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                    <v-toolbar-title class="text-gray-200">Documentos Administrativos</v-toolbar-title>
                </v-toolbar>
                <v-list>
                    <v-sheet class="pa-4 text-center mx-auto" max-width="600" rounded="lg" width="100%" flat>
                        <v-icon class="mb-5" color="success" icon="mdi-check-circle" size="112"></v-icon>

                        <h2 class="text-h5 mb-6">Tus archivos han sido cargados y/o actualizados de manera EXITOSA</h2>

                        <p class="">
                            Podrás encontrar todos tus documentos en la sección, <a
                                class="text-decoration-none text-info" href="#"
                                @click.prevent="redirectTo('busqueda')">BÚSQUEDA.</a>
                            <br>
                        </p>

                        <v-divider class="mb-4"></v-divider>

                        <div class="d-flex justify-center">
                            <!-- Clase d-flex para flexbox y justify-center para centrar horizontalmente -->
                            <v-btn class="mr-4 text-none text-subtitle-1"  variant="outline" color="black" @click="redirectTo('return')">
                                Página Principal
                                <v-icon end icon="mdi-home"></v-icon>
                            </v-btn>
                            <v-btn class="mr-4 text-none text-subtitle-1" variant="text" color="black" @click="redirectTo('busqueda')">
                                Buscar
                                <v-icon end icon="mdi-magnify"></v-icon>
                            </v-btn>
                            <v-btn class="mr-4 text-none text-subtitle-1" variant="elevated" color="blue-darken-4" @click="redirectTo('emitir')">
                                Cargar Otro
                                <v-icon end icon="mdi-arrow-u-down-left-bold"></v-icon>
                            </v-btn>
                        </div>
                    </v-sheet>

                </v-list>
            </v-card>

        </div>
    </AuthenticatedLayout>
</template>
