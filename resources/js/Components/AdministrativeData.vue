<template>
  <v-card class="mx-auto" max-width="944" title="Registro del Documentos Administrativos" flat>
    <v-container>
      <!-- Campos adicionales en la parte superior -->
      <v-row>
        <v-col cols="12" md="6">
          <v-select density="compact" placeholder="Tipos de Resolucion"
            prepend-inner-icon="mdi-format-list-bulleted-type" :items="tiposResolucion" variant="outlined"
            v-model="res.tipo_res"></v-select>
        </v-col>
        <v-col cols="12" md="6">
          <v-select density="compact" placeholder="Año" prepend-inner-icon="mdi-calendar-range" variant="outlined"
            :items="aniosDisponibles" v-model="res.anio_res"></v-select>
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
              <th class="text-left">Número de Res.</th>
              <th class="text-left">Oficina Emisora</th>
              <th class="text-left">Oficina Receptora</th>
              <th class="text-left">Motivo</th>
              <th class="text-left">Documento</th>
              <th class="text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(detres, index) in archivosList" :key="detres.codigo">
              <!-- <td>{{ archivo.codigo }}</td> -->
              <td>{{ detres.numero }}</td>
              <td>{{ detres.autor }}</td>
              <td>{{ detres.receptor }}</td>
              <td>{{ detres.motivo }}</td>
              <td>{{ detres.documento }}</td>
              <!-- Asegúrate de que se accede correctamente al nombre del archivo -->
              <td>
                <v-btn icon @click="abrirDialogoParaEditar(index)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon color="red" @click="eliminarArchivo(detres.codigo)">
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
              <v-text-field density="compact" placeholder="Numero" prepend-inner-icon="mdi-numeric" variant="outlined"
                v-model="detres.numero"></v-text-field>
              <v-text-field density="compact" placeholder="Oficina de Envio" prepend-inner-icon="mdi-email-arrow-right"
                variant="outlined" v-model="detres.autor"></v-text-field>
              <v-text-field density="compact" placeholder="Oficina de Recepcion"
                prepend-inner-icon="mdi-email-arrow-left" variant="outlined" v-model="detres.receptor"></v-text-field>
              <v-text-field density="compact" placeholder="Motivo" prepend-inner-icon="mdi-text-short"
                variant="outlined" v-model="detres.motivo"></v-text-field>

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

    <!-- <div class="mx-auto" max-width="944">
      <v-text-field density="compact" placeholder="Observaciones" prepend-inner-icon="mdi-eye-outline"
        variant="outlined" v-model="observaciones"></v-text-field>
    </div> -->

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
      res: {
        tipo_res: '',
        anio_res: '',
      },
      detres: {
        codigo: 1,
        numero: '',
        autor: '',
        receptor: '',
        motivo: '',
        documento: null,
      },
      archivos: {},
      esEdicion: false,
      indiceEdicion: null,
      numeroCaja: '',
      anioEgreso: '',
      observaciones: '',
      tiposResolucion: ["Resolución Directorial Administrativa", "Resoluciones de Asamblea Universitaria", "Resoluciones Rectorales", "Resoluciones de Consejo Universitario"],
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
        this.detres.documento = file;
      }
    },

    async completarRegistroCompleto() {
      try {
        // Primero, registra al alumno
        await this.registerRes();
        // Si el registro fue exitoso, procede a subir los documentos
        await this.completarRegistro();
        // Si todo fue exitoso, puedes hacer algo más aquí, como mostrar un mensaje de éxito
        alert('Registro y subida de documentos completados con éxito');
      } catch (error) {
        console.error('Hubo un error durante el proceso:', error);
      }
    },

    // Asegúrate de que registerAlumno devuelva una promesa
    registerRes() {
      return new Promise((resolve, reject) => {
        const url = '/register_res'; // Asegúrate de usar la URL correcta para tu API
        const resData = {
          tipo: this.res.tipo_res,
          anio_res: String(this.res.anio_res),
        };

        axios.post(url, resData)
          .then(response => {
            console.log('Resolucion registrado:', response.data);
            resolve(response); // Resuelve la promesa si el registro fue exitoso
          })
          .catch(error => {
            console.error('Error durante el registro de la Resolucion:', error);
            reject(error); // Rechaza la promesa si hubo un error
          });
      });
    },

    // Asegúrate de que completarRegistro devuelva una promesa
    completarRegistro() {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        // Iterando sobre archivosList en lugar de Object.keys(this.archivos)
        this.archivosList.forEach((detres, index) => {
          formData.append(`documentos[${index}][documento]`, detres.documento);
          formData.append(`documentos[${index}][numero]`, detres.numero);
          formData.append(`documentos[${index}][autor]`, detres.autor);
          formData.append(`documentos[${index}][receptor]`, detres.receptor);
          formData.append(`documentos[${index}][motivo]`, detres.motivo);
        });

        const uploadUrl = '/upload_res'; // Asegúrate de que esta es la URL correcta para tu API
        axios.post(uploadUrl, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        })
          .then(response => {
            window.location.href = '/completeform';
            resolve(response);
          })
          .catch(error => {
            console.error('Error durante la subida de documentos:', error);
            reject(error);
          });
      });
    },


    abrirDialogoParaEditar(index) {
      this.detres = { ...this.archivosList[index] };
      this.esEdicion = true;
      this.indiceEdicion = index;
      this.dialog = true;
    },

    agregarEditarArchivo() {
      if (!this.detres.numero || !this.detres.autor || !this.detres.receptor || !this.detres.motivo) {
        alert("Por favor, completa todos los campos.");
        return;
      }
      if (this.esEdicion) {
        // Encuentra el índice del archivo a editar en archivosList
        const index = this.archivosList.findIndex(a => a.codigo === this.detres.codigo);
        if (index !== -1) {
          this.archivosList[index] = { ...this.detres };
        }
      } else {
        // Agrega un nuevo archivo a archivosList
        const nuevoCodigo = this.proximoCodigo++;
        this.detres.codigo = nuevoCodigo;
        this.archivosList.push({ ...this.detres });
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
      this.detres = { numero: '', autor: '', receptor: '', motivo: '', documento: null };
      this.esEdicion = false;
      this.indiceEdicion = null;
    },
    redirectTo(routeName) {
      console.log('Redirigiendo a:', routeName);
      switch (routeName) {
        case 'actualizar_ac':
          window.location.href = '/update_ac'; // Reemplaza con la ruta de Laravel
          break;
      }
    },
  },
};
</script>