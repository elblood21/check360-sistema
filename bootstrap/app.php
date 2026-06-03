<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'subdominio.auth' => \App\Http\Middleware\SubdominioAuth::class,
            'subdominio.access' => \App\Http\Middleware\SubdominioAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e, \Illuminate\Http\Request $request) {
            // Siempre devolver JSON amigable para errores de transporte de correo
            return response()->json([
                'estado' => 500,
                'mensaje' => 'Error de conexión con el servidor de correo. Intente más tarde.'
            ], 500);
        });
    })->create();
