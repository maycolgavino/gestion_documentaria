<template>
    <div>
        <!-- Academic Search -->
        <v-card class="mx-auto" max-width="900">
            <v-list lines="two">
                <v-list-item prepend-icon="mdi-school" title="Búsqueda Académica">
                    <template v-slot:subtitle>
                        <span class="font-weight-bold">Ali Connors</span> &mdash; I'll be in your neighborhood doing
                        errands this weekend. Do you want to hang out?
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
                    </template>
                </v-list-item>

                <v-divider></v-divider>

                <v-list-item prepend-icon="mdi-file-document">

                    <template v-slot:title>
                        Búsqueda Documentaria
                    </template>

                    <template v-slot:subtitle>
                        <span class="font-weight-bold">to Alex, Scott, Jennifer</span> &mdash; Wish I could come, but
                        I'm out of town this weekend.
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
                    </template>
                </v-list-item>
            </v-list>
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
                case 'busqueda_ac':
                    window.location.href = '/look_ac'; // Reemplaza con la ruta de Laravel
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