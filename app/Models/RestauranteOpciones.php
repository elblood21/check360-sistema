<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestauranteOpciones extends Model
{
    protected $table = 'restaurante_opciones';

    protected $fillable = [
        'restaurante_id',
        'clave',
        'valor_json',
        'valor_texto',
    ];

    protected $casts = [
        'valor_json' => 'array',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }
}






