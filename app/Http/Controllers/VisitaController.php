<?php

namespace App\Http\Controllers;

use App\Models\Visita;
use App\Models\MisteryShopper;
use App\Models\Restaurante;
use App\Models\EstadoVisita;
use App\Models\Configuracion;
use App\Models\Encuesta;
use App\Models\PreguntaEncuesta;
use App\Models\RespuestaVisita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitaController extends Controller
{
    public function index()
    {
        if (\App\Helpers\SubdominioHelper::esTipo('restaurante')) {
            return redirect()->route('dashboard');
        }
        return view('visitas.lista');
    }

    public function getData(Request $request)
    {
        Visita::expirarVisitasVencidas();
        $filtros = $request->filtros ?? [];
        
        // Si es un shopper, solo mostrar sus propias visitas
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        $shopperId = null;
        if ($tipo === 'shopper') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $shopper = \Auth::guard($guard)->user();
            if ($shopper) {
                $shopperId = $shopper->id;
            }
        }

        $lista = Visita::orderBy('id', 'DESC')
            ->when($shopperId, function ($q) use ($shopperId) {
                // Filtrar automáticamente por el shopper autenticado
                return $q->where('mistery_shopper_id', $shopperId);
            })
            ->when(!empty($filtros['restaurante_id']), function ($q) use ($filtros) {
                return $q->where('restaurante_id', $filtros['restaurante_id']);
            })
            ->when(!empty($filtros['shopper_id']) && !$shopperId, function ($q) use ($filtros) {
                // Solo aplicar filtro de shopper si no es un shopper autenticado
                return $q->where('mistery_shopper_id', $filtros['shopper_id']);
            })
            ->when(!empty($filtros['estado_id']), function ($q) use ($filtros) {
                return $q->where('estado_id', $filtros['estado_id']);
            })
            ->when(!empty($filtros['fecha_desde']), function ($q) use ($filtros) {
                return $q->where('fecha_asignacion', '>=', $filtros['fecha_desde']);
            })
            ->when(!empty($filtros['fecha_hasta']), function ($q) use ($filtros) {
                return $q->where('fecha_asignacion', '<=', $filtros['fecha_hasta']);
            })
            ->whereNull('deleted_at')
            ->with(['shopper', 'restaurante', 'estado'])
            ->withCount([
                'respuestas as pre_encuesta_count' => function($q) {
                    $q->where('encuesta_tipo', 'entrada');
                },
                'respuestas as post_encuesta_count' => function($q) {
                    $q->where('encuesta_tipo', 'salida');
                }
            ])
            ->paginate(10);

        // Agregar fecha de término (cuando se respondió la última encuesta de salida)
        $lista->getCollection()->transform(function ($item) {
            $ultimaRespuesta = $item->respuestas()
                ->where('encuesta_tipo', 'salida')
                ->orderBy('created_at', 'DESC')
                ->first();
            
            $item->fecha_termino = $ultimaRespuesta ? $ultimaRespuesta->created_at->format('Y-m-d H:i:s') : null;
            return $item;
        });

        return $lista;
    }

    public function create(Request $request)
    {
        if (\App\Helpers\SubdominioHelper::esTipo('sistema')) {
            abort(403, 'No está permitido crear visitas desde el sistema.');
        }

        $restaurantes = Restaurante::whereNull('deleted_at')->orderBy('name')->get();
        $estados = EstadoVisita::all();

        $restaurante_id = $request->restaurante_id ? decrypt($request->restaurante_id) : null;
        
        // Solo cargar shoppers si hay restaurante seleccionado
        $shoppers = collect();
        if ($restaurante_id) {
            $shoppers = $this->obtenerShoppersDisponibles($restaurante_id);
        }

        return view('visitas.nuevo')->with([
            'visita' => null,
            'shoppers' => $shoppers,
            'restaurantes' => $restaurantes,
            'estados' => $estados,
            'restaurante_id' => $restaurante_id,
        ]);
    }

    /**
     * Obtener shoppers disponibles para un restaurante (que no hayan completado visitas)
     */
    public function getShoppers(Request $request)
    {
        $restaurante_id = $request->restaurante_id;
        $visita_id = $request->visita_id ? decrypt($request->visita_id) : null;

        if (!$restaurante_id) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe seleccionar un restaurante']);
        }

        $shoppers = $this->obtenerShoppersDisponibles($restaurante_id, $visita_id);

        return response()->json([
            'estado' => 200,
            'shoppers' => $shoppers->map(function($shopper) {
                return [
                    'id' => $shopper->id,
                    'name' => $shopper->name
                ];
            })
        ]);
    }

    /**
     * Obtener lista de shoppers que no han completado visitas al restaurante
     */
    private function obtenerShoppersDisponibles($restaurante_id, $visita_id = null)
    {
        // Si estamos editando, obtener el shopper actual de la visita
        $shopperActualId = null;
        if ($visita_id) {
            $visitaActual = Visita::find($visita_id);
            if ($visitaActual) {
                $shopperActualId = $visitaActual->mistery_shopper_id;
            }
        }

        // Obtener IDs de shoppers que ya tienen visitas completadas (estado_id = 3) a este restaurante
        $shoppersConVisitasCompletadas = Visita::where('restaurante_id', $restaurante_id)
            ->where('estado_id', 3) // Completadas
            ->whereNull('deleted_at')
            ->when($visita_id, function($q) use ($visita_id) {
                return $q->where('id', '!=', $visita_id); // Excluir la visita actual si estamos editando
            })
            ->pluck('mistery_shopper_id')
            ->unique()
            ->toArray();

        // Obtener shoppers aprobados y activos que NO estén en la lista de completados
        $shoppers = MisteryShopper::whereNull('deleted_at')
            ->where('estado', 1)
            ->where('aprobado', 1)
            ->whereNotIn('id', $shoppersConVisitasCompletadas)
            ->orderBy('name')
            ->get();

        // Si estamos editando y el shopper actual no está en la lista, agregarlo
        if ($shopperActualId) {
            $shopperActual = MisteryShopper::where('id', $shopperActualId)
                ->whereNull('deleted_at')
                ->where('estado', 1)
                ->where('aprobado', 1)
                ->first();
            
            if ($shopperActual && !$shoppers->contains('id', $shopperActualId)) {
                $shoppers->prepend($shopperActual);
            }
        }

        return $shoppers;
    }

    public function store(Request $request)
    {
        if (\App\Helpers\SubdominioHelper::esTipo('sistema')) {
            return response()->json(['estado' => 403, 'mensaje' => 'No está permitido crear visitas desde el sistema.']);
        }

        $shopper_id = $request->shopper_id;
        $restaurante_id = $request->restaurante_id;
        $fecha_asignacion = $request->fecha_asignacion;
        $hora_asignacion = $request->hora_asignacion;

        if (!$shopper_id || !$restaurante_id || !$fecha_asignacion || !$hora_asignacion) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar todos los campos requeridos']);
        }

        // Validaciones estadísticas
        $validacionLimite = $this->validarLimiteVisitas($restaurante_id, $fecha_asignacion);
        if ($validacionLimite['error']) {
            return response()->json(['estado' => 500, 'mensaje' => $validacionLimite['mensaje']]);
        }

        $validacionDistribucion = $this->validarDistribucionDias($restaurante_id, $fecha_asignacion);
        if ($validacionDistribucion['error']) {
            return response()->json(['estado' => 500, 'mensaje' => $validacionDistribucion['mensaje']]);
        }

        $validacionRotacion = $this->validarRotacionShoppers($shopper_id, $restaurante_id, $fecha_asignacion);
        if ($validacionRotacion['error']) {
            return response()->json(['estado' => 500, 'mensaje' => $validacionRotacion['mensaje']]);
        }

        // Crear visita
        $new = new Visita();
        $new->mistery_shopper_id = $shopper_id;
        $new->restaurante_id = $restaurante_id;
        $new->fecha_asignacion = $fecha_asignacion;
        $new->hora_asignacion = $hora_asignacion;
        $new->estado_id = 1; // Pendiente
        $new->tipo_horario = Visita::calcularTipoHorario($hora_asignacion);
        $new->dia_semana = Carbon::parse($fecha_asignacion)->dayOfWeekIso;
        $new->periodo_mes = Carbon::parse($fecha_asignacion)->month;
        $new->periodo_anio = Carbon::parse($fecha_asignacion)->year;
        
        // Verificar si la visita está a menos de 24 horas
        $fechaHoraVisita = Carbon::parse($fecha_asignacion . ' ' . $hora_asignacion);
        $horasHastaVisita = Carbon::now()->diffInHours($fechaHoraVisita, false);
        
        // Si está a menos de 24 horas, marcar para envío inmediato
        if ($horasHastaVisita < 24 && $horasHastaVisita > 0) {
            $new->notificado_24horas = 0; // Se enviará por el command
        }
        
        $new->save();

        return response()->json(['estado' => 200]);
    }

    public function show($id)
    {
        $id = decrypt($id);

        $visita = Visita::where('id', $id)->whereNull('deleted_at')
            ->with(['shopper', 'restaurante', 'estado', 'respuestas.pregunta'])
            ->first();
        
        if (!$visita) {
            return redirect()->route('visitas.lista');
        }

        // Si es un shopper, verificar que la visita le pertenece
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo === 'shopper') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $shopper = \Auth::guard($guard)->user();
            if (!$shopper || $visita->mistery_shopper_id != $shopper->id) {
                return redirect()->route('visitas.lista')->with('error', 'No tienes permiso para ver esta visita');
            }
        }

        // Si es un restaurante, verificar que la visita le pertenece y está finalizada (estado_id = 4)
        if ($tipo === 'restaurante') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $user = \Auth::guard($guard)->user();
            if (!$user || $visita->restaurante_id != $user->restaurante_id || $visita->estado_id != 4) {
                return redirect()->route('dashboard')->with('error', 'No tienes permiso para ver esta visita');
            }
        }

        $puedeEditar = false; // Se deshabilitó la edición de visitas en el sistema

        return view('visitas.ver')->with([
            'visita' => $visita,
            'puedeEditar' => $puedeEditar
        ]);
    }

    public function edit($id)
    {
        if (\App\Helpers\SubdominioHelper::esTipo('sistema')) {
            abort(403, 'No está permitido editar visitas desde el sistema.');
        }

        $id = decrypt($id);

        $visita = Visita::where('id', $id)->whereNull('deleted_at')->first();
        if (!$visita) {
            return redirect()->route('visitas.lista');
        }

        // No permitir editar si ya se respondió la pre-encuesta (estado_id != 1)
        if ($visita->estado_id != 1) {
            return redirect()->route('visitas.ver', encrypt($visita->id))
                ->with('error', 'No se puede editar una visita después de que se haya respondido la encuesta de expectativas');
        }

        $restaurantes = Restaurante::whereNull('deleted_at')->orderBy('name')->get();
        $estados = EstadoVisita::all();
        
        // Cargar shoppers disponibles para el restaurante de la visita
        $shoppers = $this->obtenerShoppersDisponibles($visita->restaurante_id, $visita->id);

        return view('visitas.nuevo')->with([
            'visita' => $visita,
            'shoppers' => $shoppers,
            'restaurantes' => $restaurantes,
            'estados' => $estados,
            'restaurante_id' => $visita->restaurante_id,
        ]);
    }

    public function update(Request $request)
    {
        if (\App\Helpers\SubdominioHelper::esTipo('sistema')) {
            return response()->json(['estado' => 403, 'mensaje' => 'No está permitido editar visitas desde el sistema.']);
        }

        $id = decrypt($request->id);
        
        // Verificar que la visita existe y está en estado 1 (Pendiente)
        $visitaActual = Visita::find($id);
        if (!$visitaActual) {
            return response()->json(['estado' => 500, 'mensaje' => 'Visita no encontrada']);
        }
        
        if ($visitaActual->estado_id != 1) {
            return response()->json(['estado' => 500, 'mensaje' => 'No se puede editar una visita después de que se haya respondido la encuesta de expectativas']);
        }

        $update = Visita::find($id);
        if (!$update || $update->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Visita no encontrada']);
        }

        $shopper_id = $request->shopper_id;
        $restaurante_id = $request->restaurante_id;
        $fecha_asignacion = $request->fecha_asignacion;
        $hora_asignacion = $request->hora_asignacion;

        if (!$shopper_id || !$restaurante_id || !$fecha_asignacion || !$hora_asignacion) {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe completar todos los campos requeridos']);
        }

        $update->mistery_shopper_id = $shopper_id;
        $update->restaurante_id = $restaurante_id;
        $update->fecha_asignacion = $fecha_asignacion;
        $update->hora_asignacion = $hora_asignacion;
        $update->tipo_horario = Visita::calcularTipoHorario($hora_asignacion);
        $update->dia_semana = Carbon::parse($fecha_asignacion)->dayOfWeekIso;
        $update->periodo_mes = Carbon::parse($fecha_asignacion)->month;
        $update->periodo_anio = Carbon::parse($fecha_asignacion)->year;
        
        // Si se cambió la fecha/hora, resetear notificaciones
        if ($update->isDirty('fecha_asignacion') || $update->isDirty('hora_asignacion')) {
            $update->notificado_24horas = 0;
            $update->notificado_24horas_at = null;
            $update->notificado_2horas = 0;
            $update->notificado_2horas_at = null;
        }
        
        $update->save();

        return response()->json(['estado' => 200]);
    }

    public function eliminar(Request $request)
    {
        if (\App\Helpers\SubdominioHelper::esTipo('sistema')) {
            return response()->json(['estado' => 403, 'mensaje' => 'No está permitido eliminar visitas desde el sistema.']);
        }

        $id = decrypt($request->id);

        $update = Visita::find($id);
        if (!$update || $update->deleted_at) {
            return response()->json(['estado' => 404, 'mensaje' => 'Visita no encontrada']);
        }

        $update->deleted_at = date('Y-m-d H:i:s');
        $update->save();

        return response()->json(['estado' => 200]);
    }

    public function validarLimiteVisitas($restaurante_id, $fecha_asignacion)
    {
        $visitasPorPeriodo = Configuracion::obtenerValor('visitas_por_periodo', 13);
        $diasPorPeriodo = Configuracion::obtenerValor('dias_por_periodo', 60);
        
        $fechaInicio = Carbon::parse($fecha_asignacion)->subDays($diasPorPeriodo);
        
        $count = Visita::where('restaurante_id', $restaurante_id)
            ->where('fecha_asignacion', '>=', $fechaInicio)
            ->where('fecha_asignacion', '<=', $fecha_asignacion)
            ->whereIn('estado_id', [1, 2, 3]) // Solo contar pendientes, en proceso y completadas
            ->whereNull('deleted_at')
            ->count();
        
        if ($count >= $visitasPorPeriodo) {
            return [
                'error' => true,
                'mensaje' => "Se ha alcanzado el límite de {$visitasPorPeriodo} visitas para este período de {$diasPorPeriodo} días"
            ];
        }

        return ['error' => false];
    }

    public function validarDistribucionDias($restaurante_id, $fecha_asignacion)
    {
        $requerida = Configuracion::obtenerValor('distribucion_dias_requerida', true);
        if (!$requerida) {
            return ['error' => false];
        }

        $diasPorPeriodo = Configuracion::obtenerValor('dias_por_periodo', 60);
        $fechaInicio = Carbon::parse($fecha_asignacion)->subDays($diasPorPeriodo);
        $diaSemana = Carbon::parse($fecha_asignacion)->dayOfWeekIso;

        // Contar visitas en el mismo día de la semana en el período
        $count = Visita::where('restaurante_id', $restaurante_id)
            ->where('fecha_asignacion', '>=', $fechaInicio)
            ->where('fecha_asignacion', '<=', $fecha_asignacion)
            ->where('dia_semana', $diaSemana)
            ->whereIn('estado_id', [1, 2, 3])
            ->whereNull('deleted_at')
            ->count();

        // Permitir máximo 3 visitas en el mismo día de la semana por período
        if ($count >= 3) {
            return [
                'error' => true,
                'mensaje' => 'Hay demasiadas visitas asignadas para este día de la semana en el período actual. Por favor, seleccione otro día.'
            ];
        }

        return ['error' => false];
    }

    public function validarDistribucionHorarios($restaurante_id, $fecha_asignacion, $hora_asignacion)
    {
        $requerida = Configuracion::obtenerValor('distribucion_horarios_requerida', true);
        if (!$requerida) {
            return ['error' => false];
        }

        $diasPorPeriodo = Configuracion::obtenerValor('dias_por_periodo', 60);
        $fechaInicio = Carbon::parse($fecha_asignacion)->subDays($diasPorPeriodo);
        $tipoHorario = Visita::calcularTipoHorario($hora_asignacion);

        // Contar visitas del mismo tipo de horario en el período
        $count = Visita::where('restaurante_id', $restaurante_id)
            ->where('fecha_asignacion', '>=', $fechaInicio)
            ->where('fecha_asignacion', '<=', $fecha_asignacion)
            ->where('tipo_horario', $tipoHorario)
            ->whereIn('estado_id', [1, 2, 3])
            ->whereNull('deleted_at')
            ->count();

        // Permitir máximo 5 visitas del mismo tipo de horario por período
        if ($count >= 5) {
            $tipoNombre = $tipoHorario === 'punta' ? 'horas punta' : ($tipoHorario === 'normal' ? 'horarios normales' : 'horarios bajos');
            return [
                'error' => true,
                'mensaje' => "Hay demasiadas visitas asignadas en {$tipoNombre} para este período. Por favor, seleccione otro horario."
            ];
        }

        return ['error' => false];
    }

    public function validarRotacionShoppers($shopper_id, $restaurante_id, $fecha_asignacion)
    {
        $diasPorPeriodo = Configuracion::obtenerValor('dias_por_periodo', 60);
        $fechaInicio = Carbon::parse($fecha_asignacion)->subDays($diasPorPeriodo);

        // Contar visitas del mismo shopper al mismo restaurante en el período
        $count = Visita::where('mistery_shopper_id', $shopper_id)
            ->where('restaurante_id', $restaurante_id)
            ->where('fecha_asignacion', '>=', $fechaInicio)
            ->where('fecha_asignacion', '<=', $fecha_asignacion)
            ->whereIn('estado_id', [1, 2, 3])
            ->whereNull('deleted_at')
            ->count();

        // Permitir máximo 2 visitas del mismo shopper al mismo restaurante por período
        if ($count >= 2) {
            return [
                'error' => true,
                'mensaje' => 'Este Mistery Shopper ya tiene asignaciones a este restaurante en el período actual. Por favor, seleccione otro shopper.'
            ];
        }

        return ['error' => false];
    }


    public function responderEncuestaEntrada($id)
    {
        $id = decrypt($id);

        $visita = Visita::where('id', $id)->whereNull('deleted_at')
            ->with(['shopper', 'restaurante', 'estado'])
            ->first();
        
        if (!$visita) {
            return redirect()->route('visitas.lista');
        }

        // SOLO los shoppers pueden responder encuestas
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo !== 'shopper') {
            return redirect()->route('visitas.lista')->with('error', 'Solo los Mistery Shoppers pueden responder encuestas');
        }

        // Verificar que la visita le pertenece al shopper
        $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
        $shopper = \Auth::guard($guard)->user();
        if (!$shopper || $visita->mistery_shopper_id != $shopper->id) {
            return redirect()->route('visitas.lista')->with('error', 'No tienes permiso para responder esta visita');
        }

        if ($visita->estado_id != 1) {
            return redirect()->route('visitas.ver', encrypt($visita->id))
                ->with('error', 'Esta visita no está en estado pendiente');
        }

        // Verificar si han pasado más de 24 horas desde la creación de la visita
        if ($visita->created_at->diffInHours(Carbon::now()) > 24) {
            $visita->estado_id = 5; // No se realizó
            $visita->save();
            return redirect()->route('visitas.ver', encrypt($visita->id))
                ->with('error', 'El plazo de 24 horas para responder el cuestionario inicial ha expirado. La visita ha sido anulada.');
        }

        // Validar que se puede responder 24 horas antes de la fecha de asignación
        /*
        $fechaAsignacion = Carbon::parse($visita->fecha_asignacion)->startOfDay();
        $ahora = Carbon::now();
        $horasRestantes = $ahora->diffInHours($fechaAsignacion, false);
        
        if ($horasRestantes < 24) {
            return redirect()->route('visitas.ver', encrypt($visita->id))
                ->with('error', 'La encuesta de expectativas solo puede responderse hasta 24 horas antes de la fecha de la visita');
        }
        */

        $encuesta = Encuesta::where('tipo', 'entrada')->where('estado', 1)->first();
        if (!$encuesta) {
            return redirect()->route('visitas.ver', encrypt($visita->id))
                ->with('error', 'No se encontró la encuesta de entrada');
        }

        $preguntas = $encuesta->preguntas()->with('dimension_rel')->orderBy('orden')->get();

        return view('visitas.responder_entrada')->with([
            'visita' => $visita,
            'encuesta' => $encuesta,
            'preguntas' => $preguntas,
        ]);
    }

    public function guardarEncuestaEntrada(Request $request)
    {
        $visita_id = decrypt($request->visita_id);
        $visita = Visita::find($visita_id);

        if (!$visita || $visita->estado_id != 1) {
            return response()->json(['estado' => 500, 'mensaje' => 'Visita no válida o no está en estado pendiente']);
        }

        // Verificar si han pasado más de 24 horas desde la creación de la visita
        if ($visita->created_at->diffInHours(Carbon::now()) > 24) {
            $visita->estado_id = 5; // No se realizó
            $visita->save();
            return response()->json(['estado' => 500, 'mensaje' => 'El plazo de 24 horas para responder el cuestionario inicial ha expirado. La visita ha sido anulada.']);
        }

        // Si es un shopper, verificar que la visita le pertenece
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo === 'shopper') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $shopper = \Auth::guard($guard)->user();
            if (!$shopper || $visita->mistery_shopper_id != $shopper->id) {
                return response()->json(['estado' => 403, 'mensaje' => 'No tienes permiso para responder esta visita']);
            }
        }

        $respuestas = $request->respuestas ?? [];

        foreach ($respuestas as $pregunta_id => $respuesta) {
            $pregunta = PreguntaEncuesta::find($pregunta_id);
            if (!$pregunta) continue;

            $respuestaData = [
                'visita_id' => $visita_id,
                'pregunta_id' => $pregunta_id,
                'encuesta_tipo' => 'entrada',
                'pregunta_texto' => $pregunta->texto,
            ];

            if ($pregunta->tipo_respuesta === 'texto_libre') {
                $respuestaData['respuesta_texto'] = $respuesta;
            } else {
                $respuestaData['respuesta_valor'] = $respuesta;
            }

            RespuestaVisita::updateOrCreate(
                [
                    'visita_id' => $visita_id,
                    'pregunta_id' => $pregunta_id,
                    'encuesta_tipo' => 'entrada',
                ],
                $respuestaData
            );
        }

        // Cambiar estado a "En espera de visita"
        $visita->estado_id = 2;
        $visita->save();

        // Enviar email de confirmación de encuesta inicial
        if ($visita->shopper && $visita->shopper->email) {
            try {
                $dataEmail = [
                    'nombre' => $visita->shopper->name,
                    'restaurante' => $visita->restaurante ? $visita->restaurante->name : 'N/A',
                    'descuento' => $visita->restaurante ? $visita->restaurante->porcentaje_descuento : 0,
                    'plataforma' => 'https://shopper.check360.cl',
                    'titulo' => '¡Expectativas Registradas! - Check 360',
                    'vista' => 'mails.visita_inicio_completado'
                ];
                \Mail::to($visita->shopper->email)->send(new \App\Mail\enviarEmail($dataEmail));
            } catch (\Throwable $e) {
                \Log::error("Error enviando email inicio cuestionario: " . $e->getMessage());
            }
        }

        return response()->json(['estado' => 200, 'mensaje' => 'Encuesta de entrada guardada correctamente. Ahora debes realizar la visita y marcarla como completada.']);
    }

    public function responderEncuestaSalida($id)
    {
        $id = decrypt($id);

        $visita = Visita::where('id', $id)->whereNull('deleted_at')
            ->with(['shopper', 'restaurante', 'estado'])
            ->first();
        
        if (!$visita) {
            return redirect()->route('visitas.lista');
        }

        // SOLO los shoppers pueden responder encuestas
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo !== 'shopper') {
            return redirect()->route('visitas.lista')->with('error', 'Solo los Mistery Shoppers pueden responder encuestas');
        }

        // Verificar que la visita le pertenece al shopper
        $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
        $shopper = \Auth::guard($guard)->user();
        if (!$shopper || $visita->mistery_shopper_id != $shopper->id) {
            return redirect()->route('visitas.lista')->with('error', 'No tienes permiso para responder esta visita');
        }

        if ($visita->estado_id != 2 && $visita->estado_id != 3) {
            return redirect()->route('visitas.ver', encrypt($visita->id))
                ->with('error', 'Esta visita no está en un estado válido para responder la encuesta de salida.');
        }

        // Validar límite de 24 horas desde la pre-encuesta
        $preSurveyResponse = RespuestaVisita::where('visita_id', $visita->id)
            ->where('encuesta_tipo', 'entrada')
            ->first();

        if ($preSurveyResponse) {
            $horasTranscurridas = $preSurveyResponse->created_at->diffInHours(Carbon::now());
            if ($horasTranscurridas > 24) {
                $visita->estado_id = 5; // No se realizó
                $visita->save();
                return redirect()->route('visitas.ver', encrypt($visita->id))
                    ->with('error', 'El plazo de 24 horas para responder la post-encuesta ha expirado. La visita ha sido marcada como no realizada.');
            }
        }

        $encuesta = Encuesta::where('tipo', 'salida')->where('estado', 1)->first();
        if (!$encuesta) {
            return redirect()->route('visitas.ver', encrypt($visita->id))
                ->with('error', 'No se encontró la encuesta de salida');
        }

        $preguntas = $encuesta->preguntas;

        return view('visitas.responder_salida')->with([
            'visita' => $visita,
            'encuesta' => $encuesta,
            'preguntas' => $preguntas,
        ]);
    }

    public function guardarEncuestaSalida(Request $request)
    {
        $visita_id = decrypt($request->visita_id);
        $visita = Visita::find($visita_id);

        if (!$visita || ($visita->estado_id != 2 && $visita->estado_id != 3)) {
            return response()->json(['estado' => 500, 'mensaje' => 'Visita no válida o no está en estado válido para responder la encuesta']);
        }

        // Validar límite de 24 horas desde la pre-encuesta
        $preSurveyResponse = RespuestaVisita::where('visita_id', $visita->id)
            ->where('encuesta_tipo', 'entrada')
            ->first();

        if ($preSurveyResponse) {
            $horasTranscurridas = $preSurveyResponse->created_at->diffInHours(Carbon::now());
            if ($horasTranscurridas > 24) {
                $visita->estado_id = 5; // No se realizó
                $visita->save();
                return response()->json(['estado' => 500, 'mensaje' => 'El plazo de 24 horas para responder la post-encuesta ha expirado. La visita ha sido marcada como no realizada.']);
            }
        }

        // Si es un shopper, verificar que la visita le pertenece
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo === 'shopper') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $shopper = \Auth::guard($guard)->user();
            if (!$shopper || $visita->mistery_shopper_id != $shopper->id) {
                return response()->json(['estado' => 403, 'mensaje' => 'No tienes permiso para responder esta visita']);
            }
        }

        $respuestas = $request->respuestas ?? [];

        foreach ($respuestas as $pregunta_id => $respuesta) {
            $pregunta = PreguntaEncuesta::find($pregunta_id);
            if (!$pregunta) continue;

            $respuestaData = [
                'visita_id' => $visita_id,
                'pregunta_id' => $pregunta_id,
                'encuesta_tipo' => 'salida',
                'pregunta_texto' => $pregunta->texto,
            ];

            if ($pregunta->tipo_respuesta === 'texto_libre') {
                $respuestaData['respuesta_texto'] = $respuesta;
            } else {
                $respuestaData['respuesta_valor'] = $respuesta;
            }

            RespuestaVisita::updateOrCreate(
                [
                    'visita_id' => $visita_id,
                    'pregunta_id' => $pregunta_id,
                    'encuesta_tipo' => 'salida',
                ],
                $respuestaData
            );
        }

        // Cambiar estado a "Finalizada" y generar cupón único
        $visita->estado_id = 4;
        $visita->cupon_codigo = 'CK360' . strtoupper(\Str::random(6));
        $visita->save();

        // Enviar email de finalización con cupón (si corresponde)
        if ($visita->shopper && $visita->shopper->email) {
            $tieneCupon = false;
            if ($preSurveyResponse) {
                $horasTranscurridas = $preSurveyResponse->created_at->diffInHours(Carbon::now());
                if ($horasTranscurridas <= 24) {
                    $tieneCupon = true;
                }
            }

            try {
                $dataEmail = [
                    'nombre' => $visita->shopper->name,
                    'restaurante' => $visita->restaurante ? $visita->restaurante->name : 'N/A',
                    'descuento' => $visita->restaurante ? $visita->restaurante->porcentaje_descuento : 0,
                    'tiene_cupon' => $tieneCupon,
                    'cupon_codigo' => $visita->cupon_codigo,
                    'titulo' => 'Evaluación Finalizada - Check 360',
                    'vista' => 'mails.visita_final_completado'
                ];
                \Mail::to($visita->shopper->email)->send(new \App\Mail\enviarEmail($dataEmail));
            } catch (\Throwable $e) {
                \Log::error("Error enviando email finalización visita: " . $e->getMessage());
            }
        }

        return response()->json([
            'estado' => 200, 
            'mensaje' => 'Encuesta de salida guardada correctamente. ¡Tu cupón de descuento ha sido generado!',
            'url' => route('visitas.ver_cupon', encrypt($visita->id))
        ]);
    }

    public function rechazar(Request $request)
    {
        $visita_id = decrypt($request->visita_id);
        $motivo = $request->motivo;

        if (!$motivo || trim($motivo) === '') {
            return response()->json(['estado' => 500, 'mensaje' => 'Debe proporcionar un motivo para rechazar la visita']);
        }

        $visita = Visita::find($visita_id);
        if (!$visita || $visita->estado_id != 1) {
            return response()->json(['estado' => 500, 'mensaje' => 'Visita no válida o no está en estado pendiente']);
        }

        $visita->estado_id = 6; // Rechazada
        $visita->motivo_rechazo = $motivo;
        $visita->save();

        return response()->json(['estado' => 200, 'mensaje' => 'Visita rechazada correctamente']);
    }

    public function marcarVisitado(Request $request)
    {
        try {
            $visita_id = decrypt($request->visita_id);

            $visita = Visita::with(['shopper', 'restaurante'])->find($visita_id);
            if (!$visita || $visita->estado_id != 2) {
                return response()->json(['estado' => 500, 'mensaje' => 'Visita no válida o no está en espera de visita']);
            }

            // Validar límite de 24 horas desde la finalización del cuestionario inicial (entrada)
            $preSurveyResponse = RespuestaVisita::where('visita_id', $visita->id)
                ->where('encuesta_tipo', 'entrada')
                ->first();

            if ($preSurveyResponse) {
                $horasTranscurridas = $preSurveyResponse->created_at->diffInHours(Carbon::now());
                if ($horasTranscurridas > 24) {
                    $visita->estado_id = 5; // No se realizó
                    $visita->save();
                    return response()->json(['estado' => 500, 'mensaje' => 'El plazo de 24 horas para realizar la visita desde el cuestionario inicial ha expirado. La visita ha sido marcada como no realizada.']);
                }
            }

            // Si es un shopper, verificar que la visita le pertenece
            $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
            if ($tipo === 'shopper') {
                $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
                $shopper = \Auth::guard($guard)->user();
                if (!$shopper || $visita->mistery_shopper_id != $shopper->id) {
                    return response()->json(['estado' => 403, 'mensaje' => 'No tienes permiso para marcar esta visita']);
                }
            }

            // Marcar como visitado y cambiar estado a "Visita completada"
            $visita->visitado_at = Carbon::now();
            $visita->estado_id = 3; // Visita completada
            $visita->save();

            \Log::info("Visita #{$visita->id} marcada como visitada. Enviando email...");

            // Enviar email de notificación post-encuesta si no se ha enviado
            if ($visita->notificado_post == 0) {
                $emailEnviado = $this->enviarEmailNotificacionPost($visita);
                if ($emailEnviado) {
                    $visita->notificado_post = 1;
                    $visita->notificado_post_at = Carbon::now();
                    $visita->save();
                    \Log::info("Email post-encuesta enviado correctamente para visita #{$visita->id}");
                } else {
                    \Log::error("Error al enviar email post-encuesta para visita #{$visita->id}");
                }
            }

            return response()->json(['estado' => 200, 'mensaje' => 'Visita marcada como realizada. Ahora puedes responder la encuesta de experiencia.']);
        } catch (\Exception $e) {
            \Log::error("Error en marcarVisitado: " . $e->getMessage());
            return response()->json(['estado' => 500, 'mensaje' => 'Error al marcar la visita: ' . $e->getMessage()]);
        }
    }

    /**
     * Enviar email de notificación post-encuesta
     */
    private function enviarEmailNotificacionPost($visita)
    {
        try {
            $shopper = $visita->shopper;
            $restaurante = $visita->restaurante;
            
            if (!$shopper || !$restaurante) {
                \Log::error("No se pudo enviar email post-encuesta: shopper o restaurante no encontrado para visita #{$visita->id}");
                return false;
            }
            
            if (!$shopper->email) {
                \Log::error("No se pudo enviar email post-encuesta: shopper sin email para visita #{$visita->id}");
                return false;
            }
            
            $data = [
                'vista' => 'mails.visita_notificacion_post',
                'asunto' => 'Completa tu encuesta post-visita - ' . $restaurante->name,
                'nombre' => $shopper->name,
                'restaurante' => $restaurante->name,
                'fecha' => $visita->fecha_asignacion->format('d/m/Y'),
                'hora' => date('H:i', strtotime($visita->hora_asignacion)),
                'plataforma' => 'https://shopper.check360.cl'
            ];
            
            \Log::info("Enviando email post-encuesta a: " . $shopper->email);
            \Mail::to($shopper->email)->send(new \App\Mail\enviarEmail($data));
            \Log::info("Email post-encuesta enviado exitosamente a: " . $shopper->email);
            
            return true;
        } catch (\Throwable $e) {
            \Log::error("Error al enviar email post-encuesta para visita #{$visita->id}: " . $e->getMessage() . " - Line: " . $e->getLine());
            return false;
        }
    }
    /**
     * Métodos específicos para el Dashboard de Restaurante
     */
    public function indexRestaurante()
    {
        if (!\App\Helpers\SubdominioHelper::esTipo('restaurante')) {
            return redirect()->route('dashboard');
        }
        return view('visitas.restaurante_lista');
    }

    public function getDataRestaurante(Request $request)
    {
        Visita::expirarVisitasVencidas();
        if (!\App\Helpers\SubdominioHelper::esTipo('restaurante')) {
            return response()->json(['estado' => 403, 'mensaje' => 'Acceso denegado']);
        }

        $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
        $user = \Auth::guard($guard)->user();
        if (!$user) return response()->json(['data' => []]);

        $filtros = $request->filtros ?? [];

        // Solo visitas del restaurante actual que tengan encuesta de salida completada (estado_id 4 = Finalizada)
        // Omitimos canceladas o no realizadas.
        $lista = Visita::where('restaurante_id', $user->restaurante_id)
            ->whereNull('deleted_at')
            ->where('estado_id', 4) // Solo las exitosas/finalizadas
            ->orderBy('id', 'DESC')
            ->with(['estado'])
            ->paginate(10);

        // Transformar datos para cumplir requerimientos del usuario
        $lista->getCollection()->transform(function ($item) {
            // La fecha/hora debe ser la de finalización (cuando se respondió la encuesta de salida)
            // Buscamos la respuesta más reciente de tipo salida
            $ultimaRespuesta = RespuestaVisita::where('visita_id', $item->id)
                ->where('encuesta_tipo', 'salida')
                ->orderBy('created_at', 'DESC')
                ->first();
            
            $fechaFinalizacion = $ultimaRespuesta ? $ultimaRespuesta->created_at->format('d/m/Y H:i') : 'N/A';

            return [
                'id_ref' => 'VIS-' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                'id_encrypted' => encrypt($item->id),
                'estado' => $item->estado->nombre,
                'estado_id' => $item->estado_id,
                'fecha_finalizada' => $fechaFinalizacion,
                'monto_total' => $item->total_consumo ?? 0,
                'descuento_aplicado' => $item->total_descuento ?? 0,
                'total_pagado' => $item->total_pagado ?? 0,
                'cupon_canjeado' => !empty($item->cupon_canjeado_at),
                'cupon_codigo' => $item->cupon_codigo
            ];
        });

        return $lista;
    }

    public function getDetalleVisitaRestaurante(Request $request)
    {
        // El usuario solicitó no ver las respuestas de las encuestas en este módulo
        return response()->json(['estado' => 403, 'mensaje' => 'Detalle de respuestas no disponible por configuración']);
    }

    /**
     * Métodos específicos para el Dashboard de Sistema (Ver todas las encuestas)
     */
    public function indexSistemaEncuestas()
    {
        if (!\App\Helpers\SubdominioHelper::esTipo('sistema') && !\App\Helpers\SubdominioHelper::esTipo('shopper')) {
            return redirect()->route('dashboard');
        }
        return view('visitas.sistema_encuestas_lista');
    }

    public function getDataSistemaEncuestas(Request $request)
    {
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        $shopperId = null;

        if ($tipo !== 'sistema' && $tipo !== 'shopper') {
            return response()->json(['estado' => 403, 'mensaje' => 'Acceso denegado']);
        }

        if ($tipo === 'shopper') {
            $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
            $shopper = \Auth::guard($guard)->user();
            if ($shopper) {
                $shopperId = $shopper->id;
            }
        }

        $filtros = $request->filtros ?? [];

        $lista = Visita::whereNull('deleted_at')
            ->when($shopperId, function ($q) use ($shopperId) {
                return $q->where('mistery_shopper_id', $shopperId);
            })
            ->when(!empty($filtros['estado_id']), function ($q) use ($filtros) {
                return $q->where('estado_id', $filtros['estado_id']);
            })
            ->when(!empty($filtros['restaurante_id']), function ($q) use ($filtros) {
                return $q->where('restaurante_id', $filtros['restaurante_id']);
            })
            ->when(!empty($filtros['fecha_desde']), function ($q) use ($filtros) {
                return $q->where('fecha_asignacion', '>=', $filtros['fecha_desde']);
            })
            ->when(!empty($filtros['fecha_hasta']), function ($q) use ($filtros) {
                return $q->where('fecha_asignacion', '<=', $filtros['fecha_hasta']);
            })
            ->when(!empty($filtros['shopper_nombre']), function ($q) use ($filtros) {
                return $q->whereHas('shopper', function($sq) use ($filtros) {
                    $sq->where('name', 'like', '%' . $filtros['shopper_nombre'] . '%');
                });
            })
            ->orderBy('id', 'DESC')
            ->with(['estado', 'shopper', 'restaurante'])
            ->paginate(10);

        // Transformar datos 
        $lista->getCollection()->transform(function ($item) {
            $tieneEntrada = RespuestaVisita::where('visita_id', $item->id)->where('encuesta_tipo', 'entrada')->exists();
            $tieneSalida = RespuestaVisita::where('visita_id', $item->id)->where('encuesta_tipo', 'salida')->exists();

            return [
                'id' => $item->id,
                'id_encrypted' => encrypt($item->id),
                'estado' => $item->estado->nombre,
                'estado_id' => $item->estado_id,
                'shopper_nombre' => $item->shopper ? $item->shopper->name : 'N/A',
                'restaurante_nombre' => $item->restaurante ? $item->restaurante->name : 'N/A',
                'fecha' => $item->fecha_asignacion ? \Carbon\Carbon::parse($item->fecha_asignacion)->format('d/m/Y') : 'N/A',
                'hora' => $item->hora_asignacion ? date('H:i', strtotime($item->hora_asignacion)) : 'N/A',
                'tiene_entrada' => $tieneEntrada,
                'tiene_salida' => $tieneSalida,
            ];
        });

        return $lista;
    }

    public function getDetalleVisitaSistema(Request $request)
    {
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        if ($tipo !== 'sistema' && $tipo !== 'shopper') {
            return response()->json(['estado' => 403, 'mensaje' => 'Acceso denegado']);
        }

        $id = decrypt($request->id);

        $visita = Visita::where('id', $id)
            ->whereNull('deleted_at')
            ->with(['shopper', 'restaurante'])
            ->first();

        if (!$visita) {
            return response()->json(['estado' => 404, 'mensaje' => 'Visita no encontrada']);
        }

        // Obtener respuestas
        $respuestasEntrada = RespuestaVisita::where('visita_id', $id)
            ->where('encuesta_tipo', 'entrada')
            ->with('pregunta')
            ->get()
            ->map(function($r) {
                return [
                    'pregunta' => $r->pregunta_texto ?? $r->pregunta->texto,
                    'respuesta' => $r->respuesta_valor ?? $r->respuesta_texto,
                    'tipo' => $r->pregunta ? $r->pregunta->tipo_respuesta : 'texto'
                ];
            });

        $respuestasSalida = RespuestaVisita::where('visita_id', $id)
            ->where('encuesta_tipo', 'salida')
            ->with('pregunta')
            ->get()
            ->map(function($r) {
                return [
                    'pregunta' => $r->pregunta_texto ?? $r->pregunta->texto,
                    'respuesta' => $r->respuesta_valor ?? $r->respuesta_texto,
                    'tipo' => $r->pregunta ? $r->pregunta->tipo_respuesta : 'texto'
                ];
            });

        return response()->json([
            'estado' => 200,
            'visita_id' => $visita->id,
            'shopper' => $visita->shopper ? $visita->shopper->name : 'N/A',
            'restaurante' => $visita->restaurante ? $visita->restaurante->name : 'N/A',
            'fecha' => $visita->fecha_asignacion ? \Carbon\Carbon::parse($visita->fecha_asignacion)->format('d/m/Y') : 'N/A',
            'entrada' => $respuestasEntrada,
            'salida' => $respuestasSalida
        ]);
    }

    /**
     * Permite a un shopper auto-agendar una visita
     */
    public function agendarShopperPost(Request $request)
    {
        $shopper = \Auth::guard('shopper')->user();
        if (!$shopper || $shopper->aprobado != 1 || $shopper->estado != 1) {
            return response()->json(['estado' => 403, 'mensaje' => 'Tu cuenta no está activa o aprobada para agendar visitas']);
        }

        $restaurante_id = $request->restaurante_id_modal;
        $fecha_visita = $request->fecha_visita ?: \Carbon\Carbon::now()->format('Y-m-d');
        $hora_visita = $request->hora_visita ?: \Carbon\Carbon::now()->format('H:i');

        if (!$restaurante_id) {
            return response()->json(['estado' => 500, 'mensaje' => 'El local es obligatorio']);
        }

        $restaurante = Restaurante::find($restaurante_id);
        if (!$restaurante || !$restaurante->plan_activo) {
            return response()->json(['estado' => 500, 'mensaje' => 'El restaurante no se encuentra activo o no tiene un plan vigente']);
        }

        // 1. Validar límite de visitas en el periodo de 60 días del restaurante (máx 12)
        $visitasCount = Visita::where('restaurante_id', $restaurante_id)
            ->whereIn('estado_id', [1, 2, 3, 4]) // pendiente, espera, completada, finalizada
            ->where('fecha_asignacion', '>=', $restaurante->periodo_inicio)
            ->where('fecha_asignacion', '<=', $restaurante->periodo_fin)
            ->whereNull('deleted_at')
            ->count();

        if ($visitasCount >= 12) {
            return response()->json(['estado' => 500, 'mensaje' => 'Este restaurante ya ha completado su cuota máxima de 12 visitas para este ciclo de evaluación.']);
        }

        // 2. Validar rotación: Shopper no puede repetir el local en el periodo activo de 60 días
        $shopperVisits = Visita::where('mistery_shopper_id', $shopper->id)
            ->where('restaurante_id', $restaurante_id)
            ->whereIn('estado_id', [1, 2, 3, 4])
            ->where('fecha_asignacion', '>=', $restaurante->periodo_inicio)
            ->where('fecha_asignacion', '<=', $restaurante->periodo_fin)
            ->whereNull('deleted_at')
            ->exists();

        if ($shopperVisits) {
            return response()->json(['estado' => 500, 'mensaje' => 'Ya registraste o tienes agendada una visita a este local dentro de su ciclo de evaluación activo.']);
        }

        // 3. Validar horario peak y saturación (90% de capacidad)
        $diasMap = [1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'];
        $diaNum = \Carbon\Carbon::parse($fecha_visita)->dayOfWeekIso;
        $diaKey = $diasMap[$diaNum];

        $peak = $restaurante->horario_peak;
        if ($peak && isset($peak[$diaKey])) {
            $item = $peak[$diaKey];
            if (!empty($item['desde']) && !empty($item['hasta']) && !empty($item['ocupa_90'])) {
                $hora = \Carbon\Carbon::parse($hora_visita)->format('H:i');
                $desde = \Carbon\Carbon::parse($item['desde'])->format('H:i');
                $hasta = \Carbon\Carbon::parse($item['hasta'])->format('H:i');
                
                if ($hora >= $desde && $hora <= $hasta) {
                    return response()->json(['estado' => 500, 'mensaje' => 'El horario seleccionado coincide con las horas peak del local, el cual opera a su máxima capacidad (90%+). Por favor, escoge otro horario de visita.']);
                }
            }
        }

        // Crear visita
        $new = new Visita();
        $new->mistery_shopper_id = $shopper->id;
        $new->restaurante_id = $restaurante_id;
        $new->fecha_asignacion = $fecha_visita;
        $new->hora_asignacion = $hora_visita;
        $new->estado_id = 1; // Pendiente
        $new->tipo_horario = Visita::calcularTipoHorario($hora_visita);
        $new->dia_semana = $diaNum;
        $new->periodo_mes = \Carbon\Carbon::parse($fecha_visita)->month;
        $new->periodo_anio = \Carbon\Carbon::parse($fecha_visita)->year;
        $new->save();

        return response()->json([
            'estado' => 200,
            'mensaje' => '¡Visita agendada con éxito! Completa la pre-encuesta para activar tu código.',
            'url' => route('visitas.responder_entrada', encrypt($new->id))
        ]);
    }

    /**
     * Muestra el cupón de descuento finalizado para presentarlo en caja
     */
    public function mostrarCupon($id)
    {
        $id = decrypt($id);
        $visita = Visita::where('id', $id)
            ->whereNull('deleted_at')
            ->with(['shopper', 'restaurante'])
            ->first();

        if (!$visita) {
            return redirect()->route('dashboard')->with('error', 'Visita no encontrada');
        }

        // Validar autorización
        $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
        $guard = \App\Helpers\SubdominioHelper::obtenerGuard();
        $user = \Auth::guard($guard)->user();

        if ($tipo === 'shopper' && $visita->mistery_shopper_id != $user->id) {
            return redirect()->route('dashboard')->with('error', 'No autorizado');
        } elseif ($tipo === 'restaurante' && $visita->restaurante_id != $user->restaurante_id) {
            return redirect()->route('dashboard')->with('error', 'No autorizado');
        }

        if ($visita->estado_id != 4 || !$visita->cupon_codigo) {
            return redirect()->route('visitas.ver', encrypt($visita->id))->with('error', 'El cupón aún no ha sido generado.');
        }

        return view('visitas.cupon_exito')->with([
            'visita' => $visita,
            'restaurante' => $visita->restaurante,
            'shopper' => $visita->shopper
        ]);
    }
}

