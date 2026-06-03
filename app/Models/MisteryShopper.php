<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MisteryShopper extends Authenticatable
{
    use Notifiable;
    protected $table = 'mistery_shoppers';

    protected $fillable = [
        'name',
        'email',
        'telefono',
        'observaciones',
        'estado',
        'password',
        'aprobado',
        'aprobado_por',
        'aprobado_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'mistery_shopper_id');
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    protected $appends = ['id_encrypted'];

    public function getIdEncryptedAttribute()
    {
        return encrypt($this->id);
    }

    /**
     * Verificar si el shopper está aprobado
     */
    public function estaAprobado()
    {
        return $this->aprobado == 1;
    }

    /**
     * Verificar si puede iniciar sesión
     */
    public function puedeIniciarSesion()
    {
        return $this->estado == 1 && $this->aprobado == 1;
    }
}

