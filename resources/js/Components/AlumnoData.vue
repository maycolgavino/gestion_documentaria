<template>
  <v-card class="mx-auto" max-width="944" flat>
    <v-card-title class="justify-center">
      <div class="text-center">Registro del Estudiante</div>
    </v-card-title>
    <v-container>
      <v-text-field
        density="compact"
        placeholder="Nombre del Estudiante"
        prepend-inner-icon="mdi-account"
        variant="outlined"
        :rules="[rules.required, rules.maxLength(90)]"
        :counter="90"
        v-model="alumno.nombre"
        color="blue-darken-3"
      ></v-text-field>

      <v-text-field
        density="compact"
        placeholder="Número de Documento"
        prepend-inner-icon="mdi-id-card"
        variant="outlined"
        :rules="[rules.required, rules.onlyNumbers, rules.length(8)]"
        v-model="alumno.dni"
        maxlength="8"
        color="blue-darken-3"
      ></v-text-field>

      <v-text-field
        density="compact"
        placeholder="Codigo de Matricula"
        prepend-inner-icon="mdi-ticket-confirmation-outline"
        variant="outlined"
        :rules="[rules.required, rules.onlyNumbers, rules.length(10)]"
        v-model="alumno.matricula_code"
        maxlength="10"
        color="blue-darken-3"
      ></v-text-field>

      <v-text-field
        density="compact"
        placeholder="Carrera del Estudiante"
        prepend-inner-icon="mdi-account-school"
        variant="outlined"
        :rules="[rules.required, rules.maxLength(90)]"
        :counter="90"
        v-model="alumno.carrera"
        color="blue-darken-3"
      ></v-text-field>

      <!-- Campos adicionales en la parte superior -->
      <v-row align="center" class="d-flex">
        <v-col cols="auto">
          <!-- Campo Prefijo (No editable) -->
          <v-text-field
            density="compact"
            label="Código de Caja"
            variant="outlined"
            readonly
            :value="codigoCajaPrefix"
            persistent-placeholder
            color="blue-darken-3"
            hide-details
            class="mb-1 w-100"
            style="max-width: 150px"
          ></v-text-field>
        </v-col>

        <v-col cols="auto">
          <span class="mr-2">-</span>
        </v-col>

        <v-col cols="auto">
          <!-- Campo Sufijo (Editable) -->
          <v-text-field
            density="compact"
            placeholder="Número"
            variant="outlined"
            :rules="[rules.required, rules.onlyNumbers, rules.maxLength(6)]"
            v-model="codigoCajaSuffix"
            maxlength="6"
            color="blue-darken-3"
            hide-details
            class="mb-0"
            style="max-width: 120px"
          ></v-text-field>
        </v-col>

        <v-col cols="auto">
          <div class="ml-3" style="color: #1e88e5">
            Registros en Caja: {{ contadorCaja }}
          </div>
        </v-col>
      </v-row>


      <v-row>
        <v-col cols="12" md="4">
          <v-text-field
            density="compact"
            placeholder="Año de Bachiller"
            prepend-inner-icon="mdi-school"
            variant="outlined"
            :rules="[rules.onlyNumbersOrEmpty]"
            v-model="gradosData.anio_bachiller"
            maxlength="4"
            color="blue-darken-3"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            density="compact"
            placeholder="Año de Título"
            prepend-inner-icon="mdi-certificate"
            variant="outlined"
            :rules="[rules.onlyNumbersOrEmpty]"
            v-model="gradosData.anio_titulo"
            maxlength="4"
            color="blue-darken-3"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            density="compact"
            placeholder="Año de Maestría"
            prepend-inner-icon="mdi-school"
            variant="outlined"
            :rules="[rules.onlyNumbersOrEmpty]"
            v-model="gradosData.anio_maestria"
            maxlength="4"
            color="blue-darken-3"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            density="compact"
            placeholder="Año de Doctorado"
            prepend-inner-icon="mdi-school"
            variant="outlined"
            :rules="[rules.onlyNumbersOrEmpty]"
            v-model="gradosData.anio_doctorado"
            maxlength="4"
            color="blue-darken-3"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            density="compact"
            placeholder="Año de Especialidad 1"
            prepend-inner-icon="mdi-school"
            variant="outlined"
            :rules="[rules.onlyNumbersOrEmpty]"
            v-model="gradosData.anio_especialidad1"
            maxlength="4"
            color="blue-darken-3"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="4">
          <v-text-field
            density="compact"
            placeholder="Año de Especialidad 2"
            prepend-inner-icon="mdi-school"
            variant="outlined"
            :rules="[rules.onlyNumbersOrEmpty]"
            v-model="gradosData.anio_especialidad2"
            maxlength="4"
            color="blue-darken-3"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-container>

    <v-divider></v-divider>
    <div>
      <v-card class="mx-auto" max-width="944">
        <v-card-title class="justify-center">
          <div
            class="text-center"
            style="font-weight: bold; text-decoration: underline"
          >
            Agregar nuevos documentos aquí
          </div>
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
                <v-btn
                  icon
                  @click="abrirDialogoParaEditar(index)"
                  color="blue-darken-3"
                >
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn
                  icon
                  color="red"
                  @click="eliminarArchivo(archivo.codigo)"
                >
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card>
    </div>

    <div class="text-center pa-4">
      <v-btn
        class="mr-4 text-none text-subtitle-1"
        variant="outlined"
        color="blue-darken-3"
        prepend-icon="mdi-plus-box"
        stacked
        @click="dialog = true"
        >Agregar Documento</v-btn
      >

      <v-dialog v-model="dialog" width="440" persistent>
        <v-card max-width="740">
          <v-card-title>
            <div class="text-center" style="font-weight: bold">
              Adjuntar Documentos
            </div>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-select
                variant="outlined"
                v-model="archivo.grado"
                :items="grados"
                label="Grado"
                required
                color="blue-darken-3"
              ></v-select>
              <v-select
                variant="outlined"
                v-model="archivo.tipoDocumento"
                :items="tiposDocumento"
                label="Tipo de Documento"
                required
                color="blue-darken-3"
              ></v-select>
              <input type="file" @change="onFileSelected" class="file-input" />
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-btn
              class="mr-4 text-none text-body-1"
              variant="outlined"
              color="blue-darken-3"
              @click="agregarEditarArchivo"
              >Agregar</v-btn
            >
            <v-btn
              class="mr-4 text-none text-body-1"
              variant="elevated"
              color="red-darken-4"
              @click="dialog = false"
              >Cancelar</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>

    <v-snackbar v-model="snackbar" :timeout="snackbarTimeout" color="red">
      {{ snackbarText }}
      <v-btn color="white" text @click="snackbar = false">Cerrar</v-btn>
    </v-snackbar>

    <v-text-field
      density="compact"
      placeholder="Observaciones Adicionales"
      prepend-inner-icon="mdi-note"
      variant="outlined"
      :rules="[rules.maxLength(255)]"
      :counter="0"
      v-model="alumno.observaciones"
      color="blue-darken-3"
    ></v-text-field>

    <v-dialog v-model="confirmDialog" max-width="400">
      <v-card>
        <v-card-title>
          <div class="text-center" style="font-weight: bold">
            Confirmar Registro
          </div>
        </v-card-title>
        <v-card-text> ¿Estás seguro de completar el registro? </v-card-text>
        <v-card-actions>
          <v-btn
            color="blue-darken-3"
            variant="elevated"
            @click="confirmarRegistro"
            >Sí</v-btn
          >
          <v-btn
            color="red-darken-2"
            variant="outlined"
            @click="confirmDialog = false"
            >No</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn
        class="mr-4 text-none text-subtitle-1"
        variant="elevated"
        color="blue-darken-3"
        @click="confirmDialog = true"
      >
        Completar Registro
        <v-icon right>mdi-chevron-right</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    const currentYear = new Date().getFullYear()
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
      gradosData: {
        // Renombrado para evitar conflicto con la lista de grados
        anio_bachiller: '',
        anio_titulo: '',
        anio_maestria: '',
        anio_doctorado: '',
        anio_especialidad1: '',
        anio_especialidad2: '',
      },
      codigoCajaPrefix: '', // Prefijo generado automáticamente
      contadorCaja: 0,
      codigoCajaSuffix: '', // Sufijo editable por el usuario
      tiposDocumento: [
        'ACTA',
        'RESOLUCION',
        'DIPLOMA',
        'DNI',
        'CERTIFICADO DE ESTUDIOS',
        'CONSTANCIA DE INGRESO',
      ],
      grados: [
        'BACHILLER',
        'TITULO',
        'MAESTRIA',
        'DOCTORADO',
        'ESPECIALIDAD',
        'ESPECIALIDAD 2',
        'CONVALIDACION',
        'ALUMNO',
      ],
      aniosDisponibles: Array.from(
        { length: currentYear - 1965 + 1 },
        (v, k) => currentYear - k
      ),
      rules: {
        required: (value) => !!value || 'Este campo es requerido',
        length: (length) => (value) =>
          (value && value.length === length) ||
          `Debe tener ${length} caracteres`,
        maxLength: (maxLength) => (value) =>
          (value && value.length <= maxLength) ||
          `No debe exceder los ${maxLength} caracteres`,
        onlyNumbers: (value) =>
          /^\d+$/.test(value) || 'Solo se permiten números',
        onlyNumbersOrEmpty: (value) => {
          const currentYear = new Date().getFullYear()
          return (
            value === '' ||
            (value.length === 4 &&
              /^\d+$/.test(value) &&
              value >= 1965 &&
              value <= currentYear) ||
            `Debe ser un número de 4 dígitos entre 1965 y ${currentYear}`
          )
        },
        // boxCode: (value) => /^[A-Za-z]\d$/.test(value) || 'Debe ser un carácter de letra seguido de un número',
        // boxCode: (value) => /^[A-Za-z]\d$/.test(value) || 'Debe ser un carácter de letra seguido de un número',
      },
      snackbar: false,
      snackbarText: '',
      snackbarTimeout: 3000,
    }
  },
  watch: {
    'alumno.nombre': function (newName) {
      this.codigoCajaPrefix = this.generarCodigoCaja(newName)
    },
    codigoCajaSuffix: function (newSuffix) {
      if (newSuffix) {
        this.consultarContadorCaja()
      }
    },
    contadorCaja: function (newCount) {
      if (newCount >= 50) {
        // Verificar si la caja está llena
        this.advertenciaCajaLlena = true
        this.showSnackbar(
          'La caja actual está llena. Por favor, asigne un nuevo número de caja.'
        )
      } else {
        this.advertenciaCajaLlena = false
      }
    },
  },
  methods: {
    onFileSelected(event) {
      if (event.target.files.length > 0) {
        const file = event.target.files[0]
        this.archivo.documento = file
      }
    },
    generarCodigoCaja(nombre) {
      if (!nombre) return ''

      const palabras = nombre.trim().toUpperCase().split(/\s+/)
      if (palabras.length === 0) return ''

      // Obtener antepenúltima palabra si existen al menos 3 palabras, sino tomar la primera
      const indice = palabras.length >= 3 ? palabras.length - 2 : 0
      const palabraClave = palabras[indice]

      // Verificar si la palabra comienza con CH, LL o Ñ
      if (palabraClave.startsWith('CH')) return 'CH'
      if (palabraClave.startsWith('LL')) return 'LL'
      if (palabraClave.startsWith('Ñ')) return 'Ñ'

      return palabraClave.charAt(0)
    },
    async confirmarRegistro() {
      this.confirmDialog = false // Cerrar el diálogo de confirmación
      try {
        // Primero, registra al alumno
        await this.registerAlumno()

        // Si hay documentos para subir, procede a subirlos
        if (this.archivosList.length > 0) {
          await this.completarRegistro()
        }

        // Mostrar el mensaje de éxito
        window.location.href = '/completeform_acad'
      } catch (error) {
        console.error('Hubo un error durante el proceso:', error)
      }
    },

    // Asegúrate de que registerAlumno devuelva una promesa
    registerAlumno() {
      return new Promise((resolve, reject) => {
        const url = '/register_student'

        // Asegúrate de que los campos de los grados estén definidos, incluso si están vacíos
        const alumnoData = {
          dni: this.alumno.dni,
          matricula_code: this.alumno.matricula_code,
          nombre: this.alumno.nombre,
          carrera: this.alumno.carrera,
          caja: `${this.codigoCajaPrefix}-${this.codigoCajaSuffix}`.toUpperCase(), // Concatenación del prefijo y sufijo
          observaciones: this.alumno.observaciones,
          caja: `${this.codigoCajaPrefix}-${this.codigoCajaSuffix}`.toUpperCase(), // Concatenación del prefijo y sufijo
          observaciones: this.alumno.observaciones,
          // Verificar si cada campo de grado está presente, de lo contrario asignar null
          anio_bachiller: this.gradosData.anio_bachiller || null,
          anio_titulo: this.gradosData.anio_titulo || null,
          anio_maestria: this.gradosData.anio_maestria || null,
          anio_doctorado: this.gradosData.anio_doctorado || null,
          anio_especialidad1: this.gradosData.anio_especialidad1 || null,
          anio_especialidad2: this.gradosData.anio_especialidad2 || null,
          num_registros_caja: this.contadorCaja, // Enviar el contador de la caja
        }

        axios
          .post(url, alumnoData)
          .then((response) => {
            console.log('Alumno registrado:', response.data)
            resolve(response)
          })
          .catch((error) => {
            this.showSnackbar('Por favor, completa todos los campos.')
            console.error('Error durante el registro del alumno:', error)
            reject(error)
          })
      })
    },

    // Asegúrate de que completarRegistro devuelva una promesa
    completarRegistro() {
      return new Promise((resolve, reject) => {
        const formData = new FormData()
        formData.append('nombre', this.alumno.nombre) // Cambiado para enviar el nombre del alumno

        // Iterando sobre archivosList en lugar de Object.keys(this.archivos)
        this.archivosList.forEach((archivo, index) => {
          formData.append(`documentos[${index}][documento]`, archivo.documento)
          formData.append(`documentos[${index}][grado]`, archivo.grado)
          formData.append(`documentos[${index}][tipo]`, archivo.tipoDocumento)
        })

        const uploadUrl = '/upload_documents' // Asegúrate de que esta es la URL correcta para tu API
        axios
          .post(uploadUrl, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
          .then((response) => {
            console.log('Documentos subidos exitosamente:', response.data)
            resolve(response)
          })
          .catch((error) => {
            this.showSnackbar('Por favor, completa todos los campos.')
            console.error('Error durante la subida de documentos:', error)
            reject(error)
          })
      })
    },

    //nuevooo
    async consultarContadorCaja() {
      if (!this.codigoCajaSuffix) {
        return
      }
      try {
        const response = await axios.get(
          `/api/contador_caja/${this.codigoCajaSuffix}`
        )
        this.contadorCaja = response.data.num_registros_caja
      } catch (error) {
        console.error('Error al consultar el contador de la caja:', error)
      }
    },

    abrirDialogoParaEditar(index) {
      this.archivo = { ...this.archivosList[index] }
      this.esEdicion = true
      this.indiceEdicion = index
      this.dialog = true
    },

    agregarEditarArchivo() {
      if (
        !this.archivo.grado ||
        !this.archivo.tipoDocumento ||
        !this.archivo.documento
      ) {
        this.showSnackbar('Por favor, completa todos los campos.')
        return
      }

      // Verificar duplicados por tipo y grado
      const duplicado = this.archivosList.some(
        (archivo) =>
          archivo.grado === this.archivo.grado &&
          archivo.tipoDocumento === this.archivo.tipoDocumento
      )

      if (duplicado) {
        this.showSnackbar('Ya existe un documento con el mismo tipo y grado.')
        return
      }

      if (this.esEdicion) {
        // Encuentra el índice del archivo a editar en archivosList
        const index = this.archivosList.findIndex(
          (a) => a.codigo === this.archivo.codigo
        )
        if (index !== -1) {
          this.archivosList[index] = { ...this.archivo }
        }
      } else {
        // Agrega un nuevo archivo a archivosList
        const nuevoCodigo = this.proximoCodigo++
        this.archivo.codigo = nuevoCodigo
        this.archivosList.push({ ...this.archivo })
      }
      this.dialog = false
      this.resetArchivo()
    },

    eliminarArchivo(codigo) {
      const index = this.archivosList.findIndex((a) => a.codigo === codigo)
      if (index !== -1) {
        this.archivosList.splice(index, 1)
      }
    },

    showSnackbar(message) {
      this.snackbarText = message
      this.snackbar = true
    },

    resetArchivo() {
      // Restablecer archivo a su estado inicial
      this.archivo = {
        grado: '',
        tipoDocumento: '',
        documento: null,
        codigo: null,
      }
      this.esEdicion = false
      this.indiceEdicion = -1
    },
  },
}
</script>

<style scoped>
.file-input {
  width: 100%;
  margin-top: 16px;
}
</style>

<style scoped>
.file-input {
  width: 100%;
  margin-top: 16px;
}
</style>
