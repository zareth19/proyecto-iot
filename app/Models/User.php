<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'correo',
        'telefono',
        'contraseña',
        'rol',
        'foto',
        'debe_cambiar_contraseña'
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
     * Método para obtener el nombre completo del usuario (atómico).
     */
    public function getNombreCompletoAttribute()
    {
        $nombres = trim(($this->primer_nombre ?? '') . ' ' . ($this->segundo_nombre ?? ''));
        $apellidos = trim(($this->primer_apellido ?? '') . ' ' . ($this->segundo_apellido ?? ''));
        
        // Si no hay campos atómicos, usar los campos legacy
        if (empty($nombres) && empty($apellidos)) {
            return "{$this->nombre} {$this->apellido}";
        }
        
        return trim($nombres . ' ' . $apellidos);
    }
    
    /**
     * Obtener tipo de documento en formato largo
     */
    public function getTipoDocumentoLargoAttribute()
    {
        $tipos = [
            'CC' => 'Cédula de Ciudadanía',
            'TI' => 'Tarjeta de Identidad',
            'CE' => 'Cédula de Extranjería',
            'PP' => 'Pasaporte'
        ];
        
        return $tipos[$this->tipo_documento] ?? $this->tipo_documento;
    }

    /**
     * Alias para compatibilidad con notificaciones
     */
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    public function getNameAttribute()
    {
        // Usar campos atómicos si están disponibles
        if ($this->primer_nombre || $this->primer_apellido) {
            return $this->nombre_completo;
        }
        
        return $this->nombre . ' ' . $this->apellido;
    }
}