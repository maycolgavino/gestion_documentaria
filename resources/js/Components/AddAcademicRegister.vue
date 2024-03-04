<template lang="">
  <v-container>
    <v-form @submit.prevent="submitForm">
      <v-text-field v-model="alumno.nombre" label="Nombre" required></v-text-field>
      <v-text-field v-model="alumno.dni" label="DNI" required type="number"></v-text-field>
      <v-text-field v-model="alumno.carrera" label="Carrera" required></v-text-field>

      <!-- Generador de Código Académico -->
      <!-- Este campo es generado automáticamente y no necesita ser llenado por el usuario -->

      <!-- Selector de Grado -->
      <v-select v-model="archivo.grado" :items="grados" label="Grado" required></v-select>

      <!-- Selector de Tipo de Documento -->
      <v-select v-model="archivo.tipoDocumento" :items="tiposDocumento" label="Tipo de Documento" required></v-select>

      <!-- Subida de Archivo -->
      <v-file-input v-model="archivo.documento" label="Subir documento" required></v-file-input>

      <v-btn color="primary" type="submit">Enviar</v-btn>
    </v-form>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      alumno: {
        nombre: '',
        dni: null,
        carrera: '',
        cod_acad: this.generarCodigoAcademico(),
      },
      archivo: {
        grado: '',
        tipoDocumento: '',
        documento: null,
      },
      grados: ["Bachiller", "Título", "Maestría", "Doctorado", "Especialidad", "Especialidad 2", "Convalidación"],
      tiposDocumento: ["Acta", "Resolución", "Diploma", "Otro"],
    };
  },
  methods: {
    generarCodigoAcademico() {
      // Este método simula la generación de un código académico único
      // En un caso real, esto podría ser generado por el servidor
      return "ACA00001"; // Simulación de código generado
    },
    submitForm() {
      // Enviar los datos al servidor
      // Aquí iría el código para realizar una petición HTTP POST con los datos del formulario
      console.log(this.alumno, this.archivo);
    },
  },
};
</script>

