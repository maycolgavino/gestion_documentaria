<template>
  <v-card class="mx-auto" max-width="944" title="Registro del Documentos de Silabo">
    <v-container>
      <v-text-field density="compact" placeholder="Facultad" prepend-inner-icon="mdi-account-school" variant="outlined"
        v-model="silabo.facultad"></v-text-field>

      <v-text-field density="compact" placeholder="Escuela" prepend-inner-icon="mdi-account" variant="outlined"
        :rules="[rules.required, rules.maxLength(90)]" :counter="90" v-model="silabo.escuela"
        color="blue"></v-text-field>
      <!-- Campos adicionales en la parte superior -->
      <!-- <v-row>
        <v-col cols="12" md="6">
          <v-text-field density="compact" placeholder="Código de Caja" prepend-inner-icon="mdi-archive-outline"
            variant="outlined" v-model="numeroCaja"></v-text-field>
        </v-col>
        <v-col cols="12" md="6">

        </v-col>
      </v-row> -->
    </v-container>

    <v-divider></v-divider>
    <div>
      <v-card class="mx-auto" max-width="944">
        <v-card-title>Documentos Subidos</v-card-title>
        <v-table>
          <thead>
            <tr>
              <!-- <th class="text-left">Código</th> -->
              <th class="text-left">Código de Silabo</th>
              <th class="text-left">Carrera o Programa de Estudios</th>
              <th class="text-left">Curso</th>
              <th class="text-left">Documento</th>
              <th class="text-left">Año de Silabo</th>
              <th class="text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(detsilabo, index) in archivosList" :key="detsilabo.codigo">
              <!-- <td>{{ archivo.codigo }}</td> -->
              <td>{{ detsilabo.code }}</td>
              <td>{{ detsilabo.carrera }}</td>
              <td>{{ detsilabo.curso }}</td>
              <td>{{ detsilabo.documento }}</td>
              <td>{{ detsilabo.anio }}</td>
              <!-- Asegúrate de que se accede correctamente al nombre del archivo -->
              <td>
                <v-btn icon @click="abrirDialogoParaEditar(index)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon color="red" @click="eliminarArchivo(detsilabo.codigo)">
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
              <v-text-field density="compact" placeholder="Código de Silabo" prepend-inner-icon="mdi-numeric"
                variant="outlined" v-model="detsilabo.code"
                :rules="[rules.required, rules.onlyNumbers, rules.length(5)]" required></v-text-field>
              <v-text-field density="compact" placeholder="Carrera o Programa de Estudios"
                prepend-inner-icon="mdi-library" variant="outlined" v-model="detsilabo.carrera" required></v-text-field>
              <v-text-field density="compact" placeholder="Curso" prepend-inner-icon="mdi-book" variant="outlined"
                v-model="detsilabo.curso" required></v-text-field>
              <v-select density="compact" placeholder="Año" prepend-inner-icon="mdi-calendar-range" variant="outlined"
                :items="aniosDisponibles" v-model="detsilabo.anio"></v-select>
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

      <v-btn color="success" @click="ejecutarRegistroCompleto">
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
      dialog: false,
      proximoCodigo: 1,
      archivosList: [],
      silabo: {
        facultad: '',
        escuela: '',

      },
      detsilabo: {
        code: '',
        carrera: '',
        curso: null,
        codigo: 1,
        anio: '',
      },
      archivos: {},
      esEdicion: false,
      indiceEdicion: null,
      numeroCaja: '',
      observaciones: '',
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
        this.detsilabo.documento = file;
      }
    },

    async ejecutarRegistroCompleto() {
      try {
        await this.registrarSilabo(); // Espera a que se complete el registro del sílabo
        await this.completarRegistro(); // Después procede a completar el registro con detalles y documentos
        alert('Registro completo del sílabo y documentos asociados exitoso.');
      } catch (error) {
        console.error('Ocurrió un error durante el proceso de registro:', error);
        alert('Error durante el proceso de registro. Por favor, revise la consola para más detalles.');
      }
    },

    registrarSilabo() {
      return new Promise((resolve, reject) => {
        const url = '/register_sb';
        const silaboData = {
          facultad: this.silabo.facultad,
          escuela: this.silabo.escuela,
          codigoCaja: this.numeroCaja,
          observaciones: this.observaciones,
        };
        // Endpoint para registrar silabo
        axios.post(url, silaboData)
          .then(response => {
            // this.idSilaboRegistrado = response.data.id; // Asumiendo que el backend devuelve el ID del sílabo registrado
            console.log('Silabo registrado exitosamente:', response.data);
            resolve(response);
            // this.completarRegistro(); // Procede a subir detalles y documentos si es necesario
          })
          .catch(error => {
            console.error('Error durante el registro del sílabo:', error);
            reject(error);
          });
      });
    },

    completarRegistro() {
      return new Promise((resolve, reject) => {
        const formData = new FormData();

        this.archivosList.forEach((archivo, index) => {
          formData.append(`documentos[${index}][code]`, archivo.code);
          formData.append(`documentos[${index}][carrera]`, archivo.carrera);
          formData.append(`documentos[${index}][curso]`, archivo.curso);
          formData.append(`documentos[${index}][documento]`, archivo.documento);
          formData.append(`documentos[${index}][anio]`, archivo.anio);
        });

        const uploadUrl = '/upload_sb'; // Asegúrate de que esta es la URL correcta para tu API
        axios.post(uploadUrl, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
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
      this.detsilabo = { ...this.archivosList[index] };
      this.esEdicion = true;
      this.indiceEdicion = index;
      this.dialog = true;
    },

    agregarEditarArchivo() {
      if (!this.detsilabo.code || !this.detsilabo.carrera || !this.detsilabo.curso || !this.detsilabo.documento || !this.detsilabo.anio) {
        alert("Por favor, completa todos los campos.");
        return;
      }
      if (this.esEdicion) {
        const index = this.archivosList.findIndex(a => a.codigo === this.detsilabo.codigo);
        if (index !== -1) {
          this.archivosList[index] = { ...this.detsilabo };
        }
      } else {
        const nuevoCodigo = this.proximoCodigo++;
        this.detsilabo.codigo = nuevoCodigo;
        this.archivosList.push({ ...this.detsilabo });
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
      this.detsilabo = { code: '', carrera: '', curso: '', documento: null, codigo: null, anio: '' };
      this.esEdicion = false;
      this.indiceEdicion = -1;
    },
  },
};
</script>