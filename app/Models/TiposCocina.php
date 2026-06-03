<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiposCocina extends Model
{
    use SoftDeletes;

    protected $table = 'tipos_cocinas';

    protected $fillable = [
        'name',
        'icon',
        'color_primary',
        'color_secondary',
    ];
}






