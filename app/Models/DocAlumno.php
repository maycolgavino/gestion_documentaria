<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocAlumno extends Model
{
    protected $table = 'doc_alumno';
    protected $fillable = ['dni', 'grado', 'tipo', 'ruta'];

    // Relación con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'dni', 'dni');
    }
}
