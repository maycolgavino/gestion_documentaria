<?php

// Modelo actualizado para Alumno
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class alumno extends Model
{
    protected $table = 'alumno';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'dni',
        'nombre',
        'matricula_code',
        'carrera',
        'caja',
        'num_registros_caja',
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

    // Método para incrementar el contador de registros en una caja
    // public static function incrementarRegistrosCaja($caja)
    // {
    //     $alumno = self::where('caja', $caja)->first();
    //     if ($alumno) {
    //         $alumno->increment('num_registros_caja');
    //     }
    // }
}
