<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoVisita extends Model
{
    protected $table = 'estados_visitas';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'estado_id');
    }
}




