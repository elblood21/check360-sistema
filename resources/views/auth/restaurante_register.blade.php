@extends('layouts.master-noauth')
@section('title', 'Registro de Restaurante - Check 360')

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" />
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
    body {
        background-color: #f4f6f9;
    }
    .btn-primary {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #fff !important;
    }
    .btn-primary:hover {
        background-color: #bb2d3b !important;
        border-color: #b02a37 !important;
    }
    .horario-card {
        border: 1px solid rgba(0,0,0,.08);
        border-radius: .5rem;
        padding: .75rem;
        height: 100%;
        background-color: #f9f9f9;
    }
    .hidden {
        display: none !important;
    }
    [data-theme="dark"] body {
        background-color: #1a1a1a;
    }
    [data-theme="dark"] .horario-card {
        background-color: #2b2b2b;
        border-color: #444;
        color: #fff;
    }
    [data-theme="dark"] .card {
        background-color: #222;
        border-color: #333;
    }
    [data-theme="dark"] .card-header {
        background-color: #2a2a2a !important;
        border-bottom-color: #333;
    }
    
    /* Wizard Steps Bar */
    .wizard-container {
        background: #fff;
        border-radius: .5rem;
        padding: 1rem 2rem;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
        margin-bottom: 2rem;
    }
    [data-theme="dark"] .wizard-container {
        background: #222;
    }
    .wizard-steps {
        display: flex;
        margin-bottom: 0;
    }
    .wizard-step {
        flex: 1;
        text-align: center;
        padding: 0.5rem;
        color: #6c757d;
        font-weight: 600;
        cursor: default;
        position: relative;
    }
    .wizard-step.active {
        color: #dc3545;
    }
    .wizard-step.active::after {
        content: '';
        position: absolute;
        bottom: -1rem;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #dc3545;
    }
    .wizard-step.completed {
        color: #28a745;
    }

    /* Dropzone Custom */
    .dropzone {
        border: 2px dashed #dc3545;
        border-radius: 5px;
        background: #f8f9fa;
        min-height: 150px;
        padding: 20px;
    }
    [data-theme="dark"] .dropzone {
        background: #2b2b2b;
        border-color: #dc3545;
    }
    .dropzone .dz-message {
        font-weight: 400;
        color: #6c757d;
    }
    .dropzone .dz-message .icon {
        font-size: 3rem;
        color: #dc3545;
        margin-bottom: 1rem;
    }

    /* Dropzone Hover Fix */
    .dropzone .dz-preview.dz-image-preview {
        background: transparent;
    }
    .dropzone .dz-preview:hover {
        z-index: 1000;
    }
    .dropzone .dz-preview .dz-image {
        border-radius: 8px;
    }
    .dropzone.dz-started .dz-message {
        display: none;
    }
    /* Ocultar barra de carga y marcas para que no ensucien la previsualización local */
    .dropzone .dz-preview .dz-progress,
    .dropzone .dz-preview .dz-success-mark,
    .dropzone .dz-preview .dz-error-mark {
        display: none !important;
    }
    .dropzone .dz-preview:hover .dz-image img {
        filter: none;
        transform: none;
    }

    /* Cards Style */
    .section-card {
        border: none;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .section-card .card-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    .section-card .card-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    /* Choices Overrides - Z-index FIX */
    .choices { position: relative; margin-bottom: 0; }
    .choices__inner { 
        position: relative; 
        min-height: 38px;
        padding: 4px 7.5px;
        font-size: 0.875rem;
        border-radius: 0.25rem;
    }
    .choices__list--dropdown { z-index: 999999 !important; position: absolute !important; }
    .choices.is-open { z-index: 999999 !important; }
    .choices.is-open .choices__inner { z-index: 999999 !important; }
    .choices__list--dropdown.is-active { z-index: 999999 !important; }
    .card-body { overflow: visible !important; }
    .card, .section-card, .col-md-6, .col-md-12, .col-lg-7, .col-lg-5, .col-12, .row { position: relative; }
    .card, .section-card { overflow: visible !important; }

    /* Custom Switch Toggle */
    .custom-switch-lg .form-check-input {
        width: 3.5rem;
        height: 1.75rem;
        background-color: #adb5bd;
        border-color: #adb5bd;
    }
    .custom-switch-lg .form-check-input:checked {
        background-color: #adb5bd;
        border-color: #adb5bd;
    }
    .custom-switch-lg .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(173, 181, 189, 0.25);
    }
    .lbl-active {
        font-weight: 700;
        color: #dc3545 !important;
        transition: all 0.2s;
    }
    .lbl-inactive {
        font-weight: 400;
        color: #adb5bd !important;
        transition: all 0.2s;
    }

    /* Dropzone Cover Custom UX/UI */
    .dropzone .dz-preview {
        position: relative !important;
    }
    .dropzone .dz-preview .dz-cover-badge {
        position: absolute;
        top: 6px;
        left: 6px;
        z-index: 50;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        user-select: none;
    }
    .dropzone .dz-preview .dz-cover-badge.is-cover {
        background: #28a745 !important;
        color: #fff !important;
        border: 1px solid #218838;
    }
    .dropzone .dz-preview .dz-cover-badge.not-cover {
        background: rgba(0, 0, 0, 0.6) !important;
        color: rgba(255, 255, 255, 0.9) !important;
        opacity: 0;
    }
    .dropzone .dz-preview:hover .dz-cover-badge.not-cover {
        opacity: 1;
    }
    .dropzone .dz-preview.has-cover-border .dz-image {
        border: 3px solid #28a745 !important;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.4) !important;
    }
</style>
@endsection

@section('content')
<div class="container pt-4 pb-5">
    
    <!-- Encabezado / Logo -->
    <div class="text-center mb-4">
        <a class="logo" href="{{ route('index') }}">
            <img class="img-fluid for-light" src="{{asset('assets/images/logo/logo_check360.png')}}" alt="Check 360" style="width: 12rem;">
            <img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_check360.png')}}" alt="Check 360" style="width: 12rem;">
        </a>
        <h4 class="mt-3 mb-1">Registrar mi Restaurante</h4>
        <p class="text-muted small">Únete a nuestra plataforma y recibe evaluaciones periódicas de Mistery Shoppers</p>
    </div>

    <!-- Indicador de Pasos -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="wizard-container shadow-sm">
                <div class="wizard-steps">
                    <div class="wizard-step active" id="step1-indicator">
                        <i class="icofont icofont-home"></i> 1. Datos del Restaurante
                    </div>
                    <div class="wizard-step" id="step2-indicator">
                        <i class="icofont icofont-list"></i> 2. Sobre su Restaurante
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <form id="restaurante-register-form" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- ================= PASO 1 ================= -->
                <div id="step-1">
                    <div class="row">
                        
                        <!-- Columna Izquierda (7) -->
                        <div class="col-lg-7">
                            
                            <!-- Card: Datos Básicos -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-id-card"></i> Datos Básicos del Local</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Nombre del Restaurante (*)</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Ej: Bella Italia">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email de contacto</label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="contacto@bellaitalia.cl">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Teléfono de contacto</label>
                                            <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ej: +56912345678">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de cocina</label>
                                            <select name="tipo_cocina_id" id="tipo_cocina_id" class="form-select">
                                                <option value="">Seleccione tipo...</option>
                                                @foreach($tipos_cocina as $tc)
                                                    <option value="{{$tc->id}}">{{$tc->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Rango de ticket promedio</label>
                                            <select name="rango_ticket_promedio" id="rango_ticket_promedio" class="form-select">
                                                <option value="">Seleccione rango...</option>
                                                <option value="Bajo ($10.000 - $30.000)">Bajo ($10.000 - $30.000)</option>
                                                <option value="Medio ($30.000 - $60.000)">Medio ($30.000 - $60.000)</option>
                                                <option value="Alto (Mas de $70.000)">Alto (Mas de $70.000)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Capacidad (cantidad de mesas)</label>
                                            <input type="number" name="capacidad_restaurante" id="capacidad_restaurante" class="form-control" placeholder="Ej: 20">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Descuento Mistery Shopper (*)</label>
                                            <input type="number" name="porcentaje_descuento" id="porcentaje_descuento" class="form-control" min="50" max="100" value="50" required>
                                            <div class="form-text small">Mínimo 50%, máximo 100%.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Ubicación -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-location-pin"></i> Ubicación</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Región (*)</label>
                                            <select name="region_id" id="region_id" class="form-select" required>
                                                <option value="">Seleccione región...</option>
                                                @foreach($regiones as $region)
                                                    <option value="{{ $region->id }}">{{ $region->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ciudad/Comuna (*)</label>
                                            <select name="ciudad_id" id="ciudad_id" class="form-select" disabled required>
                                                <option value="">Seleccione ciudad...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Dirección (*)</label>
                                            <input type="text" name="direccion" id="direccion" class="form-control" required placeholder="Ej: Av. Providencia 1234">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Fotos -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-camera-alt"></i> Fotos del Restaurante</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Imágenes del Local (*) (Mínimo 1)</label>
                                            <div class="dropzone" id="dzLocal">
                                                <div class="dz-message" data-dz-message>
                                                    <div class="icon"><i class="icofont icofont-camera-alt"></i></div>
                                                    <span>Arrastra fotos del local aquí o haz clic para subir</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha (5) -->
                        <div class="col-lg-5">
                            
                            <!-- Card: Logo -->
                            <div class="card section-card shadow-sm" style="z-index: 10;">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-picture"></i> Logo del Restaurante</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div id="logo-preview-container" style="width: 140px; height: 140px; border-radius: 50%; border: 2px dashed #ccc; margin: 0 auto; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa;">
                                            <i class="icofont icofont-restaurant" style="font-size: 3rem; color: #ccc;" id="logo-placeholder"></i>
                                            <img id="logo-image" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" />
                                        </div>
                                    </div>
                                    <input type="file" name="logo_file" id="logo_file" class="d-none" accept="image/*">
                                    <button type="button" class="btn btn-outline-primary btn-sm px-4 rounded-pill" onclick="document.getElementById('logo_file').click();" id="btn-logo-upload">
                                        <i class="icofont icofont-upload-alt"></i> Subir Logo
                                    </button>
                                    <div class="form-text small mt-2 text-muted">Opcional. Se recomienda imagen cuadrada (JPG, PNG).</div>
                                </div>
                            </div>

                            <!-- Card: Redes Sociales -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-share"></i> Redes Sociales</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Facebook URL</label>
                                        <input type="url" name="social_facebook" class="form-control" placeholder="https://facebook.com/mi_restaurante">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Instagram URL</label>
                                        <input type="url" name="social_instagram" class="form-control" placeholder="https://instagram.com/mi_restaurante">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">TikTok URL</label>
                                        <input type="url" name="social_tiktok" class="form-control" placeholder="https://tiktok.com/@mi_restaurante">
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Datos Administrador -->
                            <div class="card section-card shadow-sm border border-primary">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-user"></i> Datos del Administrador</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Registra los datos de la persona que administrará el panel web del restaurante.</p>
                                    <div class="mb-3">
                                        <label class="form-label">Nombre del Administrador (*)</label>
                                        <input type="text" name="admin_name" id="admin_name" class="form-control" required placeholder="Ej: Juan Pérez">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Correo electrónico (*)</label>
                                        <input type="email" name="admin_email" id="admin_email" class="form-control" required placeholder="Ej: juan@bellaitalia.cl">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Botón Siguiente -->
                        <div class="col-12 text-end mb-4">
                            <a href="{{ route('loginX') }}" class="btn btn-outline-secondary float-start">Volver al login</a>
                            <button type="button" class="btn btn-primary px-5 py-2 btn-lg" id="btn-next">Siguiente Paso <i class="icofont icofont-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- ================= PASO 2 ================= -->
                <div id="step-2" class="hidden">
                    <div class="row">
                        
                        <!-- Full Width: Horarios (Movido a Step 2) -->
                        <div class="col-12">
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-clock-time"></i> Horarios de Atención y Peak</h5>
                                    <p class="text-muted small mb-0 mt-1">Ingresa el horario de apertura y cierre. El sistema generará opciones para que selecciones el horario de mayor afluencia (Peak).</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $diasSemana = ['lunes' => 'Lunes', 'martes' => 'Martes', 'miercoles' => 'Miércoles', 'jueves' => 'Jueves', 'viernes' => 'Viernes', 'sabado' => 'Sábado', 'domingo' => 'Domingo'];
                                        @endphp
                                        @foreach($diasSemana as $key => $label)
                                        <div class="col-xl-4 col-md-6 mb-3">
                                            <div class="horario-card shadow-xs">
                                                <h6 class="fw-bold border-bottom pb-2">{{ $label }}</h6>
                                                <div class="row g-2 mb-2">
                                                    <div class="col-6">
                                                        <label class="small text-muted mb-0">Apertura</label>
                                                        <input type="time" name="horario_{{ $key }}_apertura" id="apertura_{{ $key }}" class="form-control form-control-sm horario-trigger" data-dia="{{ $key }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="small text-muted mb-0">Cierre</label>
                                                        <input type="time" name="horario_{{ $key }}_cierre" id="cierre_{{ $key }}" class="form-control form-control-sm horario-trigger" data-dia="{{ $key }}">
                                                    </div>
                                                </div>
                                                <div class="peak-container hidden" id="peak_container_{{ $key }}">
                                                    <label class="small fw-bold text-primary mb-0 mt-2">Seleccionar Horario Peak:</label>
                                                    <select name="peak_{{ $key }}" id="peak_select_{{ $key }}" class="form-select form-select-sm mb-2">
                                                        <!-- Opciones generadas dinámicamente -->
                                                    </select>
                                                    <div class="form-check form-switch pt-1">
                                                        <input class="form-check-input" type="checkbox" name="peak_{{ $key }}_ocupa_90" id="peak_{{ $key }}_ocupa_90" value="1">
                                                        <label class="form-check-label small" for="peak_{{ $key }}_ocupa_90">¿Ocupa 90%+ capacidad?</label>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="peak_{{ $key }}_desde" id="peak_desde_{{ $key }}">
                                                <input type="hidden" name="peak_{{ $key }}_hasta" id="peak_hasta_{{ $key }}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Izquierda (7) -->
                        <div class="col-lg-7">
                            
                            <!-- Card: Formato de Carta -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-file-text"></i> Carta del Restaurante</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label mb-2 d-block">Formato de la Carta (*)</label>
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="lbl-active me-2" id="lbl_img">Imágenes</span>
                                                <div class="form-check form-switch custom-switch-lg mx-1">
                                                    <input class="form-check-input shadow-none" type="checkbox" id="carta_tipo_switch" style="cursor:pointer;">
                                                </div>
                                                <span class="lbl-inactive ms-2" id="lbl_url">URL (Enlace Web)</span>
                                            </div>
                                            <input type="hidden" name="carta_tipo" id="carta_tipo" value="imagenes">
                                        </div>
                                        
                                        <div class="col-md-12 mb-4 hidden" id="carta_url_container">
                                            <label class="form-label">URL de la Carta (*)</label>
                                            <input type="url" name="carta_url" id="carta_url" class="form-control" placeholder="https://bellaitalia.cl/menu.pdf">
                                        </div>

                                        <div class="col-md-12 mb-2" id="carta_files_container">
                                            <label class="form-label">Imágenes de la Carta (*)</label>
                                            <div class="dropzone" id="dzCarta" style="min-height: 120px; padding: 10px;">
                                                <div class="dz-message" data-dz-message>
                                                    <div class="icon" style="font-size: 2rem;"><i class="icofont icofont-upload-alt"></i></div>
                                                    <span>Arrastra las imágenes de tu carta aquí o haz clic para subir</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Público Objetivo -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-users-alt-2"></i> Público Objetivo</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Edad promedio clientes</label>
                                            <select class="form-select form-select-sm" name="opciones[edad_promedio_clientes]" id="edad_promedio_clientes">
                                                <option value="">Sin definir</option>
                                                <option value="18-25">18-25 años</option>
                                                <option value="26-35">26-35 años</option>
                                                <option value="36-50">36-50 años</option>
                                                <option value="50+">Mas de 50 años</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Perfil socioeconómico predominante</label>
                                            <select class="form-select form-select-sm" name="opciones[perfil_socioeconomico_predominante]" id="perfil_socioeconomico_predominante">
                                                <option value="">Sin definir</option>
                                                <option value="Alto">Alto</option>
                                                <option value="Medio alto">Medio alto</option>
                                                <option value="Medio">Medio</option>
                                                <option value="Medio bajo">Medio bajo</option>
                                                <option value="Bajo">Bajo</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Lugar residencia principal clientes</label>
                                            <select class="form-select form-select-sm" name="opciones[lugar_residencia_principal_clientes]" id="lugar_residencia_principal_clientes">
                                                <option value="">Sin definir</option>
                                                <option value="Residentes locales">Residentes locales</option>
                                                <option value="Visitantes otras ciudades">Visitantes de otras ciudades del país</option>
                                                <option value="Turistas internacionales">Turistas internacionales</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Motivos visita restaurante</label>
                                            <select multiple class="form-select form-select-sm" name="opciones[motivos_visita_restaurante][]" id="motivos_visita_restaurante">
                                                <option value="Comida diaria">Comida diaria (almuerzos, cenas casuales)</option>
                                                <option value="Celebraciones">Celebraciones (Cumpleaños, Aniversarios, Eventos)</option>
                                                <option value="Reuniones negocios">Reuniones de negocios</option>
                                                <option value="Turistas gastronomia local">Turistas buscando gastronomia local</option>
                                                <option value="Otros">Otros</option>
                                            </select>
                                            <input name="opciones[motivos_visita_restaurante_otros]" class="form-control form-control-sm mt-2 hidden" id="motivos_visita_restaurante_otros" placeholder="Si selecciona Otros, detallar">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Estilo de vida clientes</label>
                                            <select multiple class="form-select form-select-sm" name="opciones[estilo_vida_clientes][]" id="estilo_vida_clientes">
                                                <option value="Jovenes universitarios">Jovenes universitarios</option>
                                                <option value="Familias con niño">Familias con niño</option>
                                                <option value="Parejas">Parejas</option>
                                                <option value="Grupos de amigos">Grupos de amigos</option>
                                                <option value="Profesionales o ejecutivos">Profesionales o ejecutivos</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Comportamiento habitual clientes</label>
                                            <select class="form-select form-select-sm" name="opciones[comportamiento_habitual_clientes]" id="comportamiento_habitual_clientes">
                                                <option value="">Sin definir</option>
                                                <option value="Reservan con anticipacion">Reservan con anticipación</option>
                                                <option value="Llegan sin reserva">Llegan sin reserva</option>
                                                <option value="Prefieren comidas rapidas">Prefieren comidas rápidas</option>
                                                <option value="Valoran experiencia completa">Valoran una experiencia gastronómica completa</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Columna Derecha (5) -->
                        <div class="col-lg-5">
                            
                            <!-- Card: Experiencia -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-star"></i> Experiencia del Cliente</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Ambiente / estilo</label>
                                        <select class="form-select form-select-sm" name="opciones[ambiente_estilo]" id="ambiente_estilo">
                                            <option value="">Sin definir</option>
                                            <option value="Casual">Casual</option>
                                            <option value="Elegante">Elegante</option>
                                            <option value="Tematico">Temático</option>
                                            <option value="Familiar">Familiar</option>
                                            <option value="Moderno">Moderno</option>
                                            <option value="Rustico">Rústico</option>
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Puntos fuertes</label>
                                        <select multiple class="form-select form-select-sm" name="opciones[puntos_fuertes][]" id="puntos_fuertes">
                                            <option value="Atencion al cliente">Atención al cliente</option>
                                            <option value="Calidad alimentos">Calidad de los alimentos</option>
                                            <option value="Velocidad servicio">Velocidad del servicio</option>
                                            <option value="Relacion precio-calidad">Relación precio-calidad</option>
                                            <option value="Decoracion y ambiente">Decoración y ambiente</option>
                                            <option value="Otros">Otros</option>
                                        </select>
                                        <input name="opciones[puntos_fuertes_otros]" class="form-control form-control-sm mt-2 hidden" id="puntos_fuertes_otros" placeholder="Si selecciona Otros, detallar">
                                    </div>
                                </div>
                            </div>

                            <!-- Card: Protocolos y Obs -->
                            <div class="card section-card shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="text-primary"><i class="icofont icofont-file-document"></i> Protocolos y Observaciones</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label d-block">¿Existen protocolos internos que el Mister Shopper deba conocer?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones[protocolos_internos][tiene]" id="protocolos_si" value="1">
                                            <label class="form-check-label" for="protocolos_si">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="opciones[protocolos_internos][tiene]" id="protocolos_no" value="0" checked>
                                            <label class="form-check-label" for="protocolos_no">No</label>
                                        </div>
                                        <div id="protocolos_detalle_container" class="mt-2 hidden">
                                            <textarea class="form-control form-control-sm" name="opciones[protocolos_internos][detalle]" id="protocolos_internos_detalle" rows="3" placeholder="Detalle los protocolos aquí..."></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Observaciones adicionales</label>
                                        <textarea class="form-control form-control-sm" name="opciones[observaciones_adicionales]" id="observaciones_adicionales" rows="3" placeholder="Indique cualquier información relevante..."></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Botones Finales -->
                        <div class="col-12 d-flex justify-content-between mb-5 mt-2">
                            <button type="button" class="btn btn-outline-secondary px-4 py-2" id="btn-prev"><i class="icofont icofont-arrow-left"></i> Atrás</button>
                            <button type="submit" class="btn btn-success px-5 py-2 btn-lg" id="submit-btn">Completar Registro <i class="icofont icofont-check-circled"></i></button>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;
    var dzCarta, dzLocal;
    var choicesInstances = {};

    function initChoices(selectId, isMultiple = false) {
        var element = document.getElementById(selectId);
        if (!element) return;
        var choices = new Choices(element, {
            removeItemButton: isMultiple,
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: 'Seleccione...'
        });
        
        // Z-Index fix event listeners
        choices.passedElement.element.addEventListener('showDropdown', function() {
            var dropdown = element.closest('.choices').querySelector('.choices__list--dropdown');
            if(dropdown) {
                dropdown.style.setProperty('z-index', '999999', 'important');
                var choicesContainer = element.closest('.choices');
                if (choicesContainer) choicesContainer.style.setProperty('z-index', '999999', 'important');
            }
            var card = element.closest('.card');
            if(card) card.style.zIndex = '50';
        });
        choices.passedElement.element.addEventListener('hideDropdown', function() {
            var choicesContainer = element.closest('.choices');
            if (choicesContainer) choicesContainer.style.zIndex = '';
            var card = element.closest('.card');
            if(card) card.style.zIndex = '';
        });

        choicesInstances[selectId] = choices;
        return choices;
    }

    function cargarCiudades() {
        var regionId = $('#region_id').val();
        var ciudadSelect = $('#ciudad_id');
        
        if (choicesInstances['ciudad_id']) {
            choicesInstances['ciudad_id'].destroy();
            choicesInstances['ciudad_id'] = null;
        }

        if (!regionId) {
            ciudadSelect.html('<option value="">Seleccione ciudad...</option>').prop('disabled', true);
            initChoices('ciudad_id', false);
            return;
        }

        ciudadSelect.html('<option value="">Cargando...</option>').prop('disabled', true);

        $.ajax({
            url: '{{ route("restaurantes.get_ciudades") }}',
            method: 'POST',
            data: { region_id: regionId, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if(res.estado == 200 && res.ciudades) {
                    var options = '<option value="">Seleccione ciudad...</option>';
                    $.each(res.ciudades, function(index, ciudad) {
                        options += '<option value="' + ciudad.id + '">' + ciudad.nombre + '</option>';
                    });
                    ciudadSelect.html(options).prop('disabled', false);
                    setTimeout(function() { initChoices('ciudad_id', false); }, 50);
                } else {
                    ciudadSelect.html('<option value="">No ciudades</option>').prop('disabled', true);
                    initChoices('ciudad_id', false);
                }
            }
        });
    }

    // Calcular Intervalos de Horarios Peak
    function generarIntervalos(desde, hasta) {
        if (!desde || !hasta) return [];
        let intervalos = [];
        let [hDesde, mDesde] = desde.split(':').map(Number);
        let [hHasta, mHasta] = hasta.split(':').map(Number);
        
        let minDesdeTotal = hDesde * 60 + mDesde;
        let minHastaTotal = hHasta * 60 + mHasta;
        
        if (minHastaTotal <= minDesdeTotal) {
            minHastaTotal += 24 * 60; // Pasa de medianoche
        }
        
        let curr = minDesdeTotal;
        while (curr + 60 <= minHastaTotal) {
            let nH1 = Math.floor(curr / 60) % 24;
            let nM1 = curr % 60;
            let nH2 = Math.floor((curr + 60) / 60) % 24;
            let nM2 = (curr + 60) % 60;
            
            let s1 = (nH1 < 10 ? '0' : '') + nH1 + ':' + (nM1 < 10 ? '0' : '') + nM1;
            let s2 = (nH2 < 10 ? '0' : '') + nH2 + ':' + (nM2 < 10 ? '0' : '') + nM2;
            
            intervalos.push(`${s1}-${s2}`);
            curr += 60; // avanzar 1 hora
        }
        return intervalos;
    }

    $(document).ready(function() {
        // Inicializar Choices
        initChoices('tipo_cocina_id', false);
        initChoices('rango_ticket_promedio', false);
        initChoices('region_id', false);
        initChoices('ciudad_id', false);

        initChoices('edad_promedio_clientes', false);
        initChoices('perfil_socioeconomico_predominante', false);
        initChoices('lugar_residencia_principal_clientes', false);
        initChoices('comportamiento_habitual_clientes', false);
        initChoices('ambiente_estilo', false);
        
        initChoices('motivos_visita_restaurante', true);
        initChoices('estilo_vida_clientes', true);
        initChoices('puntos_fuertes', true);

        $('#region_id').on('change', cargarCiudades);

        // Lógica de "Otros"
        function toggleOtros(selectId, inputId) {
            var choices = choicesInstances[selectId];
            if(!choices) return;
            var val = choices.getValue(true) || [];
            if(Array.isArray(val) ? val.includes('Otros') : val == 'Otros') {
                $('#'+inputId).removeClass('hidden');
            } else {
                $('#'+inputId).addClass('hidden').val('');
            }
        }
        if(choicesInstances['motivos_visita_restaurante']) {
            choicesInstances['motivos_visita_restaurante'].passedElement.element.addEventListener('change', function() { toggleOtros('motivos_visita_restaurante','motivos_visita_restaurante_otros'); });
        }
        if(choicesInstances['puntos_fuertes']) {
            choicesInstances['puntos_fuertes'].passedElement.element.addEventListener('change', function() { toggleOtros('puntos_fuertes','puntos_fuertes_otros'); });
        }

        // Switch de Carta (Imágenes / URL)
        $('#carta_tipo_switch').on('change', function() {
            if($(this).is(':checked')) {
                // URL
                $('#lbl_url').removeClass('lbl-inactive').addClass('lbl-active');
                $('#lbl_img').removeClass('lbl-active').addClass('lbl-inactive');
                $('#carta_tipo').val('url');
                $('#carta_url_container').removeClass('hidden');
                $('#carta_files_container').addClass('hidden');
            } else {
                // Imágenes
                $('#lbl_img').removeClass('lbl-inactive').addClass('lbl-active');
                $('#lbl_url').removeClass('lbl-active').addClass('lbl-inactive');
                $('#carta_tipo').val('imagenes');
                $('#carta_url_container').addClass('hidden');
                $('#carta_files_container').removeClass('hidden');
            }
        });

        // Dropzones cover photo selection custom logic
        var coverFile = null;

        function updateCoverVisuals() {
            var files = dzLocal.getAcceptedFiles();
            if (files.length === 0) {
                coverFile = null;
                return;
            }
            
            // If no cover is active or the current cover was deleted, default to first file
            if (!coverFile || files.indexOf(coverFile) === -1) {
                coverFile = files[0];
            }

            $.each(dzLocal.files, function(i, file) {
                var preview = $(file.previewElement);
                if (file === coverFile) {
                    preview.addClass('has-cover-border');
                    preview.find('.dz-cover-badge')
                        .removeClass('not-cover')
                        .addClass('is-cover')
                        .html('<i class="icofont icofont-star"></i> Portada');
                } else {
                    preview.removeClass('has-cover-border');
                    preview.find('.dz-cover-badge')
                        .removeClass('is-cover')
                        .addClass('not-cover')
                        .text('Hacer Portada');
                }
            });
        }

        dzCarta = new Dropzone("#dzCarta", {
            url: "#", 
            autoProcessQueue: false,
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            dictRemoveFile: "Eliminar",
            init: function() {
                this.on("addedfile", function(file) {
                    $(file.previewElement).find('.dz-image img').removeAttr('title').removeAttr('alt');
                });
            }
        });

        dzLocal = new Dropzone("#dzLocal", {
            url: "#", 
            autoProcessQueue: false,
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            dictRemoveFile: "Eliminar",
            init: function() {
                var self = this;
                
                this.on("addedfile", function(file) {
                    $(file.previewElement).find('.dz-image img').removeAttr('title').removeAttr('alt');
                    
                    // Inject visual cover badge
                    var badge = $('<div class="dz-cover-badge not-cover">Hacer Portada</div>');
                    $(file.previewElement).append(badge);

                    // Clicking the badge or the image itself selects it as the new cover
                    $(file.previewElement).find('.dz-cover-badge, .dz-image').css('cursor', 'pointer').on('click', function(e) {
                        e.stopPropagation();
                        coverFile = file;
                        updateCoverVisuals();
                    });

                    updateCoverVisuals();
                });

                this.on("removedfile", function(file) {
                    if (file === coverFile) {
                        coverFile = null;
                    }
                    updateCoverVisuals();
                });
            }
        });

        // Logo Upload Preview
        $('#logo_file').change(function(e) {
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo-placeholder').hide();
                    $('#logo-image').attr('src', e.target.result).show();
                    $('#btn-logo-upload').html('<i class="icofont icofont-refresh"></i> Cambiar Logo');
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Horarios Peak Dinámicos
        $('.horario-trigger').on('change', function() {
            var dia = $(this).data('dia');
            var apertura = $('#apertura_' + dia).val();
            var cierre = $('#cierre_' + dia).val();
            var container = $('#peak_container_' + dia);
            var select = $('#peak_select_' + dia);

            if(apertura && cierre) {
                var intervalos = generarIntervalos(apertura, cierre);
                select.empty();
                select.append('<option value="">Seleccione...</option>');
                intervalos.forEach(function(int) {
                    select.append('<option value="' + int + '">' + int + '</option>');
                });
                container.removeClass('hidden');
            } else {
                container.addClass('hidden');
            }
        });

        $('#restaurante-register-form').on('change', 'select[id^="peak_select_"]', function() {
            var dia = $(this).attr('id').replace('peak_select_', '');
            var val = $(this).val();
            if(val) {
                var partes = val.split('-');
                $('#peak_desde_' + dia).val(partes[0]);
                $('#peak_hasta_' + dia).val(partes[1]);
            } else {
                $('#peak_desde_' + dia).val('');
                $('#peak_hasta_' + dia).val('');
            }
        });

        // Protocolos
        $('input[name="opciones[protocolos_internos][tiene]"]').on('change', function() {
            if($(this).val() == '1') {
                $('#protocolos_detalle_container').removeClass('hidden');
            } else {
                $('#protocolos_detalle_container').addClass('hidden');
            }
        });

        function validarPaso1() {
            var nombre = $('#nombre').val();
            if(!nombre || nombre.trim() === '') {
                notify('Atención', 'Debe ingresar el nombre del restaurante', 'warning');
                $('#nombre').focus();
                return false;
            }

            var desc = parseInt($('#porcentaje_descuento').val());
            if(isNaN(desc) || desc < 50 || desc > 100) {
                notify('Atención', 'El descuento de Mystery Shopper debe estar entre 50% y 100%', 'warning');
                $('#porcentaje_descuento').focus();
                return false;
            }

            if(!$('#region_id').val()) {
                notify('Atención', 'Debe seleccionar una región', 'warning');
                return false;
            }

            if(!$('#ciudad_id').val()) {
                notify('Atención', 'Debe seleccionar una ciudad/comuna', 'warning');
                return false;
            }

            var direccion = $('#direccion').val();
            if(!direccion || direccion.trim() === '') {
                notify('Atención', 'Debe ingresar la dirección del restaurante', 'warning');
                $('#direccion').focus();
                return false;
            }

            if(dzLocal.getAcceptedFiles().length === 0) {
                notify('Atención', 'Debes subir al menos 1 imagen real del local', 'warning');
                return false;
            }

            var adminName = $('#admin_name').val();
            if(!adminName || adminName.trim() === '') {
                notify('Atención', 'Debe ingresar el nombre del administrador', 'warning');
                $('#admin_name').focus();
                return false;
            }

            var adminEmail = $('#admin_email').val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!adminEmail || !emailRegex.test(adminEmail.trim())) {
                notify('Atención', 'Debe ingresar un correo electrónico válido para el administrador', 'warning');
                $('#admin_email').focus();
                return false;
            }

            return true;
        }

        function validarPaso2() {
            var cartaTipo = $('#carta_tipo').val();
            if (cartaTipo === 'imagenes') {
                if (dzCarta.getAcceptedFiles().length === 0) {
                    notify('Atención', 'Debes subir al menos 1 imagen de la carta', 'warning');
                    return false;
                }
            } else if (cartaTipo === 'url') {
                var cartaUrl = $('#carta_url').val();
                if (!cartaUrl || cartaUrl.trim() === '') {
                    notify('Atención', 'Debe ingresar la URL de la carta digital', 'warning');
                    $('#carta_url').focus();
                    return false;
                }
                if (!cartaUrl.trim().toLowerCase().startsWith('http://') && !cartaUrl.trim().toLowerCase().startsWith('https://')) {
                    notify('Atención', 'La URL de la carta debe comenzar con http:// o https://', 'warning');
                    $('#carta_url').focus();
                    return false;
                }
            }

            var dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
            var diasLabels = {
                lunes: 'Lunes',
                martes: 'Martes',
                miercoles: 'Miércoles',
                jueves: 'Jueves',
                viernes: 'Viernes',
                sabado: 'Sábado',
                domingo: 'Domingo'
            };

            for (var i = 0; i < dias.length; i++) {
                var dia = dias[i];
                var apertura = $('#apertura_' + dia).val();
                var cierre = $('#cierre_' + dia).val();

                if ((apertura && !cierre) || (!apertura && cierre)) {
                    notify('Atención', 'Debe completar tanto el horario de apertura como de cierre para el día ' + diasLabels[dia], 'warning');
                    if (!apertura) $('#apertura_' + dia).focus();
                    else $('#cierre_' + dia).focus();
                    return false;
                }

                if (apertura && cierre) {
                    var peakVal = $('#peak_select_' + dia).val();
                    if (!peakVal || peakVal.trim() === '') {
                        notify('Atención', 'Debe seleccionar el horario peak para el día ' + diasLabels[dia], 'warning');
                        $('#peak_select_' + dia).focus();
                        return false;
                    }
                }
            }

            if ($('#protocolos_si').is(':checked')) {
                var protDetalle = $('#protocolos_internos_detalle').val();
                if (!protDetalle || protDetalle.trim() === '') {
                    notify('Atención', 'Debe detallar los protocolos internos requeridos', 'warning');
                    $('#protocolos_internos_detalle').focus();
                    return false;
                }
            }

            if (choicesInstances['motivos_visita_restaurante']) {
                var motivosVal = choicesInstances['motivos_visita_restaurante'].getValue(true) || [];
                if (motivosVal.includes('Otros')) {
                    var motivosOtros = $('#motivos_visita_restaurante_otros').val();
                    if (!motivosOtros || motivosOtros.trim() === '') {
                        notify('Atención', 'Debe detallar el otro motivo de visita seleccionado', 'warning');
                        $('#motivos_visita_restaurante_otros').focus();
                        return false;
                    }
                }
            }

            if (choicesInstances['puntos_fuertes']) {
                var puntosVal = choicesInstances['puntos_fuertes'].getValue(true) || [];
                if (puntosVal.includes('Otros')) {
                    var puntosOtros = $('#puntos_fuertes_otros').val();
                    if (!puntosOtros || puntosOtros.trim() === '') {
                        notify('Atención', 'Debe detallar los otros puntos fuertes seleccionados', 'warning');
                        $('#puntos_fuertes_otros').focus();
                        return false;
                    }
                }
            }

            return true;
        }

        // Wizard Navigation
        $('#btn-next').click(function() {
            // Validate Step 1
            if(!validarPaso1()) {
                return;
            }

            $('#step-1').addClass('hidden');
            $('#step-2').removeClass('hidden');
            $('#step1-indicator').removeClass('active').addClass('completed');
            $('#step2-indicator').addClass('active');
            window.scrollTo(0,0);
        });

        $('#btn-prev').click(function() {
            $('#step-2').addClass('hidden');
            $('#step-1').removeClass('hidden');
            $('#step2-indicator').removeClass('active');
            $('#step1-indicator').addClass('active').removeClass('completed');
            window.scrollTo(0,0);
        });

        // Submit Form
        $('#restaurante-register-form').submit(function(e) {
            e.preventDefault();
            var submitBtn = $('#submit-btn');
            
            // Re-validate Step 1
            if(!validarPaso1()) {
                $('#btn-prev').click();
                return;
            }

            // Validate Step 2
            if(!validarPaso2()) {
                return;
            }

            submitBtn.prop('disabled', true).html('Registrando... <i class="icofont icofont-spinner-alt-4"></i>');

            var formData = new FormData(this);
            
            // Append Dropzone files
            if($('#carta_tipo').val() == 'imagenes') {
                var cFiles = dzCarta.getAcceptedFiles();
                for(var i=0; i<cFiles.length; i++) { formData.append('carta_files[]', cFiles[i]); }
            }
            var lFiles = dzLocal.getAcceptedFiles();
            // Reorder the local files list so the selected coverFile is at index 0
            if (coverFile) {
                var cIdx = lFiles.indexOf(coverFile);
                if (cIdx !== -1) {
                    lFiles.splice(cIdx, 1);
                    lFiles.unshift(coverFile);
                }
            }
            for(var j=0; j<lFiles.length; j++) { formData.append('imagenes_files[]', lFiles[j]); }

            $.ajax({
                url: "{{ route('restaurante.registro.post') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.estado == 200) {
                        notify('Registro Exitoso', 'Tu restaurante ha sido registrado. Se ha enviado contraseña al administrador.', 'success', 5000);
                        setTimeout(function() { window.location.href = "{{ route('loginX') }}"; }, 3000);
                    } else {
                        submitBtn.prop('disabled', false).html('Completar Registro <i class="icofont icofont-check-circled"></i>');
                        notify('Error', res.mensaje || 'Ocurrió un error', 'danger');
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html('Completar Registro <i class="icofont icofont-check-circled"></i>');
                    var mensaje = 'Error al procesar el registro.';
                    if(xhr.responseJSON && xhr.responseJSON.mensaje) { mensaje = xhr.responseJSON.mensaje; }
                    notify('Error', mensaje, 'danger');
                }
            });
        });
    });
</script>
@endsection