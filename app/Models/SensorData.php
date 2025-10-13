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
        'oxigeno_disuelto',
        'amoniaco',
        'nitritos',
        'nitratos',
        'alcalinidad',
        'dureza',
        'turbidez',
        'conductividad'
    ];
}
