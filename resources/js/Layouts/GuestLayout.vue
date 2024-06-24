<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <v-parallax
    src="/images/background.jpg" height="100vh">
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>

                <ApplicationLogo> </ApplicationLogo>

            </div>

            <div
                class="w-full sm:max-w-md mt-6 px-6 py-4 bg-dark dark:bg-sky-900 shadow-md overflow-hidden sm:rounded-lg">
                <slot />
            </div>
        </div>
    </v-parallax>
</template>
