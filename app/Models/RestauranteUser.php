<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class RestauranteUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'restaurante_users';

    protected $fillable = [
        'restaurante_id',
        'name',
        'email',
        'password',
        'estado',
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

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    protected $appends = ['id_encrypted'];

    public function getIdEncryptedAttribute()
    {
        return encrypt($this->id);
    }
}




