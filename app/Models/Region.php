<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regiones';

    protected $fillable = [
        'nombre',
        'numero',
    ];

    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'region_id');
    }
}

