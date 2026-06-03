@extends('layouts.master')
@php
    $esRestaurante = \App\Helpers\SubdominioHelper::esTipo('restaurante');
    $routePrefix = $esRestaurante ? 'restaurantes.usuarios' : 'restaurantes.usuarios_admin';
@endphp
@section('title', $usuario ? 'Editar Usuario' : 'Nuevo Usuario')

@section('breadcrumb-title')
<h3>{{ $usuario ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Restaurantes</li>
<li class="breadcrumb-item"><a href="{{ route($routePrefix . '.lista') }}">Usuarios</a></li>
<li class="breadcrumb-item active">{{ $usuario ? 'Editar' : 'Nuevo' }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <form id="formUsuario" action="{{ $usuario ? route($routePrefix . '.update') : route($routePrefix . '.store') }}" method="POST">
                    @csrf
                    @if($usuario)
                        <input type="hidden" name="id" value="{{ $usuario->id_encrypted }}">
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Completo (*)</label>
                                <input class="form-control" type="text" name="nombre" value="{{ $usuario ? $usuario->name : '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico (*)</label>
                                <input class="form-control" type="email" name="email" value="{{ $usuario ? $usuario->email : '' }}" required>
                            </div>
                            
                            @if(!$esRestaurante)
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Restaurante (*)</label>
                                <select class="form-select js-example-basic-single" name="restaurante_id" required>
                                    <option value="">Seleccione restaurante</option>
                                    @foreach($restaurantes as $r)
                                        <option value="{{ $r->id }}" {{ ($usuario && $usuario->restaurante_id == $r->id) || (isset($restaurante_id_default) && $restaurante_id_default == $r->id) ? 'selected' : '' }}>{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="restaurante_id" value="{{ $restaurantes->first()->id }}">
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">{{ $usuario ? 'Actualizar' : 'Crear Usuario' }}</button>
                        <a href="{{ route($routePrefix . '.lista') }}" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#formUsuario').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                if(res.estado == 200) {
                    Swal.fire('Éxito', 'Operación realizada correctamente', 'success').then(() => {
                        window.location.href = "{{ route($routePrefix . '.lista') }}";
                    });
                } else {
                    Swal.fire('Error', res.mensaje || 'Error al procesar', 'error');
                }
            }
        });
    });
</script>
@endsection
