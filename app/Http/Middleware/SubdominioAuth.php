<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\SubdominioHelper;
use Symfony\Component\HttpFoundation\Response;

class SubdominioAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        // Verificar autenticación según el guard correspondiente
        if (!\Auth::guard($guard)->check()) {
            return redirect('/login');
        }

        return $next($request);
    }
}




