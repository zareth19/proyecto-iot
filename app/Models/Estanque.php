<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estanque extends Model
{
    protected $fillable = ['identificador', 'tipo_cultivo', 'cultivo_pez_id', 'capacidad', 'descripcion', 'estado', 'largo', 'ancho', 'area_m2', 'cantidad_sembrada', 'densidad_siembra'];

    public function sensores()
    {
        return $this->hasMany('App\Models\SensorFinal', 'estanque_id');
    }

    public function cultivoPez()
    {
        return $this->belongsTo(CultivoPez::class, 'cultivo_pez_id');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($estanque) {
            if ($estanque->largo && $estanque->ancho) {
                $estanque->area_m2 = $estanque->largo * $estanque->ancho;
                // Capacidad de peces: 10 peces por m²
                $estanque->capacidad = (int)($estanque->area_m2 * 10);
            }
            
            // Calcular densidad si hay peces sembrados
            if ($estanque->cantidad_sembrada && $estanque->area_m2) {
                $estanque->densidad_siembra = $estanque->cantidad_sembrada / $estanque->area_m2;
            }
        });
    }
}