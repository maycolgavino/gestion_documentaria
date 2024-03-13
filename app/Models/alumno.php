<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumno';
    // public $incrementing = false; // Si no usas un integer auto-incremental como PK
    protected $keyType = 'string'; // Si tu PK no es un integer
    protected $primaryKey = 'dni';
    protected $fillable = ['dni', 'nombre', 'carrera','email','anio_egreso', 'caja', 'observaciones'];

    // Relación con DocAlumno
    public function documentos()
    {
        return $this->hasMany(DocAlumno::class, 'dni', 'dni');
    }
}
