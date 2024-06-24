<template>
    <v-card class="mx-auto" max-width="944" flat>
        <v-card-title>
            <div class="text-center" style="font-weight: bold; ">Busqueda de Documentos de Silabo</div>
        </v-card-title>
        <v-container>
            <v-text-field v-model="searchQuery" dense placeholder="Buscar por facultad, escuela, año o código de curso"
                prepend-inner-icon="mdi-magnify" variant="outlined" @keyup.enter="buscarSilabos">
            </v-text-field>
            <div class="d-flex justify-center">
                <v-btn class="mr-4 text-none text-subtitle-1" variant="elevated" color="blue-darken-3"
                    @click="buscarSilabos">Buscar</v-btn>
            </div>
        </v-container>
        <div v-if="mostrarTabla">
            <v-card class="mx-auto" max-width="944">
                <v-card-title>Documentos Subidos</v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left"></th>
                            <th class="text-left">Código de Silabo</th>
                            <th class="text-left">Carrera o Programa de Estudios</th>
                            <th class="text-left">Curso</th>
                            <th class="text-left">Año de Silabo</th>
                            <th class="text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(detsilabo, index) in archivosList" :key="detsilabo.codigo">
                            <td>{{ index + 1 }}</td>
                            <td>{{ detsilabo.id }}</td>
                            <td>{{ detsilabo.carrera }}</td>
                            <td>{{ detsilabo.curso }}</td>
                            <td>{{ detsilabo.anio }}</td>
                            <td>
                                <v-btn icon @click="descargarDocumento(detsilabo.id)"  rounded="xl"
                                    color="green-darken-3">
                                    <v-icon>mdi-download</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>
        </div>
        <!-- Snackbar -->
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
            searchQuery: '', // Almacena la consulta de búsqueda
            archivosList: [], // Almacena los resultados de la búsqueda
            mostrarTabla: false, // Controla si se muestra la tabla
            snackbar: {
                show: false,
                text: '',
                color: '',
                timeout: 6000
            }
        };
    },
    methods: {
        // Método para mostrar el snackbar
        mostrarSnackbar(text, color) {
            this.snackbar.text = text;
            this.snackbar.color = color;
            this.snackbar.show = true;
        },
        // Método para buscar sílabos basado en la consulta
        buscarSilabos() {
            axios.get(`/syllabus_details`, { params: { query: this.searchQuery } })
                .then(response => {
                    this.archivosList = response.data.data;
                    this.mostrarTabla = this.archivosList.length > 0;
                    if (this.mostrarTabla) {
                        this.mostrarSnackbar('Silabos encontrados.', 'success');
                    } else {
                        this.mostrarSnackbar('No se encontraron documentos.', 'warning');
                    }
                })
                .catch(error => {
                    console.error("Error al buscar sílabos:", error);
                    this.mostrarSnackbar('Error al buscar documentos. Por favor, intente nuevamente.', 'error');
                });
        },
        // Método para descargar un documento
        descargarDocumento(id) {
            // Asegúrate de tener configurado correctamente el endpoint en tu backend
            window.open(`/syllabus_download/${id}`, '_blank');
        },
    }
};

</script>