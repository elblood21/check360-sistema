<?php

namespace App\Helpers;

class SubdominioHelper
{
    /**
     * Obtener el tipo de subdominio actual
     * 
     * @return string 'sistema'|'restaurante'|'shopper'
     */
    public static function obtenerTipo()
    {
        $host = request()->getHost();
        
        // Si es localhost, retornar 'sistema'
        if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, 'localhost') !== false) {
            return 'restaurante';
        }

        // Extraer subdominio
        $partes = explode('.', $host);
        
        if (count($partes) >= 3) {
            $subdominio = $partes[0];
            
            if ($subdominio === 'shoppers') {
                return 'shopper';
            } elseif ($subdominio === 'sistema') {
                return 'sistema';
            }
        }
        
        // Por defecto, sistema
        return 'restaurante';
    }

    /**
     * Obtener el guard de autenticación según el subdominio
     * 
     * @return string
     */
    public static function obtenerGuard()
    {
        $tipo = self::obtenerTipo();
        
        switch ($tipo) {
            case 'restaurante':
                return 'restaurante';
            case 'shopper':
                return 'shopper';
            default:
                return 'web';
        }
    }

    /**
     * Verificar si el tipo actual es el especificado
     * 
     * @param string $tipo
     * @return bool
     */
    public static function esTipo($tipo)
    {
        return self::obtenerTipo() === $tipo;
    }
}




