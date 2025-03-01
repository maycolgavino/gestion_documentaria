<template>
    <v-card class="mx-auto" max-width="944" variant="flat">
        <v-container>
            <v-text-field v-model="searchAlum" dense placeholder="Buscar por DNI, Código de Matricula, Nombre Completo"
                prepend-inner-icon="mdi-magnify" variant="outlined" @keyup.enter="buscarAlumno">
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
                <!-- Botón de lápiz para editar -->
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
                                            <v-list-item-title class="font-weight-bold">Código de
                                                Matricula:</v-list-item-title>
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
                                            <v-list-item-title class="font-weight-bold">Año de
                                                Bachiller:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_bachiller }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Año de
                                                Titulo:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_titulo }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Año de
                                                Maestria:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_maestria }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Año de
                                                Doctorado:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_doctorado }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Año de Especialidad
                                                1:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_especialidad1 }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title class="font-weight-bold">Año de Especialidad
                                                2:</v-list-item-title>
                                            <v-list-item-subtitle>{{ alumno.anio_especialidad2 }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>

                                </v-list>
                            </th>
                        </tr>
                    </thead>
                    <v-btn icon @click="openDialog" class="justify-center">
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                </v-table>
                <!-- Dialogo para editar los campos -->
                <v-dialog v-model="Editdialog" max-width="600px">
                    <v-card>
                        <v-card-title class="headline">Editar Datos</v-card-title>
                        <v-card-text>
                            <v-form ref="form">
                                <v-text-field label="Nombre" v-model="editedStudent.nombre"></v-text-field>
                                <v-text-field label="Carrera" v-model="editedStudent.carrera"></v-text-field>
                                <v-text-field label="DNI" v-model="editedStudent.dni"></v-text-field>
                                <v-text-field label="Código de Matrícula"
                                    v-model="editedStudent.matricula_code"></v-text-field>
                                <v-text-field label="Caja de Documentos" v-model="editedStudent.caja"></v-text-field>

                                <v-text-field label="Año de Bachiller"
                                    v-model="editedStudent.anio_bachiller"></v-text-field>
                                <v-text-field label="Año de Titulo" v-model="editedStudent.anio_titulo"></v-text-field>
                                <v-text-field label="Año de Maestria"
                                    v-model="editedStudent.anio_maestria"></v-text-field>
                                <v-text-field label="Año de Doctorado"
                                    v-model="editedStudent.anio_doctorado"></v-text-field>
                                <v-text-field label="Año de Especialidad 1"
                                    v-model="editedStudent.anio_especialidad1"></v-text-field>
                                <v-text-field label="Año de Especialidad 2"
                                    v-model="editedStudent.anio_especialidad2"></v-text-field>

                                <v-textarea label="Observaciones" v-model="editedStudent.observaciones"></v-textarea>

                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="closeDialog">Cancelar</v-btn>
                            <v-btn color="blue darken-1" text @click="saveChanges">Guardar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
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
                                <v-btn class="mr-4 text-none text-body-1" variant="elevated" color="blue-darken-3"
                                    @click="agregarDocumento">Agregar</v-btn>
                                <v-btn class="mr-4 text-none text-body-1" variant="outlined" color="red-darken-4"
                                    @click="dialog = false">Cancelar</v-btn>
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
                    <v-btn color="blue-darken-3" variant="outlined" @click="completarRegistro">Sí</v-btn>
                    <v-btn color="red-darken-2" variant="elevated"
                        @click="confirmDialogComplete = false">Cancelar</v-btn>
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
                @click="dialogoCompleto">
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
            searchAlum: '',
            archivosList: [],
            archivosListNew: [],
            Editdialog: false,
            dialog: false,
            confirmDialog: false,
            confirmDialogComplete: false,
            archivoIdAEliminar: null,
            archivosParaEliminar: [],
            editedStudent: {},
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
    watch: {
        editedStudent: {
            handler: async function () {
                // Actualizar datos del alumno automáticamente cuando cambian los valores
                await this.actualizarDatosAlumno();
            },
            deep: true
        }
    },
    methods: {

        async dialogoCompleto() {
            this.confirmDialogComplete = true; // Mostrar el diálogo de confirmación
        },

        async completarRegistro() {
            this.confirmDialogComplete = false;
            try {
                const formData = new FormData();
                formData.append('alumno_id', this.alumno.id); // Enviar el ID del alumno

                // Añadir los documentos nuevos al formulario
                this.archivosListNew.forEach((documento, index) => {
                    formData.append(`documentos[${index}][documento]`, documento.nombreArchivo);
                    formData.append(`documentos[${index}][grado]`, documento.grado);
                    formData.append(`documentos[${index}][tipo]`, documento.tipoDocumento);
                });

                // Añadir los documentos a eliminar al formulario
                this.archivosParaEliminar.forEach((id, index) => {
                    formData.append(`archivoParaEliminar[${index}]`, id);
                });

                const uploadUrl = '/update_documents'; // URL del método updateDocuments en el servidor
                const response = await axios.post(uploadUrl, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                });

                // Mostrar mensaje de éxito y redirigir si es necesario
                this.showSnackbar("Documentos actualizados exitosamente.");
                window.location.href = '/completeform_acad';
            } catch (error) {
                // Mostrar mensaje de error
                this.showSnackbar("Error durante la subida de documentos. Por favor, inténtalo de nuevo.");
                console.error('Error durante la subida de documentos:', error);
            }
        },
        openDialog() {
            // Clonar los datos del estudiante actual para editarlos en el dialog
            this.editedStudent = { ...this.alumno };
            this.Editdialog = true;
        },
        closeDialog() {
            this.Editdialog = false;
        },
        async saveChanges() {
            try {

                // Aquí puedes llamar a tu controlador para guardar los datos actualizados
                await this.saveStudentData();

                // Actualizar los datos del alumno en tiempo real
                await this.actualizarDatosAlumno();

                this.Editdialog = false;
                this.snackbarSuccess = true; // Mostrar mensaje de éxito
            } catch (error) {
                console.error('Error al guardar los cambios:', error);
                this.snackbarError = true; // Mostrar mensaje de error
            }
        },

        async saveStudentData() {
            // Asegúrate de que la URL y el método HTTP sean correctos según tu API
            const updateUrl = '/update_student';
            const response = await axios.post(updateUrl, this.editedStudent);
            return response.data;
        },

        async actualizarDatosAlumno() {
            try {
                const response = await axios.get(`/acad_details`, { params: { search: this.alumno.id } });
                if (response.data.alumno) {
                    this.alumno = response.data.alumno;
                    this.archivosList = response.data.documentos;
                }
            } catch (error) {
                console.error('Error al actualizar los datos del alumno:', error);
            }
        },
        buscarAlumno() {
            axios.get(`/api/acad_details`, { params: { search: this.searchAlum } })
                .then(response => {
                    if (response.data.alumno) {
                        this.alumno = response.data.alumno;
                        this.archivosList = response.data.documentos;
                        this.snackbarError = false; // Oculta el snackbar de error
                        this.snackbarSuccess = true; // Muestra el snackbar de éxito
                        this.$nextTick(() => {
                            // Asegurar que los datos actualizados se muestren correctamente
                            this.alumno = { ...response.data.alumno };
                        });
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
