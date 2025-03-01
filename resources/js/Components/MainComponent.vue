<template>
  <div>
    <!-- Academic Search -->
    <v-card class="mx-auto" max-width="900" variant="text">
      <v-list lines="three">
        <v-list-item>
          <template v-slot:title> Búsqueda Documentos Académicos </template>
          <template v-slot:prepend>
            <v-avatar size="64" rounded="0">
              <v-icon color="blue-darken-3" size="48">mdi-school</v-icon>
            </v-avatar>
          </template>
          <template v-slot:subtitle>
            <span class="font-weight-bold">Documentos Académicos</span> &mdash;
            Aquí es donde podrás buscar y agregar datos de alumnos y sus
            documentos
            <div class="mt-2">
              <v-row>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    rounded="sm"
                    variant="outlined"
                    prepend-icon="mdi-pencil"
                    @click="redirectTo('agregar_ac')"
                  >
                    Agregar
                  </v-btn>
                </v-col>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    variant="elevated"
                    prepend-icon="mdi-clipboard-text-search"
                    @click="redirectTo('busqueda_ac')"
                  >
                    Búsqueda
                  </v-btn>
                </v-col>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    variant="elevated"
                    prepend-icon="mdi-update"
                    @click="redirectTo('actualizar_ac')"
                  >
                    Actualizar
                  </v-btn>
                </v-col>
              </v-row>
            </div>
          </template>
        </v-list-item>

        <v-divider></v-divider>

        <v-list-item>
          <template v-slot:title> Búsqueda Documentaria </template>
          <template v-slot:prepend>
            <v-avatar size="64" rounded="0">
              <v-icon color="blue-darken-3" size="48">mdi-file-document</v-icon>
            </v-avatar>
          </template>
          <template v-slot:subtitle>
            <span class="font-weight-bold">Resoluciones Administrativas</span>
            &mdash; Aquí es donde se buscará y agregará datos de Resoluciones
            Administrativas
            <div class="mt-2">
              <v-row>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    rounded="sm"
                    variant="outlined"
                    prepend-icon="mdi-pencil"
                    @click="redirectTo('agregar_ad')"
                  >
                    Agregar
                  </v-btn>
                </v-col>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    variant="elevated"
                    @click="redirectTo('busqueda_ad')"
                    prepend-icon="mdi-clipboard-text-search"
                  >
                    Búsqueda
                  </v-btn>
                </v-col>
              </v-row>
            </div>
          </template>
        </v-list-item>

        <v-divider></v-divider>

        <v-list-item>
          <template v-slot:title> Búsqueda Sílabos Académicos </template>
          <template v-slot:prepend>
            <v-avatar size="64" rounded="0">
              <v-icon color="blue-darken-3" size="48"
                >mdi-note-text-outline</v-icon
              >
            </v-avatar>
          </template>
          <template v-slot:subtitle>
            <span class="font-weight-bold">Silabos Académicos</span> &mdash;
            Aquí se encontrarán y agregarán datos de Sílabos Académicos
            <div class="mt-2">
              <v-row>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    rounded="sm"
                    variant="outlined"
                    prepend-icon="mdi-pencil"
                    @click="redirectTo('agregar_sb')"
                  >
                    Agregar
                  </v-btn>
                </v-col>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    variant="elevated"
                    prepend-icon="mdi-clipboard-text-search"
                    @click="redirectTo('busqueda_sb')"
                  >
                    Búsqueda
                  </v-btn>
                </v-col>
              </v-row>
            </div>
          </template>
        </v-list-item>

        <v-list-item>
          <template v-slot:title> Búsqueda Documentaria </template>
          <template v-slot:prepend>
            <v-avatar size="64" rounded="0">
              <v-icon color="blue-darken-3" size="48">mdi-file-document</v-icon>
            </v-avatar>
          </template>
          <template v-slot:subtitle>
            <span class="font-weight-bold">Reportes Personalizados</span>
            &mdash; Aqui es donde se podran realizar descargas de reportes
            <div class="mt-2">
              <v-row>
                <v-col cols="auto">
                  <v-btn
                    class="text-none text-subtitle-2"
                    color="blue-darken-3"
                    variant="elevated"
                    @click="redirectTo('reporte_alumnos')"
                    prepend-icon="mdi-clipboard-text-search"
                  >
                    Ingresar
                  </v-btn>
                </v-col>
              </v-row>
            </div>
          </template>
        </v-list-item>
      </v-list>
    </v-card>
  </div>
</template>

<script>
export default {
  methods: {
    redirectTo(routeName) {
      console.log('Redirigiendo a:', routeName)
      switch (routeName) {
        case 'actualizar_ac':
          window.location.href = '/update_ac' // Reemplaza con la ruta de Laravel
          break
        case 'agregar_ac':
          window.location.href = '/add_ac' // Reemplaza con la ruta de Laravel
          break
        case 'busqueda_ac':
          window.location.href = '/look_ac' // Reemplaza con la ruta de Laravel
          break
        case 'actualizar_ad':
          window.location.href = '/update_ad' // Reemplaza con la ruta de Laravel
          break
        case 'agregar_ad':
          window.location.href = '/add_ad' // Reemplaza con la ruta de Laravel
          break
        case 'busqueda_ad':
          window.location.href = '/look_ad' // Reemplaza con la ruta de Laravel
          break

        case 'actualizar_sb':
          window.location.href = '/update_sb' // Reemplaza con la ruta de Laravel
          break
        case 'agregar_sb':
          window.location.href = '/add_sb' // Reemplaza con la ruta de Laravel
          break
        case 'busqueda_sb':
          window.location.href = '/look_sb' // Reemplaza con la ruta de Laravel
          break

        case 'reporte_alumnos':
          window.location.href = '/report_dashboard' // Reemplaza con la ruta de Laravel
          break
      }
    },
  },
}
</script>

<style lang="">
</style>
