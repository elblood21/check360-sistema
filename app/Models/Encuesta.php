<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encuesta extends Model
{
    use SoftDeletes;

    protected $table = 'encuestas';

    protected $fillable = [
        'tipo',
        'nombre',
        'descripcion',
        'estado',
    ];

    public function preguntas()
    {
        return $this->hasMany(PreguntaEncuesta::class, 'encuesta_id')->whereNull('deleted_at')->orderBy('orden');
    }

    protected $appends = ['id_encrypted'];

    public function getIdEncryptedAttribute()
    {
        return encrypt($this->id);
    }
}




