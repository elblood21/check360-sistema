@extends('layouts.master')
@section('title', 'Mis Visitas')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
<style>
    /* Stepper and Premium Cards */
    .visita-card-premium {
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
    }
    .visita-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.08) !important;
    }
    .premium-stepper .step-item.active .step-circle {
        background: #0075cd !important;
        color: white !important;
        border-color: #0075cd !important;
        box-shadow: 0 0 8px rgba(0,117,205,0.35) !important;
    }
    .premium-stepper .step-item.active .step-label {
        color: #0075cd !important;
        font-weight: 700 !important;
    }
    .premium-stepper .step-item:not(.active) .step-circle {
        color: #adb5bd !important;
        border-color: #dee2e6 !important;
    }
    .premium-stepper .step-item:not(.active) .step-label {
        color: #6c757d !important;
    }
    
    /* Dark Mode variables and overrides */
    [data-theme="dark"] .premium-stepper .step-item:not(.active) .step-circle {
        background: #1b1b29 !important;
        color: #495057 !important;
        border-color: #2b2b40 !important;
    }
    [data-theme="dark"] .premium-stepper .step-item:not(.active) .step-label {
        color: #9ca3af !important;
    }
    [data-theme="dark"] .premium-stepper .stepper-line {
        background-color: #2b2b40 !important;
    }
    [data-theme="dark"] .visita-card-premium {
        background-color: #1b1b29 !important;
        border-color: #2b2b40 !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important;
    }
    [data-theme="dark"] .visita-card-premium .bg-white {
        background-color: #1b1b29 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .dark-text-gray {
        color: #9ca3af !important;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Visitas Mystery Shopper</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Visitas</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="col-md-12 project-list">
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
           <div class="row">
              <div class="col-md-6">
                 
              </div>
              <div class="col-md-6">
                 <div class="form-group mb-0 me-0"></div>
                 <a class="btn btn-secondary actualizar" title="Actualizar listado" style="border-radius: 10px;">Actualizar</a>
                
                 <div class="">
                    <a data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros" class="btn btn-tertiary" style="border-radius: 10px;">Ver filtros</a>
                 </div>
              </div>
              <div class="col-12 collapse row mt-3" id="collapseFiltros">
                <input autocomplete="false" style="display:none!important;"/>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="filtroRestaurante">Restaurante</label>
                    <input class="form-control form-control-sm btn-square" id="filtroRestaurante" placeholder="ID restaurante" style="border-radius: 8px;">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="filtroShopper">Shopper</label>
                    <input class="form-control form-control-sm btn-square" id="filtroShopper" placeholder="ID shopper" style="border-radius: 8px;">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="filtroEstado">Estado</label>
                    <select class="form-select form-select-sm btn-square" id="filtroEstado" style="border-radius: 8px;">
                        <option value="">Todos</option>
                        <option value="1">Pendiente (Pre-encuesta)</option>
                        <option value="2">Agendado (Visita activa)</option>
                        <option value="3">Consumido (Post-encuesta)</option>
                        <option value="4">Finalizada (Aprobado)</option>
                        <option value="5">Cancelada</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="filtroFechaDesde">Fecha desde</label>
                    <input type="date" class="form-control form-control-sm btn-square" id="filtroFechaDesde" style="border-radius: 8px;">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="filtroFechaHasta">Fecha hasta</label>
                    <input type="date" class="form-control form-control-sm btn-square" id="filtroFechaHasta" style="border-radius: 8px;">
                </div>
                <div class="col-12 d-flex mt-2">
                    <button type="button" class="btn btn-danger btn-sm m-auto me-0 borrarFiltros" style="border-radius: 8px;">Borrar filtros</button>
                    <button type="button" class="btn btn-secondary btn-sm m-auto ms-2 me-0 aplicarFiltros" style="border-radius: 8px;">Aplicar filtros</button>
                </div>
            </div>
           </div>
        </div>
     </div>

    @if(\App\Helpers\SubdominioHelper::esTipo('shopper'))
        <div id="visitas-cards-container" class="row g-4 mb-4"></div>
        <nav class="d-flex mt-4 justify-content-center">
            <ul class="pagination"></ul>
        </nav>
    @else
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="bg-light">
                  <tr>
                    <th scope="col" style="border-top-left-radius: 16px;">Shopper</th>
                    <th scope="col">Restaurante / Dirección</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Finanzas / Cupón</th>
                    <th scope="col" style="border-top-right-radius: 16px;">Acciones</th>
                  </tr>
                </thead>
                <tbody id="visitastabla"></tbody>
              </table>
            </div>
            <br>
            <nav class="d-flex justify-content-end">
                <ul class="pagination me-3"></ul>
            </nav>
        </div>
    @endif
</div>
@endsection

@section('script')
<script>
    var data = [];
    var pagination = {'current_page': 1};
    getData();

    $('.aplicarFiltros').click(function() {
        pagination = {'current_page': 1};
        getData();
    });
    $('.actualizar').click(function() {
        getData();
    });
    $('.borrarFiltros').click(function() {
        $('.form-control, .form-select').val('');
        getData();
    });

    document.getElementById('collapseFiltros').addEventListener('hide.bs.collapse', function () {
        $('.btn-tertiary').html('Ver filtros');
    });
    document.getElementById('collapseFiltros').addEventListener('show.bs.collapse', function () {
        $('.btn-tertiary').html('Ocultar filtros');
    })

    $(document).on('keyup', '#collapseFiltros .form-control',function(e){
        if(e.key == "Enter") {
            e.preventDefault();
            getData();
        }
    });

    function eliminar(index) {
        $.ajax({
            url:'{{route("visitas.eliminar")}}',
            method:'POST',
            data:{id:data[index].id_encrypted},
            success:function(res) {
                if(res.estado == 200) {
                    notify("Exito","Visita eliminada con exito","success");
                    getData();
                } else {
                    notify("Error",res.mensaje || "Error","danger");
                }
            }
        })
    }

    $(document).on('click','.eliminar',function() {
        var index = $(this).closest('tr').data('id');
        swal("Esta seguro de eliminar esta visita?",  {
            buttons: {
                cancel: "Cancelar",
                eliminar: {
                    text: "Eliminar",
                    value: "eliminar",
                }
            },
        }).then((value) => {
            if(value == "eliminar") eliminar(index);
        });
    })

    function getData() {
        $.ajax({
            url:'{{route("visitas.getdata")}}',
            method:'POST',
            data:{
                page:pagination.current_page,
                filtros:{
                    restaurante_id:$('#filtroRestaurante').val(),
                    shopper_id:$('#filtroShopper').val(),
                    estado_id:$('#filtroEstado').val(),
                    fecha_desde:$('#filtroFechaDesde').val(),
                    fecha_hasta:$('#filtroFechaHasta').val()
                }
            },
            success:function(res) {
                data = res.data;

                completarData();

                pagination.total = res.total;
                pagination.from = res.from;
                pagination.to = res.to;
                if(res.from == null) { pagination.from = 0; pagination.to = 0; }
                pagination.last_page = res.last_page;

                $('ul.pagination').html(completarNav(res,pagination));
            }
        })
    }

    function detectSearch(pagina) {
        if(pagina == 'siguiente') pagination['current_page'] = Number(pagination['current_page']) + 1;
        else if(pagina == 'anterior') pagination['current_page'] = Number(pagination['current_page']) - 1;
        else if(pagina) pagination['current_page'] = pagina;
        getData();
    }

    $(document).on('click','.editar',function() {
        var index = $(this).closest('tr').data('id');
        window.location.href = "/visitas/editar/"+data[index].id_encrypted;
    })
    $(document).on('click','.ver',function() {
        var index = $(this).closest('tr').data('id');
        window.location.href = "/visitas/ver/"+data[index].id_encrypted;
    })

    function completarData() {
        @if(\App\Helpers\SubdominioHelper::esTipo('shopper'))
            $('#visitas-cards-container').html('');
            var cardsHtml = "";
            if (data.length === 0) {
                cardsHtml = `
                    <div class="col-12 text-center py-5">
                        <div class="p-5 rounded-4 max-width-500 mx-auto" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(0,0,0,0.1);">
                            <i class="icofont icofont-calendar text-primary mb-3" style="font-size: 3.5rem;"></i>
                            <h5 class="fw-bold">No tienes visitas registradas</h5>
                            <p class="text-muted small">Explora el catálogo de restaurantes y agenda una visita Mystery Shopper para empezar a ganar reembolsos.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm fw-bold px-4 py-2 mt-2" style="border-radius: 10px;">Ver Catálogo</a>
                        </div>
                    </div>
                `;
            } else {
                $.each(data, function(i, d) {
                    // Steps activation based on database conditions
                    var step1 = "active";
                    var step2 = d.pre_encuesta_count > 0 ? "active" : "";
                    var step3 = (d.estado_id >= 3 || d.post_encuesta_count > 0) ? "active" : "";
                    var step4 = d.post_encuesta_count > 0 ? "active" : "";
                    var step5 = d.estado_id == 4 ? "active" : "";

                    // Action buttons
                    var actionBtn = "";
                    if (d.estado_id == 1) {
                        actionBtn = `<a class="btn btn-primary w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" href="/visitas/responder-entrada/${d.id_encrypted}" style="border-radius: 12px;"><i class="icofont icofont-file-text"></i> Completar encuesta</a>`;
                    } else if (d.estado_id == 2) {
                        actionBtn = `<button class="btn btn-info text-white w-100 py-2.5 fw-bold marcar-visitado d-flex align-items-center justify-content-center gap-2" data-visita-id="${d.id_encrypted}" style="border-radius: 12px;"><i class="icofont icofont-check-circled"></i> Marcar Visitado</button>`;
                    } else if (d.estado_id == 3) {
                        actionBtn = `<a class="btn btn-warning text-dark w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" href="/visitas/responder-salida/${d.id_encrypted}" style="border-radius: 12px;"><i class="icofont icofont-file-alt"></i> Completar encuesta</a>`;
                    } else if (d.estado_id == 4) {
                        actionBtn = `<a class="btn btn-success w-100 py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" href="/visitas/cupon/${d.id_encrypted}" style="border-radius: 12px;"><i class="icofont icofont-ticket"></i> Ver Cupón</a>`;
                    } else {
                        actionBtn = `<button class="btn btn-secondary w-100 py-2.5 fw-bold" disabled style="border-radius: 12px;">Finalizada</button>`;
                    }

                    // Cover image extraction
                    var restImg = "{{ asset('assets/images/dashboard/bg.jpg') }}";
                    if (d.restaurante && d.restaurante.imagenes) {
                        var imgs = d.restaurante.imagenes;
                        if (typeof imgs === 'string') {
                            try { imgs = JSON.parse(imgs); } catch(e) {}
                        }
                        if (Array.isArray(imgs) && imgs.length > 0) {
                            restImg = imgs[0];
                        }
                    }

                    var restName = d.restaurante ? d.restaurante.name : 'N/A';
                    var restAddress = d.restaurante ? d.restaurante.direccion : 'N/A';
                    var refundPercent = d.restaurante ? d.restaurante.porcentaje_descuento : 50;

                    // Date Formatter
                    var fechaFormateada = 'N/A';
                    if (d.fecha_asignacion) {
                        var datePart = d.fecha_asignacion.split('T')[0];
                        var parts = datePart.split('-');
                        if (parts.length === 3) {
                            fechaFormateada = parts[2] + '/' + parts[1] + '/' + parts[0];
                        }
                    }
                    if (d.hora_asignacion) {
                        fechaFormateada += ' - ' + d.hora_asignacion.substring(0, 5) + ' hs';
                    }

                    var estadoNombre = 'N/A';
                    var estadoColor = 'warning';
                    if(d.estado_id == 1) {
                        estadoNombre = 'Cuestionario inicial pendiente';
                        estadoColor = 'warning';
                    } else if(d.estado_id == 2) {
                        estadoNombre = 'Visita pendiente';
                        estadoColor = 'info';
                    } else if(d.estado_id == 3) {
                        estadoNombre = 'Cuestionario final pendiente';
                        estadoColor = 'primary';
                    } else if(d.estado_id == 4) {
                        estadoNombre = 'Finalizada';
                        estadoColor = 'success';
                    } else if(d.estado_id == 5) {
                        estadoNombre = 'Cancelada';
                        estadoColor = 'danger';
                    } else if(d.estado_id == 6) {
                        estadoNombre = 'Rechazada';
                        estadoColor = 'dark';
                    }

                    cardsHtml += `
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden visita-card-premium" style="border-radius: 20px;">
                                <div class="position-relative" style="height: 140px; background-image: url('${restImg}'); background-size: cover; background-position: center;">
                                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);"></div>
                                    <span class="position-absolute badge bg-white text-dark fw-bold shadow-sm" style="top: 12px; right: 12px; border-radius: 12px; padding: 6px 12px; font-size: 0.75rem; z-index: 10;">
                                        Reembolso: ${refundPercent}%
                                    </span>
                                    <div class="position-absolute text-white pe-3" style="bottom: 12px; left: 15px; z-index: 10; width: 90%;">
                                        <h5 class="fw-bold mb-1 text-white" style="font-size: 1.15rem; text-shadow: 0 1px 4px rgba(0,0,0,0.5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${restName}</h5>
                                        <div class="small d-flex align-items-center" style="opacity: 0.95; font-size: 0.72rem;">
                                           <i class="icofont icofont-location-pin me-1"></i> 
                                           <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${restAddress}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="small text-muted dark-text-gray" style="font-size: 0.75rem;"><i class="icofont icofont-calendar"></i> ${fechaFormateada}</span>
                                            <span class="badge bg-light-${estadoColor} text-${estadoColor} rounded-pill" style="font-size: 0.75rem; font-weight: 700; padding: 4px 10px;">
                                                ${estadoNombre}
                                            </span>
                                        </div>

                                        <!-- Step by Step Stepper tracker -->
                                        <div class="premium-stepper d-flex justify-content-between align-items-center position-relative mb-2 mt-3">
                                            <div class="stepper-line position-absolute start-0 w-100 bg-light" style="height: 2px; z-index: 1; top: 16px;"></div>

                                            <div class="step-item d-flex flex-column align-items-center position-relative ${step1}" style="z-index: 2; width: 20%;">
                                                <div class="step-circle rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm border" style="width: 32px; height: 32px; transition: all 0.3s; font-size: 0.9rem;">
                                                    <i class="icofont icofont-calendar" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span class="step-label text-center mt-1.5" style="font-size: 0.72rem; font-weight: 600;">Agendada</span>
                                            </div>

                                            <div class="step-item d-flex flex-column align-items-center position-relative ${step2}" style="z-index: 2; width: 20%;">
                                                <div class="step-circle rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm border" style="width: 32px; height: 32px; transition: all 0.3s; font-size: 0.9rem;">
                                                    <i class="icofont ${step2 ? 'icofont-check' : 'icofont-file-text'}" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span class="step-label text-center mt-1.5" style="font-size: 0.72rem; font-weight: 600;">Pre-enc.</span>
                                            </div>

                                            <div class="step-item d-flex flex-column align-items-center position-relative ${step3}" style="z-index: 2; width: 20%;">
                                                <div class="step-circle rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm border" style="width: 32px; height: 32px; transition: all 0.3s; font-size: 0.9rem;">
                                                    <i class="icofont ${step3 ? 'icofont-check' : 'icofont-restaurant'}" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span class="step-label text-center mt-1.5" style="font-size: 0.72rem; font-weight: 600;">Visita</span>
                                            </div>

                                            <div class="step-item d-flex flex-column align-items-center position-relative ${step4}" style="z-index: 2; width: 20%;">
                                                <div class="step-circle rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm border" style="width: 32px; height: 32px; transition: all 0.3s; font-size: 0.9rem;">
                                                    <i class="icofont ${step4 ? 'icofont-check' : 'icofont-file-alt'}" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span class="step-label text-center mt-1.5" style="font-size: 0.72rem; font-weight: 600;">Post-enc.</span>
                                            </div>

                                            <div class="step-item d-flex flex-column align-items-center position-relative ${step5}" style="z-index: 2; width: 20%;">
                                                <div class="step-circle rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm border" style="width: 32px; height: 32px; transition: all 0.3s; font-size: 0.9rem;">
                                                    <i class="icofont icofont-ticket" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span class="step-label text-center mt-1.5" style="font-size: 0.72rem; font-weight: 600;">Listo</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        ${actionBtn}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            $('#visitas-cards-container').html(cardsHtml);
        @else
            $('#visitastabla').html('');
            var toappend = "";
            $.each(data, function(i, d) {
                toappend += "<tr data-id='"+i+"'>";

                // SHOPPER
                toappend += '<td><div class="fw-bold text-dark">' + (d.shopper ? d.shopper.name : 'N/A') + '</div><small class="text-muted">' + (d.shopper ? d.shopper.email : '') + '</small></td>';

                // RESTAURANTE / DIRECCION
                toappend += '<td><div class="fw-bold text-primary">' + (d.restaurante ? d.restaurante.name : 'N/A') + '</div><small class="text-muted"><i class="icofont icofont-location-pin"></i> ' + (d.restaurante ? d.restaurante.direccion : 'S/D') + '</small></td>';
                
                // ESTADO
                var estadoNombre = 'N/A';
                var estadoColor = 'secondary';
                if(d.estado_id == 1) {
                    estadoNombre = 'Cuestionario inicial pendiente';
                    estadoColor = 'warning';
                } else if(d.estado_id == 2) {
                    estadoNombre = 'Visita pendiente';
                    estadoColor = 'info';
                } else if(d.estado_id == 3) {
                    estadoNombre = 'Cuestionario final pendiente';
                    estadoColor = 'primary';
                } else if(d.estado_id == 4) {
                    estadoNombre = 'Finalizada';
                    estadoColor = 'success';
                } else if(d.estado_id == 5) {
                    estadoNombre = 'Cancelada';
                    estadoColor = 'danger';
                } else if(d.estado_id == 6) {
                    estadoNombre = 'Rechazada';
                    estadoColor = 'dark';
                }
                toappend += '<td><span class="badge rounded-pill bg-light-' + estadoColor + ' text-' + estadoColor + ' fw-bold px-3">' + estadoNombre + '</span></td>';

                // FINANZAS / CUPON
                var total = d.total_consumo ? '$' + Number(d.total_consumo).toLocaleString() : '$0';
                var desc = d.total_descuento ? '$' + Number(d.total_descuento).toLocaleString() : '$0';
                var pago = d.total_pagado ? '$' + Number(d.total_pagado).toLocaleString() : '$0';
                var cupon = d.cupon_codigo ? '<div class="badge bg-light-primary text-primary mt-1">' + d.cupon_codigo + '</div>' : '<small class="text-muted">N/A</small>';

                toappend += '<td>' +
                    '<div style="font-size: 0.75rem;">' +
                        '<div><b>Total:</b> ' + total + '</div>' +
                        '<div><b class="text-danger">Desc:</b> ' + desc + '</div>' +
                        '<div><b class="text-success">Pagado:</b> ' + pago + '</div>' +
                        cupon +
                    '</div>' +
                '</td>';

                // ACCIONES
                toappend += '<td><div class="d-flex gap-2">';
                toappend += '<button class="btn btn-light btn-xs ver" title="Ver Detalles"><i class="icofont icofont-eye text-primary"></i></button>';
                @if(!\App\Helpers\SubdominioHelper::esTipo('sistema'))
                if(d.estado_id == 1) {
                    toappend += '<button class="btn btn-light btn-xs editar" title="Editar"><i class="icofont icofont-pen text-warning"></i></button>';
                }
                toappend += '<button class="btn btn-light btn-xs eliminar" title="Eliminar"><i class="icofont icofont-trash text-danger"></i></button>';
                @endif
                toappend += '</div></td>';

                toappend += "</tr>";
            });
            $('#visitastabla').html(toappend);
        @endif
    }

    // Marcar visita como realizada
    $(document).on('click', '.marcar-visitado', function() {
        var visitaId = $(this).data('visita-id');
        var btn = $(this);
        
        swal({
            title: "¿Confirmas tu visita?",
            text: "Confirma tu visita para completar el cuestionario de experiencia. Al finalizar recibirás tu cupón de descuento.",
            icon: "info",
            buttons: ["Cancelar", "Confirmar"],
            dangerMode: false,
        }).then((willConfirm) => {
            if (willConfirm) {
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
                                getData();
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
    });
</script>
@endsection
