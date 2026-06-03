<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespuestaVisita extends Model
{
    protected $table = 'respuestas_visitas';

    protected $fillable = [
        'visita_id',
        'pregunta_id',
        'pregunta_texto',
        'respuesta_texto',
        'respuesta_valor',
        'encuesta_tipo',
    ];

    public function visita()
    {
        return $this->belongsTo(Visita::class, 'visita_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(PreguntaEncuesta::class, 'pregunta_id');
    }
}




