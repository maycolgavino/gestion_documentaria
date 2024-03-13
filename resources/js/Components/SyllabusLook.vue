<template>
    <v-card class="mx-auto" max-width="944" title="Busqueda de Documentos de Silabo">
        <v-container>
            <v-text-field v-model="searchQuery" dense placeholder="Buscar por facultad, escuela, año o código de curso"
                prepend-inner-icon="mdi-magnify" variant="outlined">
            </v-text-field>
            <v-btn color="primary" @click="buscarSilabos">Buscar</v-btn> <!-- Botón de búsqueda -->
        </v-container>
        <div>
            <v-card class="mx-auto" max-width="944">
                <v-card-title>Documentos Subidos</v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">Código de Silabo</th>
                            <th class="text-left">Carrera o Programa de Estudios</th>
                            <th class="text-left">Curso</th>
                            <th class="text-left">Año de Silabo</th>
                            <th class="text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(detsilabo, index) in archivosList" :key="detsilabo.codigo">
                            <td>{{ detsilabo.id }}</td>
                            <td>{{ detsilabo.carrera }}</td>
                            <td>{{ detsilabo.curso }}</td>
                            <td>{{ detsilabo.anio }}</td>
                            <td>
                                <v-btn icon @click="descargarDocumento(detsilabo.id)">
                                    <v-icon>mdi-download</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>
        </div>
    </v-card>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            searchQuery: '', // Almacena la consulta de búsqueda
            archivosList: [], // Almacena los resultados de la búsqueda
        };
    },
    methods: {
        // Método para buscar sílabos basado en la consulta
        buscarSilabos() {
            axios.get(`/syllabus_details`, { params: { query: this.searchQuery } })
                .then(response => {
                    this.archivosList = response.data.data;
                })
                .catch(error => {
                    console.error("Error al buscar sílabos:", error);
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