@extends('layouts.master')
@section('title', 'Restaurantes')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Restaurantes</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Restaurantes</li>
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
                 <a class="btn btn-secondary actualizar" title="Actualizar listado">Actualizar</a>
                 <a class="btn btn-primary" href="{{route('restaurantes.nuevo')}}"> Nuevo restaurante</a>
                 <div class="">
                    <a data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros" class="btn btn-tertiary">Ver filtros</a>
                 </div>
              </div>
              <div class="col-12 collapse row" id="collapseFiltros">
                <input autocomplete="false" style="display:none!important;"/>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="filtroNombre">Nombre</label>
                    <input class="form-control form-control-sm btn-square" id="filtroNombre" placeholder="Ingrese nombre">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="filtroEmail">Email</label>
                    <input class="form-control form-control-sm btn-square" id="filtroEmail" placeholder="Ingrese email">
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
                <th scope="col">Logo</th>
                <th scope="col">Restaurante</th>
                <th scope="col">Administrador</th>
                <th scope="col">Tipo cocina</th>
                <th scope="col">Plan</th>
                <th scope="col">Estado</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="restaurantestabla"></tbody>
          </table>
        </div>
        <br>
        <nav class="d-flex">
            <ul class="pagination m-auto me-2"></ul>
        </nav>
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

    $(document).on('click', '.cambiar-estado', function() {
        var index = $(this).closest('tr').data('id');
        var d = data[index];
        var nuevoEstado = d.estado == 1 ? 0 : 1;
        var estadoTexto = nuevoEstado == 1 ? 'activar' : 'desactivar';

        swal("¿Está seguro de " + estadoTexto + " el restaurante " + d.name + "?", {
            buttons: {
                cancel: "Cancelar",
                confirmar: {
                    text: "Confirmar",
                    value: "confirmar",
                }
            },
        }).then((value) => {
            if (value == "confirmar") {
                $.ajax({
                    url: '{{route("restaurantes.estado")}}',
                    method: 'POST',
                    data: { id: d.id_encrypted, estado: nuevoEstado, _token: '{{csrf_token()}}' },
                    success: function(res) {
                        if (res.estado == 200) {
                            notify("Éxito", "Estado actualizado", "success");
                            getData();
                        } else {
                            notify("Error", res.mensaje || "Error", "danger");
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.aprobar', function() {
        var index = $(this).closest('tr').data('id');
        var d = data[index];

        swal("¿Aprobar el restaurante " + d.name + "?", {
            buttons: {
                cancel: "Cancelar",
                confirmar: {
                    text: "Aprobar",
                    value: "confirmar",
                }
            },
        }).then((value) => {
            if (value == "confirmar") {
                $.ajax({
                    url: '{{route("restaurantes.aprobar")}}',
                    method: 'POST',
                    data: { id: d.id_encrypted, _token: '{{csrf_token()}}' },
                    success: function(res) {
                        if (res.estado == 200) {
                            notify("Éxito", "Restaurante aprobado", "success");
                            getData();
                        } else {
                            notify("Error", res.mensaje || "Error", "danger");
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.rechazar', function() {
        var index = $(this).closest('tr').data('id');
        var d = data[index];

        swal({
            title: "¿Rechazar y eliminar el restaurante " + d.name + "?",
            text: "Indica el motivo del rechazo (se enviará al cliente por email):",
            content: {
                element: "input",
                attributes: {
                    placeholder: "Ej: Documentación incompleta o no cumple requisitos.",
                    type: "text",
                },
            },
            buttons: {
                cancel: "Cancelar",
                confirmar: {
                    text: "Confirmar Rechazo",
                    closeModal: false,
                }
            },
        }).then((value) => {
            if (value === null) return; // Cancelado
            
            var motivo = value || "No cumple con los requisitos mínimos de la plataforma.";

            $.ajax({
                url: '{{route("restaurantes.rechazar")}}',
                method: 'POST',
                data: { id: d.id_encrypted, motivo: motivo, _token: '{{csrf_token()}}' },
                success: function(res) {
                    swal.stopLoading();
                    if (res.estado == 200) {
                        notify("Éxito", "Restaurante rechazado y notificado", "success");
                        swal.close();
                        getData();
                    } else {
                        notify("Error", res.mensaje || "Error", "danger");
                        swal.close();
                    }
                },
                error: function() {
                    swal.stopLoading();
                    swal.close();
                    notify("Error", "Ocurrió un error al procesar el rechazo", "danger");
                }
            });
        });
    });

    function getData() {
        $.ajax({
            url:'{{route("restaurantes.getdata")}}',
            method:'POST',
            data:{page:pagination.current_page,filtros:{nombre:$('#filtroNombre').val(),email:$('#filtroEmail').val()}},
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
        window.location.href = "/restaurantes/editar/"+encodeURIComponent(data[index].id_encrypted);
    })
    $(document).on('click','.ver',function() {
        var index = $(this).closest('tr').data('id');
        window.location.href = "/restaurantes/ver/"+encodeURIComponent(data[index].id_encrypted);
    })

    function completarData() {
        $('#restaurantestabla').html('');
        var toappend = "";
        $.each(data,function(i,d) {
            toappend += "<tr data-id='"+i+"'>";

            var acciones = '<ul class="dropdown-menu btns" aria-labelledby="acciones'+d.id+'">';
              acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item ver"> <i class="icofont icofont-eye"></i> Ver</a></li>';
              
              if(d.aprobado == 0) {
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item aprobar text-success"> <i class="icofont icofont-check"></i> Aprobar</a></li>';
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item rechazar text-danger"> <i class="icofont icofont-close"></i> Rechazar</a></li>';
              } else {
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item editar"> <i class="icofont icofont-pen"></i> Editar</a></li>';
                  var textoEstado = d.estado == 1 ? "Desactivar" : "Activar";
                  var iconEstado = d.estado == 1 ? "icofont-ui-close" : "icofont-check-circled";
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item cambiar-estado"> <i class="icofont '+iconEstado+'"></i> '+textoEstado+'</a></li>';
              }

            acciones += '</ul>';

            var logoImg = d.logo ? '{{asset("")}}' + d.logo : '{{asset("assets/images/user/user.png")}}';

            toappend += '<td><img src="'+logoImg+'" style="width:50px;height:50px;border-radius:50%;object-fit:cover;border:1px solid #ccc;"></td>';
            toappend += '<td><strong>'+ (d.name || '') +'</strong><br><small class="text-muted">'+ (d.direccion || 'Sin dirección') +'</small></td>';
            
            var adminName = d.admin ? d.admin.name : 'Sin Admin';
            var adminEmail = d.admin ? d.admin.email : d.email;
            toappend += '<td>'+ adminName +'<br><small class="text-muted">'+ adminEmail +'</small></td>';
            
            toappend += '<td>'+ (d.tipo_cocina ? d.tipo_cocina.name : '') +'<br><small class="text-muted">'+(d.rango_ticket_promedio||'')+'</small></td>';

            // Plan Info
            var planHtml = '<span class="badge badge-light text-dark border"><i class="icofont icofont-not-allowed"></i> Sin plan activo</span>';
            if (d.plan_activo) {
                var finPeriodoStr = d.periodo_fin ? d.periodo_fin + 'T00:00:00' : null;
                var finPlanStr = d.plan_fin ? d.plan_fin + 'T00:00:00' : null;
                
                var hoy = new Date();
                hoy.setHours(0,0,0,0);
                
                var diasStr = 'N/A';
                if (finPeriodoStr) {
                    var pFin = new Date(finPeriodoStr);
                    var diffDays = Math.ceil((pFin - hoy) / (1000 * 60 * 60 * 24));
                    diasStr = diffDays >= 0 ? diffDays + ' días' : 'Expirado';
                }

                var finPlanText = 'N/A';
                if (finPlanStr) {
                    finPlanText = new Date(finPlanStr).toLocaleDateString('es-CL');
                }
                
                var progColor = (d.visitas_periodo_count || 0) >= 12 ? 'success' : 'primary';
                
                planHtml = '<div style="font-size: 0.8rem; line-height: 1.3; min-width: 140px;">';
                planHtml += '<div class="d-flex justify-content-between mb-1"><span>Visitas:</span> <strong class="text-'+progColor+'">' + (d.visitas_periodo_count || 0) + '/12</strong></div>';
                planHtml += '<div class="progress mb-1" style="height: 4px;"><div class="progress-bar bg-'+progColor+'" style="width: '+((d.visitas_periodo_count || 0)/12*100)+'%"></div></div>';
                planHtml += '<div class="d-flex justify-content-between text-muted mt-1">';
                planHtml += '<div title="Días hasta el reinicio de visitas de este periodo"><i class="icofont icofont-refresh"></i> en ' + diasStr + '</div>';
                planHtml += '<div title="Fecha en que termina el contrato/plan del restaurante"><i class="icofont icofont-calendar"></i> Fin: ' + finPlanText + '</div>';
                planHtml += '</div>';
                planHtml += '</div>';
            }

            toappend += '<td>' + planHtml + '</td>';

            // Estado Badge
            var badge = '';
            if (d.aprobado == 0) {
                badge = '<span class="badge badge-warning">Pendiente Aprobación</span>';
            } else if (d.estado == 1) {
                badge = '<span class="badge badge-success">Activo</span>';
            } else {
                badge = '<span class="badge badge-danger">Inactivo</span>';
            }
            toappend += '<td>'+ badge +'</td>';

            toappend += '<td><div class="d-flex px-2 py-1">';
            toappend += '<div class="d-flex flex-column justify-content-center dropleft">';
            toappend += '<button type="button" class="btn btn-default btn-sm my-1" data-bs-toggle="dropdown" id="acciones'+d.id+'"><i style="font-size:1.4rem;" class="icofont icofont-options"></i></button>';
            toappend += acciones;
            toappend += '</div></div> </div> </td>';

            toappend += "</tr>";
        });
        $('#restaurantestabla').html(toappend);
    }
</script>
@endsection


