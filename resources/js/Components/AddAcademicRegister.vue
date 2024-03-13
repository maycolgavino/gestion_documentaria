<template>
  <AlumnoData></AlumnoData>
</template>

<script>
import AlumnoData from '@/Components/AlumnoData.vue';
export default {
  components: {
    AlumnoData
  },
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
