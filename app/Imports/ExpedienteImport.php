<?php

namespace App\Imports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExpedienteImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Verificar si el alumno ya existe en la base de datos
        $alumno = Alumno::where('dni', $row['dni'])->first();

        if ($alumno) {
            // Si el alumno ya existe, incrementar el conteo de documentos
            $alumno->increment('document_count');
            // Actualizar otros campos si se desea (opcional)
            $alumno->update([
                'nombre' => $row['nombre'],
                'carrera' => $row['carrera'],
                'caja' => $row['caja'],
                'observaciones' => $row['observaciones'],
                'anio_bachiller' => $row['anio_bachiller'],
                'anio_titulo' => $row['anio_titulo'],
                'anio_maestria' => $row['anio_maestria'],
                'anio_doctorado' => $row['anio_doctorado'],
                'anio_especialidad1' => $row['anio_especialidad1'],
                'anio_especialidad2' => $row['anio_especialidad2'],
            ]);

            return null; // No creamos un nuevo alumno si ya existe
        }

        // Si el alumno no existe, crear un nuevo registro
        return new Alumno([
            'dni' => $row['dni'],
            'matricula_code' => $row['matricula_code'],
            'nombre' => $row['nombre'],
            'carrera' => $row['carrera'],
            'caja' => $row['caja'],
            'observaciones' => $row['observaciones'],
            'anio_bachiller' => $row['anio_bachiller'],
            'anio_titulo' => $row['anio_titulo'],
            'anio_maestria' => $row['anio_maestria'],
            'anio_doctorado' => $row['anio_doctorado'],
            'anio_especialidad1' => $row['anio_especialidad1'],
            'anio_especialidad2' => $row['anio_especialidad2'],
            'document_count' => 1, // Asignar 1, ya que este es el primer documento importado
        ]);
    }
}
