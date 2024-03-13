<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetResolucion extends Model
{
    protected $table = 'det_resolucion';
    protected $fillable = ['id','resolucion_id', 'numero', 'autor', 'receptor', 'motivo', 'ruta'];

    // Relación con Resolucion
    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class);
    }
}