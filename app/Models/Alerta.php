<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $fillable = [
        'sensor_id',
        'tipo',
        'mensaje',
        'nivel',
        'leida',
        'fecha_alerta'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'fecha_alerta' => 'datetime'
    ];

    public function sensor()
    {
        return $this->belongsTo(SensorData::class, 'sensor_id');
    }
}