<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MisteryShopperController;
use App\Http\Controllers\VisitaController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\TiposCocinaController;
use App\Http\Controllers\DimensionController;

Route::get('/', [UserController::class, 'login'])->name('index');
Route::get('/login', [UserController::class, 'login'])->name('loginX');
Route::post('/login', [UserController::class, 'validarLogin'])->name('login');
Route::get('/olvidaste', [UserController::class, 'olvidaste']);
Route::post('/recuperar', [UserController::class, 'recuperar'])->name('recuperar');

// Redirección inteligente del registro basado en subdominio
Route::get('/registro', function() {
    $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
    if ($tipo === 'restaurante') {
        return redirect()->route('restaurante.registro');
    }
    return redirect()->route('shopper.registro');
})->name('registro');

// Registro de Mistery Shopper (solo en subdominio shopper)
Route::get('/registro-shopper', [MisteryShopperController::class, 'registroPublico'])->name('shopper.registro');
Route::post('/registro-shopper', [MisteryShopperController::class, 'registroPublicoPost'])->name('shopper.registro.post');

// Registro de Restaurante (solo en subdominio restaurante)
Route::get('/registro-restaurante', [RestauranteController::class, 'registroPublico'])->name('restaurante.registro');
Route::post('/registro-restaurante', [RestauranteController::class, 'registroPublicoPost'])->name('restaurante.registro.post');
Route::post('/restaurantes/get-ciudades', [RestauranteController::class, 'getCiudades'])->name('restaurantes.get_ciudades');

Route::get('/desconectarse', function() {
    $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
    $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
    
    \Auth::guard($guard)->logout();
    return redirect('/login');
})->name('desconectarse');


Route::middleware(['subdominio.auth'])->group(function() {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // --- GRUPO: SHOPPER ONLY ---
    Route::middleware(['subdominio.access:shopper'])->group(function() {
        Route::get('/dashboard/refresh-catalog', [UserController::class, 'refreshCatalog'])->name('shopper.catalog.refresh');
        Route::get('/completar-perfil', [MisteryShopperController::class, 'completarPerfil'])->name('shopper.completar_perfil');
        Route::post('/guardar-perfil', [MisteryShopperController::class, 'guardarPerfil'])->name('shopper.guardar_perfil');
        Route::post('/guardar-respuesta-perfil', [MisteryShopperController::class, 'guardarRespuestaPerfil'])->name('shopper.guardar_respuesta_perfil');
        Route::post('/finalizar-perfil', [MisteryShopperController::class, 'finalizarPerfil'])->name('shopper.finalizar_perfil');
        Route::get('/espera-aprobacion', [MisteryShopperController::class, 'esperaAprobacion'])->name('shopper.espera_aprobacion');
        Route::get('/mi-perfil-shopper', [MisteryShopperController::class, 'miPerfil'])->name('shopper.mi_perfil');
        Route::post('/mi-perfil-shopper/update', [MisteryShopperController::class, 'miPerfilUpdate'])->name('shopper.mi_perfil_update');
        Route::get('/restaurante/{id}', [MisteryShopperController::class, 'restauranteDetalle'])->name('shopper.restaurante_detalle');
    });

    // --- GRUPO: SISTEMA (ADMIN) ONLY ---
    Route::middleware(['subdominio.access:sistema'])->group(function() {
        Route::group(['prefix'=>'usuarios'], function() {
            Route::get('/', [UserController::class, 'index'])->name('usuarios.lista');
            Route::post('/getdata', [UserController::class, 'getData'])->name('usuarios.getdata');
            Route::get('/nuevo', [UserController::class, 'create'])->name('usuarios.nuevo');
            Route::post('/store', [UserController::class, 'store'])->name('usuarios.store');
            Route::get('/editar/{id}', [UserController::class, 'edit'])->name('usuarios.editar');
            Route::post('/update', [UserController::class, 'update'])->name('usuarios.update');
            Route::post('/cambiar-password', [UserController::class, 'cambiar_password'])->name('usuarios.cambiarContra');
            Route::post('/activar', [UserController::class, 'activar'])->name('usuarios.activar');
            Route::post('/eliminar', [UserController::class, 'eliminar'])->name('usuarios.eliminar');
        });

        Route::group(['prefix'=>'shoppers'], function() {
            Route::get('/', [MisteryShopperController::class, 'index'])->name('shoppers.lista');
            Route::post('/getdata', [MisteryShopperController::class, 'getData'])->name('shoppers.getdata');
            Route::get('/nuevo', [MisteryShopperController::class, 'create'])->name('shoppers.nuevo');
            Route::post('/store', [MisteryShopperController::class, 'store'])->name('shoppers.store');
            Route::get('/ver/{id}', [MisteryShopperController::class, 'show'])->name('shoppers.ver');
            Route::get('/editar/{id}', [MisteryShopperController::class, 'edit'])->name('shoppers.editar');
            Route::post('/update', [MisteryShopperController::class, 'update'])->name('shoppers.update');
            Route::post('/eliminar', [MisteryShopperController::class, 'eliminar'])->name('shoppers.eliminar');
            Route::post('/aprobar', [MisteryShopperController::class, 'aprobar'])->name('shoppers.aprobar');
            Route::post('/rechazar', [MisteryShopperController::class, 'rechazar'])->name('shoppers.rechazar');
        });

        Route::group(['prefix'=>'encuestas'], function() {
            Route::get('/', [EncuestaController::class, 'index'])->name('encuestas.lista');
            Route::post('/getdata', [EncuestaController::class, 'getData'])->name('encuestas.getdata');
            Route::get('/nuevo', [EncuestaController::class, 'create'])->name('encuestas.nuevo');
            Route::post('/store', [EncuestaController::class, 'store'])->name('encuestas.store');
            Route::get('/editar/{id}', [EncuestaController::class, 'edit'])->name('encuestas.editar');
            Route::post('/update', [EncuestaController::class, 'update'])->name('encuestas.update');
            Route::post('/eliminar', [EncuestaController::class, 'eliminar'])->name('encuestas.eliminar');
            Route::post('/estado', [EncuestaController::class, 'estado'])->name('encuestas.estado');
            
            // Preguntas de encuesta
            Route::get('/{id}/preguntas', [EncuestaController::class, 'verPreguntas'])->name('encuestas.ver_preguntas');
            Route::post('/preguntas/store', [EncuestaController::class, 'guardarPregunta'])->name('encuestas.guardar_pregunta');
            Route::post('/preguntas/update', [EncuestaController::class, 'actualizarPregunta'])->name('encuestas.actualizar_pregunta');
            Route::post('/preguntas/eliminar', [EncuestaController::class, 'eliminarPregunta'])->name('encuestas.eliminar_pregunta');
            Route::get('/preguntas/get-pregunta/{id}', [EncuestaController::class, 'getPregunta'])->name('encuestas.get_pregunta');
            Route::post('/preguntas/actualizar-orden', [EncuestaController::class, 'actualizarOrden'])->name('encuestas.actualizar_orden');
        });

        Route::group(['prefix' => 'dimensiones'], function() {
            Route::get('/', [DimensionController::class, 'index'])->name('dimensiones.lista');
            Route::post('/getdata', [DimensionController::class, 'getData'])->name('dimensiones.getdata');
            Route::post('/store', [DimensionController::class, 'store'])->name('dimensiones.store');
            Route::post('/update', [DimensionController::class, 'update'])->name('dimensiones.update');
            Route::post('/eliminar', [DimensionController::class, 'eliminar'])->name('dimensiones.eliminar');
        });

        Route::group(['prefix'=>'tipos-cocina'], function() {
            Route::get('/', [TiposCocinaController::class, 'index'])->name('tiposcocina.lista');
            Route::post('/getdata', [TiposCocinaController::class, 'getData'])->name('tiposcocina.getdata');
            Route::post('/store', [TiposCocinaController::class, 'store'])->name('tiposcocina.store');
            Route::post('/update', [TiposCocinaController::class, 'update'])->name('tiposcocina.update');
            Route::post('/eliminar', [TiposCocinaController::class, 'eliminar'])->name('tiposcocina.eliminar');
        });
    });

    // --- GRUPO: RESTAURANTE ONLY (OR ADMIN) ---
    Route::middleware(['subdominio.access:restaurante|sistema'])->group(function() {
        
        // Usuarios para Restaurante (Mapeo de /mis-usuarios a RestauranteController)
        Route::group(['prefix' => 'mis-usuarios'], function() {
            Route::get('/', [RestauranteController::class, 'usuariosAdminIndex'])->name('restaurantes.usuarios.lista');
            Route::post('/getdata', [RestauranteController::class, 'usuariosAdminGetData'])->name('restaurantes.usuarios.getdata');
            Route::get('/nuevo', [RestauranteController::class, 'usuariosAdminCreate'])->name('restaurantes.usuarios.nuevo');
            Route::post('/store', [RestauranteController::class, 'usuariosAdminStore'])->name('restaurantes.usuarios.store');
            Route::get('/editar/{id}', [RestauranteController::class, 'usuariosAdminEdit'])->name('restaurantes.usuarios.editar');
            Route::post('/update', [RestauranteController::class, 'usuariosAdminUpdate'])->name('restaurantes.usuarios.update');
        });

        Route::group(['prefix'=>'restaurantes'], function() {
            Route::get('/', [RestauranteController::class, 'index'])->name('restaurantes.lista');
            Route::post('/getdata', [RestauranteController::class, 'getData'])->name('restaurantes.getdata');
            Route::get('/nuevo', [RestauranteController::class, 'create'])->name('restaurantes.nuevo');
            Route::post('/store', [RestauranteController::class, 'store'])->name('restaurantes.store');
            Route::get('/ver/{id}', [RestauranteController::class, 'show'])->name('restaurantes.ver');
            Route::get('/editar/{id}', [RestauranteController::class, 'edit'])->name('restaurantes.editar');
            Route::post('/update', [RestauranteController::class, 'update'])->name('restaurantes.update');
            Route::post('/eliminar', [RestauranteController::class, 'eliminar'])->name('restaurantes.eliminar');
            Route::post('/estado', [RestauranteController::class, 'estado'])->name('restaurantes.estado');
            Route::post('/aprobar', [RestauranteController::class, 'aprobar'])->name('restaurantes.aprobar');
            Route::post('/rechazar', [RestauranteController::class, 'rechazar'])->name('restaurantes.rechazar');

            // Canje de cupones
            Route::get('/canje', [RestauranteController::class, 'canjeIndex'])->name('restaurantes.canje');
            Route::post('/canje/validar', [RestauranteController::class, 'canjeValidar'])->name('restaurantes.canje.validar');
            Route::post('/canje/confirmar', [RestauranteController::class, 'canjeConfirmar'])->name('restaurantes.canje.confirmar');

            // Usuarios Administradores de Restaurantes
            Route::get('/usuarios-admin', [RestauranteController::class, 'usuariosAdminIndex'])->name('restaurantes.usuarios_admin.lista');
            Route::post('/usuarios-admin/getdata', [RestauranteController::class, 'usuariosAdminGetData'])->name('restaurantes.usuarios_admin.getdata');
            Route::get('/usuarios-admin/nuevo', [RestauranteController::class, 'usuariosAdminCreate'])->name('restaurantes.usuarios_admin.nuevo');
            Route::post('/usuarios-admin/store', [RestauranteController::class, 'usuariosAdminStore'])->name('restaurantes.usuarios_admin.store');
            Route::get('/usuarios-admin/editar/{id}', [RestauranteController::class, 'usuariosAdminEdit'])->name('restaurantes.usuarios_admin.editar');
            Route::post('/usuarios-admin/update', [RestauranteController::class, 'usuariosAdminUpdate'])->name('restaurantes.usuarios_admin.update');
        });
    });

    // --- GRUPO: MIXTO (VISITAS) ---
    Route::group(['prefix'=>'visitas'], function() {
        Route::get('/', [VisitaController::class, 'index'])->name('visitas.lista');
        Route::post('/getdata', [VisitaController::class, 'getData'])->name('visitas.getdata');
        
        // Shopper Actions
        Route::middleware(['subdominio.access:shopper'])->group(function() {
            Route::post('/agendar', [VisitaController::class, 'agendarPublico'])->name('visitas.agendar_publico');
            Route::post('/agendar-shopper', [VisitaController::class, 'agendarShopperPost'])->name('visitas.agendar_shopper');
            Route::get('/responder-entrada/{id}', [VisitaController::class, 'responderEncuestaEntrada'])->name('visitas.responder_entrada');
            Route::post('/guardar-entrada', [VisitaController::class, 'guardarEncuestaEntrada'])->name('visitas.guardar_entrada');
            Route::get('/responder-salida/{id}', [VisitaController::class, 'responderEncuestaSalida'])->name('visitas.responder_salida');
            Route::post('/guardar-salida', [VisitaController::class, 'guardarEncuestaSalida'])->name('visitas.guardar_salida');
            Route::post('/marcar-visitado', [VisitaController::class, 'marcarVisitado'])->name('visitas.marcar_visitado');
            Route::post('/rechazar', [VisitaController::class, 'rechazar'])->name('visitas.rechazar');
            Route::get('/cupon/{id}', [VisitaController::class, 'mostrarCupon'])->name('visitas.ver_cupon');
        });

        // Admin Actions
        Route::middleware(['subdominio.access:sistema'])->group(function() {
            Route::get('/nuevo', [VisitaController::class, 'create'])->name('visitas.nuevo');
            Route::post('/store', [VisitaController::class, 'store'])->name('visitas.store');
            Route::get('/editar/{id}', [VisitaController::class, 'edit'])->name('visitas.editar');
            Route::post('/update', [VisitaController::class, 'update'])->name('visitas.update');
            Route::post('/eliminar', [VisitaController::class, 'eliminar'])->name('visitas.eliminar');
            Route::post('/get-shoppers', [VisitaController::class, 'getShoppers'])->name('visitas.get_shoppers');
        });

        // Shared Actions (Access via their own subdomains)
        Route::get('/ver/{id}', [VisitaController::class, 'show'])->name('visitas.ver');
        Route::post('/onboarding/complete', function() { return response()->json(['estado'=>200]); })->name('onboarding.complete');
    });

    // --- GRUPO: RESULTADOS / ENCUESTAS ---
    Route::group(['prefix' => 'resultados'], function() {
        
        // Rutas para Restaurante
        Route::middleware(['subdominio.access:restaurante'])->group(function() {
            Route::get('/encuestas-restaurante', [VisitaController::class, 'indexRestaurante'])->name('resultados.encuestas_restaurante');
            Route::post('/getdata-restaurante', [VisitaController::class, 'getDataRestaurante'])->name('resultados.getdata_restaurante');
            Route::post('/detalle-restaurante', [VisitaController::class, 'getDetalleVisitaRestaurante'])->name('resultados.detalle_restaurante');
        });

        // Rutas para Sistema (y Shopper según el controlador)
        Route::middleware(['subdominio.access:sistema|shopper'])->group(function() {
            Route::get('/encuestas-sistema', [VisitaController::class, 'indexSistemaEncuestas'])->name('resultados.encuestas_sistema');
            Route::post('/getdata-sistema', [VisitaController::class, 'getDataSistemaEncuestas'])->name('resultados.getdata_sistema');
            Route::post('/detalle-sistema', [VisitaController::class, 'getDetalleVisitaSistema'])->name('resultados.detalle_sistema');
        });
    });

});
