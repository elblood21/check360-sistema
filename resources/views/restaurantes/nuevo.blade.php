@extends('layouts.master')
@section('title', ($restaurante ? 'Editar' : 'Nuevo')." restaurante")

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" />
<style>
    /* Choices.js custom styling */
    .choices {
        margin-bottom: 0;
    }
    .choices__inner {
        min-height: 38px;
        padding: 4px 7.5px;
        font-size: 0.875rem;
        border-radius: 0.25rem;
    }
    .choices__input {
        font-size: 0.875rem;
    }
    .choices__list--multiple .choices__item {
        background-color: #dc3545;
        border: 1px solid #dc3545;
        font-size: 0.75rem;
        padding: 0.35rem 0.55rem;
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #dc3545;
    }
    /* Fix z-index para Choices.js - debe estar por encima de todo */
    .choices {
        position: relative;
    }
    .choices__inner {
        position: relative;
    }
    .choices__list--dropdown {
        z-index: 999999 !important;
        position: absolute !important;
    }
    .choices.is-open {
        z-index: 999999 !important;
    }
    .choices.is-open .choices__inner {
        z-index: 999999 !important;
    }
    .choices__list--dropdown.is-active {
        z-index: 999999 !important;
    }
    /* Asegurar que el contenedor de Choices tenga contexto de apilamiento */
    .card, .container-fluid, .page-body {
        position: relative;
    }
    /* Override para cualquier elemento que pueda estar encima */
    .choices__list--dropdown,
    .choices__list--dropdown * {
        z-index: 999999 !important;
    }
    /* Asegurar que los cards no tengan overflow que corte el dropdown */
    .card {
        overflow: visible !important;
    }
    /* Forzar el dropdown fuera del flujo normal y asegurar que esté por encima */
    .choices__list--dropdown {
        z-index: 999999 !important;
    }
    .choices.is-open .choices__list--dropdown {
        z-index: 999999 !important;
    }
    .hidden {
        display: none !important;
    }

    /* Horarios: compacto */
    .horario-card {
        border: 1px solid rgba(0,0,0,.08);
        border-radius: .5rem;
        padding: .75rem;
        height: 100%;
    }
    .horario-card .form-label {
        margin-bottom: .25rem;
    }
    .horario-card .form-check {
        margin-bottom: .5rem;
    }
    .horario-card input[type="time"] {
        height: 32px;
        padding-top: .2rem;
        padding-bottom: .2rem;
    }

    /* Color principal rojo para Check 360 */
    .btn-primary, .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #fff !important;
    }
    .btn-primary:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5) !important;
    }
    .form-check-input:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    .form-check-input:focus {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }

    /* Modo oscuro */
    [data-theme="dark"] .choices__inner {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    [data-theme="dark"] .choices__list--dropdown {
        background-color: #171a20 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-theme="dark"] .choices__item--selectable {
        color: #e5e7eb !important;
        background-color: transparent !important;
    }
    [data-theme="dark"] .choices__item--selectable:hover,
    [data-theme="dark"] .choices__item--selectable.is-highlighted {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    [data-theme="dark"] .choices__input {
        background-color: transparent !important;
        color: #e5e7eb !important;
    }
    [data-theme="dark"] .choices__input::placeholder {
        color: #9ca3af !important;
    }
    [data-theme="dark"] .choices__placeholder {
        color: #9ca3af !important;
    }
    [data-theme="dark"] .choices__list--single .choices__item {
        color: #e5e7eb !important;
    }
    [data-theme="dark"] .horario-card {
        background-color: #2b2b2b;
        border-color: #444;
        color: #fff;
    }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
        background-color: #1f2937 !important;
        border-color: #dc3545 !important;
        color: #f3f4f6 !important;
    }
    [data-theme="dark"] .form-control::placeholder,
    [data-theme="dark"] .form-control::-webkit-input-placeholder,
    [data-theme="dark"] .form-control::-moz-placeholder,
    [data-theme="dark"] .form-control:-ms-input-placeholder,
    [data-theme="dark"] .form-control:-moz-placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>{{$restaurante ? 'Editar' : 'Nuevo'}} restaurante</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('restaurantes.lista')}}'">Restaurantes</li>
<li class="breadcrumb-item">{{$restaurante ? 'Editar' : 'Nuevo'}}</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card px-5">
        <div class="row pt-5">

            <div class="col-md-6 mb-3">
                <label class="form-label" for="nombre">Nombre (*)</label>
                <input value="{{$restaurante ? $restaurante->name : ''}}" class="form-control form-control-sm btn-square" id="nombre" placeholder="Ingrese nombre">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="email">Email</label>
                <input value="{{$restaurante ? $restaurante->email : ''}}" class="form-control form-control-sm btn-square" id="email" placeholder="Ingrese email">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="telefono">Teléfono</label>
                <input value="{{$restaurante ? $restaurante->telefono : ''}}" class="form-control form-control-sm btn-square" id="telefono" placeholder="Ingrese teléfono">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="tipo_cocina_id">Tipo de cocina</label>
                <select class="form-select form-select-sm btn-square" id="tipo_cocina_id">
                    <option value="">Sin definir</option>
                    @foreach($tipos_cocina as $tc)
                        <option value="{{$tc->id}}" {{$restaurante && $restaurante->tipo_cocina_id == $tc->id ? 'selected' : ''}}>{{$tc->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="rango_ticket_promedio">Rango ticket promedio</label>
                <select class="form-select form-select-sm btn-square" id="rango_ticket_promedio">
                    @php
                        $rtp = $restaurante ? $restaurante->rango_ticket_promedio : '';
                    @endphp
                    <option value="">Sin definir</option>
                    <option value="Bajo ($10.000 - $30.000)" {{$rtp == 'Bajo ($10.000 - $30.000)' ? 'selected' : ''}}>Bajo ($10.000 - $30.000)</option>
                    <option value="Medio ($30.000 - $60.000)" {{$rtp == 'Medio ($30.000 - $60.000)' ? 'selected' : ''}}>Medio ($30.000 - $60.000)</option>
                    <option value="Alto (Mas de $70.000)" {{$rtp == 'Alto (Mas de $70.000)' ? 'selected' : ''}}>Alto (Mas de $70.000)</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="capacidad_restaurante">Capacidad (cantidad de mesas)</label>
                <input type="number" value="{{$restaurante ? $restaurante->capacidad_restaurante : ''}}" class="form-control form-control-sm btn-square" id="capacidad_restaurante" placeholder="Ingrese cantidad de mesas">
            </div>

        </div>
    </div>

    <!-- Card de Ubicación -->
    <div class="card px-5 mt-4">
        <div class="row pt-4">
            <div class="col-12 mb-3">
                <h5>Ubicación</h5>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label" for="region_id">Región (*)</label>
                <select class="form-select form-select-sm btn-square" id="region_id">
                    <option value="">Seleccione una región</option>
                    @foreach($regiones as $region)
                        <option value="{{ $region->id }}" 
                            {{ ($restaurante && $restaurante->ciudad && $restaurante->ciudad->region_id == $region->id) ? 'selected' : '' }}>
                            {{ $region->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label" for="ciudad_id">Ciudad (*)</label>
                <select class="form-select form-select-sm btn-square" id="ciudad_id" {{ !$restaurante || !$restaurante->ciudad_id ? 'disabled' : '' }}>
                    <option value="">Seleccione una ciudad</option>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->id }}" 
                            {{ ($restaurante && $restaurante->ciudad_id == $ciudad->id) ? 'selected' : '' }}>
                            {{ $ciudad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label" for="direccion">Dirección</label>
                <input value="{{$restaurante ? $restaurante->direccion : ''}}" class="form-control form-control-sm btn-square" id="direccion" placeholder="Ingrese dirección">
            </div>
        </div>
    </div>

    @php
        $getJson = function($key) use ($opciones) {
            $val = $opciones[$key]['valor_json'] ?? null;
            // Si es null, retornar array vacío o string vacío según el contexto
            if ($val === null) {
                return [];
            }
            // Si ya es un array, retornarlo
            if (is_array($val)) {
                return $val;
            }
            // Si es un string JSON, intentar decodificarlo
            if (is_string($val)) {
                $decoded = json_decode($val, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
                // Si no es JSON válido, retornar el string como está
                return $val;
            }
            // Si es un string simple, retornarlo como está (se manejará en la vista)
            return $val;
        };
        $getTxt = function($key) use ($opciones) {
            return $opciones[$key]['valor_texto'] ?? '';
        };
    @endphp

    <div class="card px-5 mt-4">
        <div class="row pt-4">
            <div class="col-12">
                <h5>Publico objetivo</h5>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Edad promedio clientes</label>
                @php
                    $edad = $getJson('edad_promedio_clientes');
                    $edadVal = is_array($edad) ? ($edad[0] ?? '') : $edad;
                @endphp
                <select class="form-select form-select-sm btn-square" id="edad_promedio_clientes">
                    <option value="">Sin definir</option>
                    <option value="18-25" {{$edadVal == '18-25' ? 'selected' : ''}}>18-25 años</option>
                    <option value="26-35" {{$edadVal == '26-35' ? 'selected' : ''}}>26-35 años</option>
                    <option value="36-50" {{$edadVal == '36-50' ? 'selected' : ''}}>36-50 años</option>
                    <option value="50+" {{$edadVal == '50+' ? 'selected' : ''}}>Más de 50 años</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Perfil socioeconómico predominante</label>
                @php
                    $perfil = $getJson('perfil_socioeconomico_predominante');
                    $perfilVal = is_array($perfil) ? ($perfil[0] ?? '') : $perfil;
                @endphp
                <select class="form-select form-select-sm btn-square" id="perfil_socioeconomico_predominante">
                    <option value="">Sin definir</option>
                    <option value="Alto" {{$perfilVal == 'Alto' ? 'selected' : ''}}>Alto</option>
                    <option value="Medio alto" {{$perfilVal == 'Medio alto' ? 'selected' : ''}}>Medio alto</option>
                    <option value="Medio" {{$perfilVal == 'Medio' ? 'selected' : ''}}>Medio</option>
                    <option value="Medio bajo" {{$perfilVal == 'Medio bajo' ? 'selected' : ''}}>Medio bajo</option>
                    <option value="Bajo" {{$perfilVal == 'Bajo' ? 'selected' : ''}}>Bajo</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Lugar residencia principal clientes</label>
                @php
                    $res = $getJson('lugar_residencia_principal_clientes');
                    $resVal = is_array($res) ? ($res[0] ?? '') : $res;
                @endphp
                <select class="form-select form-select-sm btn-square" id="lugar_residencia_principal_clientes">
                    <option value="">Sin definir</option>
                    <option value="Residentes locales" {{$resVal == 'Residentes locales' ? 'selected' : ''}}>Residentes locales</option>
                    <option value="Visitantes otras ciudades" {{$resVal == 'Visitantes otras ciudades' ? 'selected' : ''}}>Visitantes de otras ciudades del país</option>
                    <option value="Turistas internacionales" {{$resVal == 'Turistas internacionales' ? 'selected' : ''}}>Turistas internacionales</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Motivos visita restaurante</label>
                @php
                    $mot = $getJson('motivos_visita_restaurante');
                @endphp
                <select multiple class="form-select form-select-sm btn-square" id="motivos_visita_restaurante">
                    <option value="Comida diaria" {{in_array('Comida diaria',$mot) ? 'selected' : ''}}>Comida diaria (almuerzos, cenas casuales)</option>
                    <option value="Celebraciones" {{in_array('Celebraciones',$mot) ? 'selected' : ''}}>Celebraciones (Cumpleaños, Aniversarios, Eventos)</option>
                    <option value="Reuniones negocios" {{in_array('Reuniones negocios',$mot) ? 'selected' : ''}}>Reuniones de negocios</option>
                    <option value="Turistas gastronomia local" {{in_array('Turistas gastronomia local',$mot) ? 'selected' : ''}}>Turistas buscando gastronomía local</option>
                    <option value="Otros" {{in_array('Otros',$mot) ? 'selected' : ''}}>Otros</option>
                </select>
                <input value="{{$getTxt('motivos_visita_restaurante_otros')}}" class="form-control form-control-sm btn-square mt-2 {{$getTxt('motivos_visita_restaurante_otros') ? '' : 'hidden'}}" id="motivos_visita_restaurante_otros" placeholder="Si selecciona Otros, detallar">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Estilo de vida clientes</label>
                @php
                    $est = $getJson('estilo_vida_clientes');
                @endphp
                <select multiple class="form-select form-select-sm btn-square" id="estilo_vida_clientes">
                    <option value="Jovenes universitarios" {{in_array('Jovenes universitarios',$est) ? 'selected' : ''}}>Jóvenes universitarios</option>
                    <option value="Familias con niño" {{in_array('Familias con niño',$est) ? 'selected' : ''}}>Familias con niños</option>
                    <option value="Parejas" {{in_array('Parejas',$est) ? 'selected' : ''}}>Parejas</option>
                    <option value="Grupos de amigos" {{in_array('Grupos de amigos',$est) ? 'selected' : ''}}>Grupos de amigos</option>
                    <option value="Profesionales o ejecutivos" {{in_array('Profesionales o ejecutivos',$est) ? 'selected' : ''}}>Profesionales o ejecutivos</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Comportamiento habitual clientes</label>
                @php
                    $comp = $getJson('comportamiento_habitual_clientes');
                    $compVal = is_array($comp) ? ($comp[0] ?? '') : $comp;
                @endphp
                <select class="form-select form-select-sm btn-square" id="comportamiento_habitual_clientes">
                    <option value="">Sin definir</option>
                    <option value="Reservan con anticipacion" {{$compVal == 'Reservan con anticipacion' ? 'selected' : ''}}>Reservan con anticipación</option>
                    <option value="Llegan sin reserva" {{$compVal == 'Llegan sin reserva' ? 'selected' : ''}}>Llegan sin reserva</option>
                    <option value="Prefieren comidas rapidas" {{$compVal == 'Prefieren comidas rapidas' ? 'selected' : ''}}>Prefieren comidas rápidas</option>
                    <option value="Valoran experiencia completa" {{$compVal == 'Valoran experiencia completa' ? 'selected' : ''}}>Valoran una experiencia gastronómica completa</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card px-5 mt-4">
        <div class="row pt-4">
            <div class="col-12">
                <h5>Experiencia del cliente en restaurante</h5>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Ambiente / estilo</label>
                @php
                    $amb = $getJson('ambiente_estilo');
                    $ambVal = is_array($amb) ? ($amb[0] ?? '') : $amb;
                @endphp
                <select class="form-select form-select-sm btn-square" id="ambiente_estilo">
                    <option value="">Sin definir</option>
                    <option value="Casual" {{$ambVal == 'Casual' ? 'selected' : ''}}>Casual</option>
                    <option value="Elegante" {{$ambVal == 'Elegante' ? 'selected' : ''}}>Elegante</option>
                    <option value="Tematico" {{$ambVal == 'Tematico' ? 'selected' : ''}}>Temático</option>
                    <option value="Familiar" {{$ambVal == 'Familiar' ? 'selected' : ''}}>Familiar</option>
                    <option value="Moderno" {{$ambVal == 'Moderno' ? 'selected' : ''}}>Moderno</option>
                    <option value="Rustico" {{$ambVal == 'Rustico' ? 'selected' : ''}}>Rústico</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Puntos fuertes</label>
                @php
                    $pf = $getJson('puntos_fuertes');
                @endphp
                <select multiple class="form-select form-select-sm btn-square" id="puntos_fuertes">
                    <option value="Atencion al cliente" {{in_array('Atencion al cliente',$pf) ? 'selected' : ''}}>Atención al cliente</option>
                    <option value="Calidad alimentos" {{in_array('Calidad alimentos',$pf) ? 'selected' : ''}}>Calidad de los alimentos</option>
                    <option value="Velocidad servicio" {{in_array('Velocidad servicio',$pf) ? 'selected' : ''}}>Velocidad del servicio</option>
                    <option value="Relacion precio-calidad" {{in_array('Relacion precio-calidad',$pf) ? 'selected' : ''}}>Relación precio-calidad</option>
                    <option value="Decoracion y ambiente" {{in_array('Decoracion y ambiente',$pf) ? 'selected' : ''}}>Decoración y ambiente</option>
                    <option value="Otros" {{in_array('Otros',$pf) ? 'selected' : ''}}>Otros</option>
                </select>
                <input value="{{$getTxt('puntos_fuertes_otros')}}" class="form-control form-control-sm btn-square mt-2 {{$getTxt('puntos_fuertes_otros') ? '' : 'hidden'}}" id="puntos_fuertes_otros" placeholder="Si selecciona Otros, detallar">
            </div>
        </div>
    </div>

    <div class="card px-5 mt-4">
        <div class="row pt-4">
            <div class="col-12">
                <h5>Preferencias evaluación del Mister Shopper</h5>
            </div>

            @php
                $hor = $getJson('horarios_evaluacion');
                // Asegurar que sea un array
                if (!is_array($hor)) {
                    $hor = [];
                }
            @endphp
            @php
                $dias = ['lunes'=>'Lunes','martes'=>'Martes','miercoles'=>'Miércoles','jueves'=>'Jueves','viernes'=>'Viernes','sabado'=>'Sábado','domingo'=>'Domingo'];
            @endphp
            <div class="col-12 mb-3">
                <label class="form-label">Días y horarios preferidos para la evaluación</label>
                <div class="row">
                    @foreach($dias as $k=>$label)
                        @php
                            $cfg = isset($hor[$k]) ? $hor[$k] : ['enabled'=>false,'desde'=>'','hasta'=>''];
                            if (is_string($cfg)) {
                                $cfg = json_decode($cfg, true);
                            }
                            if (!is_array($cfg)) {
                                $cfg = ['enabled'=>false,'desde'=>'','hasta'=>''];
                            }
                            $en = isset($cfg['enabled']) ? $cfg['enabled'] : false;
                            if (is_string($en)) { $en = strtolower(trim($en)); }
                            $isEnabled = ($en === true || $en === 1 || $en === '1' || $en === 'true' || $en === 'on');
                        @endphp
                        <div class="col-md-6 mb-2">
                            <div class="horario-card">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input dia-enabled" type="checkbox" id="dia_{{$k}}_enabled" data-dia="{{$k}}" {{$isEnabled ? 'checked' : ''}}>
                                    <label class="form-check-label" for="dia_{{$k}}_enabled">{{$label}}</label>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label">Desde</label>
                                        <input type="time" class="form-control form-control-sm btn-square dia-desde" id="dia_{{$k}}_desde" data-dia="{{$k}}" value="{{$cfg['desde'] ?? ''}}" {{$isEnabled ? '' : 'disabled'}}>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Hasta</label>
                                        <input type="time" class="form-control form-control-sm btn-square dia-hasta" id="dia_{{$k}}_hasta" data-dia="{{$k}}" value="{{$cfg['hasta'] ?? ''}}" {{$isEnabled ? '' : 'disabled'}}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @php
                $prot = $getJson('protocolos_internos');
                // Asegurar que sea un array con estructura correcta
                if (!is_array($prot)) {
                    $prot = ['tiene' => null, 'detalle' => ''];
                }
                // Normalizar el valor de 'tiene'
                $protTiene = $prot['tiene'] ?? null;
                $protTieneBool = ($protTiene === true || $protTiene === 1 || $protTiene === '1' || $protTiene === 'true');
                $protDetalle = $prot['detalle'] ?? '';
            @endphp
            <div class="col-md-6 mb-3">
                <label class="form-label">¿Existen protocolos internos que el Mister Shopper deba conocer?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="protocolos_internos_tiene" id="protocolos_si" value="1" {{$protTieneBool ? 'checked' : ''}}>
                    <label class="form-check-label" for="protocolos_si">Sí</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="protocolos_internos_tiene" id="protocolos_no" value="0" {{!$protTieneBool ? 'checked' : ''}}>
                    <label class="form-check-label" for="protocolos_no">No</label>
                </div>
                <div id="protocolos_detalle_container" class="mt-2 {{$protTieneBool ? '' : 'hidden'}}">
                    <label class="form-label">Detalle de protocolos</label>
                    <small class="text-muted d-block mb-2">Por favor, detalle los protocolos internos que el Mister Shopper debe conocer para realizar la evaluación correctamente. Incluya información sobre procedimientos, políticas, o cualquier instrucción especial que deba seguir.</small>
                    <textarea class="form-control form-control-sm btn-square" id="protocolos_internos_detalle" placeholder="Ingrese los detalles de los protocolos internos...">{{$protDetalle}}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card px-5 mt-4">
        <div class="row pt-4">
            <div class="col-12">
                <h5>Observaciones adicionales</h5>
                <p>Por favor, indique cualquier información relevante que considere importante para personalizar nuestra evaluación:</p>
            </div>
            <div class="col-12 mb-3">
                <textarea class="form-control form-control-sm btn-square" id="observaciones_adicionales" rows="4" placeholder="Ingrese cualquier información relevante que considere importante para personalizar nuestra evaluación...">{{$getTxt('observaciones_adicionales')}}</textarea>
            </div>
        </div>
    </div>

    @if(!$restaurante)
    <div class="card px-5 mt-4 border-primary">
        <div class="row pt-4">
            <div class="col-12">
                <h5 class="text-primary">Usuario Administrador (Restaurante)</h5>
                <p>Ingrese los datos de la persona que administrará el panel del restaurante.</p>
            </div>
            <div class="col-md-6 mb-4">
                <label class="form-label" for="admin_name">Nombre completo (*)</label>
                <input class="form-control form-control-sm btn-square" id="admin_name" placeholder="Ej: Juan Pérez">
            </div>
            <div class="col-md-6 mb-4">
                <label class="form-label" for="admin_email">Correo electrónico (*)</label>
                <input type="email" class="form-control form-control-sm btn-square" id="admin_email" placeholder="Ej: admin@restaurante.cl">
                <small class="text-muted">Este correo se usará para iniciar sesión.</small>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12 d-flex mt-4 mb-5">
        <button class="btn btn-primary my-auto ms-auto me-0 submitButton" type="button">{{$restaurante ? 'Actualizar información' : 'Crear restaurante'}}</button>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js"></script>
<script>
    // Choices.js instances
    var choicesInstances = {};

    // Funciones auxiliares para obtener valores
    function getChoicesValue(selectId) {
        var choices = choicesInstances[selectId];
        if(choices) {
            var val = choices.getValue(true);
            return Array.isArray(val) ? val : (val ? [val] : []);
        }
        return $('#'+selectId).val() || [];
    }

    function getChoicesSingleValue(selectId) {
        var choices = choicesInstances[selectId];
        if(choices) {
            var val = choices.getValue(true);
            // Para selects simples, Choices.js devuelve un string o array con un elemento
            if(Array.isArray(val)) {
                return val.length > 0 ? val[0] : '';
            }
            return val || '';
        }
        // Fallback: obtener del select original
        return $('#'+selectId).val() || '';
    }

    $(document).on('click','.submitButton', function() {
        if(!validar()) return false;
        $('.submitButton').prop('disabled',true);

        var url = "{{$restaurante ? route('restaurantes.update') : route('restaurantes.store')}}";
        $.ajax({
            url:url,
            method:'POST',
            data:{
                nombre:$('#nombre').val(),
                email:$('#email').val(),
                telefono:$('#telefono').val(),
                direccion:$('#direccion').val(),
                ciudad_id:getChoicesSingleValue('ciudad_id'),
                tipo_cocina_id:getChoicesSingleValue('tipo_cocina_id'),
                rango_ticket_promedio:getChoicesSingleValue('rango_ticket_promedio'),
                capacidad_restaurante:$('#capacidad_restaurante').val(),
                opciones: construirOpciones(),
                admin_name: $('#admin_name').val() || '',
                admin_email: $('#admin_email').val() || '',
                id:"{{$restaurante ? encrypt($restaurante->id) : ''}}"
            },
            success:function(res) {
                if(res.estado == 200) {
                    window.location.href = "{{route('restaurantes.lista')}}";
                } else {
                    $('.submitButton').prop('disabled',false);
                    notify('Error',res.mensaje || 'Error','danger');
                }
            }
        })
    });

    function construirOpciones() {
        var horarios = {};
        $('.dia-enabled').each(function(){
            var dia = $(this).data('dia');
            horarios[dia] = {
                enabled: $(this).is(':checked'),
                desde: $('#dia_'+dia+'_desde').val(),
                hasta: $('#dia_'+dia+'_hasta').val(),
            };
        });

        var protocolosTiene = $('input[name="protocolos_internos_tiene"]:checked').val();
        var protocolos = {
            tiene: (protocolosTiene === undefined ? null : (protocolosTiene == '1')),
            detalle: $('#protocolos_internos_detalle').val(),
        };

        return {
            edad_promedio_clientes: getChoicesSingleValue('edad_promedio_clientes'),
            perfil_socioeconomico_predominante: getChoicesSingleValue('perfil_socioeconomico_predominante'),
            lugar_residencia_principal_clientes: getChoicesSingleValue('lugar_residencia_principal_clientes'),
            motivos_visita_restaurante: getChoicesValue('motivos_visita_restaurante'),
            motivos_visita_restaurante_otros: $('#motivos_visita_restaurante_otros').val(),
            estilo_vida_clientes: getChoicesValue('estilo_vida_clientes'),
            comportamiento_habitual_clientes: getChoicesSingleValue('comportamiento_habitual_clientes'),
            ambiente_estilo: getChoicesSingleValue('ambiente_estilo'),
            puntos_fuertes: getChoicesValue('puntos_fuertes'),
            puntos_fuertes_otros: $('#puntos_fuertes_otros').val(),
            horarios_evaluacion: horarios,
            protocolos_internos: protocolos,
            observaciones_adicionales: $('#observaciones_adicionales').val(),
        };
    }

    function initChoices(selectId, isMultiple = false) {
        var element = document.getElementById(selectId);
        if (!element) return;

        var choices = new Choices(element, {
            removeItemButton: isMultiple,
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: isMultiple ? 'Seleccione opciones...' : 'Seleccione...',
            searchPlaceholderValue: 'Buscar...',
            shouldSort: false,
            position: 'bottom',
            classNames: {
                containerOuter: 'choices',
                containerInner: 'choices__inner',
                input: 'choices__input',
                inputCloned: 'choices__input--cloned',
                list: 'choices__list',
                listItems: 'choices__list--multiple',
                listSingle: 'choices__list--single',
                listDropdown: 'choices__list--dropdown',
                item: 'choices__item',
                itemSelectable: 'choices__item--selectable',
                itemDisabled: 'choices__item--disabled',
                itemChoice: 'choices__item--choice',
                placeholder: 'choices__placeholder',
                group: 'choices__group',
                groupHeading: 'choices__heading',
                button: 'choices__button',
                activeState: 'is-active',
                focusState: 'is-focused',
                openState: 'is-open',
                disabledState: 'is-disabled',
                highlightedState: 'is-highlighted',
                selectedState: 'is-selected',
                flippedState: 'is-flipped',
                loadingState: 'is-loading',
                noResults: 'has-no-results',
                noChoices: 'has-no-choices'
            }
        });

        choicesInstances[selectId] = choices;
        
        // Asegurar z-index alto cuando se abre
        choices.passedElement.element.addEventListener('showDropdown', function() {
            var dropdown = element.closest('.choices').querySelector('.choices__list--dropdown');
            if(dropdown) {
                // Cerrar cualquier otro dropdown abierto primero
                var allDropdowns = document.querySelectorAll('.choices__list--dropdown.is-active');
                allDropdowns.forEach(function(otherDropdown) {
                    if (otherDropdown !== dropdown) {
                        var otherChoices = otherDropdown.closest('.choices');
                        if (otherChoices) {
                            var otherInstance = choicesInstances[Object.keys(choicesInstances).find(key => {
                                var inst = choicesInstances[key];
                                return inst && inst.passedElement.element === otherChoices.querySelector('select');
                            })];
                            if (otherInstance) {
                                otherInstance.hideDropdown();
                            }
                        }
                    }
                });
                
                // Asegurar z-index alto en el dropdown y su contenedor
                var choicesContainer = element.closest('.choices');
                if (choicesContainer) {
                    choicesContainer.style.zIndex = '999999';
                }
                
                // Encontrar el card padre y asegurar que tenga z-index alto
                var parentCard = dropdown.closest('.card');
                if (parentCard) {
                    var originalCardZIndex = parentCard.style.zIndex || '';
                    parentCard.style.zIndex = '999998';
                    parentCard.setAttribute('data-original-card-z-index', originalCardZIndex);
                }
                
                // Cambiar overflow de todos los contenedores padres a visible
                var containersWithOverflow = [];
                var current = dropdown.parentElement;
                while (current && current !== document.body) {
                    var overflow = window.getComputedStyle(current).overflow;
                    var overflowX = window.getComputedStyle(current).overflowX;
                    var overflowY = window.getComputedStyle(current).overflowY;
                    if (overflow === 'hidden' || overflowX === 'hidden' || overflowY === 'hidden' || 
                        overflow === 'auto' || overflowX === 'auto' || overflowY === 'auto' ||
                        overflow === 'scroll' || overflowX === 'scroll' || overflowY === 'scroll') {
                        containersWithOverflow.push({
                            tag: current.tagName,
                            className: current.className,
                            overflow: overflow,
                            overflowX: overflowX,
                            overflowY: overflowY
                        });
                        // Cambiar overflow a visible temporalmente
                        var originalOverflow = current.style.overflow;
                        var originalOverflowX = current.style.overflowX;
                        var originalOverflowY = current.style.overflowY;
                        current.style.overflow = 'visible';
                        current.style.overflowX = 'visible';
                        current.style.overflowY = 'visible';
                        if (!current.hasAttribute('data-original-overflow')) {
                            current.setAttribute('data-original-overflow', originalOverflow || '');
                            current.setAttribute('data-original-overflow-x', originalOverflowX || '');
                            current.setAttribute('data-original-overflow-y', originalOverflowY || '');
                        }
                    }
                    current = current.parentElement;
                }
                
                // Usar requestAnimationFrame para aplicar estilos después de que Choices.js termine
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        // Asegurar z-index alto
                        dropdown.style.setProperty('z-index', '999999', 'important');
                        
                        // Asegurar que el contenedor choices tenga z-index alto también
                        if (choicesContainer) {
                            choicesContainer.style.setProperty('z-index', '999999', 'important');
                        }
                    });
                });
            }
        });
        
        // Restaurar el dropdown y los contenedores cuando se cierra
        choices.passedElement.element.addEventListener('hideDropdown', function() {
            // Restaurar z-index del card padre
            var parentCard = element.closest('.card');
            if (parentCard && parentCard.hasAttribute('data-original-card-z-index')) {
                var originalCardZIndex = parentCard.getAttribute('data-original-card-z-index');
                parentCard.style.zIndex = originalCardZIndex;
                parentCard.removeAttribute('data-original-card-z-index');
            }
            
            // Restaurar overflow de todos los contenedores que fueron modificados
            var allContainers = document.querySelectorAll('[data-original-overflow]');
            allContainers.forEach(function(container) {
                var originalOverflow = container.getAttribute('data-original-overflow') || '';
                var originalOverflowX = container.getAttribute('data-original-overflow-x') || '';
                var originalOverflowY = container.getAttribute('data-original-overflow-y') || '';
                container.style.overflow = originalOverflow;
                container.style.overflowX = originalOverflowX;
                container.style.overflowY = originalOverflowY;
                container.removeAttribute('data-original-overflow');
                container.removeAttribute('data-original-overflow-x');
                container.removeAttribute('data-original-overflow-y');
            });
            
            // Restaurar z-index del contenedor choices
            var choicesContainer = element.closest('.choices');
            if (choicesContainer) {
                choicesContainer.style.zIndex = '';
            }
        });
        
        return choices;
    }

    function toggleOtros(selectId, inputId) {
        var choices = choicesInstances[selectId];
        if (!choices) {
            // Fallback para selects normales
            var selected = $('#'+selectId).val() || [];
            if(Array.isArray(selected) ? selected.includes('Otros') : selected == 'Otros') {
                $('#'+inputId).removeClass('hidden');
            } else {
                $('#'+inputId).addClass('hidden').val('');
            }
            return;
        }

        var selected = choices.getValue(true);
        if(Array.isArray(selected) ? selected.includes('Otros') : selected == 'Otros') {
            $('#'+inputId).removeClass('hidden');
        } else {
            $('#'+inputId).addClass('hidden').val('');
        }
    }

    // Cargar ciudades cuando se selecciona una región
    function cargarCiudades() {
        var regionId = getChoicesSingleValue('region_id');
        var ciudadChoices = choicesInstances['ciudad_id'];
        var ciudadSelect = $('#ciudad_id');
        
        if (!regionId) {
            if (ciudadChoices) {
                ciudadChoices.destroy();
                choicesInstances['ciudad_id'] = null;
            }
            ciudadSelect.html('<option value="">Seleccione una ciudad</option>').prop('disabled', true);
            initChoices('ciudad_id', false);
            return;
        }
        
        // Mostrar loading
        if (ciudadChoices) {
            ciudadChoices.destroy();
            choicesInstances['ciudad_id'] = null;
        }
        ciudadSelect.html('<option value="">Cargando ciudades...</option>').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("restaurantes.get_ciudades") }}',
            method: 'POST',
            data: {
                region_id: regionId,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if(res.estado == 200 && res.ciudades) {
                    var options = '<option value="">Seleccione una ciudad</option>';
                    $.each(res.ciudades, function(index, ciudad) {
                        options += '<option value="' + ciudad.id + '">' + ciudad.nombre + '</option>';
                    });
                    ciudadSelect.html(options).prop('disabled', false);
                    
                    // Reinicializar Choices.js para el select de ciudad
                    setTimeout(function() {
                        initChoices('ciudad_id', false);
                        
                        // Si estamos editando y hay una ciudad pre-seleccionada, mantenerla si está en la lista
                        @if($restaurante && $restaurante->ciudad_id)
                            setTimeout(function() {
                                var ciudadId = {{ $restaurante->ciudad_id }};
                                var ciudadChoicesNew = choicesInstances['ciudad_id'];
                                if(ciudadChoicesNew) {
                                    ciudadChoicesNew.setChoiceByValue(ciudadId.toString());
                                }
                            }, 100);
                        @endif
                    }, 50);
                } else {
                    ciudadSelect.html('<option value="">No se encontraron ciudades</option>').prop('disabled', true);
                    initChoices('ciudad_id', false);
                }
            },
            error: function() {
                ciudadSelect.html('<option value="">Error al cargar ciudades</option>').prop('disabled', true);
                initChoices('ciudad_id', false);
                notify('Error', 'Error al cargar las ciudades', 'danger');
            }
        });
    }

    $(function(){
        // Inicializar Choices.js para todos los selects
        // Selects simples
        initChoices('tipo_cocina_id', false);
        initChoices('rango_ticket_promedio', false);
        initChoices('region_id', false);
        initChoices('edad_promedio_clientes', false);
        initChoices('perfil_socioeconomico_predominante', false);
        initChoices('lugar_residencia_principal_clientes', false);
        initChoices('comportamiento_habitual_clientes', false);
        initChoices('ambiente_estilo', false);
        
        // Listener para cambios en región usando Choices.js
        setTimeout(function() {
            var regionChoices = choicesInstances['region_id'];
            if (regionChoices) {
                regionChoices.passedElement.element.addEventListener('change', function() {
                    cargarCiudades();
                });
            }
            
            // Si hay una región seleccionada al cargar la página, cargar las ciudades
            @if($restaurante && $restaurante->ciudad && $restaurante->ciudad->region_id)
                setTimeout(function() {
                    cargarCiudades();
                }, 300);
            @endif
        }, 200);

        // Multiselects
        initChoices('motivos_visita_restaurante', true);
        initChoices('estilo_vida_clientes', true);
        initChoices('puntos_fuertes', true);

        // “Otros” condicional - inicializar estado
        setTimeout(function(){
            toggleOtros('motivos_visita_restaurante','motivos_visita_restaurante_otros');
            toggleOtros('puntos_fuertes','puntos_fuertes_otros');
        }, 100);

        // Listeners para "Otros" usando eventos de Choices.js
        var motivosChoices = choicesInstances['motivos_visita_restaurante'];
        if(motivosChoices) {
            motivosChoices.passedElement.element.addEventListener('addItem', function(event) {
                toggleOtros('motivos_visita_restaurante','motivos_visita_restaurante_otros');
                if (event.detail.value === 'Otros') {
                    setTimeout(function() { $('#motivos_visita_restaurante_otros').focus(); }, 100);
                }
            });
            motivosChoices.passedElement.element.addEventListener('removeItem', function(event) {
                toggleOtros('motivos_visita_restaurante','motivos_visita_restaurante_otros');
            });
        }

        var puntosChoices = choicesInstances['puntos_fuertes'];
        if(puntosChoices) {
            puntosChoices.passedElement.element.addEventListener('addItem', function(event) {
                toggleOtros('puntos_fuertes','puntos_fuertes_otros');
                if (event.detail.value === 'Otros') {
                    setTimeout(function() { $('#puntos_fuertes_otros').focus(); }, 100);
                }
            });
            puntosChoices.passedElement.element.addEventListener('removeItem', function(event) {
                toggleOtros('puntos_fuertes','puntos_fuertes_otros');
            });
        }

        // Protocolos internos: mostrar/ocultar detalle
        function toggleProtocolosDetalle() {
            var tiene = $('input[name="protocolos_internos_tiene"]:checked').val();
            if(tiene == '1') {
                $('#protocolos_detalle_container').removeClass('hidden');
            } else {
                $('#protocolos_detalle_container').addClass('hidden');
                // Solo limpiar el valor si se cambia a "No", no al cargar la página
                if(tiene == '0') {
                    $('#protocolos_internos_detalle').val('');
                }
            }
        }
        $('input[name="protocolos_internos_tiene"]').on('change', function(){
            toggleProtocolosDetalle();
        });
        // Inicializar el estado después de que se cargue el DOM
        setTimeout(function() {
            toggleProtocolosDetalle();
        }, 100);

        // Habilitar/deshabilitar campos de horario según el checkbox
        function toggleHorarioDia(dia) {
            var enabled = $('#dia_' + dia + '_enabled').is(':checked');
            $('#dia_' + dia + '_desde').prop('disabled', !enabled);
            $('#dia_' + dia + '_hasta').prop('disabled', !enabled);
            
            // Limpiar valores si se desactiva
            if (!enabled) {
                $('#dia_' + dia + '_desde').val('');
                $('#dia_' + dia + '_hasta').val('');
            }
        }

        // Inicializar estado de todos los días según su estado actual (después de que se cargue el HTML)
        // Usar setTimeout para asegurar que el DOM esté completamente renderizado
        setTimeout(function() {
            $('.dia-enabled').each(function() {
                var dia = $(this).data('dia');
                // Solo aplicar el estado actual, no forzar ningún cambio
                var isChecked = $(this).is(':checked');
                $('#dia_' + dia + '_desde').prop('disabled', !isChecked);
                $('#dia_' + dia + '_hasta').prop('disabled', !isChecked);
            });
        }, 100);

        // Escuchar cambios en los checkboxes de días
        $(document).on('change', '.dia-enabled', function() {
            var dia = $(this).data('dia');
            toggleHorarioDia(dia);
        });
    });

    function validar() {
        if($('#nombre').val() == "") {
            notify('Advertencia','Debe completar el campo nombre','danger');
            return false;
        }
        if(getChoicesSingleValue('region_id') == "") {
            notify('Advertencia','Debe seleccionar una región','danger');
            return false;
        }
        if(getChoicesSingleValue('ciudad_id') == "") {
            notify('Advertencia','Debe seleccionar una ciudad','danger');
            return false;
        }

        @if(!$restaurante)
        if($('#admin_name').val() == "") {
            notify('Advertencia','Debe completar el nombre del administrador','danger');
            return false;
        }
        if($('#admin_email').val() == "") {
            notify('Advertencia','Debe completar el correo del administrador','danger');
            return false;
        }
        // Validar formato email básico
        var email = $('#admin_email').val();
        var re = /\S+@\S+\.\S+/;
        if(!re.test(email)) {
            notify('Advertencia','El formato del correo del administrador no es válido','danger');
            return false;
        }
        @endif

        return true;
    }
</script>
@endsection


