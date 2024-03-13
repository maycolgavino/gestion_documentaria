<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainComponent from '@/Components/MainComponent.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <v-card class="mx-auto" max-width="1000">
            <v-card-item class="bg-gray-custom">
                <v-card-title>
                    <span>Mis Acciones</span>
                </v-card-title>
            </v-card-item>
            <v-list>
                <main-component></main-component>
            </v-list>
        </v-card>
    </AuthenticatedLayout>
</template>

<style>
.bg-gray-custom {
    background-color: #1e87f0;
    /* Este es el color gris que quieres usar */
}
</style>