@extends('layouts.master')
@section('title', 'Dimensiones de Encuesta')

@section('css')
    
@endsection

@section('style')
<style>
    .icon-selector {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
        max-height: 200px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
    .icon-option {
        cursor: pointer;
        padding: 10px;
        text-align: center;
        border-radius: 4px;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    .icon-option:hover {
        background: #f0f7ff;
        border-color: #0075cd;
    }
    .icon-option.active {
        background: #0075cd;
        color: white;
        border-color: #0075cd;
    }
    .icon-option i {
        font-size: 1.2rem;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Dimensiones de Encuesta</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dimensiones</li>
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
                 <a class="btn btn-primary" id="btnNuevaDimension"> Nueva Dimensión</a>
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
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">Icono</th>
                <th scope="col">Nombre</th>
                <th scope="col">Color</th>
                <th scope="col" class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody id="dimensiones_tabla">
              
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

<!-- Modal para Agregar/Editar Dimensión -->
<div class="modal fade" id="modalDimension" tabindex="-1" aria-labelledby="modalDimensionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDimensionLabel">Nueva Dimensión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDimension">
                    <input type="hidden" id="modal_id" name="id">
                    <div class="mb-3">
                        <label class="form-label" for="modal_nombre">Nombre (*)</label>
                        <input type="text" class="form-control" id="modal_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Icono</label>
                        <div class="icon-selector" id="iconPicker">
                            <!-- Iconos se cargarán aquí -->
                        </div>
                        <input type="hidden" id="modal_icono" name="icono" value="icofont-layers">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="modal_color">Color</label>
                        <input type="color" class="form-control form-control-color" id="modal_color" name="color" value="#0075cd">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarDimension">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
        var data = [];
        var pagination = {'current_page': 1};
        
        const iconList = [
            'icofont-layers', 'icofont-restaurant', 'icofont-food-cart', 'icofont-waiter', 
            'icofont-fast-food', 'icofont-tasks', 'icofont-dashboard', 'icofont-users', 
            'icofont-building', 'icofont-location-pin', 'icofont-clock-time', 'icofont-money', 
            'icofont-chart-bar-graph', 'icofont-verification-check', 'icofont-search-job',
            'icofont-attachment', 'icofont-eye', 'icofont-star', 'icofont-ui-user', 'icofont-clip'
        ];

        $(document).ready(function() {
            renderIconPicker();
            getData();
        });

        function renderIconPicker() {
            let html = '';
            iconList.forEach(icon => {
                html += `<div class="icon-option" data-icon="${icon}" title="${icon}">
                            <i class="icofont ${icon}"></i>
                         </div>`;
            });
            $('#iconPicker').html(html);

            $('.icon-option').click(function() {
                $('.icon-option').removeClass('active');
                $(this).addClass('active');
                $('#modal_icono').val($(this).data('icon'));
            });
        }

        function setIconActive(iconName) {
            $('.icon-option').removeClass('active');
            $(`.icon-option[data-icon="${iconName}"]`).addClass('active');
            $('#modal_icono').val(iconName);
        }

        $('.aplicarFiltros').click(function() {
            pagination = {'current_page': 1};
            getData();
        });

        $('.borrarFiltros').click(function() {
            $('#filtroNombre').val('');
            pagination = {'current_page': 1};
            getData();
        });

        $('.actualizar').click(function() {
            getData();
        });

        function getData() {
            var filtros = {
                nombre: $('#filtroNombre').val()
            };

            $.ajax({
                url: "{{route('dimensiones.getdata')}}",
                method: 'POST',
                data: {
                    filtros: filtros,
                    page: pagination.current_page
                },
                success: function(res) {
                    data = res.data;
                    pagination = {
                        current_page: res.current_page,
                        last_page: res.last_page,
                        per_page: res.per_page,
                        total: res.total
                    };
                    completarData();
                    completarNav();
                }
            });
        }

        function completarNav() {
            var html = '';
            if (pagination.total > 0 && pagination.last_page > 1) {
                if (pagination.current_page > 1) {
                    html += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="pagination.current_page = ' + (pagination.current_page - 1) + '; getData();">Anterior</a></li>';
                }
                for (var i = 1; i <= pagination.last_page; i++) {
                    if (i == pagination.current_page) {
                        html += '<li class="page-item active"><a class="page-link" href="javascript:void(0)">' + i + '</a></li>';
                    } else {
                        html += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="pagination.current_page = ' + i + '; getData();">' + i + '</a></li>';
                    }
                }
                if (pagination.current_page < pagination.last_page) {
                    html += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="pagination.current_page = ' + (pagination.current_page + 1) + '; getData();">Siguiente</a></li>';
                }
            } else if (pagination.total == 0) {
                html = '';
            }
            $('.pagination').html(html);
        }

        function completarData() {
            $('#dimensiones_tabla').html('');
            var toappend = "";
            $.each(data,function(i,d) {
                toappend += "<tr data-id='"+i+"'>";

                var acciones = '<ul class="dropdown-menu btns" aria-labelledby="acciones'+d.id+'">';
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item editar"> <i class="icofont icofont-pen"></i> Editar</a></li>';
                acciones += '</ul>';

                var icon = d.icono || 'icofont-layers';
                var color = d.color || '#0075cd';

                // Usamos la clase icofont base + el icono guardado
                toappend += '<td><div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background: ' + color + '1A; color: ' + color + ';"><i class="icofont ' + icon + ' fs-5"></i></div></td>';
                toappend += '<td class="fw-bold">'+ (d.nombre || '') +'</td>';
                toappend += '<td><div class="d-flex align-items-center gap-2"><div style="width: 20px; height: 20px; border-radius: 4px; background: ' + color + ';"></div> <span>' + color + '</span></div></td>';

                toappend += '<td><div class="d-flex px-2 py-1 justify-content-end">';
                toappend += '<div class="d-flex flex-column justify-content-center dropleft">';
                toappend += '<button type="button" class="btn btn-default btn-sm my-1" data-bs-toggle="dropdown" id="acciones'+d.id+'"><i style="font-size:1.4rem;" class="icofont icofont-options"></i></button>';
                toappend += acciones;
                toappend += '</div></div> </td>';

                toappend += "</tr>";
            });
            $('#dimensiones_tabla').html(toappend);
        }

        $('#btnNuevaDimension').click(function() {
            $('#modalDimensionLabel').text('Nueva Dimensión');
            $('#modal_id').val('');
            $('#modal_nombre').val('');
            setIconActive('icofont-layers');
            $('#modal_color').val('#0075cd');
            $('#modalDimension').modal('show');
        });

        $(document).on('click', '.editar', function() {
            var tr = $(this).closest('tr');
            var index = tr.data('id');
            var item = data[index];
            
            $('#modalDimensionLabel').text('Editar Dimensión');
            $('#modal_id').val(item.id_encrypted);
            $('#modal_nombre').val(item.nombre);
            setIconActive(item.icono || 'icofont-layers');
            $('#modal_color').val(item.color || '#0075cd');
            $('#modalDimension').modal('show');
        });

        $('#btnGuardarDimension').click(function() {
            var id = $('#modal_id').val();
            var url = id ? "{{route('dimensiones.update')}}" : "{{route('dimensiones.store')}}";
            
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    id: id,
                    nombre: $('#modal_nombre').val(),
                    icono: $('#modal_icono').val(),
                    color: $('#modal_color').val()
                },
                success: function(res) {
                    if (res.estado == 200) {
                        notify('Éxito', res.mensaje, 'success');
                        $('#modalDimension').modal('hide');
                        getData();
                    } else {
                        notify('Error', res.mensaje, 'danger');
                    }
                }
            });
        });

        $(document).on('click', '.eliminar', function() {
            var tr = $(this).closest('tr');
            var index = tr.data('id');
            var item = data[index];
            
            swal({
                title: "¿Está seguro de eliminar esta dimensión?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{route('dimensiones.eliminar')}}",
                        method: 'POST',
                        data: {
                            id: item.id_encrypted
                        },
                        success: function(res) {
                            if (res.estado == 200) {
                                notify('Éxito', res.mensaje, 'success');
                                getData();
                            } else {
                                notify('Error', res.mensaje, 'danger');
                            }
                        }
                    });
                }
            });
        });
</script>
@endsection
