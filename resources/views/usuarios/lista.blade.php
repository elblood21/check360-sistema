@extends('layouts.master')
@section('title', 'Usuarios')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Usuarios</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Usuarios</li>
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
                 <a class="btn btn-primary" href="{{route('usuarios.nuevo')}}" data-bs-original-title="" title=""> Nuevo usuario</a>
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
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="filtroEmail">Correo electronico</label>
                    <input class="form-control form-control-sm btn-square" id="filtroEmail" placeholder="Ingrese correo electronico" data-bs-original-title="" title="">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="filtroEstado">Estado</label>
                    <select class="form-select form-select-sm btn-square digits" id="filtroEstado">
                        <option selected value="">Todos</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
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
                <th scope="col">Correo electronico</th>
                <th scope="col">Estado</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="usuariostabla">
              
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

        $(document).on('change', '#collapseFiltros .form-select',function(e){
            getData();
        });

        $(document).on('keyup', '#collapseFiltros .form-control, #collapseFiltros .form-select',function(e){
            if(e.key == "Enter") {
                e.preventDefault();
                getData();
            }
        });

        document.getElementById('collapseFiltros').addEventListener('hide.bs.collapse', function () {
            $('.btn-tertiary').html('Ver filtros');
        });
        document.getElementById('collapseFiltros').addEventListener('show.bs.collapse', function () {
            $('.btn-tertiary').html('Ocultar filtros');
        })

        $(document).on('change','.switch input', function() {
            var this_ = $(this);
            var index = $(this).closest('tr').data('id');
            var checked = $(this).is(':checked') ? 1 : 0;
            $.ajax({
                url:'{{route("usuarios.activar")}}',
                method:'POST',
                data:{id:data[index].id_encrypted,checked:checked},
                success:function(res) {
                    if(res.estado == 200) {
                        notify("Exito",res.mensaje,"primary");
                        getData();
                    } else {
                        $this_.val(checked == 1 ? false : true);
                    }
                }
            })
        });

        function eliminar(index) {
            $.ajax({
                url:'{{route("usuarios.eliminar")}}',
                method:'POST',
                data:{id:data[index].id_encrypted},
                success:function(res) {
                    console.log(res);
                    if(res.estado == 200) {
                        notify("Exito","Usuario eliminado con exito","success");
                        getData();
                    }
                }
            })
        }

        $(document).on('click','.eliminar',function() {
            var index = $(this).closest('tr').data('id');
            swal("Esta seguro de eliminar el usuario "+data[index].name+"?",  {
                buttons: {
                    cancel: "Cancelar",
                    eliminar: {
                        text: "Eliminar",
                        value: "eliminar",
                    }
                },
            }).then((value) => {
                switch (value) {
                    case "eliminar":
                        eliminar(index);
                    break;
                    default:
                    break;
                }
            });
        })

        function getData() {
            $.ajax({
                url:'{{route("usuarios.getdata")}}',
                method:'POST',
                data:{page:pagination.current_page,filtros:{nombre:$('#filtroNombre').val(),email:$('#filtroEmail').val(),estado:$('#filtroEstado').val(),perfil:$('#filtroPerfil').val()}},
                success:function(res) {
                    console.log(res);
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
            window.location.href = "{{ route('usuarios.editar', ['id' => ':id']) }}".replace(':id', data[index].id_encrypted);
        })

        function completarData() {
            $('#usuariostabla').html('');
            var toappend = "";
            $.each(data,function(i,d) {
                toappend += "<tr data-id='"+i+"'>";

                var colorRow = "";
                if(d.estado == 0) {
                    colorRow = "color:#f44336";
                }

                var acciones = '<ul class="dropdown-menu btns" aria-labelledby="acciones'+d.id+'">';
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item editar"> <i class="icofont icofont-pen"></i> Editar</a></li>';
                  acciones += '<li style="cursor:pointer;padding:0.5rem;"><a class="dropdown-item eliminar"> <i class="icofont icofont-trash"></i> Eliminar</a></li>';
                acciones += '</ul>';
    
                toappend += '<td style="'+colorRow+'">'+d.name+'</td>';

                toappend += '<td style="'+colorRow+'">'+d.email+'</td>';
                toappend += '<td class="switch-sm"><label class="switch">';
                    toappend += '<input type="checkbox" '+(d.estado == 1 ? 'checked' : '')+' data-bs-original-title="" title=""><span class="switch-state"></span>';
                toappend += '</label></td>';

                toappend += '<td><div class="d-flex px-2 py-1">';
                toappend += '<div class="d-flex flex-column justify-content-center dropleft">';
                toappend += '<button type="button" style="'+colorRow+'" class="btn btn-default btn-sm my-1" data-bs-toggle="dropdown" id="acciones'+d.id+'"><i style="font-size:1.4rem;" class="icofont icofont-options"></i></button>';
                toappend += acciones;
                toappend += '</div></div> </div> </td>';

                toappend += "</tr>";
            });
            $('#usuariostabla').html(toappend);
        }
    </script>
@endsection