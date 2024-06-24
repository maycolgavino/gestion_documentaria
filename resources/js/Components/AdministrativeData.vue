<template>
  <v-card class="mx-auto" max-width="944" flat>
    <v-card-title class="justify-center">
      <div class="text-center">Registro del Documentos Administrativos</div>
    </v-card-title>
    <v-container>
      <!-- Campos adicionales en la parte superior -->
      <v-row>
        <v-col cols="12" md="6">
          <v-select density="compact" label="Tipos de Resolucion" prepend-inner-icon="mdi-format-list-bulleted-type"
            :items="tiposResolucion" variant="outlined" v-model="res.tipo_res" color="blue-darken-3"></v-select>
        </v-col>
        <v-col cols="12" md="6">
          <v-select density="compact" label="Año de Emisión" placeholder="Año" prepend-inner-icon="mdi-calendar-range"
            variant="outlined" :items="aniosDisponibles" v-model="res.anio_res" color="blue-darken-3"></v-select>
        </v-col>
      </v-row>
    </v-container>

    <v-divider></v-divider>
    <div>
      <v-card class="mx-auto" max-width="944">
        <v-card-title class="justify-center">
          <div class="text-center">Documentos Pertenecientes al Periodo</div>
        </v-card-title>
        <v-table>
          <thead>
            <tr>
              <!-- <th class="text-left">Código</th> -->
              <th class="text-left">Nº</th>
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
      <v-btn class="mr-4 text-none text-subtitle-1" variant="outlined" color="blue-darken-3"
        prepend-icon="mdi-plus-box" stacked @click="dialog = true">Agregar Documento</v-btn>

      <v-dialog v-model="dialog" width="440" persistent>
        <v-card max-width="740">
          <v-card-title>
            <div class="text-center" style="font-weight: bold; ">Adjuntar Documentos</div>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-text-field density="compact" placeholder="Número" prepend-inner-icon="mdi-numeric" variant="outlined"
                v-model="detres.numero" maxlength="6" color="blue-darken-3"></v-text-field>
              <v-text-field density="compact" placeholder="Oficina de Envio" prepend-inner-icon="mdi-email-arrow-right"
                variant="outlined" v-model="detres.autor" maxlength="80" color="blue-darken-3"></v-text-field>
              <v-text-field density="compact" placeholder="Oficina de Recepcion"
                prepend-inner-icon="mdi-email-arrow-left" variant="outlined" v-model="detres.receptor"
                maxlength="80" color="blue-darken-3"></v-text-field>
              <v-text-field density="compact" placeholder="Motivo" prepend-inner-icon="mdi-text-short"
                variant="outlined" v-model="detres.motivo" maxlength="120" color="blue-darken-3"></v-text-field>

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


    <v-card-actions>
      <v-spacer></v-spacer>

      <v-btn class="mr-4 text-none text-subtitle-1" variant="elevated" color="blue-darken-3"
        @click="confirmDialog = true">
        Completar Registro
        <v-icon right>mdi-chevron-right</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>


  <!-- Diálogo de confirmación -->
  <v-dialog v-model="confirmDialog" max-width="500" persistent>
    <v-card>
      <v-card-title><div class="text-center" style="font-weight: bold; ">Confirmar Completar Registro</div></v-card-title>
      <v-card-text>
        <div class="text-center" >¿Estás seguro de que deseas completar el registro?</div>
        
      </v-card-text>
      <v-card-actions>
        <v-btn color="blue-darken-3" variant="elevated" @click="completarRegistroCompleto">Confirmar</v-btn>
        <v-btn color="red-darken-1" variant="outlined" @click="confirmDialog = false">Cancelar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- Snackbar para mostrar mensaje de alerta -->
  <v-snackbar v-model="snackbar.show" :color="snackbar.color" :timeout="snackbar.timeout">
    {{ snackbar.text }}
  </v-snackbar>

</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      confirmDialog: false,
      snackbar: {
        show: false,
        text: '',
        color: 'error',
        timeout: 6000
      },
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
      tiposResolucion: ["Resolución Directorial Administrativa", "Resolucion de Asamblea Universitaria", "Resolucion Rectoral", "Resolucion de Consejo Universitario"],
      aniosDisponibles: Array.from({ length: 2024 - 1965 + 1 }, (v, k) => 2024 - k),
    };
  },
  methods: {
    // Método para mostrar el Snackbar
    mostrarSnackbar(texto, color) {
      this.snackbar.text = texto;
      this.snackbar.color = color;
      this.snackbar.show = true;
    },
    onFileSelected(event) {
    const file = event.target.files[0];
    if (file) {
      this.detres.documento = file;
    }
  },
    confirmarRegistro() {
      this.confirmDialog = true;
    },

    async completarRegistroCompleto() {
      this.confirmDialog = false;
      try {
        await this.registerRes();
        await this.completarRegistro();
        this.mostrarSnackbar('Registro completado exitosamente', 'success');
        window.location.href = '/completeform_ad';
      } catch (error) {
        console.error('Hubo un error durante el proceso:', error);
        this.mostrarSnackbar('Hubo un error durante el proceso. Por favor, verifique los datos.', 'error');
      }
    },

    async registerRes() {
      try {
        const url = '/register_res';
        const resData = {
          tipo: this.res.tipo_res,
          anio_res: String(this.res.anio_res),
        };
        const response = await axios.post(url, resData);
        console.log('Resolución registrada:', response.data);
        return response;
      } catch (error) {
        console.error('Error durante el registro de la Resolución:', error);
        throw error;
      }
    },

    completarRegistro() {
  return new Promise((resolve, reject) => {
    const formData = new FormData();
    this.archivosList.forEach((detres, index) => {
      formData.append(`documentos[${index}][documento]`, detres.documento); // Ensure detres.documento is a File object
      formData.append(`documentos[${index}][numero]`, detres.numero);
      formData.append(`documentos[${index}][autor]`, detres.autor);
      formData.append(`documentos[${index}][receptor]`, detres.receptor);
      formData.append(`documentos[${index}][motivo]`, detres.motivo);
    });

    const uploadUrl = '/upload_res';
    axios.post(uploadUrl, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      }
    })
      .then(response => {
        resolve(response);
      })
      .catch(error => {
        console.error('Error al subir los documentos:', error);
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
        this.mostrarSnackbar('Por favor completa todos los campos.', 'error');
        return;
      }
      if (this.esEdicion) {
        const index = this.archivosList.findIndex(a => a.codigo === this.detres.codigo);
        if (index !== -1) {
          this.archivosList[index] = { ...this.detres };
        }
      } else {
        const nuevoCodigo = this.proximoCodigo++;
        this.detres.codigo = nuevoCodigo;
        this.archivosList.push({ ...this.detres });
      }
      this.dialog = false;
      this.resetArchivo();
      this.mostrarSnackbar('Archivo agregado correctamente.', 'success');
    },

    eliminarArchivo(codigo) {
      const index = this.archivosList.findIndex(a => a.codigo === codigo);
      if (index !== -1) {
        this.archivosList.splice(index, 1);
        this.mostrarSnackbar('Archivo eliminado correctamente.', 'success');
      }
    },

    resetArchivo() {
      this.detres = { numero: '', autor: '', receptor: '', motivo: '', documento: null };
      this.esEdicion = false;
      this.indiceEdicion = null;
    },

  },
};
</script>