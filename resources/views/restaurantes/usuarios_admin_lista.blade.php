@extends('layouts.master')
@php
    $esRestaurante = \App\Helpers\SubdominioHelper::esTipo('restaurante');
    $routePrefix = $esRestaurante ? 'restaurantes.usuarios' : 'restaurantes.usuarios_admin';
@endphp
@section('title', 'Usuarios Administradores')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{ $esRestaurante ? 'Mis Usuarios' : 'Usuarios Administradores' }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Restaurantes</li>
<li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="col-md-12 project-list">
        <div class="card">
           <div class="row">
              <div class="col-md-6"></div>
              <div class="col-md-6">
                 <a class="btn btn-secondary actualizar">Actualizar</a>
                 <a class="btn btn-primary" href="{{ route($routePrefix . '.nuevo') }}"> Nuevo usuario</a>
                 <a data-bs-toggle="collapse" data-bs-target="#collapseFiltros" class="btn btn-tertiary">Ver filtros</a>
              </div>
              <div class="col-12 collapse row mt-3" id="collapseFiltros">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nombre</label>
                    <input class="form-control form-control-sm" id="filtroNombre" placeholder="Ingrese nombre">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input class="form-control form-control-sm" id="filtroEmail" placeholder="Ingrese correo">
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
                <th scope="col">Correo electrónico</th>
                @if(!$esRestaurante)
                <th scope="col">Restaurante</th>
                @endif
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody id="tablaData"></tbody>
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
    var esRestaurante = {{ $esRestaurante ? 'true' : 'false' }};
    var editBaseUrl = "{{ $esRestaurante ? '/mis-usuarios/editar/' : '/restaurantes/usuarios-admin/editar/' }}";

    getData();

    $('.aplicarFiltros, .actualizar').click(function() { getData(); });
    $('.borrarFiltros').click(function() { $('.form-control').val(''); getData(); });

    function getData() {
        $.ajax({
            url: '{{ route($routePrefix . ".getdata") }}',
            method: 'POST',
            data: {
                page: pagination.current_page,
                filtros: {
                    nombre: $('#filtroNombre').val(),
                    email: $('#filtroEmail').val()
                }
            },
            success: function(res) {
                data = res.data;
                completarData();
                $('ul.pagination').html(completarNav(res, pagination));
            }
        });
    }

    function completarData() {
        var toappend = "";
        $.each(data, function(i, d) {
            toappend += "<tr>";
            toappend += "<td>" + d.name + "</td>";
            toappend += "<td>" + d.email + "</td>";
            if (!esRestaurante) {
                toappend += "<td>" + (d.restaurante ? d.restaurante.name : 'N/A') + "</td>";
            }
            toappend += "<td>" + (d.estado == 1 ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>') + "</td>";
            toappend += "<td>";
            toappend += "<a class='btn btn-light-primary btn-xs' href='" + editBaseUrl + d.id_encrypted + "'><i class='fa fa-edit'></i></a>";
            toappend += "</td>";
            toappend += "</tr>";
        });
        $('#tablaData').html(toappend);
    }
</script>
@endsection
