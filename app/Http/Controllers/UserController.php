<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RestauranteUser;
use App\Models\MisteryShopper;
use App\Models\Visita;
use App\Models\Restaurante;
use App\Helpers\SubdominioHelper;
use App\Mail\enviarEmail;

class UserController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function validarLogin(Request $request) {
        $correo = $request->correo;
        $pass = $request->pass;
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        // Validar según el tipo de subdominio
        if ($tipo === 'restaurante') {
            $query = RestauranteUser::where('email', $correo)->whereNull('deleted_at')->with('restaurante')->first();
            if (!$query) return response()->json(['estado' => 404]);
            if ($query->estado != 1) return response()->json(['estado' => 501]);

            if ($query->restaurante && $query->restaurante->aprobado == 0) {
                return response()->json(['estado' => 503, 'mensaje' => 'Tu restaurante está pendiente de aprobación']);
            }
            if ($query->restaurante && $query->restaurante->estado != 1) {
                return response()->json(['estado' => 501, 'mensaje' => 'El restaurante se encuentra inactivo']);
            }

            if (\Hash::check($pass, $query->password)) {
                \Auth::guard('restaurante')->login($query);
                return response()->json(['estado' => 200, 'url' => "/dashboard"]);
            } else {
                return response()->json(['estado' => 500]);
            }
        } elseif ($tipo === 'shopper') {
            $query = MisteryShopper::where('email', $correo)->whereNull('deleted_at')->first();
            if (!$query) return response()->json(['estado' => 404, 'mensaje' => 'Usuario no encontrado']);
            
            // Si ya respondió y el administrador lo desactivó explícitamente
            if ($query->respondio_encuesta == 1 && $query->estado == 0 && $query->aprobado == 1) {
                return response()->json(['estado' => 501, 'mensaje' => 'Tu cuenta está inactiva. Contacta al administrador.']);
            }
            
            // Si no tiene password, generar uno temporal
            if (!$query->password) {
                $passTemp = \Str::random(8);
                $query->password = \Hash::make($passTemp);
                $query->save();
                
                // Enviar email con password temporal
                $dataEmail = [
                    'correo_electronico' => $correo,
                    'titulo' => 'Credenciales de acceso - Check 360',
                    'vista' => 'mails.newuser',
                    'pass' => $passTemp,
                    'plataforma' => 'https://shopper.check360.cl'
                ];
                try {
                    \Mail::to($correo)->send(new enviarEmail($dataEmail));
                } catch (\Throwable $e) {
                    \Log::error("Error enviando email en login (pass temp): " . $e->getMessage() . " - Line: " . $e->getLine());
                }
                
                return response()->json(['estado' => 502, 'mensaje' => 'Se ha enviado un email con sus credenciales de acceso']);
            }
            
            if (\Hash::check($pass, $query->password)) {
                \Auth::guard('shopper')->login($query);
                return response()->json(['estado' => 200, 'url' => "/dashboard"]);
            } else {
                return response()->json(['estado' => 500, 'mensaje' => 'Contraseña incorrecta']);
            }
        } else {
            // Sistema (default)
            $query = User::where('email', $correo)->whereNull('deleted_at')->first();
            if (!$query) return response()->json(['estado' => 404]);

            if ($query->estado != 1) return response()->json(['estado' => 501]);
            
            // Verificar perfil (solo perfil_id 1 y 2 pueden acceder)
            if (!in_array($query->perfil_id, [1, 2])) {
                return response()->json(['estado' => 502, 'mensaje' => 'Usuario no autorizado']);
            }

            if (\Hash::check($pass, $query->password)) {
                \Auth::guard('web')->attempt(['email' => $correo, 'password' => $pass]);
                return response()->json(['estado' => 200, 'url' => "/dashboard"]);
            } else {
                return response()->json(['estado' => 500]);
            }
        }
    }

    public function olvidaste() {
        return view('auth.olvidaste');
    }

    public function recuperar(Request $request) {
        $correo = $request->correo ?? $request->email;
        if (!$correo) {
            return response()->json(['estado' => 400, 'mensaje' => 'El correo electrónico es requerido']);
        }

        $tipo = SubdominioHelper::obtenerTipo();

        if ($tipo === 'restaurante') {
            $queryUser = RestauranteUser::where('email', $correo)->whereNull('deleted_at')->first();
            if (!$queryUser) {
                return response()->json(['estado' => 404, 'mensaje' => 'El correo electrónico no se encuentra registrado']);
            }
            if ($queryUser->estado != 1) {
                return response()->json(['estado' => 501, 'mensaje' => 'El usuario se encuentra inactivo']);
            }
        } elseif ($tipo === 'shopper') {
            $queryUser = MisteryShopper::where('email', $correo)->whereNull('deleted_at')->first();
            if (!$queryUser) {
                return response()->json(['estado' => 404, 'mensaje' => 'El correo electrónico no se encuentra registrado']);
            }
            if ($queryUser->respondio_encuesta == 1 && $queryUser->estado == 0 && $queryUser->aprobado == 1) {
                return response()->json(['estado' => 501, 'mensaje' => 'Tu cuenta está inactiva. Contacta al administrador.']);
            }
        } else {
            // Sistema (default)
            $queryUser = User::where('email', $correo)->whereNull('deleted_at')->first();
            if (!$queryUser) {
                return response()->json(['estado' => 404, 'mensaje' => 'El correo electrónico no se encuentra registrado']);
            }
            if ($queryUser->estado != 1) {
                return response()->json(['estado' => 501, 'mensaje' => 'El usuario se encuentra inactivo']);
            }
            if (!in_array($queryUser->perfil_id, [1, 2])) {
                return response()->json(['estado' => 502, 'mensaje' => 'Usuario no autorizado']);
            }
        }

        $pass = \Str::random(5);

        $queryUser->password = \Hash::make($pass);
        $queryUser->save();

        $dataEmail = [
            'correo_electronico' => $correo,
            'titulo' => 'Recuperación de contraseña - Check 360',
            'vista' => 'mails.olvidaste',
            'nombre' => $queryUser->name ?? '',
            'plataforma' => request()->getSchemeAndHttpHost(),
            'pass' => $pass
        ];
        
        try {
            \Mail::to($correo)->send(new enviarEmail($dataEmail));
        } catch (\Throwable $e) {
            \Log::error("Error enviando email recuperación contraseña: " . $e->getMessage() . " - Line: " . $e->getLine());
            return response()->json(['estado' => 500, 'mensaje' => 'No se pudo enviar el correo electrónico. Intente más tarde.']);
        }

        return response()->json(['estado' => 200, 'mensaje' => 'Se ha enviado una nueva contraseña a su correo electrónico.']);
    }

    public function dashboard() {
        \App\Models\Visita::expirarVisitasVencidas();
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();
        
        // Dashboard según el tipo de subdominio
        if ($tipo === 'restaurante') {
            $user = \Auth::guard($guard)->user();
            $restaurante = $user->restaurante;
            
            // Definir dimensiones según lo solicitado
            $dimensiones_map = [
                1 => 'Calidad de las comidas',
                3 => 'Atención del personal',
                4 => 'Ambiente del restaurante',
                7 => 'Factores adicionales',
                6 => 'Precio y relación precio-calidad',
                5 => 'Tiempo de espera y servicio'
            ];

            // Obtener promedios por dimensión y tipo de encuesta
            $statsRaw = \DB::table('respuestas_visitas')
                ->join('preguntas_encuestas', 'respuestas_visitas.pregunta_id', '=', 'preguntas_encuestas.id')
                ->join('visitas', 'respuestas_visitas.visita_id', '=', 'visitas.id')
                ->where('visitas.restaurante_id', $restaurante->id)
                ->whereIn('visitas.estado_id', [3, 4]) // Completadas o Finalizadas
                ->whereIn('preguntas_encuestas.dimension', array_keys($dimensiones_map))
                ->where('preguntas_encuestas.tipo_respuesta', 'escala_1_5') // Asegurar que solo sumamos escalas numéricas
                ->select(
                    'preguntas_encuestas.dimension',
                    'respuestas_visitas.encuesta_tipo',
                    \DB::raw('AVG(respuestas_visitas.respuesta_valor) as promedio')
                )
                ->groupBy('preguntas_encuestas.dimension', 'respuestas_visitas.encuesta_tipo')
                ->get();

            $estadisticas = [];
            foreach ($dimensiones_map as $dimId => $label) {
                $expectativa = $statsRaw->where('dimension', $dimId)->where('encuesta_tipo', 'entrada')->first();
                $experiencia = $statsRaw->where('dimension', $dimId)->where('encuesta_tipo', 'salida')->first();

                $estadisticas[] = [
                    'id' => $dimId,
                    'label' => $label,
                    'expectativa' => $expectativa ? round($expectativa->promedio, 1) : 0,
                    'experiencia' => $experiencia ? round($experiencia->promedio, 1) : 0,
                ];
            }

            // Asegurar que el periodo esté inicializado y verificar si debe reiniciarse
            if ($restaurante) {
                $restaurante->checkAndResetPeriod();
            }

            // Calcular visitas del periodo actual (completadas/finalizadas)
            $visitasPeriodo = \App\Models\Visita::where('restaurante_id', $restaurante->id)
                ->where('estado_id', 4) // Finalizada
                ->where('fecha_asignacion', '>=', $restaurante->periodo_inicio)
                ->where('fecha_asignacion', '<=', $restaurante->periodo_fin)
                ->whereNull('deleted_at')
                ->count();

            // Calcular días restantes del periodo de 60 días
            $diasRestantes = 0;
            if ($restaurante->periodo_fin) {
                $fin = \Carbon\Carbon::parse($restaurante->periodo_fin);
                $diasRestantes = max(0, (int)\Carbon\Carbon::now()->diffInDays($fin, false));
            }

            // Obtener todas las visitas para la lista de trazabilidad
            $todasVisitas = \App\Models\Visita::where('restaurante_id', $restaurante->id)
                ->whereNull('deleted_at')
                ->with(['shopper', 'estado'])
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function ($visita) {
                    // Anonimizar shopper si el cupón NO ha sido canjeado todavía
                    if (!$visita->cupon_canjeado_at) {
                        $visita->shopper_nombre_visible = 'Mistery Shopper Anónimo';
                    } else {
                        $visita->shopper_nombre_visible = $visita->shopper ? $visita->shopper->name : 'Mistery Shopper';
                    }
                    return $visita;
                });

            // Totales generales de encuesta
            $total_expectativa = count($estadisticas) > 0 ? round(collect($estadisticas)->avg('expectativa'), 2) : 0;
            $total_experiencia = count($estadisticas) > 0 ? round(collect($estadisticas)->avg('experiencia'), 2) : 0;

            return view('dashboard')->with([
                'tipo' => 'restaurante',
                'restaurante' => $restaurante,
                'user' => $user,
                'estadisticas' => $estadisticas,
                'total_expectativa' => $total_expectativa,
                'total_experiencia' => $total_experiencia,
                'visitasPeriodo' => $visitasPeriodo,
                'diasRestantes' => $diasRestantes,
                'todasVisitas' => $todasVisitas,
                'periodo' => 'Periodo Activo: ' . ($restaurante->periodo_inicio ? \Carbon\Carbon::parse($restaurante->periodo_inicio)->format('d/m/Y') : 'N/A') . ' al ' . ($restaurante->periodo_fin ? \Carbon\Carbon::parse($restaurante->periodo_fin)->format('d/m/Y') : 'N/A'),
            ]);
        } elseif ($tipo === 'shopper') {
            $shopper = \Auth::guard($guard)->user();

            // Lógica de redirección según el estado del perfil
            // Si no respondió encuesta, debe completarla primero (incluso si no está aprobado)
            if ($shopper->respondio_encuesta == 0) {
                return redirect()->route('shopper.completar_perfil');
            }

            // Solo redirigir a espera de aprobación si ya respondió la encuesta y no está aprobado
            if ($shopper->aprobado == 0 && $shopper->respondio_encuesta == 1) {
                return redirect()->route('shopper.espera_aprobacion');
            }
            
            // Obtener estadísticas de visitas del shopper
            $visitas_totales = Visita::where('mistery_shopper_id', $shopper->id)
                ->whereNull('deleted_at')
                ->count();
            
            $visitas_pendientes = Visita::where('mistery_shopper_id', $shopper->id)
                ->where('estado_id', 1)
                ->whereNull('deleted_at')
                ->count();
            
            $visitas_en_proceso = Visita::where('mistery_shopper_id', $shopper->id)
                ->where('estado_id', 2)
                ->whereNull('deleted_at')
                ->count();
            
            $visitas_completadas = Visita::where('mistery_shopper_id', $shopper->id)
                ->whereIn('estado_id', [3, 4]) // Completadas y Finalizadas
                ->whereNull('deleted_at')
                ->count();
            
            $visitas_recientes = Visita::where('mistery_shopper_id', $shopper->id)
                ->whereNull('deleted_at')
                ->with(['restaurante', 'estado', 'respuestas'])
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();

            // Gráfico: Visitas por estado
            $stats_estados = \DB::table('visitas')
                ->select('estado_id', \DB::raw('count(*) as total'))
                ->where('mistery_shopper_id', $shopper->id)
                ->whereNull('deleted_at')
                ->groupBy('estado_id')
                ->get();

            // Gráfico: Visitas por mes (últimos 6 meses)
            $stats_mensual = \DB::table('visitas')
                ->select(\DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'), \DB::raw('count(*) as total'))
                ->where('mistery_shopper_id', $shopper->id)
                ->whereNull('deleted_at')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();

            // Obtener restaurantes disponibles basados en las reglas de exclusión por periodo de 60 días
            $restaurantesQuery = Restaurante::whereNull('deleted_at')
                ->where('plan_activo', 1)
                ->with(['tipoCocina', 'ciudad.region'])
                ->get();

            $restaurantes_disponibles = [];
            foreach ($restaurantesQuery as $rest) {
                // Forzar inicialización de periodo si no existe
                if (!$rest->periodo_inicio || !$rest->periodo_fin) {
                    $rest->checkAndResetPeriod();
                }

                // Excluir si ya visitó el local durante el periodo de evaluación actual del restaurante
                $visitadoEnPeriodo = Visita::where('mistery_shopper_id', $shopper->id)
                    ->where('restaurante_id', $rest->id)
                    ->whereIn('estado_id', [1, 2, 3, 4]) // pendiente, espera, completada, finalizada
                    ->where('fecha_asignacion', '>=', $rest->periodo_inicio)
                    ->where('fecha_asignacion', '<=', $rest->periodo_fin)
                    ->whereNull('deleted_at')
                    ->exists();

                if (!$visitadoEnPeriodo) {
                    $restaurantes_disponibles[] = $rest;
                }
            }

            $tipos_cocina = \App\Models\TiposCocina::whereNull('deleted_at')->orderBy('name')->get();
            $regiones = \App\Models\Region::orderBy('nombre')->get();
            $ciudades = \App\Models\Ciudad::orderBy('nombre')->get();

            return view('dashboard')->with([
                'tipo' => 'shopper',
                'shopper' => $shopper,
                'visitas_totales' => $visitas_totales,
                'visitas_pendientes' => $visitas_pendientes,
                'visitas_en_proceso' => $visitas_en_proceso,
                'visitas_completadas' => $visitas_completadas,
                'visitas_recientes' => $visitas_recientes,
                'stats_estados' => $stats_estados,
                'stats_mensual' => $stats_mensual,
                'restaurantes_disponibles' => $restaurantes_disponibles,
                'tipos_cocina' => $tipos_cocina,
                'regiones' => $regiones,
                'ciudades' => $ciudades,
            ]);
        } else {
            // Sistema
            $usuarios_activos = User::where('estado', 1)->whereNull('deleted_at')->count();
            $usuarios_total = User::whereNull('deleted_at')->count();
            
            // Estadísticas de visitas
            $visitas_pendientes = Visita::where('estado_id', 1)->whereNull('deleted_at')->count();
            $visitas_en_espera = Visita::where('estado_id', 2)->whereNull('deleted_at')->count();
            $visitas_completadas = Visita::where('estado_id', 3)->whereNull('deleted_at')->count();
            $visitas_finalizadas = Visita::where('estado_id', 4)->whereNull('deleted_at')->count();
            $visitas_no_realizadas = Visita::where('estado_id', 5)->whereNull('deleted_at')->count();
            $visitas_rechazadas = Visita::where('estado_id', 6)->whereNull('deleted_at')->count();
            $visitas_totales = Visita::whereNull('deleted_at')->count();
            
            // Estadísticas de restaurantes
            $restaurantes_activos = Restaurante::whereNull('deleted_at')->count();
            
            // Estadísticas de Mistery Shoppers
            $shoppers_activos = MisteryShopper::where('estado', 1)->whereNull('deleted_at')->count();
            $shoppers_totales = MisteryShopper::whereNull('deleted_at')->count();
            
            // Visitas recientes (últimas 10)
            $visitas_recientes = Visita::whereNull('deleted_at')
                ->with(['shopper', 'restaurante', 'estado'])
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get();
            
            // Visitas por mes (últimos 6 meses)
            $visitas_por_mes = Visita::whereNull('deleted_at')
                ->where('created_at', '>=', now()->subMonths(6))
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as total')
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();
            
            // Visitas finalizadas este mes (estado 4)
            $visitas_completadas_mes = Visita::where('estado_id', 4)
                ->whereNull('deleted_at')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // Tasa de completitud (finalizadas / totales)
            $tasa_completitud = $visitas_totales > 0 
                ? round(($visitas_finalizadas / $visitas_totales) * 100, 2) 
                : 0;

            // Datos para el gráfico de Percepción Nacional (Expectativa vs Experiencia Agregado)
            $dimensiones_map = [
                1 => 'Calidad de las comidas',
                3 => 'Atención del personal',
                4 => 'Ambiente del restaurante',
                7 => 'Factores adicionales',
                6 => 'Precio y relación precio-calidad',
                5 => 'Tiempo de espera y servicio'
            ];

            $statsNacionalRaw = \DB::table('respuestas_visitas')
                ->join('preguntas_encuestas', 'respuestas_visitas.pregunta_id', '=', 'preguntas_encuestas.id')
                ->join('visitas', 'respuestas_visitas.visita_id', '=', 'visitas.id')
                ->whereIn('visitas.estado_id', [3, 4]) 
                ->whereIn('preguntas_encuestas.dimension', array_keys($dimensiones_map))
                ->where('preguntas_encuestas.tipo_respuesta', 'escala_1_5')
                ->select(
                    'preguntas_encuestas.dimension',
                    'respuestas_visitas.encuesta_tipo',
                    \DB::raw('AVG(respuestas_visitas.respuesta_valor) as promedio')
                )
                ->groupBy('preguntas_encuestas.dimension', 'respuestas_visitas.encuesta_tipo')
                ->get();

            $stats_nacional = [];
            foreach ($dimensiones_map as $dimId => $label) {
                $expectativa = $statsNacionalRaw->where('dimension', $dimId)->where('encuesta_tipo', 'entrada')->first();
                $experiencia = $statsNacionalRaw->where('dimension', $dimId)->where('encuesta_tipo', 'salida')->first();

                $stats_nacional[] = [
                    'label' => $label,
                    'expectativa' => $expectativa ? round($expectativa->promedio, 1) : 0,
                    'experiencia' => $experiencia ? round($experiencia->promedio, 1) : 0,
                ];
            }

            return view('dashboard')->with([
                'tipo' => 'sistema',
                'usuarios_activos' => $usuarios_activos,
                'usuarios_total' => $usuarios_total,
                'visitas_pendientes' => $visitas_pendientes,
                'visitas_en_espera' => $visitas_en_espera,
                'visitas_completadas' => $visitas_completadas,
                'visitas_finalizadas' => $visitas_finalizadas,
                'visitas_no_realizadas' => $visitas_no_realizadas,
                'visitas_rechazadas' => $visitas_rechazadas,
                'visitas_totales' => $visitas_totales,
                'restaurantes_activos' => $restaurantes_activos,
                'shoppers_activos' => $shoppers_activos,
                'shoppers_totales' => $shoppers_totales,
                'visitas_recientes' => $visitas_recientes,
                'visitas_por_mes' => $visitas_por_mes,
                'visitas_completadas_mes' => $visitas_completadas_mes,
                'tasa_completitud' => $tasa_completitud,
                'stats_nacional' => $stats_nacional
            ]);
        }
    }


    public function dashboard_update(Request $request) {
        return response()->json(['estado'=>200]);
    }

    public function refreshCatalog(Request $request) {
        $guard = SubdominioHelper::obtenerGuard();
        $shopper = \Auth::guard($guard)->user();
        
        if (!$shopper) {
            return response()->json(['estado' => 403, 'mensaje' => 'No autorizado']);
        }

        $restaurantesQuery = Restaurante::whereNull('deleted_at')
            ->where('plan_activo', 1)
            ->with(['tipoCocina', 'ciudad.region'])
            ->get();

        $restaurantes_disponibles = [];
        foreach ($restaurantesQuery as $rest) {
            if (!$rest->periodo_inicio || !$rest->periodo_fin) {
                $rest->checkAndResetPeriod();
            }

            $visitadoEnPeriodo = Visita::where('mistery_shopper_id', $shopper->id)
                ->where('restaurante_id', $rest->id)
                ->whereIn('estado_id', [1, 2, 3, 4])
                ->where('fecha_asignacion', '>=', $rest->periodo_inicio)
                ->where('fecha_asignacion', '<=', $rest->periodo_fin)
                ->whereNull('deleted_at')
                ->exists();

            if (!$visitadoEnPeriodo) {
                $restaurantes_disponibles[] = $rest;
            }
        }

        $html = view('partials.shopper_catalog', compact('restaurantes_disponibles'))->render();
        
        return response()->json([
            'estado' => 200,
            'html' => $html
        ]);
    }

    public function index()
    {
        return view('usuarios.lista');
    }

    public function getData(Request $request) {
        $filtros = $request->filtros;
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        if ($tipo === 'restaurante') {
            $user = \Auth::guard($guard)->user();
            $query = RestauranteUser::where('restaurante_id', $user->restaurante_id);
        } else {
            $query = User::with(['perfil']);
        }

        $lista = $query->orderBy('id','DESC')
        ->when($filtros['nombre'], function ($q) use ($filtros) {
            return $q->where('name','LIKE',"%".$filtros['nombre']."%");
        })
        ->when($filtros['email'], function ($q) use ($filtros) {
            return $q->where('email','LIKE',"%".$filtros['email']."%");
        })
        ->when(isset($filtros['estado']) && $filtros['estado'] !== '', function ($q) use ($filtros) {
            return $q->where('estado',$filtros['estado']);
        })
        ->whereNull('deleted_at')
        ->paginate(10);
        
        return $lista;
    }

    public function create()
    {
        return view('usuarios.nuevo')->with(['usuario'=>null]);
    }

    public function store(Request $request) {
        $nombre = $request->nombre;
        $correo_electronico = $request->correo_electronico;
        $rut = $request->rut;
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        if ($tipo === 'restaurante') {
            $userAuth = \Auth::guard($guard)->user();
            $model = RestauranteUser::class;
            $checkEmail = RestauranteUser::where('email', $correo_electronico)->whereNull('deleted_at')->first();
        } else {
            $model = User::class;
            $checkEmail = User::where('email', $correo_electronico)->whereNull('deleted_at')->first();
        }

        if($checkEmail) return response()->json(['estado' => 500,'mensaje'=>"El correo electronico ya existe en el sistema"]);

        if($tipo !== 'restaurante' && $rut && $rut != "") {
            $queryRut = User::where('rut', $rut)->whereNull('deleted_at')->first();
            if($queryRut) return response()->json(['estado' => 500,'mensaje'=>"El rut ya existe en el sistema"]);
        }
        
        $pass = \Str::random(6);

        if ($tipo === 'restaurante') {
            $new = new RestauranteUser;
            $new->restaurante_id = $userAuth->restaurante_id;
        } else {
            $new = new User;
            $new->rut = $rut;
            $new->perfil_id = 1;
        }
        
        $new->name = $nombre;
        $new->email = $correo_electronico;
        $new->estado = 1;
        $new->password = \Hash::make($pass);
        $new->save();

        $dataEmail = [
            'correo_electronico'=>$correo_electronico,
            'titulo'=>'Bienvenido a Sistema CHECK 360',
            'vista'=>'mails.newuser',
            'pass'=>$pass,
            'plataforma' => $tipo === 'restaurante' ? 'https://restaurante.check360.cl' : 'https://sistema.check360.cl'
        ];
        try {
            \Mail::to($correo_electronico)->send(new enviarEmail($dataEmail));
        } catch (\Throwable $e) {
            \Log::error("Error enviando email al crear usuario: " . $e->getMessage() . " - Line: " . $e->getLine());
        }

        return response()->json(['estado' => 200]);
    }

    public function edit($id) {
        $id = decrypt($id);
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        if ($tipo === 'restaurante') {
            $userAuth = \Auth::guard($guard)->user();
            $usuario = RestauranteUser::where('id',$id)->where('restaurante_id', $userAuth->restaurante_id)->first();
            if (!$usuario) return redirect()->route('usuarios.lista');
        } else {
            $usuario = User::where('id',$id)->with('perfil')->first();
        }

        return view('usuarios.nuevo')->with(['usuario'=>$usuario]);
    }

    public function update(Request $request) {
        $nombre = $request->nombre;
        $correo_electronico = $request->correo_electronico;
        $rut = $request->rut;
        $id = decrypt($request->id);
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        if ($tipo === 'restaurante') {
            $userAuth = \Auth::guard($guard)->user();
            $update = RestauranteUser::where('id',$id)->where('restaurante_id', $userAuth->restaurante_id)->first();
            if (!$update) return response()->json(['estado' => 404]);

            $checkEmail = RestauranteUser::where('email', $correo_electronico)
                ->where('id','!=',$id)->whereNull('deleted_at')->first();
        } else {
            $update = User::find($id);
            if (!$update) return response()->json(['estado' => 404]);

            $checkEmail = User::where('email', $correo_electronico)
                ->where('id','!=',$id)->whereNull('deleted_at')->first();

            if($rut && $rut != "") {
                $queryRut = User::where('rut', $rut)
                ->where('id','!=',$id)->whereNull('deleted_at')->first();
                if($queryRut) return response()->json(['estado' => 500,'mensaje'=>"El rut ya existe en el sistema"]);
            }
        }

        if($checkEmail) return response()->json(['estado' => 500,'mensaje'=>"El correo electronico ya existe en el sistema"]);

        $update->name = $nombre;
        $update->email = $correo_electronico;
        if ($tipo !== 'restaurante') {
            $update->rut = $rut;
        }
        $update->save();

        return response()->json(['estado' => 200]);
    }

    public function cambiar_password(Request $request) {
        $contra = $request->contra;
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        $user = \Auth::guard($guard)->user();
        $user->password = \Hash::make($contra);
        $user->save();

        return response()->json(['estado'=>200]);
    }

    public function activar(Request $request) {
        $id = decrypt($request->id);
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        if ($tipo === 'restaurante') {
            $userAuth = \Auth::guard($guard)->user();
            $update = RestauranteUser::where('id',$id)->where('restaurante_id', $userAuth->restaurante_id)->first();
        } else {
            $update = User::find($id);
        }

        if (!$update) return response()->json(['estado' => 404]);

        if($update->estado == 1) $update->estado = 0;
        else $update->estado = 1;
        $update->save();

        return response()->json(['estado'=>200, 'mensaje'=>($update->estado == 1 ? 'Usuario activado' : 'Usuario desactivado')]);
    }

    public function eliminar(Request $request) {
        $id = decrypt($request->id);
        $tipo = SubdominioHelper::obtenerTipo();
        $guard = SubdominioHelper::obtenerGuard();

        if ($tipo === 'restaurante') {
            $userAuth = \Auth::guard($guard)->user();
            $update = RestauranteUser::where('id',$id)->where('restaurante_id', $userAuth->restaurante_id)->first();
        } else {
            $update = User::find($id);
        }

        if (!$update) return response()->json(['estado' => 404]);

        $update->deleted_at = date('Y-m-d H:i:s');
        $update->save();

        return response()->json(['estado'=>200]);
    }
}
