<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradoAlumno extends Model
{
    use HasFactory;

    // Especificar la tabla
    protected $table = 'grados_alumno';

    // Especificar los campos que pueden ser llenados en masa (mass assignment)
    protected $fillable = [
        'dni',
        'anio_bachiller',
        'anio_titulo',
        'anio_maestria',
        'anio_doctorado',
        'anio_especialidad1',
        'anio_especialidad2'
    ];

    // Definir la relación con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'dni', 'dni');
    }
}
