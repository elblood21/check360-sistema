<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespuestaPerfilShopper extends Model
{
    protected $table = 'respuestas_perfil_shoppers';

    protected $fillable = [
        'mistery_shopper_id',
        'pregunta_id',
        'respuesta_texto',
        'respuesta_valor',
    ];

    public function shopper()
    {
        return $this->belongsTo(MisteryShopper::class, 'mistery_shopper_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(PreguntaEncuesta::class, 'pregunta_id');
    }
}
