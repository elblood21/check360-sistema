<?php

namespace App\Http\Controllers;

use App\Models\MisteryShopper;
use App\Models\Restaurante;
use App\Models\RestauranteOpciones;
use App\Helpers\SubdominioHelper;
use App\Mail\enviarEmail;
use Illuminate\Http\Request;

class MisteryShopperController extends Controller
{
    public function index()
    {
        if (SubdominioHelper::esTipo('restaurante')) {
            return redirect()->route('dashboard');
        }

        // Estadísticas globales para las cards superiores
        $stats = [
            'totales' => MisteryShopper::whereNull('deleted_at')->count(),
            'pendientes_aprobacion' => MisteryShopper::whereNull('deleted_at')->where('aprobado', 0)->count(),
            'visitas_pendientes' => \App\Models\Visita::whereIn('estado_id', [1, 2])->count(),
            'visitas_completadas' => \App\Models\Visita::where('estado_id', 4)->count(),
        ];

        // Calcular promedio de tiempo global (opcional aquí o en getData)
        // Por ahora lo dejaremos para mostrarlo en las cards si es necesario

        return view('shoppers.lista', compact('stats'));
    }

    public function getData(Request $request)
    {
        $filtros = $request->filtros ?? [];

        $lista = MisteryShopper::orderBy('id', 'DESC')
            ->when(!empty($filtros['nombre']), function ($q) use ($filtros) {
                return $q->where('name', 'LIKE', '%' . $filtros['nombre'] . '%');
            })
            ->when(!empty($filtros['email']), function ($q) use ($filtros) {
                return $q->where('email', 'LIKE', '%' . $filtros['email'] . '%');
            })
            ->withCount([
                'visitas as visitas_pendientes' => function($q) {
                    $q->whereIn('estado_id', [1, 2]);
                },
                'visitas as visitas_completadas' => function($q) {
                    $q->where('estado_id', 4);
                }
            ])
            ->whereNull('deleted_at')
            ->paginate(10);

        // Calcular tiempo promedio por shopper en el resultado actual
        foreach ($lista as $shopper) {
            $shopper->promedio_tiempo = $this->calcularPromedioTiempoShopper($shopper->id);
            $shopper->promedio_tiempo_human = $this->formatearTiempoHumano($shopper->promedio_tiempo);
        }

        return $lista;
    }

    private function calcularPromedioTiempoShopper($shopperId)
    {
        $visitasIds = \App\Models\Visita::where('mistery_shopper_id', $shopperId)
            ->where('estado_id', 4)
            ->pluck('id');

        if ($visitasIds->isEmpty()) return 0;

        $tiempos = [];
        foreach ($visitasIds as $visitaId) {
            $entrada = \App\Models\RespuestaVisita::where('visita_id', $visitaId)
                ->where('encuesta_tipo', 'entrada')
                ->orderBy('created_at', 'ASC')
                ->first();

            $salida = \App\Models\RespuestaVisita::where('visita_id', $visitaId)
                ->where('encuesta_tipo', 'salida')
                ->orderBy('created_at', 'DESC')
                ->first();

            if ($entrada && $salida) {
                $inicio = \Carbon\Carbon::parse($entrada->created_at);
                $fin = \Carbon\Carbon::parse($salida->created_at);
                $tiempos[] = $fin->diffInSeconds($inicio);
            }
        }

        if (count($tiempos) === 0) return 0;

        return array_sum($tiempos) / count($tiempos);
    }

    private function formatearTiempoHumano($segundos)
    {
        if ($segundos <= 0) return 'N/A';

        $dt = \Carbon\CarbonInterval::seconds($segundos)->cascade();
        
        $partes = [];
        if ($dt->days > 0) $partes[] = $dt->days . 'd';
        if ($dt->hours > 0) $partes[] = $dt->hours . 'h';
        if ($dt->minutes > 0) $partes[] = $dt->minutes . 'm';
        if ($dt->seconds > 0 && count($partes) < 2) $partes[] = $dt->seconds . 's';

        return implode(' ', $partes);
    }

    public function create()
    {
        return view('shoppers.nuevo')->with([
            'shopper' => null,
        ]);
    }

    public function store(Request $request)
    {
        $nombre = $request->nombre;

        if (!$nombre || trim($nombre) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar el campo nombre']);
        }

        // Verificar si el email ya existe
        if ($request->email) {
            $existeEmail = MisteryShopper::where('email', $request->email)
                ->whereNull('deleted_at')
                ->first();
            if ($existeEmail) {
                return response()->json(['estado' => 500, 'mensaje' => 'El correo electrónico ya está registrado']);
            }
        }

        $tipo = SubdominioHelper::obtenerTipo();

        $new = new MisteryShopper();
        $new->name = $nombre;
        $new->email = $request->email;
        $new->telefono = $request->telefono;
        $new->observaciones = $request->observaciones;
        $new->estado = 1;
        
        // Si se crea desde sistema, auto-aprobar
        if ($tipo === 'sistema') {
            $new->aprobado = 1;
            $new->aprobado_por = \Auth::id();
            $new->aprobado_at = now();
            
            // Generar contraseña temporal de 6 caracteres
            $passTemp = \Str::random(6);
            $new->password = \Hash::make($passTemp);
            
            // Enviar email con credenciales
            if ($request->email) {
                $dataEmail = [
                    'correo_electronico' => $request->email,
                    'titulo' => 'Bienvenido a Check 360 - Mistery Shopper',
                    'vista' => 'mails.shopper_creado_sistema',
                    'nombre' => $nombre,
                    'pass' => $passTemp,
                    'plataforma' => 'https://shopper.check360.cl'
                ];
                try {
                    \Mail::to($request->email)->send(new enviarEmail($dataEmail));
                } catch (\Throwable $e) {
                    \Log::error("Error enviando email al crear shopper: " . $e->getMessage() . " - Line: " . $e->getLine());
                }
            }
        } else {
            // Si se registra públicamente, queda pendiente de aprobación
            $new->aprobado = 0;
        }
        
        $new->save();

        return response()->json(['estado' => 200]);
    }

    public function edit($id)
    {
        $id = decrypt($id);

        $shopper = MisteryShopper::where('id', $id)->whereNull('deleted_at')->first();
        if (!$shopper) {
            return redirect()->route('shoppers.lista');
        }

        return view('shoppers.nuevo')->with([
            'shopper' => $shopper,
        ]);
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        $update = MisteryShopper::find($id);
        if (!$update || $update->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Mistery Shopper no encontrado']);
        }

        $nombre = $request->nombre;
        if (!$nombre || trim($nombre) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar el campo nombre']);
        }

        $update->name = $nombre;
        $update->email = $request->email;
        $update->telefono = $request->telefono;
        $update->observaciones = $request->observaciones;
        // No actualizar estado desde aquí, se hace desde la lista
        $update->save();

        return response()->json(['estado' => 200]);
    }

    public function eliminar(Request $request)
    {
        $id = decrypt($request->id);

        $update = MisteryShopper::find($id);
        if (!$update || $update->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Mistery Shopper no encontrado']);
        }

        $update->deleted_at = date('Y-m-d H:i:s');
        $update->save();

        return response()->json(['estado' => 200]);
    }

    public function show($id)
    {
        $id = decrypt($id);

        $shopper = MisteryShopper::where('id', $id)->whereNull('deleted_at')->first();
        if (!$shopper) {
            return redirect()->route('shoppers.lista')->with('error', 'Mistery Shopper no encontrado');
        }

        // Obtener encuesta de perfil
        $encuesta = \App\Models\Encuesta::where('tipo', 'perfil_shopper')->first();
        $preguntas = [];
        $respuestas = [];
        
        if ($encuesta) {
            $preguntas = \App\Models\PreguntaEncuesta::where('encuesta_id', $encuesta->id)
                ->orderBy('orden', 'ASC')
                ->get();
            
            // Obtener respuestas del shopper
            $respuestasData = \App\Models\RespuestaPerfilShopper::where('mistery_shopper_id', $shopper->id)
                ->with('pregunta')
                ->get()
                ->keyBy('pregunta_id');
            
            // Crear array de respuestas con pregunta
            foreach ($preguntas as $pregunta) {
                $respuesta = $respuestasData->get($pregunta->id);
                if ($respuesta) {
                    $respuestas[$pregunta->id] = [
                        'pregunta' => $pregunta,
                        'respuesta_texto' => $respuesta->respuesta_texto,
                        'respuesta_valor' => $respuesta->respuesta_valor,
                    ];
                }
            }
        }

        return view('shoppers.ver')->with([
            'shopper' => $shopper,
            'preguntas' => $preguntas,
            'respuestas' => $respuestas,
        ]);
    }

    public function activar(Request $request)
    {
        $id = decrypt($request->id);

        $shopper = MisteryShopper::find($id);
        if (!$shopper || $shopper->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Mistery Shopper no encontrado']);
        }

        $shopper->estado = $request->estado == 1 ? 1 : 0;
        $shopper->save();

        return response()->json(['estado' => 200, 'mensaje' => 'Estado actualizado correctamente']);
    }

    /**
     * Mostrar formulario de registro público (para shoppers)
     */
    public function registroPublico()
    {
        // Verificar que estamos en subdominio shopper
        if (!SubdominioHelper::esTipo('shopper')) {
            return redirect()->route('loginX');
        }

        return view('auth.shopper_register');
    }

    /**
     * Procesar registro público de shopper
     */
    public function registroPublicoPost(Request $request)
    {
        // Validaciones
        $nombre = $request->nombre;
        $email = $request->email;

        if (!$nombre || trim($nombre) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar el campo nombre']);
        }

        if (!$email || trim($email) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar el campo correo electrónico']);
        }

        // Verificar si el email ya existe
        $existeEmail = MisteryShopper::where('email', $email)
            ->whereNull('deleted_at')
            ->first();
        if ($existeEmail) {
            return response()->json(['estado' => 500, 'mensaje' => 'El correo electrónico ya está registrado']);
        }

        // Validar y formatear teléfono
        $telefono = $request->telefono;
        if ($telefono) {
            // Remover espacios y caracteres no numéricos excepto +
            $telefono = preg_replace('/[^0-9+]/', '', $telefono);
            
            // Si no tiene +56, agregarlo
            if (!str_starts_with($telefono, '+56')) {
                // Si empieza con 56, agregar +
                if (str_starts_with($telefono, '56')) {
                    $telefono = '+' . $telefono;
                } else {
                    // Si solo tiene números, agregar +56
                    $telefono = '+56' . $telefono;
                }
            }
            
            // Validar que después de +56 tenga exactamente 9 dígitos
            $numeroSinPrefijo = substr($telefono, 3); // Remover +56
            if (strlen($numeroSinPrefijo) !== 9 || !ctype_digit($numeroSinPrefijo)) {
                return response()->json(['estado' => 500, 'mensaje' => 'El teléfono debe tener exactamente 9 dígitos después del prefijo +56']);
            }
        }

        // Generar contraseña temporal de 8 caracteres
        $passTemp = \Str::random(8);

        // Crear shopper en estado pendiente de aprobación y sin haber respondido encuesta
        $new = new MisteryShopper();
        $new->name = $nombre;
        $new->email = $email;
        $new->telefono = $telefono;
        $new->observaciones = $request->observaciones;
        $new->password = \Hash::make($passTemp);
        $new->estado = 1; // Dejamos estado 1 para que pueda completar perfil, aprobado = 0 es el filtro real
        $new->aprobado = 0; // Pendiente de aprobación
        $new->respondio_encuesta = 0;
        $new->save();

        // Enviar notificación al usuario
        $dataEmail = [
            'correo_electronico' => $email,
            'titulo' => 'Solicitud recibida - Check 360',
            'vista' => 'mails.shopper_registro',
            'nombre' => $nombre,
            'password' => $passTemp,
        ];
        try {
            \Mail::to($email)->send(new enviarEmail($dataEmail));
        } catch (\Throwable $e) {
            \Log::error("Error enviando email de registro shopper: " . $e->getMessage() . " - Line: " . $e->getLine());
        }

        // Iniciar sesión automáticamente para que complete el perfil
        \Auth::guard('shopper')->login($new);

        return response()->json(['estado' => 200, 'url' => route('shopper.completar_perfil')]);
    }

    /**
     * Vista para completar el perfil (encuesta inicial)
     */
    public function completarPerfil()
    {
        $shopper = \Auth::guard('shopper')->user();
        if ($shopper->respondio_encuesta == 1) {
            return redirect()->route('dashboard');
        }

        // Buscar la encuesta de perfil
        $encuesta = \App\Models\Encuesta::where('tipo', 'perfil_shopper')->first();
        $preguntas = [];
        if ($encuesta) {
            $preguntas = \App\Models\PreguntaEncuesta::where('encuesta_id', $encuesta->id)
                ->orderBy('orden', 'ASC')
                ->get();
        }

        return view('auth.shopper_perfil_encuesta')->with([
            'preguntas' => $preguntas
        ]);
    }

    /**
     * Guardar las respuestas del perfil (método antiguo - mantener por compatibilidad)
     */
    public function guardarPerfil(Request $request)
    {
        $shopper = \Auth::guard('shopper')->user();
        
        // Almacenar respuestas en JSON incluyendo la pregunta
        $respuestas = $request->respuestas; // Array de {pregunta, pregunta_id, respuesta}
        
        $shopper->respuestas_perfil = json_encode($respuestas);
        $shopper->respondio_encuesta = 1;
        $shopper->save();

        return response()->json([
            'estado' => 200, 
            'url' => route('shopper.espera_aprobacion'),
            'mensaje' => 'Perfil completado correctamente'
        ]);
    }

    /**
     * Guardar una respuesta individual del perfil
     */
    public function guardarRespuestaPerfil(Request $request)
    {
        $shopper = \Auth::guard('shopper')->user();
        
        if (!$shopper) {
            return response()->json(['estado' => 401, 'mensaje' => 'No autenticado']);
        }

        $preguntaId = $request->pregunta_id;
        $respuestaTexto = $request->respuesta_texto;
        $respuestaValor = $request->respuesta_valor;

        // Verificar que la pregunta existe
        $pregunta = \App\Models\PreguntaEncuesta::find($preguntaId);
        if (!$pregunta) {
            return response()->json(['estado' => 404, 'mensaje' => 'Pregunta no encontrada']);
        }

        // Guardar o actualizar respuesta
        \App\Models\RespuestaPerfilShopper::updateOrCreate(
            [
                'mistery_shopper_id' => $shopper->id,
                'pregunta_id' => $preguntaId
            ],
            [
                'respuesta_texto' => $respuestaTexto,
                'respuesta_valor' => $respuestaValor
            ]
        );

        return response()->json([
            'estado' => 200,
            'mensaje' => 'Respuesta guardada correctamente'
        ]);
    }

    /**
     * Finalizar el perfil (marcar encuesta como respondida)
     */
    public function finalizarPerfil(Request $request)
    {
        $shopper = \Auth::guard('shopper')->user();
        
        if (!$shopper) {
            return response()->json(['estado' => 401, 'mensaje' => 'No autenticado']);
        }

        // Verificar que todas las preguntas estén respondidas
        $encuesta = \App\Models\Encuesta::where('tipo', 'perfil_shopper')->first();
        if ($encuesta) {
            $preguntas = \App\Models\PreguntaEncuesta::where('encuesta_id', $encuesta->id)->get();
            $respuestas = \App\Models\RespuestaPerfilShopper::where('mistery_shopper_id', $shopper->id)->pluck('pregunta_id')->toArray();
            
            foreach ($preguntas as $pregunta) {
                if (!in_array($pregunta->id, $respuestas)) {
                    return response()->json([
                        'estado' => 400,
                        'mensaje' => 'Debes responder todas las preguntas antes de finalizar'
                    ]);
                }
            }
        }

        // Marcar encuesta como respondida
        $shopper->respondio_encuesta = 1;
        $shopper->save();

        return response()->json([
            'estado' => 200,
            'url' => route('shopper.espera_aprobacion'),
            'mensaje' => 'Perfil completado correctamente'
        ]);
    }

    /**
     * Vista de espera de aprobación
     */
    public function esperaAprobacion()
    {
        $shopper = \Auth::guard('shopper')->user();
        
        if (!$shopper) {
            return redirect()->route('loginX');
        }
        
        if ($shopper->aprobado == 1) {
            return redirect()->route('dashboard');
        }
        if ($shopper->respondio_encuesta == 0) {
            return redirect()->route('shopper.completar_perfil');
        }

        return view('auth.espera_aprobacion')->with([
            'shopper' => $shopper
        ]);
    }

    /**
     * Aprobar un Mistery Shopper
     */
    public function aprobar(Request $request)
    {
        $id = decrypt($request->id);

        $shopper = MisteryShopper::find($id);
        if (!$shopper || $shopper->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Mistery Shopper no encontrado']);
        }

        // Generar nueva contraseña de 6 caracteres para el shopper aprobado
        $passTemp = \Str::random(6);
        
        // Aprobar shopper y actualizar contraseña
        $shopper->aprobado = 1;
        $shopper->aprobado_por = \Auth::id();
        $shopper->aprobado_at = now();
        $shopper->estado = 1; // Activar también
        $shopper->password = \Hash::make($passTemp); // Actualizar contraseña
        $shopper->save();

        // Enviar email de aprobación con credenciales
        if ($shopper->email) {
            $dataEmail = [
                'correo_electronico' => $shopper->email,
                'titulo' => 'Cuenta aprobada - Check 360',
                'vista' => 'mails.shopper_aprobado',
                'nombre' => $shopper->name,
                'password' => $passTemp,
                'plataforma' => 'https://shopper.check360.cl'
            ];
            try {
                \Mail::to($shopper->email)->send(new enviarEmail($dataEmail));
            } catch (\Throwable $e) {
                \Log::error("Error enviando email de aprobacion shopper: " . $e->getMessage() . " - Line: " . $e->getLine());
            }
        }

        return response()->json(['estado' => 200, 'mensaje' => 'Mistery Shopper aprobado correctamente']);
    }

    /**
     * Rechazar un Mistery Shopper
     */
    public function rechazar(Request $request)
    {
        $id = decrypt($request->id);

        $shopper = MisteryShopper::find($id);
        if (!$shopper || $shopper->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Mistery Shopper no encontrado']);
        }

        // Marcar como eliminado
        $shopper->deleted_at = now();
        $shopper->save();

        // Enviar email de rechazo
        if ($shopper->email && $request->motivo) {
            $dataEmail = [
                'correo_electronico' => $shopper->email,
                'titulo' => 'Registro no aprobado - Check 360',
                'vista' => 'mails.shopper_rechazado',
                'nombre' => $shopper->name,
                'motivo' => $request->motivo
            ];
            try {
                \Mail::to($shopper->email)->send(new enviarEmail($dataEmail));
            } catch (\Throwable $e) {
                \Log::error("Error enviando email de rechazo shopper: " . $e->getMessage() . " - Line: " . $e->getLine());
            }
        }

        return response()->json(['estado' => 200, 'mensaje' => 'Mistery Shopper rechazado']);
    }

    public function restauranteDetalle($id)
    {
        $id = decrypt($id);
        $restaurante = Restaurante::where('id', $id)
            ->whereNull('deleted_at')
            ->with(['tipoCocina', 'ciudad.region'])
            ->first();

        if (!$restaurante) {
            return redirect()->route('dashboard')->with('error', 'Restaurante no encontrado');
        }

        $opciones = RestauranteOpciones::where('restaurante_id', $restaurante->id)->get()
            ->mapWithKeys(function ($o) {
                return [$o->clave => ['valor_json' => $o->valor_json, 'valor_texto' => $o->valor_texto]];
            })->toArray();

        return view('shoppers.restaurante_detalle')->with([
            'restaurante' => $restaurante,
            'opciones' => $opciones
        ]);
    }
}




