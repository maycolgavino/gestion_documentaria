<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Silabo extends Model
{

    protected $table = 'silabo';
    
    protected $fillable = ['email', 'anio', 'facultad', 'escuela'];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    // Relación con DetSilabo
    public function detalles()
    {
        return $this->hasMany(DetSilabo::class);
    }
}