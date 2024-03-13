<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    protected $table = 'resolucion';
    protected $fillable = ['email', 'tipo_res', 'anio'];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    // Relación con DetResolucion
    public function detalles()
    {
        return $this->hasMany(DetResolucion::class);
    }
}
