<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainComponent from '@/Components/MainComponent.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Archivo Central - Logeado" />

    <AuthenticatedLayout>
        <div class="my-8">

            <v-card class="mx-auto" max-width="1000" variant="flat">
                <v-toolbar color="blue-darken-3">
                    <v-toolbar-title class="text-gray-100">
                        Mis Acciones
                    </v-toolbar-title>
                </v-toolbar>
                <v-card-item class="bg-dark dark:bg-neutral-50" >
                    <main-component></main-component>
                </v-card-item>
            </v-card>
        </div>

    </AuthenticatedLayout>
</template>

<style></style>