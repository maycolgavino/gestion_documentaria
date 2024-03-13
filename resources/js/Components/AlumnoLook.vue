<template>
    <v-card class="mx-auto" max-width="944"  variant="flat">
        <v-container>
            <v-text-field v-model="searchDni" dense placeholder="Buscar por DNI" prepend-inner-icon="mdi-magnify"
                variant="outlined">
            </v-text-field>
            <v-btn class="text-none" color="blue-grey-darken-4" @click="buscarAlumno">Buscar</v-btn> <!-- Botón de búsqueda -->
        </v-container>
        <div>
            <v-card v-if="alumno" class="mb-4" variant="outlined" >
                <v-card-title>Detalles del Alumno:</v-card-title>
                <v-spacer></v-spacer>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ alumno.nombre }}</v-list-item-title>
                        <v-list-item-subtitle>{{ alumno.carrera }}</v-list-item-subtitle>
                        <v-list-item-subtitle>{{ alumno.caja }}</v-list-item-subtitle>
                        <v-list-item-subtitle>{{ alumno.observaciones }}</v-list-item-subtitle>
                        <!-- Agrega aquí más detalles del alumno si es necesario -->
                    </v-list-item-content>
                </v-list-item>
            </v-card>
        </div>
        <div>
            <v-card class="mx-auto" max-width="944">
                <v-card-title>Documentos Subidos</v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">DNI</th>
                            <th class="text-left">Grado</th>
                            <th class="text-left">Tipo de Documento</th>
                            <th class="text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(archivo, index) in archivosList" :key="archivo.id">
                            <td>{{ archivo.dni }}</td>
                            <td>{{ archivo.grado }}</td>
                            <td>{{ archivo.tipo }}</td>
                            <td>
                                <v-btn icon @click="descargarDocumento(archivo.id)">
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
            alumno: null, 
            searchDni: '', // Almacena la consulta de búsqueda
            archivosList: [], // Almacena los resultados de la búsqueda
        };
    },
    methods: {
        // Método para buscar sílabos basado en la consulta
        buscarAlumno() {
            axios.get(`/acad_details`, { params: { dni: this.searchDni } })
                .then(response => {
                    this.alumno = response.data.alumno;
                    this.archivosList = response.data.documentos;

                })
                .catch(error => {
                    console.error("Error al buscar al alumno:", error);
                });
        },
        // Método para descargar un documento
        descargarDocumento(id) {
            // Asegúrate de tener configurado correctamente el endpoint en tu backend
            window.open(`/acad_download/${id}`, '_blank');
        },

    }
};
</script>