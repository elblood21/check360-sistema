<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\SubdominioHelper;
use Symfony\Component\HttpFoundation\Response;

class SubdominioAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $allowedTypes  Tipos permitidos separados por pipe (shopper|restaurante|sistema)
     */
    public function handle(Request $request, Closure $next, string $allowedTypes): Response
    {
        $tipoActual = SubdominioHelper::obtenerTipo();
        $tiposPermitidos = explode('|', $allowedTypes);

        // Si el subdominio actual no está en la lista de permitidos para esta ruta
        if (!in_array($tipoActual, $tiposPermitidos)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['estado' => 403, 'mensaje' => 'Acceso no autorizado para este subdominio'], 403);
            }
            abort(403, 'No tienes permiso para acceder a este recurso desde este subdominio.');
        }

        // Verificar que el usuario esté autenticado con el guard que corresponde al subdominio
        $guard = SubdominioHelper::obtenerGuard();
        if (!\Auth::guard($guard)->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['estado' => 401, 'mensaje' => 'Sesión expirada'], 401);
            }
            return redirect()->route('loginX');
        }

        return $next($request);
    }
}
