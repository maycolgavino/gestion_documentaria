<template>
  <v-sheet class="pa-4 text-center mx-auto" max-width="600" rounded="lg" width="100%" flat>
    <v-icon class="mb-5" color="success" icon="mdi-check-circle" size="112"></v-icon>

    <h2 class="text-h5 mb-6">Tus archivos han sido cargados de manera exitosa</h2>

    <p class="">
      Podrás encontrar todos tus documentos en la sección, <a class="text-decoration-none text-info"
        href="#">BÚSQUEDA.</a>
      <br>
    </p>

    <v-divider class="mb-4"></v-divider>

    <div class="text-end">

      <v-btn class="text-none" border variant="text" color="black" @click="redirectTo('busqueda_sb')">
        Emitir otro
        <v-icon end icon="mdi-arrow-left"></v-icon>
      </v-btn>
      <v-btn class="text-none" border variant="text" color="black" @click="redirectTo('busqueda_sb')">
        Emitir otro
        <v-icon end icon="mdi-arrow-left"></v-icon>
      </v-btn>
      
    </div>
  </v-sheet>


</template>

<script>
export default {

  methods: {
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