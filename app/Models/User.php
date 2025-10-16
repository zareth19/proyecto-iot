<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'users';

    /**
     * Campos que se pueden llenar masivamente.
     */
    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'contraseña',
        'rol',
        
    ];

    /**
     * Campos ocultos al serializar.
     */
    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    /**
     * esto para que Laravel use 'contraseña' como el campo de password.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    /**
     * Campos con conversión automática de tipos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Método para obtener el nombre completo del usuario.
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}
