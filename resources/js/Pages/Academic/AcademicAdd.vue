<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddAcademicRegister from '@/Components/AddAcademicRegister.vue';
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

    <Head title="Documentos Academicos" />

    <AuthenticatedLayout>
        <div class="my-8">
            <v-card class="mx-auto" max-width="1000">
                <v-toolbar color="blue-grey-darken-4">
                    <v-btn icon="mdi-arrow-left" @click="redirectTo('return')" class="text-none" density="compact"></v-btn>
                    <v-toolbar-title class="text-gray-100">Agregar Documentos</v-toolbar-title>
                </v-toolbar>
                <v-list>
                    <AddAcademicRegister></AddAcademicRegister>
                </v-list>
            </v-card>
        </div>

    </AuthenticatedLayout>
</template>