<template>
    <div>
        <!-- Academic Search -->
        <v-card class="mx-auto" width="550" prepend-icon="mdi-school">
            <template v-slot:title>
                Búsqueda Académica
            </template>
            <v-card-text>
                <div>
                    <v-btn class="ma-2" color="white" @click="redirectTo('actualizar_ac')">
                        Actualizar
                        <v-icon end icon="mdi-note-edit"></v-icon>
                    </v-btn>
                    <v-btn class="ma-2" color="white" @click="redirectTo('agregar_ac')">
                        Agregar
                        <v-icon end icon="mdi-pencil"></v-icon>
                    </v-btn>
                    <v-btn class="ma-2" color="white" @click="redirectTo('busqueda_ac')">
                        Búsqueda
                        <v-icon end icon="mdi-clipboard-text-search"></v-icon>
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>

        <!-- Document Search -->
        <v-card class="mx-auto" width="550" prepend-icon="mdi-file-document">

            <template v-slot:title>
                Búsqueda Documentaria

            </template>

            <v-card-text>
                <div>
                    <v-btn class="ma-2" color="white" @click="redirectTo('actualizar_ad')">
                        Actualizar
                        <v-icon end icon="mdi-note-edit"></v-icon>
                    </v-btn>
                    <v-btn class="ma-2" color="white" @click="redirectTo('agregar_ad')">
                        Agregar
                        <v-icon end icon="mdi-pencil"></v-icon>
                    </v-btn>
                    <v-btn class="ma-2" color="white" @click="redirectTo('busqueda_ad')">
                        Búsqueda
                        <v-icon end icon="mdi-clipboard-text-search"></v-icon>
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    methods: {
        redirectTo(routeName) {
            console.log('Redirigiendo a:', routeName);
            switch (routeName) {
                case 'actualizar_ac':
                    window.location.href = '/update_ac'; // Reemplaza con la ruta de Laravel
                    break;
                case 'agregar_ac':
                    window.location.href = '/add_ac'; // Reemplaza con la ruta de Laravel
                    break;
                case 'actualizar_ac':
                    window.location.href = '/update_ac'; // Reemplaza con la ruta de Laravel
                    break;
                case 'actualizar_ad':
                    window.location.href = '/update_ad'; // Reemplaza con la ruta de Laravel
                    break;
                case 'agregar_ad':
                    window.location.href = '/add_ad'; // Reemplaza con la ruta de Laravel
                    break;
                case 'busqueda_ad':
                    window.location.href = '/look_ad'; // Reemplaza con la ruta de Laravel
                    break;
            }
        },
    },
}
</script>

<style lang="">

</style>