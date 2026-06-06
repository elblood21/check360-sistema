@extends('layouts.master')
@section('title', $restaurante->name)

@section('style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .restaurant-header {
        position: relative;
        height: 250px;
        background-size: cover;
        background-position: center;
        border-radius: 0 0 1.5rem 1.5rem;
    }
    .restaurant-header::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,0.7) 100%);
        border-radius: 0 0 1.5rem 1.5rem;
    }
    .restaurant-logo-container {
        position: relative;
        margin-top: -60px;
        z-index: 10;
        padding: 0 20px;
    }
    .restaurant-logo {
        width: 120px;
        height: 120px;
        border-radius: 1rem;
        border: 2px solid rgba(0, 0, 0, 0.08);
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        object-fit: cover;
        transition: all 0.25s ease;
    }
    .restaurant-info {
        padding: 20px;
    }
    .category-badge {
        background: #f1f3f5;
        color: #495057;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        margin-right: 8px;
        display: inline-block;
    }
    .discount-badge {
        background: rgba(220, 53, 69, 0.08);
        color: #dc3545;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 700;
        border: 1px solid rgba(220, 53, 69, 0.15);
        display: inline-block;
    }
    .menu-section {
        margin-top: 32px;
    }
    .section-title {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f3f5;
        color: #212529;
    }
    .booking-card {
        position: sticky;
        top: 100px;
        border-radius: 1.5rem;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #ffffff;
        margin-top: 40px;
    }
    .booking-card .bg-light {
        color: #475569 !important;
    }
    .social-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f3f5;
        color: #495057;
        margin-right: 10px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .social-link:hover {
        background: #0075cd;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Map premium style */
    #restaurant-map {
        height: 220px;
        border-radius: 1.25rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.08);
        margin-top: 15px;
    }

    /* Thumbnail cards for Images Menu */
    .hover-zoom-thumbnail {
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-zoom-thumbnail:hover {
        transform: scale(1.05);
    }
    .hover-zoom-thumbnail:hover .overlay-thumbnail {
        opacity: 1 !important;
    }
    .scrollbar-premium::-webkit-scrollbar {
        height: 6px;
    }
    .scrollbar-premium::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }
    .scrollbar-premium::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    /* Soft badges styles */
    .bg-success-soft {
        background-color: rgba(40, 167, 69, 0.08) !important;
    }
    .bg-danger-soft {
        background-color: rgba(220, 53, 69, 0.08) !important;
    }

    @keyframes pulsePin {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 117, 205, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(0, 117, 205, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 117, 205, 0); }
    }

    /* Dark Mode styling */
    [data-theme="dark"] .restaurant-logo {
        border-color: #1e1e2f !important;
        background: #1b1b29 !important;
    }
    [data-theme="dark"] .restaurant-info h1 {
        color: #ffffff !important;
    }
    [data-theme="dark"] .section-title {
        color: #ffffff !important;
        border-bottom-color: #2b2b40 !important;
    }
    [data-theme="dark"] .booking-card {
        background-color: #1b1b29 !important;
        border: 1px solid #2b2b40 !important;
        color: #ffffff !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    [data-theme="dark"] .booking-card .bg-light {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .category-badge {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #d1d5db !important;
    }
    [data-theme="dark"] .discount-badge {
        background: rgba(220, 53, 69, 0.12) !important;
        color: #ff6b6b !important;
        border-color: rgba(220, 53, 69, 0.25) !important;
    }
    [data-theme="dark"] .social-link {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #d1d5db !important;
    }
    [data-theme="dark"] .scrollbar-premium::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    [data-theme="dark"] .scrollbar-premium::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
    }
    [data-theme="dark"] #restaurant-map {
        border-color: rgba(255,255,255,0.08);
    }
    [data-theme="dark"] .dark-text-white {
        color: #ffffff !important;
    }
    [data-theme="dark"] .dark-text-gray {
        color: #9ca3af !important;
    }

    [data-theme="dark"] .bg-recommendations {
        background: rgba(13, 110, 253, 0.15) !important;
        border: 1px solid rgba(13, 110, 253, 0.3) !important;
    }
    .bg-recommendations {
        background: rgba(13, 110, 253, 0.05);
        border: 1px solid rgba(13, 110, 253, 0.1);
    }
    [data-theme="dark"] .text-recommendations-title {
        color: #60a5fa !important;
    }
    .text-recommendations-title {
        color: #0d6efd;
    }
    [data-theme="dark"] .text-recommendations-body {
        color: #e2e8f0 !important;
    }
    .text-recommendations-body {
        color: #475569;
    }

    @media (max-width: 768px) {
        .booking-card {
            position: relative;
            top: 0;
            margin-top: 30px;
        }
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Detalle de Local</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Detalle</li>
@endsection

@section('content')
@php
    $imgs = $restaurante->imagenes;
    if(!is_array($imgs)) $imgs = json_decode($imgs, true) ?: [];
    $portada = count($imgs) > 0 ? $imgs[0] : asset('assets/images/dashboard/bg.jpg');
    $logo = $restaurante->logo ? $restaurante->logo : asset('assets/images/dashboard/avtar.jpg');

    // Smart Schedule Calculation
    $horariosEvaluacionRaw = $opciones['horarios_evaluacion']['valor_json'] ?? [];
    if (is_string($horariosEvaluacionRaw)) {
        $horariosEvaluacion = json_decode($horariosEvaluacionRaw, true) ?: [];
    } else {
        $horariosEvaluacion = $horariosEvaluacionRaw;
    }
    if (!is_array($horariosEvaluacion)) {
        $horariosEvaluacion = [];
    }
    
    $peakHours = $restaurante->horario_peak;
    if (is_string($peakHours)) $peakHours = json_decode($peakHours, true) ?: [];

    $diasLabels = [
        'lunes' => 'Lunes', 
        'martes' => 'Martes', 
        'miercoles' => 'Miércoles', 
        'jueves' => 'Jueves', 
        'viernes' => 'Viernes', 
        'sabado' => 'Sábado', 
        'domingo' => 'Domingo'
    ];

    $diasMostrar = [];

    foreach ($diasLabels as $key => $label) {
        $cfg = $horariosEvaluacion[$key] ?? null;
        if (is_string($cfg)) {
            $cfg = json_decode($cfg, true);
        }
        
        if (!is_array($cfg)) {
            continue;
        }
        
        $en = $cfg['enabled'] ?? false;
        if (is_string($en)) {
            $en = strtolower(trim($en));
        }
        $isEnabled = ($en === true || $en === 1 || $en === '1' || $en === 'true' || $en === 'on');
        
        $desdeStd = $cfg['desde'] ?? null;
        $hastaStd = $cfg['hasta'] ?? null;
        
        if (!$isEnabled || empty($desdeStd) || empty($hastaStd)) {
            continue;
        }
        
        $p = $peakHours[$key] ?? null;
        $desdePeak = $p['desde'] ?? null;
        $hastaPeak = $p['hasta'] ?? null;
        $ocupa90 = $p['ocupa_90'] ?? false;
        
        $bloquesDisponibles = [];
        $advertenciaPeak = null;
        
        if ($desdePeak && $hastaPeak && $ocupa90) {
            $advertenciaPeak = "$desdePeak - $hastaPeak";
            
            if ($desdeStd < $desdePeak) {
                $bloquesDisponibles[] = "$desdeStd a $desdePeak";
            }
            if ($hastaPeak < $hastaStd) {
                $bloquesDisponibles[] = "$hastaPeak a $hastaStd";
            }
            if (empty($bloquesDisponibles)) {
                $bloquesDisponibles[] = "Sin cupos disponibles";
            }
        } else {
            $bloquesDisponibles[] = "$desdeStd a $hastaStd";
        }
        
        $diasMostrar[$key] = [
            'label' => $label,
            'bloques' => $bloquesDisponibles,
            'advertencia' => $advertenciaPeak
        ];
    }
@endphp

<div class="container-fluid p-0">
    <div class="restaurant-header" style="background-image: url('{{ $portada }}');"></div>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="restaurant-logo-container d-flex align-items-end justify-content-between">
                    <img src="{{ $logo }}" class="restaurant-logo">
                    <div class="d-flex mb-2">
                        @if($restaurante->social_instagram)
                            <a href="{{ $restaurante->social_instagram }}" target="_blank" class="social-link"><i class="icofont icofont-social-instagram"></i></a>
                        @endif
                        @if($restaurante->social_facebook)
                            <a href="{{ $restaurante->social_facebook }}" target="_blank" class="social-link"><i class="icofont icofont-social-facebook"></i></a>
                        @endif
                        @if($restaurante->social_tiktok)
                            <a href="{{ $restaurante->social_tiktok }}" target="_blank" class="social-link"><i class="icofont icofont-play"></i></a>
                        @endif
                    </div>
                </div>

                <div class="restaurant-info">
                    <h1 class="fw-bold mb-1">{{ $restaurante->name }}</h1>
                    <div class="d-flex align-items-center mb-3">
                        <span class="category-badge">{{ $restaurante->tipoCocina ? $restaurante->tipoCocina->name : 'Cocina' }}</span>
                        <span class="discount-badge">{{ $restaurante->porcentaje_descuento }}% Reembolso</span>
                    </div>
                    
                    <p class="text-muted mb-4">
                        <i class="icofont icofont-location-pin text-primary"></i> {{ $restaurante->direccion }}, {{ $restaurante->ciudad ? $restaurante->ciudad->nombre : 'N/A' }}
                    </p>

                    <!-- Ficha del Local -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-6">
                            <div class="p-3 border rounded-3 text-center h-100" style="background: rgba(40, 167, 69, 0.03); border: 1px solid rgba(40, 167, 69, 0.1) !important;">
                                <i class="icofont icofont-money text-success mb-2 d-block" style="font-size: 1.5rem;"></i>
                                <span class="d-block fw-bold small text-dark dark-text-white">Ticket Promedio</span>
                                <span class="text-muted small">{{ $restaurante->rango_ticket_promedio ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="p-3 border rounded-3 text-center h-100" style="background: rgba(13, 110, 253, 0.03); border: 1px solid rgba(13, 110, 253, 0.1) !important;">
                                <i class="icofont icofont-users-social text-primary mb-2 d-block" style="font-size: 1.5rem;"></i>
                                <span class="d-block fw-bold small text-dark dark-text-white">Capacidad</span>
                                <span class="text-muted small">{{ $restaurante->capacidad_restaurante ? $restaurante->capacidad_restaurante . ' pers.' : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="p-3 border rounded-3 text-center h-100" style="background: rgba(255, 193, 7, 0.03); border: 1px solid rgba(255, 193, 7, 0.1) !important;">
                                <i class="icofont icofont-restaurant text-warning mb-2 d-block" style="font-size: 1.5rem;"></i>
                                <span class="d-block fw-bold small text-dark dark-text-white">Tipo Cocina</span>
                                <span class="text-muted small">{{ $restaurante->tipoCocina ? $restaurante->tipoCocina->name : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Map Container -->
                    <div id="restaurant-map"></div>

                    <!-- Galería de Fotos del Local -->
                    @if(count($imgs) > 1)
                    <div class="menu-section">
                        <h2 class="section-title">Fotos del Local</h2>
                        <div class="row g-2">
                            @foreach($imgs as $index => $img)
                                @if($index > 0)
                                <div class="col-md-4 col-6">
                                    <img src="{{ $img }}" class="img-fluid rounded-3 shadow-sm" style="height: 120px; width: 100%; object-fit: cover; cursor: pointer;" onclick="openLightbox('{{ $img }}')">
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Carta / Menú Premium UX -->
                    <div class="menu-section">
                        <h2 class="section-title">Menú / Carta</h2>
                        @if($restaurante->carta_tipo === 'url' && $restaurante->carta_url)
                            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(13, 110, 253, 0.02)); border: 1px solid rgba(13, 110, 253, 0.1) !important;">
                                <div class="p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="icofont icofont-restaurant" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0.5 text-dark dark-text-white">Carta Digital Activa</h6>
                                            <p class="text-muted small mb-0">Accede al menú oficial online del restaurante.</p>
                                        </div>
                                    </div>
                                    <a href="{{ $restaurante->carta_url }}" target="_blank" class="btn btn-primary px-4 py-2 fw-bold d-inline-flex align-items-center gap-2" style="border-radius: 12px; transition: all 0.3s ease;">
                                        <span>Explorar Menú</span>
                                        <i class="icofont icofont-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @else
                            @php
                                $cartaImgs = $restaurante->carta_imagenes;
                                if(is_string($cartaImgs)) $cartaImgs = json_decode($cartaImgs, true) ?: [];
                            @endphp
                            @if(count($cartaImgs) > 0)
                                <div class="d-flex gap-3 overflow-auto pb-2 scrollbar-premium" style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
                                    @foreach($cartaImgs as $img)
                                    <div class="position-relative flex-shrink-0 rounded-3 overflow-hidden cursor-pointer hover-zoom-thumbnail shadow-sm" style="width: 110px; height: 140px;" onclick="openLightbox('{{ $img }}')">
                                        <img src="{{ $img }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        <div class="overlay-thumbnail d-flex align-items-center justify-content-center position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-40 opacity-0 transition-opacity" style="transition: opacity 0.2s;">
                                            <i class="icofont icofont-search-2 text-white" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-light text-center py-3" style="border-radius: 12px;">
                                    <p class="mb-0 text-muted small">No hay imágenes de la carta registradas.</p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Perfil del Cliente Objetivo -->
                    @php
                        $perfil = $opciones['perfil_socioeconomico_predominante']['valor_json'] ?? null;
                        $motivos = $opciones['motivos_visita_restaurante']['valor_json'] ?? null;
                        $ambiente = $opciones['ambiente_estilo']['valor_json'] ?? null;
                        $edad = $opciones['edad_promedio_clientes']['valor_json'] ?? null;
                    @endphp
                    @if($perfil || $motivos || $ambiente || $edad)
                    <div class="menu-section">
                        <h2 class="section-title">Información para el Shopper</h2>
                        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.05) !important;">
                            <div class="row g-4">
                                @if($perfil)
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2 text-dark dark-text-white"><i class="icofont icofont-users-social text-primary me-2"></i> Perfil Socioeconómico</h6>
                                    <p class="text-muted small mb-0">{{ is_array($perfil) ? implode(', ', $perfil) : $perfil }}</p>
                                </div>
                                @endif
                                @if($motivos)
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2 text-dark dark-text-white"><i class="icofont icofont-heart text-danger me-2"></i> Motivos de Visita</h6>
                                    <p class="text-muted small mb-0">{{ is_array($motivos) ? implode(', ', $motivos) : $motivos }}</p>
                                </div>
                                @endif
                                @if($ambiente)
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2 text-dark dark-text-white"><i class="icofont icofont-building-alt text-warning me-2"></i> Ambiente y Estilo</h6>
                                    <p class="text-muted small mb-0">{{ is_array($ambiente) ? implode(', ', $ambiente) : $ambiente }}</p>
                                </div>
                                @endif
                                @if($edad)
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2 text-dark dark-text-white"><i class="icofont icofont-calendar text-success me-2"></i> Edad Promedio</h6>
                                    <p class="text-muted small mb-0">{{ is_array($edad) ? implode(', ', $edad) : $edad }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Horarios de Reservas Disponibles -->
                    <div class="menu-section mb-5">
                        <h2 class="section-title">Horarios de Atención y Reservas</h2>
                        <p class="small text-muted mb-3">A continuación se muestran los horarios disponibles para realizar tu visita Mystery Shopper, excluyendo automáticamente los bloques de saturación o alta ocupación.</p>
                        
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            @forelse($diasMostrar as $diaKey => $dia)
                                <div class="col">
                                    <div class="card border-0 shadow-sm p-3 h-100" style="background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px solid rgba(0,0,0,0.05) !important;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-dark dark-text-white" style="font-size: 1.05rem;">{{ $dia['label'] }}</span>
                                            <span class="badge bg-success-soft text-success px-2.5 py-1 rounded-pill small fw-bold">Disponible</span>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($dia['bloques'] as $bloque)
                                                <div class="d-flex align-items-center text-muted small">
                                                    <i class="icofont icofont-clock-time text-success me-2" style="font-size: 1.1rem;"></i>
                                                    <span class="fw-semibold text-secondary dark-text-gray">{{ $bloque }}</span>
                                                </div>
                                            @endforeach
                                            @if($dia['advertencia'])
                                                <div class="mt-2 p-2 rounded bg-danger-soft text-danger d-flex align-items-center gap-2" style="font-size: 0.78rem;">
                                                    <i class="icofont icofont-warning-alt" style="font-size: 1rem;"></i>
                                                    <span>Pico alta ocupación: <strong>{{ $dia['advertencia'] }}</strong> (Bloqueado)</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light text-center py-4" style="border-radius: 16px; border: 1px dashed rgba(0,0,0,0.1) !important; background: rgba(0,0,0,0.02);">
                                        <i class="icofont icofont-clock-time text-muted mb-2 d-block" style="font-size: 2rem;"></i>
                                        <p class="mb-0 text-muted small">No hay horarios de atención o reservas disponibles para este local.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card booking-card">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Agendar Visita</h4>
                        <p class="text-muted small mb-4">Agenda tu visita de evaluación. Recuerda que después de agendar deberás responder una breve pre-encuesta.</p>
                        
                        <form id="agendar-visita-form-detalle">
                            @csrf
                            <input type="hidden" name="restaurante_id_modal" value="{{ $restaurante->id }}">
                            <input type="hidden" name="fecha_visita" id="fecha_visita_detalle_hidden">
                            <input type="hidden" name="hora_visita" id="hora_visita_detalle_hidden">

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" id="btn-reservar-detalle" style="border-radius: 12px; font-size: 1.1rem;">
                                Agendar visita
                            </button>
                        </form>

                        @if(!empty($opciones['observaciones_adicionales']['valor_texto']) || !empty($opciones['protocolos_internos']['valor_json']))
                        <div class="mt-4 p-3 rounded-3 bg-recommendations">
                            <h6 class="fw-bold small mb-2 text-recommendations-title">Recomendaciones para tu visita</h6>
                            <div class="small text-recommendations-body mb-0">
                                @if(!empty($opciones['observaciones_adicionales']['valor_texto']))
                                    <p class="mb-2"><strong>Observaciones:</strong><br>{{ $opciones['observaciones_adicionales']['valor_texto'] }}</p>
                                @endif
                                @if(!empty($opciones['protocolos_internos']['valor_json']))
                                    @php $protocolos = $opciones['protocolos_internos']['valor_json']; @endphp
                                    @if(is_array($protocolos) && count($protocolos) > 0)
                                        <p class="mb-1"><strong>Protocolos a evaluar:</strong></p>
                                        <ul class="ps-3 mb-0">
                                            @foreach($protocolos as $p)
                                                <li>{{ $p }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="mt-4 p-3 bg-light rounded-3">
                            <h6 class="fw-bold small mb-2 text-primary">¿Cómo funciona?</h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><i class="icofont icofont-check-circled text-success"></i> Agenda tu visita aquí.</li>
                                <li class="mb-2"><i class="icofont icofont-check-circled text-success"></i> Responde la pre-encuesta.</li>
                                <li class="mb-2"><i class="icofont icofont-check-circled text-success"></i> Ve al local, consume y paga.</li>
                                <li class="mb-1"><i class="icofont icofont-check-circled text-success"></i> Sube tu boleta y post-encuesta para recibir tu reembolso.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lightbox -->
<div class="modal fade" id="modal-lightbox" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute" style="top: -40px; right: 0;" data-bs-dismiss="modal"></button>
                <img src="" id="lightbox-img" class="img-fluid rounded border" style="max-height: 85vh;">
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function openLightbox(src) {
        $('#lightbox-img').attr('src', src);
        $('#modal-lightbox').modal('show');
    }

    $(document).ready(function() {
        const peakHours = @json($peakHours);
        const horariosEvaluacion = @json($horariosEvaluacion);
        const diasSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

        function populateDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            $('#fecha_visita_detalle_hidden').val(`${year}-${month}-${day}`);
            $('#hora_visita_detalle_hidden').val(`${hours}:${minutes}`);
        }

        // Geocoding and Map Initialization
        const address = "{{ $restaurante->direccion }}, {{ $restaurante->ciudad ? $restaurante->ciudad->nombre : '' }}";
        const mapContainer = document.getElementById('restaurant-map');
        if (mapContainer) {
            const map = L.map('restaurant-map', {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([-33.4489, -70.6693], 13);

            let isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
            let tileUrl = isDarkMode 
                ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' 
                : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

            const tiles = L.tileLayer(tileUrl, {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Listen to data-theme changes to update map tiles
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === "data-theme") {
                        const newDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
                        const newTileUrl = newDarkMode 
                            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' 
                            : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
                        tiles.setUrl(newTileUrl);
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });

            // Call OSM Nominatim geocoding
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address))
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        map.setView([lat, lon], 16);
                        
                        const customIcon = L.divIcon({
                            className: 'custom-map-pin',
                            html: '<div style="background: #0075cd; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,117,205,0.8); animation: pulsePin 1.5s infinite;"></div>',
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });

                        L.marker([lat, lon], { icon: customIcon }).addTo(map)
                            .bindPopup(`<b class="text-dark">${@json($restaurante->name)}</b><br><span class="text-muted small">${address}</span>`)
                            .openPopup();
                    }
                })
                .catch(err => console.error("Geocoding failed:", err));
        }

        $('#agendar-visita-form-detalle').submit(function(e) {
            e.preventDefault();
            populateDateTime();
            
            var btn = $('#btn-reservar-detalle');
            btn.prop('disabled', true).text('Procesando...');

            $.ajax({
                url: '{{ route("visitas.agendar_shopper") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.estado == 200) {
                        notify('Agendamiento exitoso', res.mensaje, 'success');
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 2000);
                    } else {
                        btn.prop('disabled', false).text('Agendar visita');
                        notify('Error al agendar', res.mensaje || 'Ocurrió un error', 'danger');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('Agendar visita');
                    var msg = 'Ocurrió un error al agendar la visita.';
                    if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                        msg = xhr.responseJSON.mensaje;
                    }
                    notify('Error', msg, 'danger');
                }
            });
        });
    });
</script>
@endsection
