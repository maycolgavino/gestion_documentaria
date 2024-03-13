<template>
  <v-card class="mx-auto" max-width="944" title="Registro del Estudiante">
    <v-container>
      <v-text-field density="compact" placeholder="Nombre del Estudiante" prepend-inner-icon="mdi-account"
        variant="outlined" :rules="[rules.required, rules.maxLength(90)]" :counter="90" v-model="alumno.nombre"
        color="blue"></v-text-field>

      <v-text-field density="compact" placeholder="Número de Documento" prepend-inner-icon="mdi-id-card"
        variant="outlined" :rules="[rules.required, rules.onlyNumbers, rules.length(8)]"
        v-model="alumno.dni"></v-text-field>

      <v-text-field density="compact" placeholder="Carrera del Estudiante" prepend-inner-icon="mdi-account-school"
        variant="outlined" v-model="alumno.carrera"></v-text-field>

      <!-- Campos adicionales en la parte superior -->
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field density="compact" placeholder="Código de Caja" prepend-inner-icon="mdi-archive-outline"
            variant="outlined" v-model="numeroCaja"></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-select density="compact" placeholder="Año" prepend-inner-icon="mdi-calendar-range" variant="outlined"
            :items="aniosDisponibles" v-model="anioEgreso"></v-select>
        </v-col>
      </v-row>
    </v-container>

    <v-divider></v-divider>
    <div>
      <v-card class="mx-auto" max-width="944">
        <v-card-title>Documentos Subidos</v-card-title>
        <v-table>
          <thead>
            <tr>
              <!-- <th class="text-left">Código</th> -->
              <th class="text-left">Grado</th>
              <th class="text-left">Tipo de Documento</th>
              <th class="text-left">Nombre del Archivo</th>
              <th class="text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(archivo, index) in archivosList" :key="archivo.codigo">
              <!-- <td>{{ archivo.codigo }}</td> -->
              <td>{{ archivo.grado }}</td>
              <td>{{ archivo.tipoDocumento }}</td>
              <td>{{ archivo.documento }}</td>
              <!-- Asegúrate de que se accede correctamente al nombre del archivo -->
              <td>
                <v-btn icon @click="abrirDialogoParaEditar(index)">
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
    <!-- AQUI ES DONDE SE REALIZARAN CAMBIOS EN LA CARPETA -->
    <div class="text-center pa-4">
      <v-btn @click="dialog = true">AGREGAR DOCUMENTO</v-btn>

      <v-dialog v-model="dialog" width="440">
        <v-card max-width="740">
          <v-card-title>Subir Documento</v-card-title>
          <v-card-text>
            <v-container>
              <v-select v-model="archivo.grado" :items="grados" label="Grado" required></v-select>
              <v-select v-model="archivo.tipoDocumento" :items="tiposDocumento" label="Tipo de Documento"
                required></v-select>
              <!-- <v-file-input v-model="archivo.documento" label="Subir documento" required></v-file-input> -->
              <input type="file" @change="onFileSelected" class="file-input" />
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="agregarEditarArchivo">Agregar</v-btn>
            <v-btn color="grey darken-1" text @click="dialog = false">Cancelar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>

    <div class="mx-auto" max-width="944">
      <v-text-field density="compact" placeholder="Observaciones" prepend-inner-icon="mdi-eye-outline"
        variant="outlined" v-model="observaciones"></v-text-field>
    </div>

    <!-- HASTA AQUI -->
    <v-card-actions>
      <v-spacer></v-spacer>

      <v-btn color="success" @click="completarRegistroCompleto">
        Completar Registro
        <v-icon right>mdi-chevron-right</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>

</template>

<script>
export default {
  data() {
    return {
      archivosParaSubir: [],
      dialog: false,
      proximoCodigo: 1,
      archivosList: [],
      alumno: {
        nombre: '',
        dni: '',
        carrera: '',
      },
      archivo: {
        grado: '',
        tipoDocumento: '',
        documento: null,
        codigo: 1,
      },
      archivos: {},
      esEdicion: false,
      indiceEdicion: null,
      numeroCaja: '',
      anioEgreso: '',
      observaciones: '',
      grados: ["Bachiller", "Título", "Maestría", "Doctorado", "Especialidad", "Especialidad 2", "Convalidación", "Alumno"],
      tiposDocumento: ["Acta", "Resolución", "Diploma", "Otro"],
      aniosDisponibles: Array.from({ length: 2024 - 1965 + 1 }, (v, k) => 2024 - k),
      rules: {
        required: (value) => !!value || 'Este campo es requerido',
        length: (length) => (value) => (value && value.length === length) || `Debe tener ${length} caracteres`,
        maxLength: (maxLength) => (value) => (value && value.length <= maxLength) || `No debe exceder los ${maxLength} caracteres`,
        onlyNumbers: (value) => /^\d+$/.test(value) || 'Solo se permiten números',
      },
    };
  },
  methods: {
    onFileSelected(event) {
      if (event.target.files.length > 0) {
        const file = event.target.files[0];
        this.archivo.documento = file;
      }
    },

    async completarRegistroCompleto() {
      try {
        // Primero, registra al alumno
        await this.registerAlumno();
        // Si el registro fue exitoso, procede a subir los documentos
        await this.completarRegistro();
        // Si todo fue exitoso, puedes hacer algo más aquí, como mostrar un mensaje de éxito
        alert('Registro y subida de documentos completados con éxito');
      } catch (error) {
        console.error('Hubo un error durante el proceso:', error);
      }
    },

    // Asegúrate de que registerAlumno devuelva una promesa
    registerAlumno() {
      return new Promise((resolve, reject) => {
        const url = '/register-student'; // Asegúrate de usar la URL correcta para tu API
        const alumnoData = {
          dni: this.alumno.dni,
          nombre: this.alumno.nombre,
          carrera: this.alumno.carrera,
          anio_egreso: this.anioEgreso,
          caja: this.numeroCaja,
          observaciones: this.observaciones,
        };

        axios.post(url, alumnoData)
          .then(response => {
            console.log('Alumno registrado:', response.data);
            resolve(response); // Resuelve la promesa si el registro fue exitoso
          })
          .catch(error => {
            console.error('Error durante el registro del alumno:', error);
            reject(error); // Rechaza la promesa si hubo un error
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

        const uploadUrl = '/upload-documents'; // Asegúrate de que esta es la URL correcta para tu API
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
        alert("Por favor, completa todos los campos.");
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


    resetArchivo() {
      // Restablecer archivo a su estado inicial
      this.archivo = { grado: '', tipoDocumento: '', documento: null, codigo: null };
      this.esEdicion = false;
      this.indiceEdicion = -1;
    },
  },
};
</script>
