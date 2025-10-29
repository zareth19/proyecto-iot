<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorFinal extends Model
{
    protected $table = 'sensores_final';
    protected $fillable = ['estanque_id', 'temperatura', 'ph', 'turbidez', 'fecha'];

    public function estanque()
    {
        return $this->belongsTo(Estanque::class);
    }
}