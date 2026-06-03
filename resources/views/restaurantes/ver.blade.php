@extends('layouts.master')
@section('title', 'Ver Restaurante: ' . $restaurante->name)

@section('css')
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<style>
    .card-header-bg {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 0.2rem;
    }
    .info-value {
        font-size: 1rem;
        color: #212529;
        margin-bottom: 1rem;
    }
    .badge-soft {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        font-weight: 500;
        border: 1px solid rgba(13, 110, 253, 0.2);
    }
    .horario-badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.85em;
        font-weight: 600;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        background-color: #198754;
        margin: 2px;
    }
    .horario-badge.disabled {
        background-color: #6c757d;
        opacity: 0.6;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Detalle de Restaurante</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('restaurantes.lista')}}'">Restaurantes</li>
<li class="breadcrumb-item">Ver</li>
@endsection

@php
    $getJson = function($key) use ($opciones) {
        $val = $opciones[$key]['valor_json'] ?? null;
        if ($val === null) return [];
        if (is_array($val)) return $val;
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            return $val;
        }
        return $val;
    };
    $getTxt = function($key) use ($opciones) {
        return $opciones[$key]['valor_texto'] ?? '';
    };

    $logoImg = $restaurante->logo ? asset($restaurante->logo) : asset("assets/images/user/user.png");
@endphp

@section('content')
<div class="container-fluid">
    
    <!-- Encabezado Principal -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $logoImg }}" alt="Logo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #dee2e6; margin-right: 20px;">
            <div>
                <h3 class="mb-1">{{ $restaurante->name }}</h3>
                <p class="text-muted mb-2"><i class="icofont icofont-location-pin"></i> {{ $restaurante->direccion }}, {{ optional($restaurante->ciudad)->nombre }}, {{ optional(optional($restaurante->ciudad)->region)->nombre }}</p>
                <div>
                    @if($restaurante->aprobado == 0)
                        <span class="badge badge-warning">Pendiente de Aprobación</span>
                    @elseif($restaurante->estado == 1)
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                    <span class="badge badge-primary ms-2">{{ optional($restaurante->tipoCocina)->name }}</span>
                    @if($restaurante->plan_activo)
                        <span class="badge badge-info ms-2">Plan Activo</span>
                    @endif
                </div>
            </div>
            <div class="ms-auto">
                @if($restaurante->aprobado != 0)
                    <a class="btn btn-outline-primary" href="{{ route('restaurantes.editar', ['id' => encrypt($restaurante->id)]) }}"><i class="icofont icofont-pen"></i> Editar</a>
                @endif
                <a class="btn btn-secondary ms-2" href="{{ route('restaurantes.lista') }}">Volver al listado</a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda -->
        <div class="col-lg-6">
            
            <!-- Contacto y Admin -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header card-header-bg py-3">
                    <h5 class="mb-0"><i class="icofont icofont-ui-user"></i> Contacto y Administración</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="info-label">Email Principal</div>
                            <div class="info-value">{{ $restaurante->email ?? 'No registrado' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-label">Teléfono</div>
                            <div class="info-value">{{ $restaurante->telefono ?? 'No registrado' }}</div>
                        </div>
                        <div class="col-12 mt-2 border-top pt-3">
                            <div class="info-label text-primary">Administrador del Panel</div>
                            <div class="info-value mb-0">
                                <strong>{{ optional($restaurante->admin)->name ?? 'No asignado' }}</strong><br>
                                <span class="text-muted">{{ optional($restaurante->admin)->email ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Características y Redes -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header card-header-bg py-3">
                    <h5 class="mb-0"><i class="icofont icofont-info-square"></i> Características y Redes</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="info-label">Capacidad (Mesas)</div>
                            <div class="info-value">{{ $restaurante->capacidad_restaurante ?? 'No definida' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-label">Ticket Promedio</div>
                            <div class="info-value">{{ $restaurante->rango_ticket_promedio ?? 'No definido' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-label">Descuento Mistery Shopper</div>
                            <div class="info-value text-success fw-bold">{{ $restaurante->porcentaje_descuento ?? 50 }}%</div>
                        </div>
                        
                        <div class="col-12 mt-2 border-top pt-3">
                            <div class="info-label">Redes Sociales</div>
                            <div class="mt-2">
                                @if($restaurante->social_facebook)
                                    <a href="{{ $restaurante->social_facebook }}" target="_blank" class="btn btn-sm btn-outline-primary me-2 mb-2"><i class="icofont icofont-social-facebook"></i> Facebook</a>
                                @endif
                                @if($restaurante->social_instagram)
                                    <a href="{{ $restaurante->social_instagram }}" target="_blank" class="btn btn-sm btn-outline-danger me-2 mb-2"><i class="icofont icofont-social-instagram"></i> Instagram</a>
                                @endif
                                @if($restaurante->social_tiktok)
                                    <a href="{{ $restaurante->social_tiktok }}" target="_blank" class="btn btn-sm btn-outline-dark me-2 mb-2"><i class="icofont icofont-brand-tiktok"></i> TikTok</a>
                                @endif
                                @if(!$restaurante->social_facebook && !$restaurante->social_instagram && !$restaurante->social_tiktok)
                                    <span class="text-muted">Ninguna red social registrada.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios y Peak -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header card-header-bg py-3">
                    <h5 class="mb-0"><i class="icofont icofont-clock-time"></i> Horarios de Evaluación y Peak</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="info-label">Días preferidos para evaluación</div>
                            <div class="mt-2">
                                @php
                                    $horarios = $getJson('horarios_evaluacion');
                                    $diasMap = ['lunes'=>'Lunes','martes'=>'Martes','miercoles'=>'Miércoles','jueves'=>'Jueves','viernes'=>'Viernes','sabado'=>'Sábado','domingo'=>'Domingo'];
                                @endphp
                                @if(is_array($horarios) && count($horarios) > 0)
                                    @foreach($diasMap as $k => $label)
                                        @php
                                            $cfg = $horarios[$k] ?? null;
                                            if (is_string($cfg)) $cfg = json_decode($cfg, true);
                                            $en = $cfg['enabled'] ?? false;
                                            if (is_string($en)) $en = strtolower(trim($en));
                                            $isEnabled = ($en === true || $en === 1 || $en === '1' || $en === 'true' || $en === 'on');
                                        @endphp
                                        @if($isEnabled)
                                            <span class="horario-badge">{{ $label }}: {{ $cfg['desde'] ?? '--:--' }} a {{ $cfg['hasta'] ?? '--:--' }}</span>
                                        @else
                                            <span class="horario-badge disabled">{{ $label }}: Cerrado/No eval.</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-muted">No hay horarios de evaluación configurados.</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mt-2 border-top pt-3">
                            <div class="info-label">Horarios Peak por día</div>
                            <div class="mt-2">
                                @php
                                    $peaks = $restaurante->horario_peak;
                                    if (is_string($peaks)) $peaks = json_decode($peaks, true);
                                @endphp
                                @if(is_array($peaks) && count($peaks) > 0)
                                    <ul class="list-unstyled">
                                    @foreach($diasMap as $k => $label)
                                        @php
                                            $p = $peaks[$k] ?? null;
                                        @endphp
                                        @if($p && !empty($p['desde']) && !empty($p['hasta']))
                                            <li class="mb-1">
                                                <strong>{{ $label }}:</strong> {{ $p['desde'] }} - {{ $p['hasta'] }} 
                                                @if(isset($p['ocupa_90']) && $p['ocupa_90'])
                                                    <span class="badge badge-warning text-dark ms-2"><i class="icofont icofont-fire-burn"></i> 90%+ Ocupación</span>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">No se definieron horarios peak.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Columna Derecha -->
        <div class="col-lg-6">
            
            <!-- Público Objetivo -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header card-header-bg py-3">
                    <h5 class="mb-0"><i class="icofont icofont-users-alt-2"></i> Público Objetivo y Experiencia</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="info-label">Edad Promedio</div>
                            @php $edad = $getJson('edad_promedio_clientes'); $edadVal = is_array($edad) ? ($edad[0] ?? '') : $edad; @endphp
                            <div class="info-value">{{ $edadVal ?: 'No definido' }}</div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="info-label">Perfil Socioeconómico</div>
                            @php $perfil = $getJson('perfil_socioeconomico_predominante'); $perfilVal = is_array($perfil) ? ($perfil[0] ?? '') : $perfil; @endphp
                            <div class="info-value">{{ $perfilVal ?: 'No definido' }}</div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="info-label">Residencia de clientes</div>
                            @php $res = $getJson('lugar_residencia_principal_clientes'); $resVal = is_array($res) ? ($res[0] ?? '') : $res; @endphp
                            <div class="info-value">{{ $resVal ?: 'No definido' }}</div>
                        </div>
                        <div class="col-12 mb-3 border-top pt-3">
                            <div class="info-label">Motivos de visita</div>
                            <div class="mt-1">
                                @php $mot = $getJson('motivos_visita_restaurante'); @endphp
                                @if(is_array($mot) && count($mot) > 0)
                                    @foreach($mot as $m)
                                        @if($m === 'Otros')
                                            <span class="badge badge-soft me-1 mb-1">Otros: {{ $getTxt('motivos_visita_restaurante_otros') }}</span>
                                        @else
                                            <span class="badge badge-soft me-1 mb-1">{{ $m }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-muted small">No definidos</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mb-3 border-top pt-3">
                            <div class="info-label">Estilo de vida</div>
                            <div class="mt-1">
                                @php $est = $getJson('estilo_vida_clientes'); @endphp
                                @if(is_array($est) && count($est) > 0)
                                    @foreach($est as $e)
                                        <span class="badge badge-soft me-1 mb-1">{{ $e }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">No definidos</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 border-top pt-3">
                            <div class="info-label">Comportamiento Habitual</div>
                            @php $comp = $getJson('comportamiento_habitual_clientes'); $compVal = is_array($comp) ? ($comp[0] ?? '') : $comp; @endphp
                            <div class="info-value mb-0">{{ $compVal ?: 'No definido' }}</div>
                        </div>
                        <div class="col-sm-6 border-top pt-3">
                            <div class="info-label">Ambiente / Estilo</div>
                            @php $amb = $getJson('ambiente_estilo'); $ambVal = is_array($amb) ? ($amb[0] ?? '') : $amb; @endphp
                            <div class="info-value mb-0">{{ $ambVal ?: 'No definido' }}</div>
                        </div>
                        <div class="col-12 mt-3 border-top pt-3">
                            <div class="info-label">Puntos Fuertes</div>
                            <div class="mt-1">
                                @php $pf = $getJson('puntos_fuertes'); @endphp
                                @if(is_array($pf) && count($pf) > 0)
                                    @foreach($pf as $p)
                                        @if($p === 'Otros')
                                            <span class="badge bg-success me-1 mb-1">Otros: {{ $getTxt('puntos_fuertes_otros') }}</span>
                                        @else
                                            <span class="badge bg-success me-1 mb-1">{{ $p }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-muted small">No definidos</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Protocolos y Observaciones -->
            <div class="card mb-4 shadow-sm border-warning">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 text-warning"><i class="icofont icofont-file-document"></i> Protocolos y Observaciones</h5>
                </div>
                <div class="card-body pt-0">
                    @php
                        $prot = $getJson('protocolos_internos');
                        if (!is_array($prot)) $prot = ['tiene' => null, 'detalle' => ''];
                        $protTiene = $prot['tiene'] ?? null;
                        $protTieneBool = ($protTiene === true || $protTiene === 1 || $protTiene === '1' || $protTiene === 'true');
                    @endphp
                    <div class="mb-3">
                        <div class="info-label">Protocolos Internos para Shopper</div>
                        @if($protTieneBool)
                            <div class="alert alert-light-warning mt-2 mb-0">
                                <i class="icofont icofont-warning"></i> {{ $prot['detalle'] ?? 'Sí, pero sin detalle.' }}
                            </div>
                        @else
                            <div class="info-value text-muted">No existen protocolos especiales.</div>
                        @endif
                    </div>
                    
                    <div class="mt-2 border-top pt-3">
                        <div class="info-label">Observaciones Adicionales</div>
                        <p class="mb-0">{{ $getTxt('observaciones_adicionales') ?: 'Sin observaciones.' }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Fila Carta y Fotos Completa -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-bg py-3">
                    <h5 class="mb-0"><i class="icofont icofont-culinary"></i> Carta e Imágenes del Local</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Carta -->
                        <div class="col-md-6 border-end">
                            <div class="info-label mb-3">Carta del Restaurante</div>
                            @if($restaurante->carta_tipo === 'url' && $restaurante->carta_url)
                                <div class="alert alert-light-primary text-center">
                                    <i class="icofont icofont-link" style="font-size: 2rem;"></i><br>
                                    <a href="{{ $restaurante->carta_url }}" target="_blank" class="fw-bold d-block mt-2">Ver Carta Digital</a>
                                </div>
                            @elseif($restaurante->carta_tipo === 'imagenes')
                                @php
                                    $cartaImgs = $restaurante->carta_imagenes;
                                    if(is_string($cartaImgs)) $cartaImgs = json_decode($cartaImgs, true);
                                @endphp
                                @if(is_array($cartaImgs) && count($cartaImgs) > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($cartaImgs as $img)
                                            <a href="{{ asset($img) }}" target="_blank">
                                                <img src="{{ asset($img) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No hay imágenes de la carta.</p>
                                @endif
                            @else
                                <p class="text-muted">No se ha registrado información de la carta.</p>
                            @endif
                        </div>
                        
                        <!-- Imágenes Locales -->
                        <div class="col-md-6 ps-md-4">
                            <div class="info-label mb-3">Fotos del Local</div>
                            @php
                                $localImgs = $restaurante->imagenes;
                                if(is_string($localImgs)) $localImgs = json_decode($localImgs, true);
                            @endphp
                            @if(is_array($localImgs) && count($localImgs) > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($localImgs as $img)
                                        <a href="{{ asset($img) }}" target="_blank">
                                            <img src="{{ asset($img) }}" class="img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No hay fotos del local registradas.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection