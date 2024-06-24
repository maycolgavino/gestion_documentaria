<template>
    <v-card class="mx-auto" max-width="944" variant="flat">
        <v-container>
            <v-text-field v-model="searchDni" dense placeholder="Buscar por DNI" prepend-inner-icon="mdi-magnify"
                variant="outlined" @keyup.enter="buscarAlumno" maxlength="8">
            </v-text-field>
            <div class="d-flex justify-center">
                <v-btn class="mr-4 text-none text-subtitle-1" variant="elevated" color="blue-darken-3"
                    @click="buscarAlumno">Buscar</v-btn>
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
            <v-card v-if="alumno" class="mb-4" variant="tonal" max-width="600px">
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
                                            <v-list-item-title
                                                class="font-weight-bold">Observaciones:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.observaciones }}</v-list-item-subtitle>
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
            <v-card v-if="alumno" class="mx-auto" max-width="944">
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
                        <tr v-for="(uploaded, index) in archivosList" :key="uploaded.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ uploaded.grado }}</td>
                            <td>{{ uploaded.tipo }}</td>
                            <td>
                                <v-btn icon @click="confirmarEliminacion(uploaded.id)" variant="outlined" rounded="xl"
                                    color="red-darken-3">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>
        </div>

        <div>

            <v-card v-if="alumno" class="mx-auto" max-width="944">
                <v-card-title class="justify-center">
                    <div class="text-center" style="font-weight: bold; text-decoration: underline;">Agregar nuevos
                        documentos aquí</div>
                </v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left"></th>
                            <th class="text-left">GRADO</th>
                            <th class="text-left">TIPO DE DOCUMENTO</th>
                            <th class="text-left">ARCHIVO</th>
                            <th class="text-left">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(documento, index) in archivosListNew" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ documento.grado }}</td>
                            <td>{{ documento.tipoDocumento }}</td>
                            <td>{{ documento.nombreArchivo.name }}</td>
                            <td>
                                <v-btn icon @click="eliminarArchivoNuevo(index)" variant="outlined" rounded="xl"
                                    color="red-darken-3">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <div class="text-center pa-4">
                    <v-btn class="mr-4 text-none text-subtitle-1" variant="outlined" color="blue-darken-3"
                        prepend-icon="mdi-plus-box" stacked @click="dialog = true">Agregar Documento</v-btn>
                    <v-dialog v-model="dialog" width="440" persistent @click:outside="() => { }">
                        <v-card max-width="740">
                            <v-card-title>
                                <div class="text-center" style="font-weight: bold; ">Adjuntar Documentos</div>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-select v-model="documento.grado" :items="grados" label="Grado"
                                        required></v-select>
                                    <v-select v-model="documento.tipoDocumento" :items="tiposDocumento"
                                        label="Tipo de Documento" required></v-select>
                                    <input type="file" @change="onFileSelected" class="file-input" />
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn class="mr-4 text-none text-body-1" variant="elevated" color="blue-darken-3" @click="agregarEditarArchivo">Agregar</v-btn>
                                <v-btn class="mr-4 text-none text-body-1" variant="outlined" color="red-darken-4" @click="dialog = false">Cancelar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
            </v-card>
        </div>

        <v-dialog v-model="confirmDialog" max-width="500" persistent>
            <v-card>
                <v-card-title>Confirmar Eliminación</v-card-title>
                <v-card-text>¿Estás seguro de que deseas eliminar este archivo?</v-card-text>
                <v-card-actions>
                    <v-btn color="red" text @click="eliminarArchivoConfirmado">Sí</v-btn>
                    <v-btn color="grey" text @click="confirmDialog = false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="confirmDialogComplete" max-width="500">
            <v-card>
                <v-card-title>Confirmar Completar Registro</v-card-title>
                <v-card-text>¿Estás seguro de que deseas completar el registro?</v-card-text>
                <v-card-actions>
                    <v-btn color="green darken-1" text @click="completarRegistro">Sí</v-btn>
                    <v-btn color="grey darken-1" text @click="confirmDialogComplete = false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" :timeout="snackbarTimeout" color="red">
            {{ snackbarText }}
            <v-btn color="white" text @click="snackbar = false">Cerrar</v-btn>
        </v-snackbar>

        <v-card-actions v-if="alumno">
            <v-spacer></v-spacer>
            <v-btn class="mr-4 text-none text-subtitle-1" variant="elevated" color="blue-darken-3"
                @click="confirmDialog = true">
                Completar Registro
                <v-icon right>mdi-chevron-right</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            alumno: null,
            searchDni: '',
            archivosList: [],
            archivosListNew: [],
            dialog: false,
            confirmDialog: false,
            confirmDialogComplete: false,
            archivoIdAEliminar: null,
            archivosParaEliminar: [],
            documento: {
                grado: '',
                tipoDocumento: '',
                nombreArchivo: null
            },
            grados: ["BACHILLER", "TITULO", "MAESTRIA", "DOCTORADO", "ESPECIALIDAD", "ESPECIALIDAD 2", "CONVALIDACION", "ALUMNO"],
            tiposDocumento: ["ACTA", "RESOLUCION", "DIPLOMA", "DNI", "CERTIFICADO DE ESTUDIOS", "CONSTANCIA DE INGRESO"],
            snackbarError: false, // Controla la visibilidad del snackbar de error
            snackbarSuccess: false, // Controla la visibilidad del snackbar
            snackbar: false,
            snackbarText: '',
            snackbarTimeout: 3000
        };
    },
    methods: {

        async dialogoCompleto() {
            this.confirmDialogComplete = true; // Mostrar el diálogo de confirmación
        },

        async completarRegistro() {
            this.confirmDialogComplete = false;
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('dni', this.alumno.dni);

                this.archivosListNew.forEach((documento, index) => {
                    formData.append(`documentos[${index}][documento]`, documento.nombreArchivo);
                    formData.append(`documentos[${index}][grado]`, documento.grado);
                    formData.append(`documentos[${index}][tipo]`, documento.tipoDocumento);
                });

                this.archivosParaEliminar.forEach((id, index) => {
                    formData.append(`archivoParaEliminar[${index}]`, id);
                });

                const uploadUrl = '/update_documents'; // Ajusta la URL según tu API
                axios.post(uploadUrl, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                })
                    .then(response => {
                        window.location.href = '/completeform_acad';
                        resolve(response);
                    })
                    .catch(error => {
                        this.showSnackbar("Error durante la subida de documentos. Por favor, inténtalo de nuevo.");
                        console.error('Error durante la subida de documentos:', error);
                        reject(error);
                    });
            });
        },
        buscarAlumno() {
            axios.get(`/acad_details`, { params: { dni: this.searchDni } })
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
        onFileSelected(event) {
            this.documento.nombreArchivo = event.target.files[0];
        },
        confirmarEliminacion(id) {
            this.archivoIdAEliminar = id;
            this.confirmDialog = true;
        },
        eliminarArchivoConfirmado() {
            if (this.archivoIdAEliminar !== null) {
                this.archivosParaEliminar.push(this.archivoIdAEliminar);
                this.archivosList = this.archivosList.filter(uploaded => uploaded.id !== this.archivoIdAEliminar);
                this.archivoIdAEliminar = null;
                this.showSnackbar("Archivo marcado para eliminación.");
            }
            this.confirmDialog = false;
        },
        agregarDocumento() {
            if (!this.documento.grado || !this.documento.tipoDocumento || !this.documento.nombreArchivo) {
                this.showSnackbar("Por favor, completa todos los campos.");
                return;
            }

            const archivoExistente = this.archivosList.some(uploaded =>
                uploaded.grado === this.documento.grado && uploaded.tipo === this.documento.tipoDocumento
            );

            if (archivoExistente) {
                this.showSnackbar("Ya existe un archivo con el mismo grado y tipo de documento.");
                return;
            }

            this.archivosListNew.push({
                grado: this.documento.grado,
                tipoDocumento: this.documento.tipoDocumento,
                nombreArchivo: this.documento.nombreArchivo
            });
            this.dialog = false;
            this.resetForm();
        },
        eliminarArchivoNuevo(index) {
            this.archivosListNew.splice(index, 1);
        },
        resetForm() {
            this.documento.grado = '';
            this.documento.tipoDocumento = '';
            this.documento.nombreArchivo = null;
        },
        showSnackbar(message) {
            this.snackbarText = message;
            this.snackbar = true;
        }
    }
};
</script>
