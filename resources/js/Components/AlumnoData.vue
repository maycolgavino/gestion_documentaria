<template>
  <v-card class="mx-auto" max-width="944" flat>
    <v-card-title class="justify-center">
      <div class="text-center">Registro del Estudiante</div>
    </v-card-title>
    <v-container>
      <v-text-field density="compact" placeholder="Nombre del Estudiante" prepend-inner-icon="mdi-account"
        variant="outlined" :rules="[rules.required, rules.maxLength(90)]" :counter="90" v-model="alumno.nombre"
        color="blue-darken-3"></v-text-field>

      <v-text-field density="compact" placeholder="Número de Documento" prepend-inner-icon="mdi-id-card"
        variant="outlined" :rules="[rules.required, rules.onlyNumbers, rules.length(8)]" v-model="alumno.dni"
        maxlength="8" color="blue-darken-3"></v-text-field>

      <v-text-field density="compact" placeholder="Codigo de Matricula"
        prepend-inner-icon="mdi-ticket-confirmation-outline" variant="outlined"
        :rules="[rules.required, rules.onlyNumbers, rules.length(10)]" v-model="alumno.matricula_code" maxlength="10"
        color="blue-darken-3"></v-text-field>

      <v-text-field density="compact" placeholder="Carrera del Estudiante" prepend-inner-icon="mdi-account-school"
        variant="outlined" :rules="[rules.required, rules.maxLength(90)]" :counter="90" v-model="alumno.carrera"
        color="blue-darken-3"></v-text-field>

      <!-- Campos adicionales en la parte superior -->
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field density="compact" placeholder="Código de Caja" prepend-inner-icon="mdi-archive-outline"
            variant="outlined" :rules="[rules.required, rules.boxCode]" v-model="numeroCaja" maxlength="6"
            color="blue-darken-3"></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-select density="compact" placeholder="Año" prepend-inner-icon="mdi-calendar-range" variant="outlined"
            :items="aniosDisponibles" v-model="anioEgreso" color="blue-darken-3"></v-select>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="4">
          <v-text-field density="compact" placeholder="Año de Bachiller" prepend-inner-icon="mdi-school"
            variant="outlined" :rules="[rules.onlyNumbersOrEmpty]" v-model="grados.anio_bachiller" maxlength="4"
            color="blue-darken-3"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field density="compact" placeholder="Año de Título" prepend-inner-icon="mdi-certificate"
            variant="outlined" :rules="[rules.onlyNumbersOrEmpty]" v-model="grados.anio_titulo" maxlength="4"
            color="blue-darken-3"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field density="compact" placeholder="Año de Maestría" prepend-inner-icon="mdi-school"
            variant="outlined" :rules="[rules.onlyNumbersOrEmpty]" v-model="grados.anio_maestria" maxlength="4"
            color="blue-darken-3"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field density="compact" placeholder="Año de Doctorado" prepend-inner-icon="mdi-school"
            variant="outlined" :rules="[rules.onlyNumbersOrEmpty]" v-model="grados.anio_doctorado" maxlength="4"
            color="blue-darken-3"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field density="compact" placeholder="Año de Especialidad 1" prepend-inner-icon="mdi-school"
            variant="outlined" :rules="[rules.onlyNumbersOrEmpty]" v-model="grados.anio_especialidad1" maxlength="4"
            color="blue-darken-3"></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field density="compact" placeholder="Año de Especialidad 2" prepend-inner-icon="mdi-school"
            variant="outlined" :rules="[rules.onlyNumbersOrEmpty]" v-model="grados.anio_especialidad2" maxlength="4"
            color="blue-darken-3"></v-text-field>
        </v-col>
      </v-row>

    </v-container>

    <v-divider></v-divider>
    <div>
      <v-card class="mx-auto" max-width="944">
        <v-card-title class="justify-center">
          <div class="text-center" style="font-weight: bold; text-decoration: underline;">Agregar nuevos
            documentos aquí</div>
        </v-card-title>
        <v-table>
          <thead>
            <tr>
              <th class="text-left">Grado</th>
              <th class="text-left">Tipo de Documento</th>
              <th class="text-left">Nombre del Archivo</th>
              <th class="text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(archivo, index) in archivosList" :key="archivo.codigo">
              <td>{{ archivo.grado }}</td>
              <td>{{ archivo.tipoDocumento }}</td>
              <td>{{ archivo.documento }}</td>
              <td>
                <v-btn icon @click="abrirDialogoParaEditar(index)" color="blue-darken-3">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon color="red" @click="eliminarArchivo(archivo.codigo)">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card>
    </div>

    <div class="text-center pa-4">
      <v-btn class="mr-4 text-none text-subtitle-1" variant="outlined" color="blue-darken-3" prepend-icon="mdi-plus-box"
        stacked @click="dialog = true">Agregar Documento</v-btn>

      <v-dialog v-model="dialog" width="440" persistent>
        <v-card max-width="740">
          <v-card-title>
            <div class="text-center" style="font-weight: bold; ">Adjuntar Documentos</div>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-select variant="outlined" v-model="archivo.grado" :items="grados" label="Grado" required
                color="blue-darken-3"></v-select>
              <v-select variant="outlined" v-model="archivo.tipoDocumento" :items="tiposDocumento"
                label="Tipo de Documento" required color="blue-darken-3"></v-select>
              <input type="file" @change="onFileSelected" class="file-input" />
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-btn class="mr-4 text-none text-body-1" variant="outlined" color="blue-darken-3"
              @click="agregarEditarArchivo">Agregar</v-btn>
            <v-btn class="mr-4 text-none text-body-1" variant="elevated" color="red-darken-4"
              @click="dialog = false">Cancelar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>

    <v-snackbar v-model="snackbar" :timeout="snackbarTimeout" color="red">
      {{ snackbarText }}
      <v-btn color="white" text @click="snackbar = false">Cerrar</v-btn>
    </v-snackbar>

    <v-text-field density="compact" placeholder="Observaciones Adicionales" prepend-inner-icon="mdi-note"
      variant="outlined" :rules="[rules.maxLength(255)]" :counter="255" v-model="observaciones"
      color="blue-darken-3"></v-text-field>

    <v-dialog v-model="confirmDialog" max-width="400">
      <v-card>
        <v-card-title>
          <div class="text-center" style="font-weight: bold; ">Confirmar Registro</div>
        </v-card-title>
        <v-card-text>
          ¿Estás seguro de completar el registro?
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue-darken-3" variant="elevated" @click="confirmarRegistro">Sí</v-btn>
          <v-btn color="red-darken-2" variant="outlined" @click="confirmDialog = false">No</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card-actions>
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
    const currentYear = new Date().getFullYear();
    return {
      archivosParaSubir: [],
      dialog: false,
      confirmDialog: false,
      proximoCodigo: 1,
      archivosList: [],
      alumno: {
        matricula_code: '',
        nombre: '',
        dni: '',
        carrera: '',
        observaciones: '',
      },
      archivo: {
        grado: '',
        tipoDocumento: '',
        documento: null,
        codigo: 1,
      },
      grados: {
        anio_bachiller: '',
        anio_titulo: '',
        anio_maestria: '',
        anio_doctorado: '',
        anio_especialidad1: '',
        anio_especialidad2: '',
      },
      archivos: {},
      esEdicion: false,
      indiceEdicion: null,
      numeroCaja: '',
      anioEgreso: '',
      observaciones: '',
      grados: ["BACHILLER", "TITULO", "MAESTRIA", "DOCTORADO", "ESPECIALIDAD", "ESPECIALIDAD 2", "CONVALIDACION", "ALUMNO"],
      tiposDocumento: ["ACTA", "RESOLUCION", "DIPLOMA", "DNI", "CERTIFICADO DE ESTUDIOS", "CONSTANCIA DE INGRESO"],
      aniosDisponibles: Array.from({ length: currentYear - 1965 + 1 }, (v, k) => currentYear - k),
      rules: {
        required: (value) => !!value || 'Este campo es requerido',
        length: (length) => (value) => (value && value.length === length) || `Debe tener ${length} caracteres`,
        maxLength: (maxLength) => (value) => (value && value.length <= maxLength) || `No debe exceder los ${maxLength} caracteres`,
        onlyNumbers: (value) => /^\d+$/.test(value) || 'Solo se permiten números',
        onlyNumbersOrEmpty: (value) => {
          const currentYear = new Date().getFullYear();
          return value === '' || (value.length === 4 && /^\d+$/.test(value) && value >= 1965 && value <= currentYear) || `Debe ser un número de 4 dígitos entre 1965 y ${currentYear}`;
        },
        //boxCode: (value) => /^[A-Za-z]\d$/.test(value) || 'Debe ser un carácter de letra seguido de un número',
      },
      snackbar: false,
      snackbarText: '',
      snackbarTimeout: 3000
    };
  },
  methods: {
    onFileSelected(event) {
      if (event.target.files.length > 0) {
        const file = event.target.files[0];
        this.archivo.documento = file;
      }
    },

    async confirmarRegistro() {
      this.confirmDialog = false; // Cerrar el diálogo de confirmación
      try {
        // Primero, registra al alumno
        await this.registerAlumno();

        // Si hay documentos para subir, procede a subirlos
        if (this.archivosList.length > 0) {
          await this.completarRegistro();
        }

        // Mostrar el mensaje de éxito
        window.location.href = '/completeform_acad';

      } catch (error) {
        console.error('Hubo un error durante el proceso:', error);
      }
    },

    // Asegúrate de que registerAlumno devuelva una promesa
    registerAlumno() {
      return new Promise((resolve, reject) => {
        const url = '/register_student';

        // Asegúrate de que los campos de los grados estén definidos, incluso si están vacíos
        const alumnoData = {
          dni: this.alumno.dni,
          matricula_code: this.alumno.matricula_code,
          nombre: this.alumno.nombre,
          carrera: this.alumno.carrera,
          anio_egreso: this.anioEgreso,
          caja: this.numeroCaja.toUpperCase(),
          observaciones: this.observaciones,
          // Verificar si cada campo de grado está presente, de lo contrario asignar null
          anio_bachiller: this.grados.anio_bachiller || null,
          anio_titulo: this.grados.anio_titulo || null,
          anio_maestria: this.grados.anio_maestria || null,
          anio_doctorado: this.grados.anio_doctorado || null,
          anio_especialidad1: this.grados.anio_especialidad1 || null,
          anio_especialidad2: this.grados.anio_especialidad2 || null,
        };

        axios.post(url, alumnoData)
          .then(response => {
            console.log('Alumno registrado:', response.data);
            resolve(response);
          })
          .catch(error => {
            this.showSnackbar("Por favor, completa todos los campos.");
            console.error('Error durante el registro del alumno:', error);
            reject(error);
          });
      });
    },


    // Asegúrate de que completarRegistro devuelva una promesa
    completarRegistro() {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('dni', this.alumno.dni);

        // Iterando sobre archivosList en lugar de Object.keys(this.archivos)
        this.archivosList.forEach((archivo, index) => {
          formData.append(`documentos[${index}][documento]`, archivo.documento);
          formData.append(`documentos[${index}][grado]`, archivo.grado);
          formData.append(`documentos[${index}][tipo]`, archivo.tipoDocumento);
        });

        const uploadUrl = '/upload_documents'; // Asegúrate de que esta es la URL correcta para tu API
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
            this.showSnackbar("Por favor, completa todos los campos.");
            console.error('Error durante la subida de documentos:', error);
            reject(error);
          });
      });
    },

    abrirDialogoParaEditar(index) {
      this.archivo = { ...this.archivosList[index] };
      this.esEdicion = true;
      this.indiceEdicion = index;
      this.dialog = true;


    },

    agregarEditarArchivo() {
      if (!this.archivo.grado || !this.archivo.tipoDocumento || !this.archivo.documento) {
        this.showSnackbar("Por favor, completa todos los campos.");
        return;
      }

      // Verificar duplicados por tipo y grado
      const duplicado = this.archivosList.some(archivo =>
        archivo.grado === this.archivo.grado && archivo.tipoDocumento === this.archivo.tipoDocumento
      );

      if (duplicado) {
        this.showSnackbar("Ya existe un documento con el mismo tipo y grado.");
        return;
      }

      if (this.esEdicion) {
        // Encuentra el índice del archivo a editar en archivosList
        const index = this.archivosList.findIndex(a => a.codigo === this.archivo.codigo);
        if (index !== -1) {
          this.archivosList[index] = { ...this.archivo };
        }
      } else {
        // Agrega un nuevo archivo a archivosList
        const nuevoCodigo = this.proximoCodigo++;
        this.archivo.codigo = nuevoCodigo;
        this.archivosList.push({ ...this.archivo });
      }
      this.dialog = false;
      this.resetArchivo();
    },

    eliminarArchivo(codigo) {
      const index = this.archivosList.findIndex(a => a.codigo === codigo);
      if (index !== -1) {
        this.archivosList.splice(index, 1);
      }
    },

    showSnackbar(message) {
      this.snackbarText = message;
      this.snackbar = true;
    },

    resetArchivo() {
      // Restablecer archivo a su estado inicial
      this.archivo = { grado: '', tipoDocumento: '', documento: null, codigo: null };
      this.esEdicion = false;
      this.indiceEdicion = -1;
    },
  },
};
</script>
