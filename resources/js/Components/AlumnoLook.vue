<template>
    <v-card class="mx-auto" max-width="2044" variant="flat">
        <v-container>
            <v-text-field v-model="searchAlum" dense placeholder="Buscar por DNI, Código de Matricula, Nombre Completo" prepend-inner-icon="mdi-magnify"
                variant="outlined" @keyup.enter="buscarAlumno" >
            </v-text-field>
            <div class="d-flex justify-center">
                <v-btn class="mr-4 text-none text-subtitle-1" variant="elevated" color="blue-darken-3"
                    @click="buscarAlumno" >Buscar</v-btn>
            </div>
            <!-- Snackbar para mostrar mensaje cuando no se encuentra el alumno -->
            <v-snackbar v-model="snackbarError" color="error" top>
                No se encontró ningún alumno con ese DNI.
            </v-snackbar>
            <!-- Snackbar para mostrar mensaje de éxito cuando se encuentra el alumno -->
            <v-snackbar v-model="snackbarSuccess" color="success" top>
                Se encontró el alumno correctamente.
            </v-snackbar>
        </v-container>

        <div class="d-flex justify-center">
            <v-card v-if="alumno" class="mb-4" variant="tonal" max-width="3000px">
                <v-card-title class="justify-center">
                    <div class="text-center">Detalles del Estudiante</div>
                </v-card-title>
                <v-divider></v-divider>
                <v-table>
                    <thead>
                        <tr>
                            <th>
                                <v-avatar size="96">
                                    <img src="/images/student.png" alt="avatar">
                                </v-avatar>
                            </th>
                            <th>
                                <v-list>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Nombre:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.nombre }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Carrera:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.carrera }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Caja de
                                                Documentos:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.caja }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">DNI:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.dni }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Código de Matricula:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.matricula_code }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                class="font-weight-bold">Observaciones:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.observaciones }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                class="font-weight-bold">Año de Bachiller:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_bachiller }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                class="font-weight-bold">Año de Titulo:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_titulo }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                class="font-weight-bold">Año de Maestria:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_maestria }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                class="font-weight-bold">Año de Doctorado:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_doctorado }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                class="font-weight-bold">Año de Especialidad 1:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_especialidad1 }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title
                                                class="font-weight-bold">Año de Especialidad 2:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_especialidad2 }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                </v-list>
                            </th>
                        </tr>
                    </thead>
                </v-table>
            </v-card>
        </div>
        
        <div>
            <v-card v-if="alumno" class="justify-center" max-width="944">
                <v-card-title class="justify-center">
                    <div class="text-center">Documentos Pertenecientes al Alumno</div>
                </v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left"></th>
                            <th class="text-left">GRADO</th>
                            <th class="text-left">TIPO DE DOCUMENTO</th>
                            <th class="text-left">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(archivo, index) in archivosList" :key="archivo.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ archivo.grado }}</td>
                            <td>{{ archivo.tipo }}</td>
                            <td>
                                <v-btn icon @click="descargarDocumento(archivo.id)" variant="outlined" rounded="xl"
                                    color="green-darken-3">
                                    <v-icon>mdi-download</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>
            <v-divider></v-divider>
            <v-divider></v-divider>
        </div>
    </v-card>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            alumno: null,
            searchAlum: '', // Almacena la consulta de búsqueda
            archivosList: [], // Almacena los resultados de la búsqueda
            snackbarError: false, // Controla la visibilidad del snackbar de error
            snackbarSuccess: false, // Controla la visibilidad del snackbar de éxito
            snackbarTimeout: 100
        };
    },
    methods: {
        // Método para buscar sílabos basado en la consulta
        buscarAlumno() {
            axios.get(`/acad_details`, { params: { search: this.searchAlum } })
                .then(response => {
                    if (response.data.alumno) {
                        this.alumno = response.data.alumno;
                        this.archivosList = response.data.documentos;
                        this.snackbarError = false; // Oculta el snackbar de error
                        this.snackbarSuccess = true; // Muestra el snackbar de éxito
                    } else {
                        this.alumno = null;
                        this.archivosList = [];
                        this.snackbarError = true; // Muestra el snackbar de error si no se encontró el alumno
                        this.snackbarSuccess = false; // Oculta el snackbar de éxito
                    }
                })
                .catch(error => {
                    console.error("Error al buscar al alumno:", error);
                    this.snackbarError = true; // Muestra el snackbar de error en caso de error
                    this.snackbarSuccess = false; // Oculta el snackbar de éxito
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