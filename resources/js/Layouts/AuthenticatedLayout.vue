<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <v-parallax
        src="https://images.unsplash.com/photo-1481151500463-1fa2dd2d5dbe?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8b2ZmaWNlJTIwZGFya3xlbnwwfHwwfHx8MA%3D%3D"
        height="280vh">
        <div>
            <div class="min-h-screen">
                <nav class="bg-dark dark:bg-sky-900">
                    <!-- Primary Navigation Menu -->
                    <div class="max-w-5xl mx-auto px-2 sm:px-2 lg:px-6">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-dark-900" />
                                    </Link>
                                </div>

                                <!-- Navigation Links -->
                                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                                        class="text-gray-200 ">
                                        ARCHIVO CENTRAL
                                    </NavLink>
                                </div>
                                <!-- texto central -->
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                                <!-- Settings Dropdown -->
                                <div class="ml-3 relative">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <v-btn icon>
                                                    <v-icon size="36" color="sky">mdi-account-circle</v-icon>
                                                </v-btn>
                                            </span>
                                        </template>

                                        <template #content>
                                            <DropdownLink :href="route('profile.edit')" class="text-gray-200"> Cuenta
                                            </DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button"
                                                class="text-gray-200"> Salir </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>

                            <!-- Hamburger -->
                            <div class="-mr-2 flex items-center sm:hidden">
                                <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-blue-500 hover:text-blue-700 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-blue-700 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path
                                            :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                            strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
                                        <path
                                            :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                            strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }"
                        class="sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"
                                class="bg-dark dark:bg-gray-700 hover:text-blue-600">
                                Dashboard
                            </ResponsiveNavLink>
                        </div>

                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-blue-300">
                            <div class="px-4">
                                <div class="font-medium text-base text-gray-600">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-blue-500">{{ $page.props.auth.user.email }}</div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')"
                                    class="text-gray-800 hover:text-blue-600">
                                    Profile
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                                    class="text-gray-600 hover:text-blue-600"> Log Out </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Heading -->
                <header class="bg-blue-200 shadow" v-if="$slots.header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </v-parallax>



</template>

<style></style>