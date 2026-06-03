<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'descripcion',
    ];

    /**
     * Obtener valor de configuración por clave
     */
    public static function obtenerValor(string $clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        if (!$config) {
            return $default;
        }

        switch ($config->tipo) {
            case 'integer':
                return (int) $config->valor;
            case 'boolean':
                return filter_var($config->valor, FILTER_VALIDATE_BOOLEAN);
            default:
                return $config->valor;
        }
    }

    /**
     * Establecer valor de configuración
     */
    public static function establecerValor(string $clave, $valor, string $tipo = 'string', string $descripcion = null)
    {
        return self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => (string) $valor,
                'tipo' => $tipo,
                'descripcion' => $descripcion,
            ]
        );
    }
}




