<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteManual extends Model
{
    use HasFactory;

    protected $table = 'reportes_manuales';

    protected $fillable = [
        'usuario_id',
        'temperatura',
        'ph',
        'turbidez',
        'observaciones',
        'fecha_toma'
    ];

    protected $casts = [
        'fecha_toma' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}