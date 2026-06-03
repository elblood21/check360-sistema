@extends('layouts.master')
@section('title', 'Encuestas')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Encuestas</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Encuestas</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="col-md-12 project-list">
        <div class="card">
           <div class="row">
              <div class="col-md-6">
                
              </div>
              <div class="col-md-6">
                 <div class="form-group mb-0 me-0"></div>
                 <a class="btn btn-secondary actualizar" data-bs-original-title="" title="Actualizar listado">Actualizar</a>
                 <div class="">
                    <a data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros" class="btn btn-tertiary" data-bs-original-title="" title="">Ver filtros</a>
                 </div>
              </div>
              <div class="col-12 collapse row" id="collapseFiltros" style="">
                <input autocomplete="false" style="display:none!important;"/>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="filtroRestaurante">Restaurante</label>
                    <select class="form-select form-select-sm btn-square digits" id="filtroRestaurante" style="height: 38px;">
                        <option selected value="">Todos</option>
                        @foreach(App\Models\Restaurante::whereNull('deleted_at')->orderBy('name')->get() as $res)
                            <option value="{{$res->id}}">{{$res->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label" for="filtroShopper">Mistery Shopper</label>
                    <input type="text" class="form-control form-control-sm" id="filtroShopper" placeholder="Buscar por nombre..." style="height: 38px;">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label" for="filtroEstado">Estado</label>
                    <select class="form-select form-select-sm btn-square digits" id="filtroEstado" style="height: 38px;">
                        <option selected value="">Todos</option>
                        @foreach(App\Models\EstadoVisita::where('id', '!=', 1)->get() as $estado)
                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label" for="filtroFechaDesde">Desde</label>
                    <input type="date" class="form-control form-control-sm" id="filtroFechaDesde" style="height: 38px;">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label" for="filtroFechaHasta">Hasta</label>
                    <input type="date" class="form-control form-control-sm" id="filtroFechaHasta" style="height: 38px;">
                </div>
                
                <div class="col-12 d-flex">
                    <button type="button" class="btn btn-danger btn-sm m-auto me-0 borrarFiltros">Borrar filtros</button>
                    <button type="button" class="btn btn-secondary btn-sm m-auto ms-2 me-0 aplicarFiltros">Aplicar filtros</button>
                </div>
            </div>
           </div>
        </div>
     </div>


    <div class="card">
        <div class="table-responsive">
          <table class="table table-lg">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Restaurante</th>
                <th scope="col">Mistery Shopper</th>
                <th scope="col">Fecha/Hora</th>
                <th scope="col">Estado</th>
                <th scope="col" class="text-center">Expectativa</th>
                <th scope="col" class="text-center">Experiencia</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody id="visitastabla">
              
            </tbody>
          </table>
        </div>
        <br>
        <nav class="d-flex">
            <ul class="pagination m-auto me-2">
                
            </ul>
        </nav>
    </div>
</div>

<!-- Modal Ver Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">Encuestas Visita #<span id="modalVisitaId"></span></h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 px-3">
                    <div class="col-12 p-3 bg-transparent rounded border">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Restaurante:</strong> <span id="info-restaurante" class="text-muted"></span></p>
                                <p class="mb-1"><strong>Shopper:</strong> <span id="info-shopper" class="text-muted"></span></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1"><strong>Fecha:</strong> <span id="info-fecha" class="text-muted"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="expectativa-tab" data-bs-toggle="tab" href="#expectativa" role="tab" aria-controls="expectativa" aria-selected="true"><i class="icofont icofont-ui-user"></i>Expectativa</a></li>
                    <li class="nav-item"><a class="nav-link" id="experiencia-tab" data-bs-toggle="tab" href="#experiencia" role="tab" aria-controls="experiencia" aria-selected="false"><i class="icofont icofont-ui-home"></i>Experiencia</a></li>
                </ul>
                <div class="tab-content" id="top-tabContent" style="background: transparent !important;">
                    <div class="tab-pane fade show active" id="expectativa" role="tabpanel" aria-labelledby="expectativa-tab">
                        <div class="p-3" id="content-expectativa">
                            <!-- Answers here -->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="experiencia" role="tabpanel" aria-labelledby="experiencia-tab">
                        <div class="p-3" id="content-experiencia">
                           <!-- Answers here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
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

        $(document).on('change', '#collapseFiltros .form-select',function(e){
            getData();
        });

        $(document).on('click','.ver-detalle',function() {
            var index = $(this).data('index');
            var visita = data[index];
            $('#modalVisitaId').text(visita.id);
            $('#info-restaurante').text('Cargando...');
            $('#info-shopper').text('Cargando...');
            $('#info-fecha').text('Cargando...');
            $('#content-expectativa').html('<div class="text-center p-4"><i class="fa fa-spin fa-spinner"></i> Cargando...</div>');
            $('#content-experiencia').html('<div class="text-center p-4"><i class="fa fa-spin fa-spinner"></i> Cargando...</div>');
            
            $('#modalDetalle').modal('show');

            $.ajax({
                url: '{{ route("resultados.detalle_sistema") }}',
                method: 'POST',
                data: { id: visita.id_encrypted },
                success: function(res) {
                    if (res.estado == 200) {
                        $('#info-restaurante').text(res.restaurante);
                        $('#info-shopper').text(res.shopper);
                        $('#info-fecha').text(res.fecha);
                        renderRespuestas('expectativa', res.entrada);
                        renderRespuestas('experiencia', res.salida);
                    } else {
                        $('#content-expectativa').html('<div class="alert alert-danger">Error al cargar datos</div>');
                        $('#content-experiencia').html('<div class="alert alert-danger">Error al cargar datos</div>');
                    }
                },
                error: function() {
                    $('#content-expectativa').html('<div class="alert alert-danger">Error de conexión</div>');
                    $('#content-experiencia').html('<div class="alert alert-danger">Error de conexión</div>');
                }
            });
        });

        function renderRespuestas(containerId, respuestas) {
            var html = '';
            if (respuestas.length === 0) {
                html = '<div class="alert alert-info">No hay respuestas registradas para esta encuesta.</div>';
            } else {
                html += '<div class="list-group list-group-flush" style="background: transparent !important;">';
                $.each(respuestas, function(i, r) {
                    html += '<div class="list-group-item bg-transparent border-bottom px-0">';
                    html += '<p class="mb-1 fw-bold theme-text-color">' + r.pregunta + '</p>';
                    html += '<div class="theme-text-color opacity-75">';
                    if (r.tipo === 'escala_1_5' || r.tipo === 'escala_1_10' || r.tipo === 'si_no') {
                        html += '<span class="badge badge-primary">' + r.respuesta + '</span>';
                    } else {
                        html += r.respuesta;
                    }
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
            }
            $('#content-' + containerId).html(html);
        }

        function getData() {
            $.ajax({
                url:'{{route("resultados.getdata_sistema")}}',
                method:'POST',
                data:{
                    page:pagination.current_page,
                    filtros:{
                        estado_id:$('#filtroEstado').val(),
                        restaurante_id:$('#filtroRestaurante').val(),
                        shopper_nombre:$('#filtroShopper').val(),
                        fecha_desde:$('#filtroFechaDesde').val(),
                        fecha_hasta:$('#filtroFechaHasta').val()
                    }
                },
                success:function(res) {
                    if (res.estado == 403) {
                        window.location.href = "{{ route('dashboard') }}";
                        return;
                    }

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

        function completarData() {
            $('#visitastabla').html('');
            var toappend = "";
            $.each(data,function(i,d) {
                toappend += "<tr data-id='"+i+"'>";
                
                // ID
                toappend += '<td>#'+d.id+'</td>';

                // Restaurante
                toappend += '<td>'+d.restaurante_nombre+'</td>';

                // Shopper
                toappend += '<td>'+d.shopper_nombre+'</td>';

                // Fecha/Hora
                toappend += '<td>'+d.fecha+' <br><small class="text-muted">'+d.hora+'</small></td>';
                
                // Estado
                var badgeClass = 'secondary';
                if(d.estado_id == 1) badgeClass = 'warning';
                else if(d.estado_id == 2) badgeClass = 'info';
                else if(d.estado_id == 3) badgeClass = 'primary';
                else if(d.estado_id == 4) badgeClass = 'success';
                else if(d.estado_id == 5) badgeClass = 'danger';
                else if(d.estado_id == 6) badgeClass = 'dark';
                toappend += '<td><span class="badge badge-'+badgeClass+'">'+d.estado+'</span></td>';

                // Expectativa
                var iconEntrada = d.tiene_entrada ? '<i class="fa fa-check-circle text-success fa-lg"></i>' : '<i class="fa fa-times-circle text-muted fa-lg"></i>';
                toappend += '<td class="text-center">' + iconEntrada + '</td>';

                // Experiencia
                var iconSalida = d.tiene_salida ? '<i class="fa fa-check-circle text-success fa-lg"></i>' : '<i class="fa fa-times-circle text-muted fa-lg"></i>';
                toappend += '<td class="text-center">' + iconSalida + '</td>';

                // Acciones
                toappend += '<td>';
                toappend += '<button class="btn btn-primary btn-sm ver-detalle" data-index="'+i+'"><i class="icofont icofont-eye-alt"></i> Ver</button>';
                toappend += '</td>';

                toappend += "</tr>";
            });
            $('#visitastabla').html(toappend);
        }
    </script>
@endsection
