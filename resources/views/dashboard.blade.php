@extends('layouts.master')
@section('title', 'Dashboard')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
<style>
    .rotate-refresh {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
@endsection

@section('content')
@if(isset($tipo) && $tipo === 'shopper')
<div class="container">
@else
<div class="container-fluid">
@endif
    @if(isset($tipo) && $tipo === 'sistema')
    <!-- Dashboard Sistema -->
    <div class="row">
        <!-- Tarjetas de estadísticas principales -->
        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-primary">
                                <i class="icofont icofont-calendar" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Visitas Totales</h6>
                            <h4 class="mb-0">{{ $visitas_totales ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-success">
                                <i class="icofont icofont-check-circled" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Visita Completada</h6>
                            <h4 class="mb-0">{{ $visitas_completadas ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-warning">
                                <i class="icofont icofont-clock-time" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Pendientes</h6>
                            <h4 class="mb-0">{{ $visitas_pendientes ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-info">
                                <i class="icofont icofont-spinner-alt-3" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">En Espera de Visita</h6>
                            <h4 class="mb-0">{{ $visitas_en_espera ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Estadísticas secundarias -->
        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-danger">
                                <i class="icofont icofont-close-circled" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">No Realizadas</h6>
                            <h4 class="mb-0">{{ $visitas_no_realizadas ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-dark">
                                <i class="icofont icofont-ban" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Rechazadas</h6>
                            <h4 class="mb-0">{{ $visitas_rechazadas ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-secondary">
                                <i class="icofont icofont-restaurant" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Restaurantes</h6>
                            <h4 class="mb-0">{{ $restaurantes_activos ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="dashboard-icon bg-primary">
                                <i class="icofont icofont-users-alt-5" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Mistery Shoppers</h6>
                            <h4 class="mb-0">{{ $shoppers_activos ?? 0 }} / {{ $shoppers_totales ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de visitas por estado -->
        <div class="col-xl-4 col-md-12 box-col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Distribución de Visitas por Estado</h5>
                </div>
                <div class="card-body">
                    @if(($visitas_totales ?? 0) > 0)
                        <div id="chart-visitas-estado"></div>
                    @else
                        <div class="text-center py-5">
                            <i class="icofont icofont-bar-chart" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">No hay datos para mostrar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Análisis de Percepción Nacional -->
        <div class="col-xl-8 col-md-12 box-col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Análisis de Percepción Nacional</h5>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">Consolidado general de percepción (Expectativa vs Experiencia) de todos los restaurantes evaluados.</p>
                </div>
                <div class="card-body">
                    <div id="sistema-radar-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitas recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Visitas Recientes</h5>
                    <a href="{{ route('visitas.lista') }}" class="btn btn-primary btn-sm">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mistery Shopper</th>
                                    <th>Restaurante</th>
                                    <th>Fecha Asignación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($visitas_recientes ?? [] as $visita)
                                <tr>
                                    <td>#{{ $visita->id }}</td>
                                    <td>{{ $visita->shopper ? $visita->shopper->name : 'N/A' }}</td>
                                    <td>{{ $visita->restaurante ? $visita->restaurante->name : 'N/A' }}</td>
                                    <td>{{ $visita->fecha_asignacion ? \Carbon\Carbon::parse($visita->fecha_asignacion)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if($visita->estado)
                                            @php
                                                $estadoColor = 'secondary';
                                                if($visita->estado_id == 1) $estadoColor = 'warning';
                                                elseif($visita->estado_id == 2) $estadoColor = 'info';
                                                elseif($visita->estado_id == 3) $estadoColor = 'primary';
                                                elseif($visita->estado_id == 4) $estadoColor = 'success';
                                                elseif($visita->estado_id == 5) $estadoColor = 'danger';
                                                elseif($visita->estado_id == 6) $estadoColor = 'dark';
                                            @endphp
                                            <span class="badge badge-{{ $estadoColor }}">{{ $visita->estado->nombre }}</span>
                                        @else
                                            <span class="badge badge-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('visitas.ver', encrypt($visita->id)) }}" class="btn btn-sm btn-primary">
                                            <i class="icofont icofont-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay visitas recientes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @elseif(isset($tipo) && $tipo === 'restaurante')
    <!-- Dashboard Restaurante -->
    
    <!-- Trazabilidad del Plan Activo -->
    <div class="row mb-4">
        <!-- Tarjeta Progreso Visitas -->
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm border-0" style="border-radius: 16px; background: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0 font-weight-bold">Progreso de Visitas</h6>
                        <span class="badge bg-primary rounded-pill px-3 py-1">{{ $visitasPeriodo }} / 12</span>
                    </div>
                    <div class="progress mb-3" style="height: 12px; border-radius: 50px;">
                        @php
                            $progresoVisitas = min(100, ($visitasPeriodo / 12) * 100);
                        @endphp
                        <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {{ $progresoVisitas }}%; border-radius: 50px;" aria-valuenow="{{ $progresoVisitas }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="small text-muted mb-0"><i class="fa fa-info-circle text-primary"></i> Límite de 12 visitas por periodo de 60 días. El periodo se reinicia automáticamente.</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta Días Restantes -->
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm border-0" style="border-radius: 16px; background: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0 font-weight-bold">Días Restantes en Periodo</h6>
                        <span class="badge bg-warning rounded-pill px-3 py-1 text-dark">{{ $diasRestantes }} / 60</span>
                    </div>
                    <div class="progress mb-3" style="height: 12px; border-radius: 50px;">
                        @php
                            $progresoDias = min(100, ($diasRestantes / 60) * 100);
                        @endphp
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progresoDias }}%; border-radius: 50px;" aria-valuenow="{{ $progresoDias }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="small text-muted mb-0"><i class="fa fa-calendar text-warning"></i> Periodo activo de 60 días de evaluación. Días restantes para el reinicio.</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta Estado del Plan -->
        <div class="col-xl-4 col-md-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px; background: white;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-muted mb-0 font-weight-bold">Estado del Plan de 6 Meses</h6>
                        @if($restaurante->plan_activo)
                            <span class="badge bg-success rounded-pill px-3 py-1">Vigente</span>
                        @else
                            <span class="badge bg-danger rounded-pill px-3 py-1">Inactivo</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Inicio: {{ $restaurante->plan_inicio ? \Carbon\Carbon::parse($restaurante->plan_inicio)->format('d/m/Y') : 'N/A' }}</span>
                        <span>Término: {{ $restaurante->plan_fin ? \Carbon\Carbon::parse($restaurante->plan_fin)->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <p class="small text-muted mb-0"><i class="fa fa-hourglass-half text-success"></i> El plan general dura 6 meses y agrupa múltiples periodos de 60 días.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Panel de Resultados (Izquierda) -->
        <div class="col-xl-6 col-md-12">
            <div class="card shadow-lg border-0 h-100 overflow-hidden">
                <div class="card-header bg-gradient-primary py-3">
                    <h5 class="text-white mb-0"><i class="icofont icofont-restaurant"></i> Panel de Resultados: {{ $restaurante->name ?? 'Restaurante' }}</h5>
                    <small class="text-white-50">{{ $periodo }}</small>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover mb-0 custom-table">
                            <thead>
                                <tr class="bg-light dark-table-header">
                                    <th class="ps-3 py-3" style="font-size: 0.75rem;">DIMENSIÓN</th>
                                    <th class="text-center py-3" style="color: #1a5276; font-size: 0.75rem;">EXPECTATIVA</th>
                                    <th class="text-center py-3" style="color: #e67e22; font-size: 0.75rem;">EXPERIENCIA</th>
                                </tr>
                            </thead>
                            <tbody class="border-0">
                                @foreach($estadisticas as $stat)
                                <tr class="align-middle">
                                    <td class="ps-3 fw-bold theme-text-color" style="font-size: 0.85rem;">{{ $stat['label'] }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-1" style="background-color: rgba(26, 82, 118, 0.1); color: #5dade2; font-size: 0.85rem; border: 1px solid rgba(93, 173, 226, 0.3);">
                                            {{ number_format($stat['expectativa'], 1) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-1" style="background-color: rgba(230, 126, 34, 0.1); color: #f39c12; font-size: 0.85rem; border: 1px solid rgba(243, 156, 18, 0.3);">
                                            {{ number_format($stat['experiencia'], 1) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-light dark-table-footer py-3 border-top d-flex justify-content-between align-items-center px-4 mt-auto fw-bold">
                        <span class="theme-text-color" style="font-size: 0.8rem; letter-spacing: 0.5px;">PUNTAJE PROMEDIO</span>
                        <div class="d-flex gap-4">
                            <div class="text-center">
                                <small class="d-block text-muted" style="font-size: 0.6rem;">EXPECTATIVA</small>
                                <span style="color: #5dade2; font-size: 1.2rem;">{{ number_format($total_expectativa, 2) }}</span>
                            </div>
                            <div class="text-center">
                                <small class="d-block text-muted" style="font-size: 0.6rem;">EXPERIENCIA</small>
                                <span style="color: #f39c12; font-size: 1.2rem;">{{ number_format($total_experiencia, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Análisis de Percepción (Derecha) -->
        <div class="col-xl-6 col-md-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-primary">Análisis de Percepción</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div id="restaurante-radar-chart" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gap de Satisfacción y Trazabilidad (Full Width) -->
    <div class="row mt-4">
        <div class="col-xl-6 col-md-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-primary">Gap de Satisfacción (Experiencia vs Expectativa)</h5>
                </div>
                <div class="card-body">
                    <div id="restaurante-gap-chart"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-12">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="fa fa-users text-primary me-2"></i> Trazabilidad de Visitas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-sm">
                            <thead>
                                <tr class="text-muted" style="font-size: 0.75rem;">
                                    <th>Ref.</th>
                                    <th>Evaluador</th>
                                    <th>Fecha</th>
                                    <th>Código</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todasVisitas->take(5) ?? [] as $vis)
                                <tr style="font-size: 0.85rem;">
                                    <td class="font-weight-bold text-dark">#{{ str_pad($vis->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <span class="text-muted"><i class="fa fa-user-secret me-1"></i> <em>Anónimo</em></span>
                                    </td>
                                    <td>{{ $vis->fecha_asignacion ? \Carbon\Carbon::parse($vis->fecha_asignacion)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if($vis->cupon_codigo)
                                            <span class="badge bg-light text-dark">{{ $vis->cupon_codigo }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($vis->estado)
                                            @php
                                                $badgeColor = 'secondary';
                                                if($vis->estado_id == 1) $badgeColor = 'warning';
                                                elseif($vis->estado_id == 2) $badgeColor = 'info';
                                                elseif($vis->estado_id == 3) $badgeColor = 'primary';
                                                elseif($vis->estado_id == 4) $badgeColor = 'success';
                                                elseif($vis->estado_id == 5) $badgeColor = 'danger';
                                                elseif($vis->estado_id == 6) $badgeColor = 'dark';
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}" style="font-size: 0.7rem;">{{ $vis->estado->nombre }}</span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No se registran visitas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @elseif(isset($tipo) && $tipo === 'shopper')
    <!-- Dashboard Shopper Premium Mobile-First Catalog -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-1">¡Hola, {{ $shopper->name }}!</h4>
            <p class="mb-0 small text-muted">Descubre nuevos restaurantes y obtén descuentos en tu consumo al completar tu evaluación.</p>
        </div>
    </div>

    <!-- Section 1: Catálogo de Restaurantes -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                <i class="icofont icofont-restaurant"></i> Catálogo de Restaurantes
            </h4>
            <!-- Reload button requested by user -->
            <button id="btn-refresh-catalog" class="btn btn-link p-0 text-muted" style="text-decoration: none;" title="Actualizar Catálogo">
                <i class="icofont icofont-refresh" style="font-size: 1.1rem;"></i>
            </button>
        </div>
        
        <!-- Barra de Filtros Premium -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <div class="col flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="icofont icofont-search text-muted"></i></span>
                            <input type="text" id="filtro-nombre" class="form-control border-start-0" placeholder="Buscar restaurante por nombre...">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary px-4 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalFiltros" style="border-radius: 10px; height: 45px;">
                            <i class="icofont icofont-filter"></i> <span class="d-none d-md-inline">Filtros</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Filtros -->
        <div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 20px; border: none;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modalFiltrosLabel">Filtros de Búsqueda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Región</label>
                            <select id="filtro-region" class="form-select" style="border-radius: 10px;">
                                <option value="">Todas las regiones</option>
                                @foreach($regiones as $reg)
                                    <option value="{{ $reg->id }}">{{ $reg->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Ciudad</label>
                            <select id="filtro-ciudad" class="form-select" style="border-radius: 10px;">
                                <option value="">Todas las ciudades</option>
                                @foreach($ciudades as $ciu)
                                    <option value="{{ $ciu->id }}" data-region="{{ $ciu->region_id }}">{{ $ciu->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tipo de Cocina</label>
                            <select id="filtro-cocina-modal" class="form-select" style="border-radius: 10px;">
                                <option value="">Todas las cocinas</option>
                                @foreach($tipos_cocina as $tc)
                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Descuento Mínimo</label>
                            <select id="filtro-descuento" class="form-select" style="border-radius: 10px;">
                                <option value="">Cualquier descuento</option>
                                <option value="50">50% o más</option>
                                <option value="75">75% o más</option>
                                <option value="100">100% (¡Gratis!)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light w-100 mb-2" id="btn-limpiar-filtros" style="border-radius: 10px;">Limpiar Filtros</button>
                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" style="border-radius: 10px;">Ver Resultados</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías de Cocina (Horizontal Scroll) -->
        <div class="mb-4">
            <h6 class="fw-bold text-muted mb-2 ps-3" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">Tipos de Cocina</h6>
            <div class="d-flex gap-3 overflow-auto pb-2 scrollbar-hidden cuisine-scroll-container ps-3" style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;">
                
                <!-- Card Todos -->
                <div class="cuisine-card active d-flex flex-column align-items-center justify-content-center p-3 text-center" 
                     data-cocina-id=""
                     style="min-width: 90px; width: 90px; height: 90px; border-radius: 16px; background: #ffffff; color: #2b2b2b; cursor: pointer; scroll-snap-align: start; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05);">
                    <div class="icon-wrapper mb-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 50%; background: rgba(0, 117, 205, 0.1); color: #0075cd;">
                        <i class="icofont icofont-restaurant fs-5"></i>
                    </div>
                    <span class="small fw-bold" style="font-size: 0.72rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">Todos</span>
                </div>

                @foreach($tipos_cocina->take(7) as $tc)
                @php
                    $cColor = $tc->color_primary ?? '#6c757d';
                    $hex = str_replace('#', '', $cColor);
                    if (strlen($hex) == 3) {
                        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
                    } else {
                        $r = hexdec(substr($hex, 0, 2));
                        $g = hexdec(substr($hex, 2, 2));
                        $b = hexdec(substr($hex, 4, 2));
                    }
                    $rgb = "$r, $g, $b";
                @endphp
                <!-- Card de Cocina Individual -->
                <div class="cuisine-card d-flex flex-column align-items-center justify-content-center p-3 text-center" 
                     data-cocina-id="{{ $tc->id }}"
                     style="min-width: 90px; width: 90px; height: 90px; border-radius: 16px; background: #ffffff; color: #2b2b2b; cursor: pointer; scroll-snap-align: start; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05);">
                    <div class="icon-wrapper mb-2 d-flex align-items-center justify-content-center" 
                         style="width: 42px; height: 42px; border-radius: 50%; background: rgba({{ $rgb }}, 0.1); color: {{ $cColor }};">
                        <i class="icofont {{ $tc->icon ?? 'icofont-restaurant' }} fs-5"></i>
                    </div>
                    <span class="small fw-bold text-dark" style="font-size: 0.72rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">{{ $tc->name }}</span>
                </div>
                @endforeach

                <!-- Ver Todos Card -->
                <div class="cuisine-card-all d-flex flex-column align-items-center justify-content-center p-3 text-center" 
                     id="btn-ver-todas-cocinas"
                     style="min-width: 90px; width: 90px; height: 90px; border-radius: 16px; background: #ffffff; color: #0075cd; cursor: pointer; scroll-snap-align: start; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05);">
                    <div class="icon-wrapper mb-2 d-flex align-items-center justify-content-center" 
                         style="width: 42px; height: 42px; border-radius: 50%; background: rgba(0, 117, 205, 0.1); color: #0075cd;">
                        <i class="icofont icofont-grid fs-5"></i>
                    </div>
                    <span class="small fw-bold" style="font-size: 0.72rem; color: #0075cd;">Ver todos</span>
                </div>

            </div>
        </div>

        <!-- Grilla del Catálogo -->
        <div class="row" id="catalog-grid">
            @include('partials.shopper_catalog')
        </div>
    </div>

    <!-- FULL SCREEN CUISINE OVERLAY WITH MOTION DESIGN -->
    <div id="cuisine-overlay" class="cuisine-overlay-container">
        <div class="overlay-header d-flex justify-content-between align-items-center px-4 py-3 border-bottom bg-transparent">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Todas las Cocinas</h4>
                <p class="mb-0 text-muted small">Elige tu especialidad favorita para buscar locales</p>
            </div>
            <button id="close-cuisine-overlay" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <i class="icofont icofont-close fs-4 text-dark"></i>
            </button>
        </div>
        <div class="overlay-body px-4 py-4 scrollbar-hidden" style="max-height: calc(100vh - 90px); overflow-y: auto;">
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                
                <!-- Todos in Overlay -->
                <div class="col overlay-cuisine-card" style="--item-index: 0;">
                    <div class="cuisine-card d-flex flex-column align-items-center justify-content-center p-3 text-center w-100" 
                         data-cocina-id=""
                         style="height: 110px; border-radius: 20px; background: #ffffff; color: #2b2b2b; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05);">
                        <div class="icon-wrapper mb-2 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; border-radius: 50%; background: rgba(0, 117, 205, 0.1); color: #0075cd;">
                            <i class="icofont icofont-restaurant fs-4"></i>
                        </div>
                        <span class="fw-bold" style="font-size: 0.8rem;">Todos</span>
                    </div>
                </div>

                @foreach($tipos_cocina as $index => $tc)
                @php
                    $cColor = $tc->color_primary ?? '#6c757d';
                    $hex = str_replace('#', '', $cColor);
                    if (strlen($hex) == 3) {
                        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
                    } else {
                        $r = hexdec(substr($hex, 0, 2));
                        $g = hexdec(substr($hex, 2, 2));
                        $b = hexdec(substr($hex, 4, 2));
                    }
                    $rgb = "$r, $g, $b";
                @endphp
                <div class="col overlay-cuisine-card" style="--item-index: {{ $index + 1 }};">
                    <div class="cuisine-card d-flex flex-column align-items-center justify-content-center p-3 text-center w-100" 
                         data-cocina-id="{{ $tc->id }}"
                         style="height: 110px; border-radius: 20px; background: #ffffff; color: #2b2b2b; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05);">
                        <div class="icon-wrapper mb-2 d-flex align-items-center justify-content-center" 
                             style="width: 46px; height: 46px; border-radius: 50%; background: rgba({{ $rgb }}, 0.1); color: {{ $cColor }};">
                            <i class="icofont {{ $tc->icon ?? 'icofont-restaurant' }} fs-4"></i>
                        </div>
                        <span class="fw-bold" style="font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">{{ $tc->name }}</span>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- MODAL DETALLE RESTAURANTE Y AGENDAMIENTO -->
    <div class="modal fade" id="modal-restaurante" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold" id="modal-title">Detalle del Local</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Carrusel de imágenes reales del local -->
                    <div id="restaurante-carrusel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carrusel-imagenes">
                            <!-- Se poblará vía JS -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#restaurante-carrusel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#restaurante-carrusel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>

                    <!-- Detalles del Local -->
                    <div class="p-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="" id="modal-logo" class="rounded-circle border" style="width: 55px; height: 55px; object-fit: cover;">
                            <div class="ms-3">
                                <h4 class="fw-bold mb-0 text-primary" id="modal-nombre"></h4>
                                <span class="badge bg-light text-primary border" id="modal-tipo-cocina"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-2">
                                <p class="mb-1 text-muted small"><i class="icofont icofont-location-pin text-primary"></i> <b>Ubicación:</b> <span id="modal-direccion"></span></p>
                                <p class="mb-1 text-muted small"><i class="icofont icofont-money text-success"></i> <b>Ticket Promedio:</b> <span id="modal-ticket"></span></p>
                                <p class="mb-1 text-muted small"><i class="icofont icofont-restaurant text-warning"></i> <b>Capacidad:</b> <span id="modal-capacidad"></span> mesas</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1 text-muted small"><i class="icofont icofont-sale-discount text-danger"></i> <b>Reembolso / Descuento:</b> <span id="modal-descuento-txt" class="fw-bold text-danger"></span></p>
                                <div class="d-flex gap-2 mt-2" id="modal-socials">
                                    <!-- Se poblará vía JS -->
                                </div>
                            </div>
                        </div>

                        <!-- Sección de la Carta / Menú -->
                        <div class="card bg-light border-0 p-3 mb-4 rounded">
                            <h6 class="fw-bold text-primary mb-2"><i class="icofont icofont-file-text"></i> Carta / Menú del Local</h6>
                            <div id="carta-url-btn" class="hidden">
                                <a href="" id="carta-enlace" target="_blank" class="btn btn-outline-primary btn-sm"><i class="icofont icofont-external-link"></i> Ver Carta Online (URL)</a>
                            </div>
                            <div id="carta-imagenes-gallery" class="hidden">
                                <p class="small text-muted mb-2">Haz clic en las hojas para expandir la carta:</p>
                                <div class="d-flex gap-2 overflow-auto pb-2" id="carta-fotos">
                                    <!-- Se poblará vía JS -->
                                </div>
                            </div>
                        </div>

                        <!-- Horarios Peak Informativos -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-2"><i class="icofont icofont-clock-time"></i> Horarios Peak de Ocupación</h6>
                            <div class="row row-cols-2 row-cols-md-4 g-2" id="horarios-peak-container">
                                <!-- Se poblará vía JS -->
                            </div>
                        </div>

                        <hr>

                        <!-- Formulario de Agendamiento rápido -->
                        <div class="mt-4">
                            <h5 class="fw-bold text-primary mb-3"><i class="icofont icofont-calendar"></i> Agendar mi Visita</h5>
                            <p class="small text-muted mb-3"><i class="icofont icofont-info-bubble text-warning"></i> Recuerda: Debes completar la visita real y la post-encuesta en un plazo menor a 24 horas después del agendamiento. Además, no puedes agendar durante los horarios peak marcados al 90%+ de capacidad.</p>
                            
                            <form id="agendar-visita-form">
                                @csrf
                                <input type="hidden" name="restaurante_id_modal" id="restaurante_id_modal">
                                <input type="hidden" name="fecha_visita" id="fecha_visita_dashboard_hidden">
                                <input type="hidden" name="hora_visita" id="hora_visita_dashboard_hidden">

                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 0.5rem;" id="btn-reservar">
                                    <i class="icofont icofont-check-circled"></i> Agendar visita
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EXPANDIDO DE CARTA (LIGHTBOX) -->
    <div class="modal fade" id="modal-lightbox" tabindex="-1" style="z-index: 1065;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bg-transparent">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute" style="top: -40px; right: 0;" data-bs-dismiss="modal"></button>
                    <img src="" id="lightbox-img" class="img-fluid rounded border" style="max-height: 85vh;">
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Dashboard por defecto -->
    <div class="row">
        <!-- Gráfico de visitas por estado -->
        <div class="col-xl-4 col-md-12 box-col-12">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Visitas por estado</h5>
                </div>
                <div class="card-body">
                    @if(($visitas_totales ?? 0) > 0)
                        <div id="chart-visitas-estado"></div>
                    @else
                        <div class="text-center py-5">
                            <i class="icofont icofont-bar-chart" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">No hay datos para mostrar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Análisis de Percepción Nacional -->
        <div class="col-xl-8 col-md-12 box-col-12">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Análisis de Percepción Nacional</h5>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">Consolidado general de percepción (Expectativa vs Experiencia) de todos los restaurantes evaluados.</p>
                </div>
                <div class="card-body">
                    <div id="sistema-radar-chart"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('script')
<script>
    @if(isset($tipo) && $tipo === 'sistema')
    $(document).ready(function() {
        // Gráfico de visitas por estado (Donut)
        var chartElement = document.querySelector("#chart-visitas-estado");
        if (chartElement) {
            var visitasEstado = {
                series: [{{ $visitas_pendientes ?? 0 }}, {{ $visitas_en_espera ?? 0 }}, {{ $visitas_completadas ?? 0 }}, {{ $visitas_finalizadas ?? 0 }}, {{ $visitas_no_realizadas ?? 0 }}, {{ $visitas_rechazadas ?? 0 }}],
                chart: { type: 'donut', height: 420 },
                labels: ['Pendientes', 'En espera', 'Completadas', 'Finalizadas', 'No Realizadas', 'Rechazadas'],
                colors: ['#ffc107', '#17a2b8', '#0d6efd', '#28a745', '#0075cd', '#343a40'],
                legend: { position: 'bottom' },
                plotOptions: { 
                    pie: { 
                        donut: { 
                            size: '70%', 
                            labels: { 
                                show: true, 
                                total: { 
                                    show: true, 
                                    label: 'Total', 
                                    color: '#adb5bd',
                                    fontSize: '18px',
                                    formatter: () => {{ $visitas_totales }} 
                                },
                                value: {
                                    color: '#ffffff',
                                    fontSize: '32px',
                                    fontWeight: 600,
                                    offsetY: 2
                                }
                            } 
                        } 
                    } 
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 }
            };
            new ApexCharts(chartElement, visitasEstado).render();
        }

        // Función para romper el texto en líneas para el radar (Copia para Sistema)
        function wrapRadarText(text) {
            const words = text.split(' ');
            if (words.length <= 2) return words;
            const mid = Math.ceil(words.length / 2);
            return [words.slice(0, mid).join(' '), words.slice(mid).join(' ')];
        }

        // Análisis de Percepción Nacional (Radar)
        var radarElement = document.querySelector("#sistema-radar-chart");
        if (radarElement) {
            var optionsRadar = {
                series: [{
                    name: 'Expectativa Nacional',
                    data: [@foreach($stats_nacional as $s) {{ $s['expectativa'] }}, @endforeach]
                }, {
                    name: 'Experiencia Nacional',
                    data: [@foreach($stats_nacional as $s) {{ $s['experiencia'] }}, @endforeach]
                }],
                chart: {
                    height: 450,
                    type: 'radar',
                    toolbar: { show: false },
                    background: 'transparent',
                    dropShadow: { enabled: true, blur: 1, left: 1, top: 1 }
                },
                colors: ['#3498db', '#2ecc71'],
                theme: { mode: 'dark' },
                stroke: { width: 3 },
                fill: { opacity: 0.2 },
                markers: { size: 4 },
                xaxis: {
                    categories: [
                        @foreach($stats_nacional as $s) 
                        wrapRadarText('{{ $s['label'] }}'), 
                        @endforeach
                    ],
                    labels: {
                        style: {
                            colors: '#fff',
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    }
                },
                yaxis: {
                    show: false,
                    min: 0,
                    max: 5,
                    tickAmount: 5
                },
                plotOptions: {
                    radar: {
                        size: 160,
                        polygons: {
                            strokeColors: 'rgba(255,255,255,0.1)',
                            fill: { colors: ['rgba(255,255,255,0.02)', 'rgba(255,255,255,0.05)'] }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '14px',
                    labels: { colors: '#fff' }
                }
            };
            new ApexCharts(radarElement, optionsRadar).render();
        }
    });

    @elseif(isset($tipo) && $tipo === 'restaurante')
    $(document).ready(function() {
        // Función para romper el texto en líneas para el radar
        function wrapText(text) {
            const words = text.split(' ');
            let result = [];
            let currentLine = "";
            words.forEach(word => {
                if ((currentLine + word).length > 15) {
                    result.push(currentLine.trim());
                    currentLine = word + " ";
                } else {
                    currentLine += word + " ";
                }
            });
            result.push(currentLine.trim());
            return result;
        }

        // Radar Chart (Imagen 2) - Configuración para Modo Oscuro
        var radarElement = document.querySelector("#restaurante-radar-chart");
        if (radarElement) {
            var optionsRadar = {
                series: [
                    { name: 'Expectativa', data: [@foreach($estadisticas as $s) {{ $s['expectativa'] }}, @endforeach] },
                    { name: 'Experiencia', data: [@foreach($estadisticas as $s) {{ $s['experiencia'] }}, @endforeach] }
                ],
                chart: { 
                    height: 500,
                    type: 'radar', 
                    toolbar: { show: false },
                    background: 'transparent',
                    dropShadow: { enabled: true, blur: 2, left: 1, top: 1 } 
                },
                theme: { mode: 'dark' },
                colors: ['#3498db', '#e67e22'],
                stroke: { width: 3 },
                fill: { opacity: 0.25 },
                markers: { size: 5 },
                xaxis: { 
                    categories: [
                        @foreach($estadisticas as $s) 
                        wrapText('{{ $s['label'] }}'), 
                        @endforeach
                    ],
                    labels: {
                        show: true,
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: ['#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff']
                        }
                    }
                },
                yaxis: {
                    show: false,
                    min: 0,
                    max: 5,
                    tickAmount: 5
                },
                plotOptions: {
                    radar: {
                        size: 140,
                        polygons: {
                            strokeColors: 'rgba(255,255,255,0.1)',
                            fill: { colors: ['rgba(255,255,255,0.02)', 'rgba(255,255,255,0.05)'] }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '14px',
                    labels: { colors: '#fff' }
                }
            };
            new ApexCharts(radarElement, optionsRadar).render();
        }

        // Gap Chart - Configuración para Modo Oscuro Mejorada
        var gapElement = document.querySelector("#restaurante-gap-chart");
        if (gapElement) {
            var optionsGap = {
                series: [{
                    name: 'Diferencia (Gap)',
                    data: [@foreach($estadisticas as $s) {{ round($s['experiencia'] - $s['expectativa'], 2) }}, @endforeach]
                }],
                chart: { 
                    type: 'bar', 
                    height: 400,
                    toolbar: { show: false },
                    background: 'transparent',
                    dropShadow: { enabled: true, blur: 2, left: 1, top: 1 }
                },
                title: {
                    text: 'Diferencia entre Experiencia y Expectativa',
                    align: 'center',
                    style: { color: '#fff', fontSize: '14px', fontWeight: 600 }
                },
                theme: { mode: 'dark' },
                colors: ['#27ae60'], // Color base, se sobreescribe por rangos
                plotOptions: {
                    bar: {
                        colors: {
                            ranges: [
                                { from: -5, to: -0.01, color: '#e74c3c' }, // Rojo para gap negativo
                                { from: 0, to: 5, color: '#2ecc71' }    // Verde para gap positivo
                            ]
                        },
                        columnWidth: '45%',
                        dataLabels: { position: 'top' }
                    }
                },
                dataLabels: { 
                    enabled: true, 
                    formatter: function (val) {
                        return (val > 0 ? '+' : '') + val.toFixed(2);
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff'],
                        fontWeight: 'bold'
                    }
                },
                xaxis: {
                    categories: [
                        @foreach($estadisticas as $s) 
                        wrapText('{{ $s['label'] }}'), 
                        @endforeach
                    ],
                    labels: { 
                        offsetY: 5,
                        style: { 
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: '#fff'
                        }
                    },
                    axisBorder: { show: true, color: 'rgba(255,255,255,0.1)' },
                    axisTicks: { show: true, color: 'rgba(255,255,255,0.1)' }
                },
                yaxis: {
                    title: {
                        text: 'Puntos de Diferencia',
                        style: { color: '#adb5bd', fontWeight: 500 }
                    },
                    labels: { 
                        style: { 
                            colors: '#adb5bd',
                            fontSize: '11px'
                        } 
                    },
                    min: -3,
                    max: 3
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.05)',
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return (val > 0 ? 'Excede expectativa en ' : 'Bajo expectativa en ') + Math.abs(val).toFixed(2) + ' pts';
                        }
                    }
                }
            };
            new ApexCharts(gapElement, optionsGap).render();
        }
    });
    @elseif(isset($tipo) && $tipo === 'shopper')
    $(document).ready(function() {
        // Doughnut Chart: Visitas por estado
        var statusElement = document.querySelector("#shopper-status-chart");
        if (statusElement) {
            var optionsStatus = {
                series: [@foreach($stats_estados as $s) {{ $s->total }}, @endforeach],
                chart: { type: 'donut', height: 350 },
                labels: [
                    @foreach($stats_estados as $s)
                        @php
                            $label = 'Desconocido';
                            if($s->estado_id == 1) $label = 'Pendiente';
                            elseif($s->estado_id == 2) $label = 'En Espera';
                            elseif($s->estado_id == 3) $label = 'Completada';
                            elseif($s->estado_id == 4) $label = 'Finalizada';
                            elseif($s->estado_id == 5) $label = 'No Realizada';
                            elseif($s->estado_id == 6) $label = 'Rechazada';
                        @endphp
                        '{{ $label }}',
                    @endforeach
                ],
                colors: [
                    @foreach($stats_estados as $s)
                        @if($s->estado_id == 1) '#ffc107',
                        @elseif($s->estado_id == 2) '#17a2b8',
                        @elseif($s->estado_id == 3) '#0d6efd',
                        @elseif($s->estado_id == 4) '#28a745',
                        @elseif($s->estado_id == 5) '#0075cd',
                        @elseif($s->estado_id == 6) '#343a40',
                        @else '#6c757d',
                        @endif
                    @endforeach
                ],
                legend: { 
                    position: 'bottom', 
                    labels: { colors: 'inherit' } 
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: () => {{ $visitas_totales }}
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 }
            };
            new ApexCharts(statusElement, optionsStatus).render();
        }

        // Bar Chart: Evolución mensual
        var monthlyElement = document.querySelector("#shopper-monthly-chart");
        if (monthlyElement) {
            var optionsMonthly = {
                series: [{
                    name: 'Visitas',
                    data: [@foreach($stats_mensual as $s) {{ $s->total }}, @endforeach]
                }],
                chart: { 
                    type: 'bar', 
                    height: 350, 
                    toolbar: { show: false },
                    background: 'transparent'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '15%',
                        distributed: true
                    }
                },
                colors: ['#0075cd'],
                xaxis: {
                    categories: [@foreach($stats_mensual as $s) '{{ \Carbon\Carbon::parse($s->mes)->translatedFormat("M Y") }}', @endforeach],
                    labels: { style: { colors: 'inherit' } }
                },
                yaxis: {
                    labels: { 
                        style: { colors: 'inherit' },
                        formatter: (val) => Math.floor(val)
                    },
                    tickAmount: (Math.max(@foreach($stats_mensual as $s) {{ $s->total }}, @endforeach) > 5) ? undefined : Math.max(@foreach($stats_mensual as $s) {{ $s->total }}, @endforeach)
                },
                tooltip: {
                    y: {
                        formatter: (val) => Math.floor(val)
                    }
                },
                legend: { show: false }
            };
            new ApexCharts(monthlyElement, optionsMonthly).render();
        }

        // Lógica de Filtros en Catálogo
        var selectedCuisineId = '';

        $(document).on('click', '.cuisine-card', function() {
            // Deseleccionar todas las tarjetas en ambos selectores (scroll horizontal y overlay)
            $('.cuisine-card').removeClass('active');
            
            var cocinaId = $(this).data('cocina-id') || '';
            
            // Seleccionar ambas tarjetas sincronizadamente
            $('.cuisine-card[data-cocina-id="' + cocinaId + '"]').addClass('active');
            
            selectedCuisineId = cocinaId;

            // Sincronizar select del modal
            $('#filtro-cocina-modal').val(selectedCuisineId);
            
            // Cerrar el overlay con animación
            $('#cuisine-overlay').removeClass('show');
            
            filtrarRestaurantes();
        });

        // Abrir overlay de todas las cocinas
        $(document).on('click', '#btn-ver-todas-cocinas', function() {
            $('#cuisine-overlay').addClass('show');
        });

        // Cerrar overlay
        $(document).on('click', '#close-cuisine-overlay', function() {
            $('#cuisine-overlay').removeClass('show');
        });

        function filtrarRestaurantes() {
            var nombre = $('#filtro-nombre').val().toLowerCase();
            var cocina = selectedCuisineId;
            var region = $('#filtro-region').val();
            var ciudad = $('#filtro-ciudad').val();
            var descuento = $('#filtro-descuento').val();

            $('.restaurante-item').each(function() {
                var itemNombre = $(this).data('nombre');
                var itemCocina = $(this).data('cocina');
                var itemRegion = $(this).data('region');
                var itemCiudad = $(this).data('ciudad');
                var itemDescuento = parseInt($(this).data('descuento'));

                var matchNombre = itemNombre.indexOf(nombre) !== -1;
                var matchCocina = !cocina || itemCocina == cocina;
                var matchRegion = !region || itemRegion == region;
                var matchCiudad = !ciudad || itemCiudad == ciudad;

                var matchDescuento = true;
                if (descuento) {
                    matchDescuento = itemDescuento >= parseInt(descuento);
                }

                if (matchNombre && matchCocina && matchRegion && matchCiudad && matchDescuento) {
                    $(this).removeClass('hidden');
                } else {
                    $(this).addClass('hidden');
                }
            });
        }

        $('#filtro-nombre').on('keyup', filtrarRestaurantes);
        $('#filtro-region, #filtro-ciudad, #filtro-descuento').on('change', filtrarRestaurantes);

        // Al cambiar cocina en el modal, sincronizar con scroll horizontal
        $('#filtro-cocina-modal').on('change', function() {
            var val = $(this).val();
            selectedCuisineId = val;
            
            $('.cuisine-card').removeClass('active');
            if (val === '') {
                $('.cuisine-card[data-cocina-id=""]').addClass('active');
            } else {
                $('.cuisine-card[data-cocina-id="' + val + '"]').addClass('active');
            }
            
            filtrarRestaurantes();
        });

        // Limpiar filtros
        $('#btn-limpiar-filtros').on('click', function() {
            $('#filtro-region').val('');
            $('#filtro-ciudad').val('').find('option').show();
            $('#filtro-cocina-modal').val('');
            $('#filtro-descuento').val('');
            
            // Resetear cocina a "Todos"
            selectedCuisineId = "";
            $('.cuisine-card').removeClass('active');
            $('.cuisine-card[data-cocina-id=""]').addClass('active');

            filtrarRestaurantes();
        });

        // Filtrar ciudades por región en el modal
        $('#filtro-region').on('change', function() {
            var regionId = $(this).val();
            $('#filtro-ciudad').val('');
            if (regionId) {
                $('#filtro-ciudad option').each(function() {
                    var cityRegion = $(this).data('region');
                    if (cityRegion && cityRegion != regionId) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            } else {
                $('#filtro-ciudad option').show();
            }
        });

        // Recarga de Catálogo vía AJAX
        $('#btn-refresh-catalog').on('click', function() {
            var btn = $(this);
            var icon = btn.find('i');
            
            // Animación de rotación
            icon.addClass('rotate-refresh');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{ route("shopper.catalog.refresh") }}',
                type: 'GET',
                success: function(res) {
                    if (res.estado == 200) {
                        $('#catalog-grid').html(res.html);
                        // Re-aplicar filtros si hay alguno activo
                        filtrarRestaurantes();
                        notify('Catálogo actualizado', 'Se han refrescado los locales disponibles.', 'success');
                    } else {
                        notify('Error', 'No se pudo actualizar el catálogo.', 'danger');
                    }
                },
                error: function() {
                    notify('Error', 'Error de conexión al servidor.', 'danger');
                },
                complete: function() {
                    icon.removeClass('rotate-refresh');
                    btn.prop('disabled', false);
                }
            });
        });

        // Lógica para poblar y abrir Modal de Detalles
        $(document).on('click', '.ver-detalles', function() {
            var rest = $(this).data('restaurante');
            var cocina = $(this).data('tipo-cocina');
            var ciudad = $(this).data('ciudad');
            var region = $(this).data('region');

            $('#restaurante_id_modal').val(rest.id);
            $('#modal-nombre').text(rest.name);
            $('#modal-tipo-cocina').text(cocina);
            $('#modal-direccion').text(rest.direccion + ', ' + ciudad + ', ' + region);
            $('#modal-ticket').text(rest.rango_ticket_promedio || 'N/A');
            $('#modal-capacidad').text(rest.capacidad_restaurante || 'N/A');
            $('#modal-descuento-txt').text(rest.porcentaje_descuento + '% de descuento en consumo');

            // Logo
            $('#modal-logo').attr('src', rest.logo ? rest.logo : '{{ asset("assets/images/dashboard/avtar.jpg") }}');

            // Carrusel de fotos
            var images = rest.imagenes || [];
            var carruselHtml = '';
            if (images.length === 0) {
                carruselHtml = '<div class="carousel-item active"><img src="{{ asset("assets/images/dashboard/bg.jpg") }}" class="d-block w-100" style="height: 320px; object-fit: cover;"></div>';
            } else {
                images.forEach(function(img, index) {
                    var activeClass = index === 0 ? 'active' : '';
                    carruselHtml += '<div class="carousel-item ' + activeClass + '"><img src="' + img + '" class="d-block w-100" style="height: 320px; object-fit: cover;"></div>';
                });
            }
            $('#carrusel-imagenes').html(carruselHtml);

            // Redes Sociales
            var socialsHtml = '';
            if (rest.social_instagram) {
                socialsHtml += '<a href="' + rest.social_instagram + '" target="_blank" class="btn btn-outline-danger px-2 py-1 small rounded"><i class="icofont icofont-social-instagram"></i> Instagram</a>';
            }
            if (rest.social_facebook) {
                socialsHtml += '<a href="' + rest.social_facebook + '" target="_blank" class="btn btn-outline-primary px-2 py-1 small rounded"><i class="icofont icofont-social-facebook"></i> Facebook</a>';
            }
            if (rest.social_tiktok) {
                socialsHtml += '<a href="' + rest.social_tiktok + '" target="_blank" class="btn btn-outline-dark px-2 py-1 small rounded"><i class="icofont icofont-play"></i> TikTok</a>';
            }
            $('#modal-socials').html(socialsHtml);

            // Carta / Menú
            if (rest.carta_tipo === 'url') {
                $('#carta-url-btn').removeClass('hidden');
                $('#carta-enlace').attr('href', rest.carta_url);
                $('#carta-imagenes-gallery').addClass('hidden');
            } else {
                $('#carta-url-btn').addClass('hidden');
                $('#carta-imagenes-gallery').removeClass('hidden');
                
                var cartaImgs = rest.carta_imagenes || [];
                var cartaHtml = '';
                if (cartaImgs.length === 0) {
                    cartaHtml = '<p class="small text-muted mb-0">No se subieron fotos de la carta.</p>';
                } else {
                    cartaImgs.forEach(function(img) {
                        cartaHtml += '<img src="' + img + '" class="img-thumbnail img-carta-click" style="width: 80px; height: 100px; object-fit: cover; cursor: pointer;">';
                    });
                }
                $('#carta-fotos').html(cartaHtml);
            }

            // Horarios Peak de Ocupación
            var peak = rest.horario_peak || {};
            var diasLabels = {lunes: 'Lu', martes: 'Ma', miercoles: 'Mi', jueves: 'Ju', viernes: 'Vi', sabado: 'Sá', domingo: 'Do'};
            var peakHtml = '';
            $.each(diasLabels, function(key, label) {
                var item = peak[key] || {desde: '', hasta: '', ocupa_90: false};
                var badgeClass = 'bg-light text-dark';
                var peakText = 'Sin definir';
                
                if (item.desde && item.hasta) {
                    peakText = item.desde + ' - ' + item.hasta;
                    if (item.ocupa_90) {
                        badgeClass = 'bg-danger-subtle text-danger border-danger border';
                        peakText += ' (90%+)';
                    } else {
                        badgeClass = 'bg-success-subtle text-success border-success border';
                    }
                }
                
                peakHtml += '<div class="col"><div class="p-2 border rounded text-center ' + badgeClass + '" style="font-size: 0.75rem;"><b class="d-block">' + label + '</b>' + peakText + '</div></div>';
            });
            $('#horarios-peak-container').html(peakHtml);

            // Abrir Modal
            $('#modal-restaurante').modal('show');
        });

        // Lightbox para expandir carta
        $(document).on('click', '.img-carta-click', function() {
            var src = $(this).attr('src');
            $('#lightbox-img').attr('src', src);
            $('#modal-lightbox').modal('show');
        });

        // Agendar Visita AJAX
        $('#agendar-visita-form').submit(function(e) {
            e.preventDefault();

            // Populate current date and time
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            $('#fecha_visita_dashboard_hidden').val(`${year}-${month}-${day}`);
            $('#hora_visita_dashboard_hidden').val(`${hours}:${minutes}`);

            var btn = $('#btn-reservar');
            btn.prop('disabled', true).text('Procesando agendamiento...');

            $.ajax({
                url: '{{ route("visitas.agendar_shopper") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.estado == 200) {
                        notify('Agendamiento exitoso', res.mensaje, 'success');
                        setTimeout(function() {
                            // Redirigir a responder pre-encuesta de inmediato
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
    @endif

    // Marcar visita como realizada
    $(document).on('click', '.marcar-visitado', function() {
        var visitaId = $(this).data('visita-id');
        var btn = $(this);
        
        if(confirm('¿Confirmas que ya realizaste esta visita? Se te enviará un email para que completes la encuesta de experiencia.')) {
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
                            location.reload();
                        }, 1500);
                    } else {
                        notify('Error', response.mensaje, 'danger');
                    }
                },
                error: function() {
                    notify('Error', 'Ocurrió un error al marcar la visita', 'danger');
                }
            });
        }
    });
</script>
@endsection

<style>
.dashboard-icon {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-gradient-primary {
    background: linear-gradient(90deg, #0075cd 0%, #005fa6 100%);
}
.custom-table thead th {
    border-top: none;
    font-size: 0.85rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 700;
}
.custom-table tbody tr {
    transition: all 0.3s ease;
}
.custom-table tbody tr:hover {
    background-color: rgba(0, 117, 205, 0.05);
}
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
#restaurante-radar-chart, #restaurante-gap-chart, #chart-comparativo-sistema {
    min-height: 400px;
}
.dark-table-header {
    background-color: rgba(0,0,0,0.2) !important;
}
.dark-table-footer {
    background-color: rgba(0,0,0,0.3) !important;
    border-color: rgba(255,255,255,0.1) !important;
}
[data-theme='dark'] .theme-text-color,
.dark-only .theme-text-color {
    color: #efefef !important;
}
[data-theme='dark'] .bg-light,
.dark-only .bg-light {
    background-color: rgba(0,0,0,0.2) !important;
}

/* Premium Shopper Subdomain Custom Styling */
.scrollbar-hidden::-webkit-scrollbar {
    display: none;
}
.scrollbar-hidden {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.cuisine-scroll-container {
    padding-top: 5px;
    padding-bottom: 5px;
}
.cuisine-card, .cuisine-card-all {
    border: 1px solid rgba(0,0,0,0.05) !important;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    flex-shrink: 0;
}
.cuisine-card:hover, .cuisine-card-all:hover {
    transform: translateY(-5px) scale(1.04);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    border-color: rgba(0,0,0,0.1) !important;
}
.cuisine-card.active {
    border-color: #0075cd !important;
    box-shadow: 0 8px 20px rgba(0, 117, 205, 0.15) !important;
    transform: translateY(-3px) scale(1.03);
}

/* Premium Fullscreen Overlay */
.cuisine-overlay-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1070;
    backdrop-filter: blur(25px);
    background: rgba(255, 255, 255, 0.94);
    transform: translateY(100vh);
    opacity: 0;
    transition: transform 0.55s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.55s ease;
    display: flex;
    flex-direction: column;
}
.cuisine-overlay-container.show {
    transform: translateY(0);
    opacity: 1;
}
#close-cuisine-overlay:hover {
    transform: scale(1.1) rotate(90deg);
    background: #e9ecef;
}
.overlay-cuisine-card {
    opacity: 0;
}
.cuisine-overlay-container.show .overlay-cuisine-card {
    animation: fadeInUpSpring 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes fadeInUpSpring {
    0% {
        opacity: 0;
        transform: translateY(35px) scale(0.92);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.card-restaurante {
    transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    border-radius: 1.25rem !important;
    overflow: hidden;
    background: #fff;
    border: none !important;
    box-shadow: 0 5px 20px rgba(0,0,0,0.04) !important;
}
.card-restaurante:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important;
}
.card-restaurante .card-img-container {
    overflow: hidden;
    position: relative;
    border-radius: 1.25rem 1.25rem 0 0;
}
.card-restaurante .card-img-bg {
    transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.card-restaurante:hover .card-img-bg {
    transform: scale(1.08);
}
</style>
