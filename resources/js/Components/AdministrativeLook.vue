<template>
    <v-card class="mx-auto" max-width="944" flat>
        <v-card-title>
            <div class="text-center" style="font-weight: bold;">Búsqueda de Resoluciones Universitarias</div>
        </v-card-title>
        <v-container>
            <v-container>
                <!-- Campos adicionales en la parte superior -->
                <v-row>
                    <v-col cols="12" md="6">
                        <v-select density="compact" label="Tipos de Resolucion"
                            prepend-inner-icon="mdi-format-list-bulleted-type" :items="tiposResolucion"
                            variant="outlined" v-model="tipoResSeleccionado" color="blue-darken-3"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-select density="compact" label="Año de Emisión" prepend-inner-icon="mdi-calendar-range"
                            variant="outlined" :items="aniosDisponibles" v-model="anioSeleccionado"
                            color="blue-darken-3"></v-select>
                    </v-col>
                </v-row>
            </v-container>

            <div class="d-flex justify-center">
                <v-btn class="mr-4 text-none text-subtitle-1" variant="elevated" color="blue-darken-3"
                    @click="buscarResoluciones">Buscar</v-btn>
            </div>
        </v-container>

        <div v-if="mostrarTabla">
            <v-card class="mx-auto" max-width="944">
                <v-card-title>
                    <div class="text-center" style="font-weight: bold;">Documentos Adjuntados</div>
                </v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">Número de Res.</th>
                            <th class="text-left">Oficina Emisora</th>
                            <th class="text-left">Oficina Receptora</th>
                            <th class="text-left">Motivo</th>
                            <th class="text-left">Documento</th>
                            <th class="text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(detres, index) in archivosList" :key="detres.codigo">
                            <td>{{ detres.numero }}</td>
                            <td>{{ detres.autor }}</td>
                            <td>{{ detres.receptor }}</td>
                            <td>{{ detres.motivo }}</td>
                            <td>{{ detres.documento }}</td>
                            <td>
                                <v-btn icon @click="descargarDocumento(detres.id)" rounded="xl" color="green-darken-3">
                                    <v-icon>mdi-download</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>
        </div>

        <!-- Snackbar for notifications -->
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" :timeout="snackbar.timeout">
            {{ snackbar.text }}
        </v-snackbar>
    </v-card>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            archivosList: [],
            tipoResSeleccionado: '',
            anioSeleccionado: '',
            mostrarTabla: false, // State to control table visibility
            snackbar: {
                show: false,
                text: '',
                color: 'error',
                timeout: 6000
            },
            tiposResolucion: ["Resolución Directorial Administrativa", "Resolucion de Asamblea Universitaria", "Resolucion Rectoral", "Resolucion de Consejo Universitario"],
            aniosDisponibles: Array.from({ length: 2024 - 1965 + 1 }, (v, k) => 2024 - k)
        };
    },
    methods: {
        // Method to search resolutions
        buscarResoluciones() {
            const params = {
                tipoResolucion: this.tipoResSeleccionado,
                anio: this.anioSeleccionado,
            };

            axios.get('/res_details', { params })
                .then(response => {
                    this.archivosList = response.data.data;
                    this.mostrarTabla = this.archivosList.length > 0;

                    // Show snackbar based on search result
                    if (this.archivosList.length > 0) {
                        this.mostrarSnackbar('Documentos encontrados', 'success');
                    } else {
                        this.mostrarSnackbar('No se encontraron documentos', 'warning');
                    }
                })
                .catch(error => {
                    console.error("Error al buscar resoluciones:", error);
                    this.mostrarSnackbar('Error al buscar resoluciones', 'error');
                });
        },

        // Method to download document
        descargarDocumento(id) {
            window.open(`/res_download/${id}`, '_blank');
        },

        // Method to show snackbar
        mostrarSnackbar(texto, color) {
            this.snackbar.text = texto;
            this.snackbar.color = color;
            this.snackbar.show = true;
        }
    }
};

</script>