@extends('layouts.master')
@section('title', 'Tipos de Cocina')

@section('css')
    
@endsection

@section('style')
    
@endsection

@section('breadcrumb-title')
<h3>Tipos de Cocina</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Tipos de Cocina</li>
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
                 <a class="btn btn-primary" href="{{route('tipos_cocina.nuevo')}}" data-bs-original-title="" title=""> Nuevo tipo de cocina</a>
                 <div class="">
                    <a data-bs-toggle="collapse" data-bs-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros" class="btn btn-tertiary" data-bs-original-title="" title="">Ver filtros</a>
                 </div>
              </div>
              <div class="col-12 collapse row" id="collapseFiltros" style="">
                <input autocomplete="false" style="display:none!important;"/>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="filtroNombre">Nombre</label>
                    <input class="form-control form-control-sm btn-square" id="filtroNombre" placeholder="Ingrese nombre" data-bs-original-title="" title="">
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
                <th scope="col">Color Principal</th>
                <th scope="col">Color Degradado</th>
                <th scope="col" class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody id="tipos_cocina_tabla">
              
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
                url: "{{route('tipos_cocina.getdata')}}",
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
            // Solo mostrar paginación si hay datos y más de una página
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
                // Si no hay datos, no mostrar nada
                html = '';
            }
            $('.pagination').html(html);
        }

        function completarData() {
            $('#tipos_cocina_tabla').html('');
            var toappend = "";
            $.each(data,function(i,d) {
                toappend += "<tr data-id='"+i+"'>";

                var acciones = '<ul class="dropdown-menu btns" aria-labelledby="acciones'+d.id+'">';
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item editar"> <i class="icofont icofont-pen"></i> Editar</a></li>';
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item eliminar"> <i class="icofont icofont-trash"></i> Eliminar</a></li>';
                acciones += '</ul>';

                var icon = d.icon || 'icofont icofont-restaurant';
                var colorP = d.color_primary || '#0075cd';
                var colorS = d.color_secondary || '#005fa6';

                toappend += '<td><div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background: ' + colorP + '1A; color: ' + colorP + ';"><i class="' + icon + ' fs-5"></i></div></td>';
                toappend += '<td class="fw-bold">'+ (d.name || '') +'</td>';
                toappend += '<td><div class="d-flex align-items-center gap-2"><div style="width: 20px; height: 20px; border-radius: 4px; background: ' + colorP + ';"></div> <span>' + colorP + '</span></div></td>';
                toappend += '<td><div class="d-flex align-items-center gap-2"><div style="width: 20px; height: 20px; border-radius: 4px; background: ' + colorS + ';"></div> <span>' + colorS + '</span></div></td>';

                toappend += '<td><div class="d-flex px-2 py-1 justify-content-end">';
                toappend += '<div class="d-flex flex-column justify-content-center dropleft">';
                toappend += '<button type="button" class="btn btn-default btn-sm my-1" data-bs-toggle="dropdown" id="acciones'+d.id+'"><i style="font-size:1.4rem;" class="icofont icofont-options"></i></button>';
                toappend += acciones;
                toappend += '</div></div> </td>';

                toappend += "</tr>";
            });
            $('#tipos_cocina_tabla').html(toappend);
        }

        $(document).on('click', '.editar', function() {
            var tr = $(this).closest('tr');
            var index = tr.data('id');
            var item = data[index];
            window.location.href = "/tipos-cocina/editar/" + item.id;
        });

        $(document).on('click', '.eliminar', function() {
            var tr = $(this).closest('tr');
            var index = tr.data('id');
            var item = data[index];
            
            if (confirm('¿Está seguro de eliminar este tipo de cocina?')) {
                $.ajax({
                    url: "{{route('tipos_cocina.eliminar')}}",
                    method: 'POST',
                    data: {
                        id: item.id
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
</script>
@endsection
