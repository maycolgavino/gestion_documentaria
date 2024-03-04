<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddAcademicRegister from '@/Components/AddAcademicRegister.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <v-card class="mx-auto" max-width="1000">
            <v-card-item class="bg-cyan-darken-1">
                <v-card-title>
                    <v-btn density="compact" icon="mdi-arrow-left-circle"></v-btn>  <span>Agregar Documentos</span>
                </v-card-title>
            </v-card-item>
            <v-list>
                <AddAcademicRegister></AddAcademicRegister>
            </v-list>
        </v-card>
    </AuthenticatedLayout>
</template>