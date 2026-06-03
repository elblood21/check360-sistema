@extends('layouts.master')
@section('title', 'Mistery Shoppers')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
<style>
    .custom-switch-lg .form-check-input {
        width: 3.5rem;
        height: 1.75rem;
        margin-left: -3.5rem;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Mistery Shoppers</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Mistery Shoppers</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="col-md-12 project-list">
        <div class="card">
           <div class="row">
              <div class="col-md-6">
                <h5 class="mb-0">Listado de Mistery Shoppers</h5>
              </div>
              <div class="col-md-6">
                 <div class="form-group mb-0 me-0"></div>
                 <a class="btn btn-secondary actualizar" title="Actualizar listado">Actualizar</a>
                 <a class="btn btn-primary" href="{{route('shoppers.nuevo')}}"> Nuevo Mistery Shopper</a>
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
                <th scope="col">Nombre</th>
                <th scope="col">Visitas</th>
                <th scope="col">Prom. Tiempo</th>
                <th scope="col">Aprobación</th>
                <th scope="col">Estado</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="shopperstabla"></tbody>
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

    function eliminar(index) {
        $.ajax({
            url:'{{route("shoppers.eliminar")}}',
            method:'POST',
            data:{id:data[index].id_encrypted},
            success:function(res) {
                if(res.estado == 200) {
                    notify("Exito","Mistery Shopper eliminado con exito","success");
                    getData();
                } else {
                    notify("Error",res.mensaje || "Error","danger");
                }
            }
        })
    }

    $(document).on('click','.eliminar',function() {
        var index = $(this).closest('tr').data('id');
        swal("Esta seguro de eliminar el Mistery Shopper "+data[index].name+"?",  {
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
            url:'{{route("shoppers.getdata")}}',
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
        window.location.href = "/shoppers/editar/"+encodeURIComponent(data[index].id_encrypted);
    })
    $(document).on('click','.ver',function() {
        var index = $(this).closest('tr').data('id');
        window.location.href = "/shoppers/ver/"+encodeURIComponent(data[index].id_encrypted);
    })

    // Aprobar shopper
    $(document).on('click', '.aprobar', function() {
        var index = $(this).closest('tr').data('id');
        swal("¿Está seguro de aprobar a " + data[index].name + "?", {
            buttons: {
                cancel: "Cancelar",
                aprobar: {
                    text: "Aprobar",
                    value: "aprobar",
                }
            },
        }).then((value) => {
            if(value == "aprobar") {
                $.ajax({
                    url: '{{route("shoppers.aprobar")}}',
                    method: 'POST',
                    data: {id: data[index].id_encrypted},
                    success: function(res) {
                        if(res.estado == 200) {
                            notify("Éxito", "Mistery Shopper aprobado correctamente", "success");
                            $('.dropdown-menu').removeClass('show');
                            setTimeout(getData, 300);
                        } else {
                            notify("Error", res.mensaje || "Error al aprobar", "danger");
                        }
                    }
                });
            }
        });
    });

    // Rechazar shopper
    $(document).on('click', '.rechazar', function() {
        var index = $(this).closest('tr').data('id');
        swal({
            title: "¿Está seguro de rechazar a " + data[index].name + "?",
            text: "Motivo del rechazo (opcional):",
            content: "input",
            buttons: {
                cancel: "Cancelar",
                rechazar: {
                    text: "Rechazar",
                    value: "rechazar",
                }
            },
        }).then((value) => {
            if(value == "rechazar") {
                var motivo = $('.swal-content__input').val();
                $.ajax({
                    url: '{{route("shoppers.rechazar")}}',
                    method: 'POST',
                    data: {
                        id: data[index].id_encrypted,
                        motivo: motivo
                    },
                    success: function(res) {
                        if(res.estado == 200) {
                            notify("Éxito", "Registro rechazado correctamente", "success");
                            $('.dropdown-menu').removeClass('show');
                            setTimeout(getData, 300);
                        } else {
                            notify("Error", res.mensaje || "Error al rechazar", "danger");
                        }
                    }
                });
            }
        });
    });

    // Manejar cambio de estado con switch
    $(document).on('change', '.estado-switch', function() {
        var switchElement = $(this);
        var id = switchElement.data('id');
        var index = switchElement.data('index');
        var nuevoEstado = switchElement.is(':checked') ? 1 : 0;
        
        $.ajax({
            url: '{{route("shoppers.activar")}}',
            method: 'POST',
            data: {
                id: id,
                estado: nuevoEstado
            },
            success: function(res) {
                if(res.estado == 200) {
                    data[index].estado = nuevoEstado;
                    notify('Éxito', res.mensaje || 'Estado actualizado correctamente', 'success');
                } else {
                    // Revertir el switch si falla
                    switchElement.prop('checked', !switchElement.is(':checked'));
                    notify('Error', res.mensaje || 'Error al actualizar el estado', 'danger');
                }
            },
            error: function() {
                // Revertir el switch si hay error
                switchElement.prop('checked', !switchElement.is(':checked'));
                notify('Error', 'Error al actualizar el estado', 'danger');
            }
        });
    });

    function completarData() {
        $('#shopperstabla').html('');
        var toappend = "";
        $.each(data,function(i,d) {
            toappend += "<tr data-id='"+i+"'>";

            var acciones = '<ul class="dropdown-menu btns" aria-labelledby="acciones'+d.id+'">';
              acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item ver"> <i class="icofont icofont-eye"></i> Ver</a></li>';
              // Botones de aprobación si está pendiente
              if (d.aprobado == 0) {
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item aprobar text-success"> <i class="icofont icofont-check"></i> Aprobar</a></li>';
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item rechazar text-danger"> <i class="icofont icofont-close"></i> Rechazar</a></li>';
              } else {
                  // Editar solo si está aprobado
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item editar"> <i class="icofont icofont-pen"></i> Editar</a></li>';
              }
            acciones += '</ul>';

            toappend += '<td><strong>'+ (d.name || '') +'</strong><br><small class="text-muted">'+ (d.email || '') +'</small></td>';
            toappend += '<td><span class="badge badge-warning me-1">' + (d.visitas_pendientes || 0) + ' Pendientes</span> <span class="badge badge-success">' + (d.visitas_completadas || 0) + ' Completadas</span></td>';
            toappend += '<td>'+ (d.promedio_tiempo_human || 'N/A') +'</td>';
            
            // Columna de aprobación
            toappend += '<td>';
            if(d.aprobado == 1) {
                toappend += '<span class="badge badge-success">Aprobado</span>';
            } else {
                toappend += '<span class="badge badge-warning">Pendiente</span>';
            }
            toappend += '</td>';
            
            // Columna de estado activo/inactivo
            toappend += '<td><div class="form-check form-switch custom-switch-lg" style="padding-left: 3.5rem;">';
            toappend += '<input class="form-check-input estado-switch" type="checkbox" data-id="'+d.id_encrypted+'" data-index="'+i+'" ' + (d.estado == 1 ? 'checked' : '') + ' ' + (d.aprobado == 0 ? 'disabled' : '') + ' style="cursor: pointer;">';
            toappend += '</div></td>';

            toappend += '<td><div class="d-flex px-2 py-1">';
            toappend += '<div class="d-flex flex-column justify-content-center dropleft">';
            toappend += '<button type="button" class="btn btn-default btn-sm my-1" data-bs-toggle="dropdown" id="acciones'+d.id+'"><i style="font-size:1.4rem;" class="icofont icofont-options"></i></button>';
            toappend += acciones;
            toappend += '</div></div> </div> </td>';

            toappend += "</tr>";
        });
        $('#shopperstabla').html(toappend);
    }
</script>
@endsection




