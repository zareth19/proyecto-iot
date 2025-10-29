<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultivoPez extends Model
{
    use HasFactory;

    protected $table = 'cultivo_peces';

    protected $fillable = [
        'nombre_especie',
        'nombre_cientifico',
        'descripcion',
        'densidad_recomendada',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'densidad_recomendada' => 'decimal:2'
    ];

    public function estanques()
    {
        return $this->hasMany(Estanque::class, 'cultivo_pez_id');
    }
}