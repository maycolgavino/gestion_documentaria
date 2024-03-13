<template>
    <v-card class="mx-auto" max-width="944" title="Busqueda de Documentos de Tema Resoluciones">
        <v-container>
            <v-container>
                <!-- Campos adicionales en la parte superior -->
                <v-row>
                    <v-col cols="12" md="6">
                        <v-select density="compact" placeholder="Tipos de Resolucion"
                            prepend-inner-icon="mdi-archive-outline" :items="tiposResolucion" variant="outlined"
                            v-model="tipoResSeleccionado"></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-select density="compact" placeholder="Año" prepend-inner-icon="mdi-calendar-range"
                            variant="outlined" :items="aniosDisponibles" v-model="anioSeleccionado"></v-select>
                    </v-col>
                </v-row>
            </v-container>


            <v-btn color="primary" @click="buscarResoluciones">Buscar</v-btn> <!-- Botón de búsqueda -->
        </v-container>
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
                            <td>{{ detres.numero }}</td>
                            <td>{{ detres.autor }}</td>
                            <td>{{ detres.receptor }}</td>
                            <td>{{ detres.motivo }}</td>
                            <td>{{ detres.documento }}</td>
                            <td>
                                <v-btn icon @click="descargarDocumento(detres.id)">
                                    <v-icon>mdi-download</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>
        </div>
    </v-card>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            archivosList: [],
            tipoResSeleccionado: '',
            anioSeleccionado: '',
            tiposResolucion: ["Resolución Directorial Administrativa", "Resoluciones de Asamblea Universitaria", "Resoluciones Rectorales", "Resoluciones de Consejo Universitario"],
            aniosDisponibles: Array.from({ length: 2024 - 1965 + 1 }, (v, k) => 2024 - k), // Almacena los resultados de la búsqueda
        };
    },
    methods: {
        // Método para buscar sílabos basado en la consulta

        buscarResoluciones() {
            const params = {
                tipoResolucion: this.tipoResSeleccionado,
                anio: this.anioSeleccionado,
            };
            axios.get('/res_details', { params })
                .then(response => {
                    this.archivosList = response.data.data;
                })
                .catch(error => {
                    console.error("Error al buscar resoluciones:", error);
                });
        },
        descargarDocumento(id) {
            window.open(`/res_download/${id}`, '_blank');
        },
        // Método para descargar un documento
    }
};
</script>