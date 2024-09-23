<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class alumno extends Model
{
    protected $table = 'alumno';
    // public $incrementing = false; // Si no usas un integer auto-incremental como PK
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'dni',
        'nombre',
        'matricula_code',
        'carrera',
        'email',
        'anio_egreso',
        'caja',
        'observaciones',
        'anio_bachiller',
        'anio_titulo',
        'anio_maestria',
        'anio_doctorado',
        'anio_especialidad1',
        'anio_especialidad2'
    ];

    // Relación con DocAlumno
    public function documentos()
    {
        return $this->hasMany(DocAlumno::class, 'dni', 'dni');
    }
}
