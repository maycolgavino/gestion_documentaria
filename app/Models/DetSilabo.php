<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DetSilabo extends Model
{
    protected $table = 'det_silabo';
    protected $fillable = ['id','silabo_id', 'carrera', 'curso', 'ruta','anio'];

    // Relación con Silabo
    public function silabo()
    {
        return $this->belongsTo(Silabo::class);
    }
}