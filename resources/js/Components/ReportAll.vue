<template>
    <v-container ref="reporte">
        <h2 class="text-h4 mb-4">📊 Reporte de Alumnos con Datos Incompletos</h2>

        <!-- Botones de Exportación -->

        <v-btn color="green-darken-2" dark @click="exportarExcel">📊 Exportar a Excel</v-btn>

        <!-- Resumen -->
        <v-card class="mt-4 pa-4">
            <h3 class="text-h6">📌 Resumen de Alumnos con Datos Incompletos</h3>
            <p>🔴 Sin DNI: {{ resumen.sinDNI }}</p>
            <p>🔵 Sin Matrícula: {{ resumen.sinMatricula }}</p>
            <p>🟣 Sin Ambos: {{ resumen.sinAmbos }}</p>
        </v-card>

        <!-- Campo de búsqueda -->
        <v-text-field v-model="search" label="Buscar alumno..." outlined dense class="mt-4"></v-text-field>

        <!-- Tabla con Scroll -->
        <div class="tabla-contenedor">
            <v-data-table :items="alumnosFiltrados" dense>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>DNI</th>
                            <th>Código Matrícula</th>
                            <th>Caja</th>
                            <th>Carrera</th>
                            <th>Fecha de Ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in alumnosFiltrados" :key="index">
                            <td>{{ item.nombre }}</td>
                            <td>{{ item.dni || "N/A" }}</td>
                            <td>{{ item.matricula_code || "N/A" }}</td>
                            <td>{{ item.caja || "N/A" }}</td>
                            <td>{{ item.carrera || "N/A" }}</td>
                            <td>{{ item.created_at || "N/A" }}</td>
                        </tr>
                    </tbody>
                </template>
            </v-data-table>
        </div>
    </v-container>
</template>

<script>
import axios from "axios";
import { jsPDF } from "jspdf";
import html2canvas from "html2canvas";
import * as xlsx from "node-xlsx";
import { saveAs } from "file-saver";

export default {
    data() {
        return {
            alumnos: [],
            search: "",
            resumen: { sinDNI: 0, sinMatricula: 0, sinAmbos: 0 },
        };
    },
    computed: {
        alumnosFiltrados() {
            return this.alumnos.filter((alumno) =>
                Object.values(alumno).some((value) =>
                    String(value).toLowerCase().includes(this.search.toLowerCase())
                )
            );
        },
    },
    methods: {
        async fetchAlumnos() {
            try {
                const response = await axios.get("/alumnos_incompletos");
                this.alumnos = response.data;
                this.calcularResumen();
            } catch (error) {
                console.error("Error obteniendo datos:", error);
            }
        },
        calcularResumen() {
            this.resumen.sinDNI = this.alumnos.filter(
                (a) => !a.dni || a.dni === "00000000" || a.dni === "-"
            ).length;
            this.resumen.sinMatricula = this.alumnos.filter(
                (a) => !a.matricula_code || a.matricula_code === "0000000000" || a.matricula_code === "-"
            ).length;
            this.resumen.sinAmbos = this.alumnos.filter(
                (a) =>
                    (!a.dni || a.dni === "00000000" || a.dni === "-") &&
                    (!a.matricula_code || a.matricula_code === "0000000000" || a.matricula_code === "-")
            ).length;
        },
        async descargarPDF() {
            try {
                const element = this.$refs.reporte?.$el || this.$refs.reporte;
                if (!element) throw new Error("No se encontró el contenedor del reporte.");

                const canvas = await html2canvas(element, { scale: 2 });
                const imgData = canvas.toDataURL("image/png");

                const doc = new jsPDF("p", "mm", "a4");
                doc.addImage(imgData, "PNG", 10, 10, 190, 0);
                doc.save("Reporte_Alumnos.pdf");
            } catch (error) {
                console.error("Error al generar el PDF:", error);
            }
        },
        exportarExcel() {
            // Encabezados
            const headers = ["Nombre", "DNI", "Código Matrícula", "Caja", "Carrera", "Fecha de Ingreso"];
            // Datos
            const data = this.alumnos.map(a => [
                a.nombre || "N/A",
                a.dni || "N/A",
                a.matricula_code || "N/A",
                a.caja || "N/A",
                a.carrera || "N/A",
                a.created_at || "N/A"
            ]);
            // Unir encabezados y datos
            const sheetData = [headers, ...data];
            // Generar buffer
            const buffer = xlsx.build([{ name: "Reporte Alumnos", data: sheetData }]);
            // Guardar archivo
            const blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            saveAs(blob, "Reporte_Alumnos.xlsx");
        },
    },
    mounted() {
        this.fetchAlumnos();
    },
};
</script>

<style scoped>
/* Limitar la altura de la tabla y permitir el scroll */
.tabla-contenedor {
    max-height: 400px;
    /* Altura fija */
    overflow-y: auto;
    /* Scroll vertical */
    border: 1px solid #ddd;
    /* Borde para mejor visualización */
}

/* Ajustar tamaño de la tabla */
.v-data-table {
    background-color: white;
    font-size: 0.85rem;
    /* Reducir tamaño de texto */
}
</style>
