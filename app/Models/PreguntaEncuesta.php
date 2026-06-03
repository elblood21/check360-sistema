<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreguntaEncuesta extends Model
{
    use SoftDeletes;

    protected $table = 'preguntas_encuestas';

    protected $fillable = [
        'encuesta_id',
        'texto',
        'tipo_respuesta',
        'orden',
        'dimension',
        'opciones',
    ];

    protected $casts = [
        'opciones' => 'array',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaVisita::class, 'pregunta_id');
    }

    public function dimension_rel()
    {
        return $this->belongsTo(DimensionEncuesta::class, 'dimension');
    }
}




