<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
    class="border-gray-300 focus:border-blue-300 focus:ring-blue-300 rounded-md shadow-sm"
        v-model="model" ref="input" />
</template>
<style>
.dark .input {
    border-color: #cbd5e0;
    /* Esto es para un gris claro */
    background-color: #1a202c;
    /* Esto es para un azul oscuro */
    color: #e2e8f0;
    /* Esto es para un texto gris claro */
}

.dark .input:focus {
    border-color: #93c5fd;
    /* Esto es para un azul claro al enfocar */
    box-shadow: 0 0 0 1px #93c5fd;
    /* Esto agrega un anillo de sombra alrededor del input cuando está enfocado */
    outline: none;
}
</style>