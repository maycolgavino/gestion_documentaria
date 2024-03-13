<template>
    <v-card class="mx-auto" max-width="944" variant="flat">
        <v-container>
            <v-text-field v-model="searchDni" dense placeholder="Buscar por DNI" prepend-inner-icon="mdi-magnify"
                variant="outlined"></v-text-field>
            <v-btn class="text-none" color="blue-grey-darken-4" @click="buscarAlumno">Buscar</v-btn>
        </v-container>

        <div>
            <v-card v-if="alumno" class="mb-4" variant="outlined">
                <v-card-title>Detalles del Alumno</v-card-title>
                <v-spacer></v-spacer>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>Nombre del Alumno: {{ alumno.nombre }}</v-list-item-title>
                        <v-list-item-subtitle>Carrera: {{ alumno.carrera }}</v-list-item-subtitle>
                        <v-list-item-subtitle>Caja de Documentos: {{ alumno.caja }}</v-list-item-subtitle>
                        <v-list-item-subtitle>Observaciones: {{ alumno.observaciones }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-card>
        </div>

        <div>
            <v-card v-if="alumno" class="mx-auto" max-width="944">
                <v-card-title>Documentos Subidos</v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">ID</th>
                            <th class="text-left">Grado</th>
                            <th class="text-left">Tipo de Documento</th>
                            <th class="text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(uploaded, index) in archivosList" :key="uploaded.id">
                            <td>{{ uploaded.id }}</td>
                            <td>{{ uploaded.grado }}</td>
                            <td>{{ uploaded.tipo }}</td>
                            <td>
                                <v-btn icon @click="confirmarEliminacion(uploaded.id)">
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
                <v-card-title>Nuevos Documentos a subir</v-card-title>
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">ID</th>
                            <th class="text-left">Grado</th>
                            <th class="text-left">Tipo de Documento</th>
                            <th class="text-left">Nombre del Archivo</th>
                            <th class="text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(documento, index) in archivosListNew" :key="index">
                            <td>{{ documento.index }}</td>
                            <td>{{ documento.grado }}</td>
                            <td>{{ documento.tipoDocumento }}</td>
                            <td>{{ documento.nombreArchivo.name }}</td>
                            <td>
                                <v-btn icon @click="eliminarArchivoNuevo(index)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <div class="text-center pa-4" >
                    <v-btn @click="dialog = true" v-if="alumno">AGREGAR DOCUMENTO</v-btn>
                    <v-dialog v-model="dialog" width="440">
                        <v-card max-width="740">
                            <v-card-title>Subir Documento</v-card-title>
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
                                <v-btn color="blue darken-1" text @click="agregarDocumento">Agregar</v-btn>
                                <v-btn color="grey darken-1" text @click="dialog = false">Cancelar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
            </v-card>
        </div>

        <v-dialog v-model="confirmDialog" max-width="500">
            <v-card>
                <v-card-title>Confirmar Eliminación</v-card-title>
                <v-card-text>¿Estás seguro de que deseas eliminar este archivo?</v-card-text>
                <v-card-actions>
                    <v-btn color="red" text @click="eliminarArchivoConfirmado">Sí</v-btn>
                    <v-btn color="grey" text @click="confirmDialog = false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" :timeout="snackbarTimeout" color="red">
            {{ snackbarText }}
            <v-btn color="white" text @click="snackbar = false">Cerrar</v-btn>
        </v-snackbar>

        <v-card-actions v-if="alumno">
            <v-spacer></v-spacer>
            <v-btn color="blue-grey-darken-4" @click="completarRegistro">
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
            archivoIdAEliminar: null,
            archivosParaEliminar: [],
            documento: {
                grado: '',
                tipoDocumento: '',
                nombreArchivo: null
            },
            grados: ["BACHILLER", "TITULO", "MAESTRIA", "DOCTORADO", "ESPECIALIDAD", "ESPECIALIDAD 2", "CONVALIDACION", "ALUMNO"],
            tiposDocumento: ["ACTA", "RESOLUCION", "DIPLOMA", "OTRO"],
            snackbar: false,
            snackbarText: '',
            snackbarTimeout: 3000
        };
    },
    methods: {
        completarRegistro() {
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
                        console.log('Documentos subidos exitosamente:', response.data);
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
                    this.alumno = response.data.alumno;
                    this.archivosList = response.data.documentos;
                })
                .catch(error => {
                    console.error("Error al buscar al alumno:", error);
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
