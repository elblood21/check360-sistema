@extends('layouts.master')
@section('title', 'Detalle de Visita')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<style>
    /* Card design for shopper details */
    .rest-cover-card {
        position: relative;
        height: 200px;
        background-size: cover;
        background-position: center;
        border-radius: 1.25rem 1.25rem 0 0;
    }
    .rest-cover-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.7) 100%);
        border-radius: 1.25rem 1.25rem 0 0;
    }
    .rest-logo-overlap {
        width: 90px;
        height: 90px;
        border-radius: 1rem;
        border: 2px solid rgba(0,0,0,0.08);
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        object-fit: cover;
        margin-top: -45px;
        z-index: 10;
        position: relative;
        margin-left: 20px;
    }
    
    /* Premium assistance limit card */
    .assist-limit-card {
        border: 1px solid rgba(220, 53, 69, 0.12);
        border-left: 4px solid #dc3545;
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.06) 0%, rgba(220, 53, 69, 0.01) 100%);
        border-radius: 14px;
        padding: 18px 22px;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.04);
    }
    
    /* Next Action / And Now What Card */
    .action-now-card {
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        padding: 24px;
        position: relative;
    }
    .action-now-header {
        border-bottom: 1.5px solid #f1f3f5;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    /* Soft primary-colored background circles for list items */
    .bg-primary-soft {
        background-color: rgba(0, 117, 205, 0.08) !important;
    }
    
    /* Gold Voucher design */
    .coupon-gold-card {
        background: linear-gradient(135deg, #fcf3db 0%, #f7e1b5 100%);
        border: 2px dashed #d4af37;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(212,175,55,0.25);
        position: relative;
        overflow: hidden;
    }
    .coupon-gold-card::before, .coupon-gold-card::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background: #f8f9fa;
        border-radius: 50%;
        top: 50%;
        margin-top: -10px;
        z-index: 5;
    }
    .coupon-gold-card::before { left: -10px; border-right: 1.5px dashed #d4af37; }
    .coupon-gold-card::after { right: -10px; border-left: 1.5px dashed #d4af37; }

    /* Dark Mode overrides */
    [data-theme="dark"] .rest-logo-overlap {
        background: #1b1b29 !important;
        border-color: #2b2b40 !important;
    }
    [data-theme="dark"] .assist-limit-card {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.12) 0%, rgba(220, 53, 69, 0.03) 100%) !important;
        border-color: rgba(220, 53, 69, 0.25) !important;
        color: #ff8787 !important;
    }
    [data-theme="dark"] .coupon-gold-card {
        background: linear-gradient(135deg, #2b2518 0%, #3d3320 100%);
        border-color: #d4af37;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    }
    [data-theme="dark"] .coupon-gold-card::before, [data-theme="dark"] .coupon-gold-card::after {
        background: #151521 !important;
    }
    [data-theme="dark"] .action-now-card {
        background: #1b1b29 !important;
        border-color: #2b2b40 !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important;
    }
    [data-theme="dark"] .action-now-header {
        border-bottom-color: #2b2b40 !important;
    }
    [data-theme="dark"] .dark-text-white {
        color: #ffffff !important;
    }
    [data-theme="dark"] .dark-text-gray {
        color: #9ca3af !important;
    }
    [data-theme="dark"] .table-dark-mode th {
        color: #ffffff !important;
        border-bottom-color: #2b2b40 !important;
    }
    [data-theme="dark"] .table-dark-mode td {
        color: #d1d5db !important;
        border-bottom-color: #2b2b40 !important;
    }
    .page-title {
        display: none !important;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Detalle de Visita</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('visitas.lista')}}'">Visitas</li>
<li class="breadcrumb-item">Ver</li>
@endsection

@section('content')
@php
    $tipo = \App\Helpers\SubdominioHelper::obtenerTipo();
    $esShopper = $tipo === 'shopper';
    
    // Date formats
    $fechaAsignacion = $visita->fecha_asignacion ? \Carbon\Carbon::parse($visita->fecha_asignacion)->format('d/m/Y') : 'N/A';
    $horaAsignacion = $visita->hora_asignacion ? date('H:i', strtotime($visita->hora_asignacion)) : 'N/A';
    
    // Status colors
    $estadoColor = 'secondary';
    if($visita->estado_id == 1) $estadoColor = 'warning';
    elseif($visita->estado_id == 2) $estadoColor = 'info';
    elseif($visita->estado_id == 3) $estadoColor = 'primary';
    elseif($visita->estado_id == 4) $estadoColor = 'success';
    elseif($visita->estado_id == 5) $estadoColor = 'danger';
    elseif($visita->estado_id == 6) $estadoColor = 'dark';

    // Survey count/loaded checks
    if(!$visita->relationLoaded('respuestas')) {
        $visita->load('respuestas');
    }
    $respuestasEntrada = $visita->respuestas->where('encuesta_tipo', 'entrada');
    $respuestasSalida = $visita->respuestas->where('encuesta_tipo', 'salida');

    $tieneRespuestasEntrada = $respuestasEntrada->count() > 0;
    $tieneRespuestasSalida = $respuestasSalida->count() > 0;

    // Dates for summary
    $fechaCuestionarioInicio = $respuestasEntrada->first()?->created_at ? \Carbon\Carbon::parse($respuestasEntrada->first()->created_at)->format('d/m/Y H:i') : 'Pendiente';
    $fechaVisita = $visita->visitado_at ? \Carbon\Carbon::parse($visita->visitado_at)->format('d/m/Y H:i') : ($visita->fecha_asignacion ? \Carbon\Carbon::parse($visita->fecha_asignacion)->format('d/m/Y') . ' ' . date('H:i', strtotime($visita->hora_asignacion)) : 'Pendiente');
    $fechaCuestionarioFinal = $respuestasSalida->first()?->created_at ? \Carbon\Carbon::parse($respuestasSalida->first()->created_at)->format('d/m/Y H:i') : 'Pendiente';

    // Financial calculations
    $totalConsumo = $visita->total_consumo ? '$' . number_format($visita->total_consumo, 0, ',', '.') : 'N/A';
    $totalDescuento = $visita->total_descuento ? '$' . number_format($visita->total_descuento, 0, ',', '.') : 'N/A';
    $totalPagado = $visita->total_pagado ? '$' . number_format($visita->total_pagado, 0, ',', '.') : 'N/A';
@endphp

<div class="container py-3" style="max-width: 1280px; margin: 0 auto;">
    @if($esShopper)
        <!-- ================= VISTA SHOPPER PREMIUM ================= -->
        @php
            $rest = $visita->restaurante;
            $imgs = $rest ? $rest->imagenes : [];
            if(!is_array($imgs)) $imgs = json_decode($imgs, true) ?: [];
            $portada = count($imgs) > 0 ? $imgs[0] : asset('assets/images/dashboard/bg.jpg');
            $logo = $rest && $rest->logo ? $rest->logo : asset('assets/images/dashboard/avtar.jpg');
        @endphp

        <div class="row">
            <!-- Restaurant Info Banner Card -->
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                    <div class="rest-cover-card" style="background-image: url('{{ $portada }}');">
                        <span class="position-absolute badge bg-white text-dark fw-bold shadow-sm" style="top: 15px; right: 15px; border-radius: 12px; padding: 6px 12px; font-size: 0.75rem; z-index: 10;">
                            Reembolso: {{ $rest->porcentaje_descuento ?? 50 }}%
                        </span>
                        <!-- Left coordinate shifted to 130px to sit next to the logo and prevent overlap -->
                        <div class="position-absolute text-white pe-3" style="bottom: 15px; left: 130px; z-index: 10; width: calc(100% - 150px);">
                            <span class="badge bg-{{ $estadoColor }} text-white mb-2" style="border-radius: 12px; padding: 5px 12px; font-size: 0.72rem; font-weight: 700;">
                                {{ $visita->estado ? $visita->estado->nombre : 'N/A' }}
                            </span>
                            <h4 class="fw-bold mb-1 text-white" style="font-size: 1.45rem; text-shadow: 0 1px 4px rgba(0,0,0,0.5);">{{ $rest ? $rest->name : 'N/A' }}</h4>
                            <div class="small d-flex align-items-center" style="opacity: 0.95; font-size: 0.78rem;">
                               <i class="icofont icofont-location-pin me-1"></i> 
                               <span>{{ $rest ? $rest->direccion : 'N/A' }}, {{ $rest && $rest->ciudad ? $rest->ciudad->nombre : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start justify-content-between p-3 flex-wrap gap-2" style="background: rgba(255,255,255,0.01);">
                        <div class="d-flex">
                            <img src="{{ $logo }}" class="rest-logo-overlap">
                            <div class="ms-3 mt-1.5">
                                <span class="badge bg-light text-secondary dark-text-gray px-3 py-1.5" style="border-radius: 20px; font-size: 0.78rem; font-weight: 600;">
                                    {{ $rest && $rest->tipoCocina ? $rest->tipoCocina->name : 'Cocina' }}
                                </span>
                                <span class="badge bg-light text-success px-3 py-1.5 ms-1" style="border-radius: 20px; font-size: 0.78rem; font-weight: 600;">
                                    Ticket: {{ $rest->rango_ticket_promedio ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex mt-2 mb-1 pe-2">
                            @if($rest && $rest->social_instagram)
                                <a href="{{ $rest->social_instagram }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle me-1 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; border-color: rgba(0,0,0,0.1); color: #495057;"><i class="icofont icofont-social-instagram" style="font-size: 0.95rem;"></i></a>
                            @endif
                            @if($rest && $rest->social_facebook)
                                <a href="{{ $rest && $rest->social_facebook }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle me-1 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; border-color: rgba(0,0,0,0.1); color: #495057;"><i class="icofont icofont-social-facebook" style="font-size: 0.95rem;"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- "What should I do now?" Active Action Container -->
            <div class="col-lg-8 mb-4">
                <div class="action-now-card h-100">
                    <div class="action-now-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 dark-text-white">¿Qué debo hacer ahora?</h5>
                        <span class="badge bg-light text-primary dark-text-gray px-3 py-1.5 fw-bold" style="border-radius: 12px; font-size: 0.75rem;"> Mystery Shopper Activo </span>
                    </div>

                    <!-- Dynamic action states based on current progress -->
                    @if(!$tieneRespuestasEntrada && $visita->estado_id == 1)
                        <!-- State 1: Entrance survey expectations pending -->
                        <div class="p-2">
                            <h4 class="fw-bold dark-text-white mb-2">Completar encuesta de expectativas</h4>
                            <p class="text-muted dark-text-gray mb-4" style="font-size: 0.88rem; line-height: 1.6;">Antes de asistir al restaurante, debes registrar tus expectativas iniciales de la visita. Esto es indispensable para activar tu reserva y habilitar los siguientes pasos de la evaluación.</p>
                            
                            <a href="{{ route('visitas.responder_entrada', encrypt($visita->id)) }}" class="btn btn-primary fw-bold px-4 py-2.5 d-inline-flex align-items-center gap-2" style="border-radius: 10px; font-size: 0.9rem;">
                                <i class="icofont icofont-file-text"></i> Responder Cuestionario de Entrada
                            </a>
                        </div>

                    @elseif($tieneRespuestasEntrada && $visita->estado_id == 2)
                        <!-- State 2: Expectations filled, visit in progress (Step 2 and 3 combined into 1) -->
                        <div class="p-1">
                            <h4 class="fw-bold dark-text-white mb-3">Comer, registrar visita y completar la encuesta</h4>
                            
                            <!-- Premium Alert Box for Assistance limit -->
                            <div class="assist-limit-card d-flex align-items-start gap-3.5 mb-4">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0; box-shadow: 0 2px 8px rgba(220,53,69,0.3);">
                                    <i class="icofont icofont-clock-time" style="font-size: 1.15rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-danger">⚠️ Límite de asistencia obligatorio</h6>
                                    <p class="small mb-0 opacity-90">Debes acudir al restaurante en menos de <b>24 horas</b> desde la hora agendada: <b>{{ $fechaAsignacion }} a las {{ $horaAsignacion }} hs</b>.</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold dark-text-white mb-3">Instrucciones obligatorias:</h6>
                                <ul class="list-unstyled d-flex flex-column gap-4 text-muted dark-text-gray" style="font-size: 0.92rem; padding-left: 5px; line-height: 1.65;">
                                    <li class="d-flex align-items-start mb-3">
                                        <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,117,205,0.1);">
                                            <i class="icofont icofont-restaurant" style="font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <strong class="dark-text-white d-block mb-1" style="font-size: 0.98rem;">1. Consumo Discreto y sin Revelaciones</strong>
                                            <span>Asiste al restaurante <strong>{{ $rest->name }}</strong> (Dirección: <strong>{{ $rest->direccion }}</strong>), pide tu consumo habitual y evalúa la comida y el servicio de forma 100% discreta. <strong>Bajo ningún motivo debes revelar que eres Mystery Shopper antes de pagar.</strong></span>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-start mb-3">
                                        <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,117,205,0.1);">
                                            <i class="icofont icofont-phone" style="font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <strong class="dark-text-white d-block mb-1" style="font-size: 0.98rem;">2. Comer, Registrar la Visita y Completar la Encuesta en el Local</strong>
                                            <span>Una vez que termines de comer (<strong>antes de pedir la cuenta</strong>), abre este panel desde tu celular y presiona el botón <strong>"Registrar Visita"</strong>. Inmediatamente después, responde la encuesta de experiencia que se habilitará.</span>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-start">
                                        <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,117,205,0.1);">
                                            <i class="icofont icofont-ticket" style="font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <strong class="dark-text-white d-block mb-1" style="font-size: 0.98rem;">3. Mostrar Cupón al Pagar para obtener Descuento</strong>
                                            <span>Al finalizar la encuesta, el sistema te mostrará automáticamente tu <strong>cupón digital de descuento del {{ $rest->porcentaje_descuento }}%</strong>. Pide la cuenta, muéstrale el cupón al mesero o cajero y se aplicará el descuento directamente en tu total a pagar.</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <hr class="my-4" style="opacity: 0.15; border-color: rgba(0,0,0,0.15);">

                            <!-- Visual button separation with justify-content-between -->
                            <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-3 mt-3">
                                <button type="button" class="btn btn-info text-white fw-bold px-4 py-2.5 marcar-visitado" data-visita-id="{{ encrypt($visita->id) }}" style="border-radius: 10px; font-size: 0.9rem;">
                                    <i class="icofont icofont-check-circled"></i> Registrar Visita
                                </button>
                                <button type="button" class="btn btn-outline-danger fw-bold px-4 py-2.5" style="border-radius: 10px; font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#modalRechazar">
                                    Cancelar visita
                                </button>
                            </div>
                        </div>

                    @elseif($tieneRespuestasEntrada && $visita->estado_id == 3)
                        <!-- State 3: Visit registered, exit survey pending -->
                        <div class="p-2">
                            <h4 class="fw-bold dark-text-white mb-2">Completar encuesta de experiencia</h4>
                            <p class="text-muted dark-text-gray mb-4" style="font-size: 0.88rem; line-height: 1.6;">¡Excelente! Has registrado tu visita en <b>{{ $rest->name }}</b>. Ahora, por favor responde el cuestionario final sobre tu experiencia y sube una fotografía legible de tu boleta de consumo. Con esto procesaremos tu reembolso.</p>
                            
                            <a href="{{ route('visitas.responder_salida', encrypt($visita->id)) }}" class="btn btn-warning text-dark fw-bold px-4 py-2.5 d-inline-flex align-items-center gap-2" style="border-radius: 10px; font-size: 0.9rem;">
                                <i class="icofont icofont-file-alt"></i> Completar Cuestionario de Salida
                            </a>
                        </div>

                    @elseif($visita->estado_id == 4)
                        <!-- State 4: Completed -->
                        <div class="p-2 text-center py-4">
                            <div class="bg-success-soft text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="icofont icofont-check-alt" style="font-size: 2.2rem;"></i>
                            </div>
                            <h4 class="fw-bold dark-text-white mb-2">¡Evaluación completada con éxito!</h4>
                            <p class="text-muted dark-text-gray mb-4 mx-auto" style="font-size: 0.88rem; max-width: 500px; line-height: 1.6;">Has culminado satisfactoriamente los cuestionarios y el envío de tu comprobante. Tu reembolso de <b>{{ $rest->porcentaje_descuento }}%</b> ha sido aprobado. Ya puedes consultar tu cupón digital.</p>
                            
                            <a href="{{ route('visitas.ver_cupon', $visita->id_encrypted) }}" class="btn btn-success fw-bold px-5 py-2.5" style="border-radius: 10px; font-size: 0.9rem;">
                                <i class="icofont icofont-ticket"></i> Ver mi Cupón de Reembolso
                            </a>
                        </div>

                    @elseif($visita->estado_id == 5 || $visita->estado_id == 6)
                        <!-- State 5: Canceled/Rejected -->
                        <div class="p-2 text-center py-4">
                            <div class="bg-danger-soft text-danger rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="icofont icofont-close-line" style="font-size: 2.2rem;"></i>
                            </div>
                            <h4 class="fw-bold dark-text-white mb-2">Esta visita ha sido cancelada</h4>
                            <p class="text-muted dark-text-gray mb-2 mx-auto" style="font-size: 0.88rem; max-width: 500px;">Esta asignación Mystery Shopper figura como cancelada en el sistema.</p>
                            @if($visita->motivo_rechazo)
                                <p class="small text-danger mb-4"><b>Motivo de la cancelación:</b> <i>"{{ $visita->motivo_rechazo }}"</i></p>
                            @endif
                            
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary fw-bold px-4 py-2.5" style="border-radius: 10px; font-size: 0.9rem;">
                                Volver al Catálogo
                            </a>
                        </div>
                    @endif

                    <!-- Questionnaires Modal buttons bar next to each other -->
                    @if($tieneRespuestasEntrada || $tieneRespuestasSalida)
                        <hr class="my-4" style="opacity: 0.15; border-color: rgba(0,0,0,0.15);">
                        <div class="mb-2">
                            <span class="small fw-semibold text-muted dark-text-gray">Cuestionarios completados:</span>
                        </div>
                        <div class="d-flex flex-wrap gap-3">
                            @if($tieneRespuestasEntrada)
                                <button type="button" class="btn btn-primary btn-sm fw-bold px-4 py-2.5 d-inline-flex align-items-center gap-2" style="border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15); transition: all 0.2s ease;" data-bs-toggle="modal" data-bs-target="#modalExpectativas">
                                    <i class="icofont icofont-file-text" style="font-size: 1.1rem;"></i> Cuestionario inicial
                                </button>
                            @endif

                            @if($tieneRespuestasSalida)
                                <button type="button" class="btn btn-success btn-sm fw-bold px-4 py-2.5 d-inline-flex align-items-center gap-2" style="border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #047857 100%); border: none; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.15); transition: all 0.2s ease;" data-bs-toggle="modal" data-bs-target="#modalExperiencia">
                                    <i class="icofont icofont-file-alt" style="font-size: 1.1rem;"></i> Cuestionario final
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Info & Voucher Area -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm p-4 h-100 d-flex flex-column justify-content-between" style="border-radius: 20px;">
                    <div>
                        <h6 class="fw-bold mb-3 dark-text-white">Resumen de Agendamiento</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-light">
                                <span class="small text-muted dark-text-gray">Fecha cuestionario inicio</span>
                                <span class="small fw-bold dark-text-white">{{ $fechaCuestionarioInicio }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-light">
                                <span class="small text-muted dark-text-gray">Fecha visita</span>
                                <span class="small fw-bold dark-text-white">{{ $fechaVisita }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-light">
                                <span class="small text-muted dark-text-gray">Fecha cuestionario final</span>
                                <span class="small fw-bold dark-text-white">{{ $fechaCuestionarioFinal }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-light">
                                <span class="small text-muted dark-text-gray">Total</span>
                                <span class="small fw-bold dark-text-white">{{ $totalConsumo }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-light">
                                <span class="small text-muted dark-text-gray">Descuento</span>
                                <span class="small fw-bold text-success">{{ $totalDescuento }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-light">
                                <span class="small text-muted dark-text-gray">Total pagado</span>
                                <span class="small fw-bold text-primary dark-text-white">{{ $totalPagado }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Coupon Banner -->
                    @if($visita->estado_id == 4)
                        <div class="coupon-gold-card p-4 mt-4 text-center">
                            <i class="icofont icofont-ticket text-warning mb-2" style="font-size: 2.5rem; text-shadow: 0 2px 5px rgba(0,0,0,0.15);"></i>
                            <h6 class="fw-bold text-dark dark-text-white mb-1">CUPÓN DE REEMBOLSO</h6>
                            <h3 class="fw-bold text-success mb-2">{{ $rest->porcentaje_descuento ?? 50 }}% OFF</h3>
                            <p class="text-muted small dark-text-gray mb-3" style="font-size: 0.72rem;">Muestra este cupón aprobado en la caja del local al realizar la validación.</p>
                            <a href="{{ route('visitas.ver_cupon', $visita->id_encrypted) }}" class="btn btn-success btn-sm w-100 fw-bold py-2" style="border-radius: 10px;">
                                <i class="icofont icofont-eye"></i> Ver mi Cupón
                            </a>
                        </div>
                    @else
                        <div class="p-3 bg-light rounded-3 mt-4 text-center">
                            <i class="icofont icofont-lock text-muted mb-2" style="font-size: 2rem;"></i>
                            <h6 class="fw-bold small dark-text-white mb-1">Cupón Bloqueado</h6>
                            <p class="text-muted small dark-text-gray mb-0" style="font-size: 0.7rem;">Completa las encuestas y sube la boleta de consumo para habilitar el cupón.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @else
        <!-- ================= VISTA ADMINISTRADOR/SISTEMA MEJORADA ================= -->
        <div class="row">
            <!-- Columna 1: Información General y Shopper -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                    <div class="card-header bg-light-primary py-3" style="border-radius: 16px 16px 0 0;">
                        <h5 class="m-0 text-primary fw-bold"><i class="icofont icofont-id-card me-2"></i>Información General</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-{{ $estadoColor }} text-white px-3 py-2 rounded-pill fs-6 w-100">
                                {{ $visita->estado ? $visita->estado->nombre : 'N/A' }}
                            </span>
                            @if($puedeEditar)
                                <a class="btn btn-outline-primary btn-xs ms-2" href="{{ route('visitas.editar', ['id' => encrypt($visita->id)]) }}" title="Editar Visita">
                                    <i class="icofont icofont-pen"></i>
                                </a>
                            @endif
                        </div>

                        <div class="mb-4 pb-3 border-bottom border-light">
                            <h6 class="fw-bold mb-3 text-dark dark-text-white">Mistery Shopper</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="icofont icofont-user text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark dark-text-white">{{ $visita->shopper ? $visita->shopper->name : 'N/A' }}</div>
                                    <small class="text-muted dark-text-gray">{{ $visita->shopper ? $visita->shopper->email : '' }}</small>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h6 class="fw-bold mb-3 text-dark dark-text-white">Detalles del Proceso</h6>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">ID Interno:</span>
                                    <span class="fw-bold">VIS-{{ str_pad($visita->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Tipo Horario:</span>
                                    <span class="fw-bold text-capitalize">{{ $visita->tipo_horario ?? 'N/A' }}</span>
                                </div>
                                @if($visita->motivo_rechazo)
                                <div class="mt-3 p-3 bg-light-danger rounded-3">
                                    <small class="fw-bold text-danger d-block mb-1">Motivo Cancelación:</small>
                                    <small class="text-muted">{{ $visita->motivo_rechazo }}</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna 2: Restaurante y Finanzas -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                    <div class="card-header bg-light-success py-3" style="border-radius: 16px 16px 0 0;">
                        <h5 class="m-0 text-success fw-bold"><i class="icofont icofont-restaurant me-2"></i>Establecimiento y Cifras</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4 pb-3 border-bottom border-light">
                            <h6 class="fw-bold mb-3 text-dark dark-text-white">Restaurante</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="icofont icofont-location-pin text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark dark-text-white">{{ $visita->restaurante ? $visita->restaurante->name : 'N/A' }}</div>
                                    <small class="text-muted dark-text-gray">{{ $visita->restaurante ? $visita->restaurante->direccion : 'S/D' }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 pb-3 border-bottom border-light">
                            <h6 class="fw-bold mb-3 text-dark dark-text-white">Resultados Financieros</h6>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Total Consumo:</span>
                                    <span class="fw-bold text-dark dark-text-white">{{ $totalConsumo }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Descuento Aplicado ({{ $visita->restaurante->porcentaje_descuento ?? 0 }}%):</span>
                                    <span class="fw-bold text-danger">{{ $totalDescuento }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Total Pagado:</span>
                                    <span class="fw-bold text-success" style="font-size: 1.1rem;">{{ $totalPagado }}</span>
                                </div>
                            </div>
                        </div>

                        @if($visita->cupon_codigo)
                        <div class="p-3 border-dashed border-primary rounded-3 text-center" style="border: 2px dashed #0075cd; background: rgba(0,117,205,0.03);">
                            <small class="text-muted d-block mb-1">CÓDIGO DE CUPÓN</small>
                            <h4 class="fw-bold text-primary mb-0" style="letter-spacing: 2px;">{{ $visita->cupon_codigo }}</h4>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna 3: Línea de Tiempo de Fechas -->
            <div class="col-xl-4 mb-4">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 16px;">
                    <div class="card-header bg-light-warning py-3" style="border-radius: 16px 16px 0 0;">
                        <h5 class="m-0 text-warning fw-bold"><i class="icofont icofont-clock-time me-2"></i>Historial de Tiempos</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline-small">
                            <!-- Registro -->
                            <div class="d-flex mb-4">
                                <div class="timeline-dot bg-secondary me-3" style="width: 12px; height: 12px; border-radius: 50%; margin-top: 6px; flex-shrink: 0;"></div>
                                <div>
                                    <small class="text-muted d-block">Registro de Visita</small>
                                    <span class="fw-bold small dark-text-white">{{ $visita->created_at->format('d/m/Y H:i') }} hs</span>
                                </div>
                            </div>
                            <!-- Cuestionario Inicial -->
                            <div class="d-flex mb-4">
                                <div class="timeline-dot {{ $tieneRespuestasEntrada ? 'bg-success' : 'bg-warning' }} me-3" style="width: 12px; height: 12px; border-radius: 50%; margin-top: 6px; flex-shrink: 0;"></div>
                                <div>
                                    <small class="text-muted d-block">Cuestionario Inicial</small>
                                    <span class="fw-bold small {{ $tieneRespuestasEntrada ? 'text-success' : 'text-warning' }}">{{ $fechaCuestionarioInicio }}</span>
                                </div>
                            </div>
                            <!-- Visita Realizada -->
                            <div class="d-flex mb-4">
                                <div class="timeline-dot {{ $visita->visitado_at ? 'bg-success' : 'bg-light' }} me-3" style="width: 12px; height: 12px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; border: 2px solid #ddd;"></div>
                                <div>
                                    <small class="text-muted d-block">Visita al Local</small>
                                    <span class="fw-bold small {{ $visita->visitado_at ? 'text-success' : 'text-muted' }}">{{ $fechaVisita }}</span>
                                </div>
                            </div>
                            <!-- Cuestionario Final -->
                            <div class="d-flex">
                                <div class="timeline-dot {{ $tieneRespuestasSalida ? 'bg-success' : 'bg-light' }} me-3" style="width: 12px; height: 12px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; border: 2px solid #ddd;"></div>
                                <div>
                                    <small class="text-muted d-block">Cuestionario Final / Cupón</small>
                                    <span class="fw-bold small {{ $tieneRespuestasSalida ? 'text-success' : 'text-muted' }}">{{ $fechaCuestionarioFinal }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($visita->respuestas && $visita->respuestas->count() > 0)
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card p-4 h-100 shadow-sm border-0" style="border-radius: 16px;">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="icofont icofont-listine-dots text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="m-0 dark-text-white">Expectativas (Entrada)</h5>
                    </div>
                    <hr>
                    @if($respuestasEntrada->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-dark-mode">
                                <thead>
                                    <tr>
                                        <th>Pregunta</th>
                                        <th>Respuesta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($respuestasEntrada as $respuesta)
                                    <tr>
                                        <td class="text-muted small">{{$respuesta->pregunta_texto}}</td>
                                        <td>
                                            @if($respuesta->pregunta && $respuesta->pregunta->tipo_respuesta == 'escala_1_5' && $respuesta->respuesta_valor)
                                                <span class="badge bg-primary text-white">{{$respuesta->respuesta_valor}} / 5</span>
                                            @else
                                                <span class="text-dark dark-text-white font-weight-bold">{{$respuesta->respuesta_texto ?: $respuesta->respuesta_valor}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center my-4">No hay respuestas de entrada</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card p-4 h-100 shadow-sm border-0" style="border-radius: 16px;">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="icofont icofont-verification-check text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5 class="m-0 dark-text-white">Experiencia (Salida)</h5>
                    </div>
                    <hr>
                    @if($respuestasSalida->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-dark-mode">
                                <thead>
                                    <tr>
                                        <th>Pregunta</th>
                                        <th>Respuesta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($respuestasSalida as $respuesta)
                                    <tr>
                                        <td class="text-muted small">{{$respuesta->pregunta_texto}}</td>
                                        <td>
                                            @if($respuesta->pregunta && $respuesta->pregunta->tipo_respuesta == 'escala_1_5' && $respuesta->respuesta_valor)
                                                <span class="badge bg-success text-white">{{$respuesta->respuesta_valor}} / 5</span>
                                            @else
                                                <span class="text-dark dark-text-white font-weight-bold">{{$respuesta->respuesta_texto ?: $respuesta->respuesta_valor}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center my-4">No hay respuestas de salida</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    @endif
</div>

<!-- Modal Cancelar -->
<div class="modal fade" id="modalRechazar" tabindex="-1" aria-labelledby="modalRechazarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalRechazarLabel">Cancelar visita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold" for="motivoRechazo">Motivo de la cancelación (*)</label>
                    <textarea class="form-control" id="motivoRechazo" rows="4" placeholder="Ingrese el motivo de la cancelación" style="border-radius: 8px;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Cerrar</button>
                <button type="button" class="btn btn-danger" id="btnRechazar" style="border-radius: 8px;">Cancelar visita</button>
            </div>
        </div>
    </div>
</div>

@if($esShopper)
    <!-- Modal Respuestas Expectativas (Entrada) -->
    <div class="modal fade" id="modalExpectativas" tabindex="-1" aria-labelledby="modalExpectativasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalExpectativasLabel"><i class="icofont icofont-listine-dots text-warning me-2"></i> Expectativas de Entrada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-dark-mode mb-0">
                            <thead>
                                <tr>
                                    <th>Pregunta</th>
                                    <th>Respuesta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($respuestasEntrada as $respuesta)
                                <tr>
                                    <td class="small py-3" style="width: 70%;">{{$respuesta->pregunta_texto}}</td>
                                    <td class="py-3">
                                        @if($respuesta->pregunta && $respuesta->pregunta->tipo_respuesta == 'escala_1_5' && $respuesta->respuesta_valor)
                                            <span class="badge bg-primary text-white" style="border-radius: 8px; padding: 5px 10px;">{{$respuesta->respuesta_valor}} / 5</span>
                                        @else
                                            <span class="fw-bold text-dark dark-text-white">{{$respuesta->respuesta_texto ?: $respuesta->respuesta_valor}}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Respuestas Experiencia (Salida) -->
    <div class="modal fade" id="modalExperiencia" tabindex="-1" aria-labelledby="modalExperienciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalExperienciaLabel"><i class="icofont icofont-verification-check text-success me-2"></i> Experiencia de Salida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-dark-mode mb-0">
                            <thead>
                                <tr>
                                    <th>Pregunta</th>
                                    <th>Respuesta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($respuestasSalida as $respuesta)
                                <tr>
                                    <td class="small py-3" style="width: 70%;">{{$respuesta->pregunta_texto}}</td>
                                    <td class="py-3">
                                        @if($respuesta->pregunta && $respuesta->pregunta->tipo_respuesta == 'escala_1_5' && $respuesta->respuesta_valor)
                                            <span class="badge bg-success text-white" style="border-radius: 8px; padding: 5px 10px;">{{$respuesta->respuesta_valor}} / 5</span>
                                        @else
                                            <span class="fw-bold text-dark dark-text-white">{{$respuesta->respuesta_texto ?: $respuesta->respuesta_valor}}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@section('script')
<script>
    $('#btnRechazar').click(function() {
        var motivo = $('#motivoRechazo').val();
        if(!motivo || motivo.trim() === '') {
            notify('Advertencia', 'Debe ingresar un motivo para cancelar la visita', 'danger');
            return;
        }

        $.ajax({
            url:'{{route("visitas.rechazar")}}',
            method:'POST',
            data:{
                visita_id: '{{encrypt($visita->id)}}',
                motivo: motivo
            },
            success:function(res) {
                if(res.estado == 200) {
                    notify('Exito', res.mensaje || 'Visita cancelada correctamente', 'success');
                    $('#modalRechazar').modal('hide');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    notify('Error', res.mensaje || 'Error', 'danger');
                }
            }
        })
    });

    // Marcar visita como realizada
    $(document).on('click', '.marcar-visitado', function() {
        var visitaId = $(this).data('visita-id');
        var btn = $(this);
        
        swal({
            title: "¿Confirmas tu visita?",
            text: "Confirma tu visita para completar el cuestionario de experiencia. Al finalizar y subir tu boleta, recibirás tu cupón de descuento.",
            icon: "info",
            buttons: ["Cancelar", "Confirmar"],
            dangerMode: false,
        }).then((willConfirm) => {
            if (willConfirm) {
                btn.prop('disabled', true).html('<i class="icofont icofont-spinner-alt-4"></i> Registrando...');
                $.ajax({
                    url: '{{ route("visitas.marcar_visitado") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        visita_id: visitaId
                    },
                    success: function(response) {
                        if(response.estado == 200) {
                            notify('Éxito', response.mensaje, 'success');
                            setTimeout(function() {
                                window.location.href = '{{ route("visitas.responder_salida", encrypt($visita->id)) }}';
                            }, 1500);
                        } else {
                            btn.prop('disabled', false).html('<i class="icofont icofont-check-circled"></i> Registrar Visita');
                            notify('Error', response.mensaje, 'danger');
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).html('<i class="icofont icofont-check-circled"></i> Registrar Visita');
                        notify('Error', 'Ocurrió un error al registrar la visita', 'danger');
                    }
                });
            }
        });
    });
</script>
@endsection
