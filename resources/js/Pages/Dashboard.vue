<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainComponent from '@/Components/MainComponent.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-blue-800 dark:text-blue-200 leading-tight">Dashboard</h2>
        </template>
        <main-component></main-component>
    </AuthenticatedLayout>
</template>
