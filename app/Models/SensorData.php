<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
      use HasFactory;
  protected $table = 'sensores_final'; // tu tabla existente
    protected $primaryKey = 'id';

    public $timestamps = false; // ya tienes columna fecha

    protected $fillable = [
        'fecha',
        'temperatura',
        'ph',
        'turbidez'
    ];

    // Atributos simulados para compatibilidad
    protected $appends = [
        'oxigeno_disuelto',
        'amoniaco', 
        'nitritos',
        'nitratos',
        'alcalinidad',
        'dureza',
        'conductividad'
    ];

    // Generar valores simulados para sensores que no tienes
    public function getOxigenoDisueltoAttribute()
    {
        return round(5.0 + (rand(-10, 20) / 10), 2);
    }

    public function getAmoniacoAttribute()
    {
        return round(0.02 + (rand(0, 5) / 100), 3);
    }

    public function getNitritosAttribute()
    {
        return round(0.1 + (rand(0, 10) / 100), 2);
    }

    public function getNitratosAttribute()
    {
        return round(50.0 + rand(-20, 40), 2);
    }

    public function getAlcalinidadAttribute()
    {
        return round(80.0 + rand(-30, 30), 2);
    }

    public function getDurezaAttribute()
    {
        return round(150.0 + rand(-50, 100), 2);
    }

    public function getConductividadAttribute()
    {
        return round(200.0 + rand(-50, 100), 2);
    }
}
