<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainComponent from '@/Components/MainComponent.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Archivo Central - Logeado" />

    <AuthenticatedLayout>
        <div class="my-8">
            <v-card class="mx-auto" max-width="1000">
                <v-card-item class="bg-dark dark:bg-blue-700">
                    <v-card-title>
                        <span class="text-gray-100">Mis Acciones</span>
                    </v-card-title>
                </v-card-item>
                <v-card-item class="bg-dark dark:bg-neutral-50" >
                    <main-component></main-component>
                </v-card-item>
                
            </v-card>
        </div>

    </AuthenticatedLayout>
</template>

<style></style>