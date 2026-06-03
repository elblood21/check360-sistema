<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimensionEncuesta extends Model
{
    protected $table = 'dimension_encuestas';

    protected $fillable = [
        'nombre',
        'color',
        'icono',
        'orden',
        'estado',
    ];

    protected $appends = ['id_encrypted'];

    public function getIdEncryptedAttribute()
    {
        return encrypt($this->id);
    }
}
