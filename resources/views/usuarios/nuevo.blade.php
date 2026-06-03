@extends('layouts.master')
@section('title', ($usuario ? 'Editar' : 'Nuevo')." usuario")

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{$usuario ? 'Editar' : 'Nuevo'}} usuario</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('usuarios.lista')}}'">Empresas</li>
<li class="breadcrumb-item">Nuevo</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card px-5">
        
        <div class="row pt-5">

            <div class="col-md-6 mb-3">
                <label class="form-label" for="nombre">Nombre (*)</label>
                <input value="{{$usuario ? $usuario->name : ''}}" class="form-control form-control-sm btn-square" id="nombre" placeholder="Ingrese nombre" data-bs-original-title="" title="">
            </div>

            @if(!\App\Helpers\SubdominioHelper::esTipo('restaurante'))
            <div class="col-md-6 mb-3">
                <label class="form-label" for="rut">Rut</label>
                <input value="{{$usuario ? $usuario->rut : ''}}" class="form-control form-control-sm btn-square" id="rut" placeholder="Ingrese rut" data-bs-original-title="" title="">
            </div>
            @endif

            <div class="col-md-6 mb-3">
                <label class="form-label" for="correoelectronico">Correo electronico (*)</label>
                <input value="{{$usuario ? $usuario->email : ''}}" class="form-control form-control-sm btn-square" id="correoelectronico" placeholder="Ingrese correo electronico" data-bs-original-title="" title="">
            </div>

            <div class="col-12 d-flex mt-5 mb-5">
                <button class="btn btn-primary my-auto ms-auto me-0 submitButton" type="button" data-bs-original-title="" title="">{{$usuario ? 'Actualizar información' : 'Crear usuario'}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("#rut").rut({
        formatOn: 'keyup',
        minimumLength: 8
    });

    $(document).on('click','.submitButton', function() {
        if(!validar()) return false;
        $('.submitButton').prop('disabled',true);

        var url = "{{$usuario ? route('usuarios.update') : route('usuarios.store')}}";
        $.ajax({
            url:url,
            method:'POST',
            data:{
                nombre:$('#nombre').val(),
                correo_electronico:$('#correoelectronico').val(),
                rut:$('#rut').val(),
                id:"{{$usuario ? $usuario->id_encrypted : ''}}"
            },
            success:function(res) {
                if(res.estado == 200) {
                    window.location.href = "{{route('usuarios.lista')}}";
                } else {
                    $('.submitButton').prop('disabled',false);
                    notify('Error',res.mensaje,'danger');
                }
            }
        })
    });

    function validar() {
        if($('#nombre').val() == "") {
            notify('Advertencia','Debe completar el campo nombre','danger');
            return false;
        } else if($('#rut').length > 0 && $('#rut').val() != "" && !$.validateRut($('#rut').val())) {
            notify("Advertencia","Debe ingresar un rut valido","warning");
            return false;
        } else if($('#correoelectronico').val() == "") {
            notify('Advertencia','Debe completar el campo correo electronico','danger');
            return false;
        }

        return true;
    }
</script>
@endsection

