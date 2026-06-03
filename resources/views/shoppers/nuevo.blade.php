@extends('layouts.master')
@section('title', ($shopper ? 'Editar' : 'Nuevo')." Mistery Shopper")

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{$shopper ? 'Editar' : 'Nuevo'}} Mistery Shopper</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('shoppers.lista')}}'">Mistery Shoppers</li>
<li class="breadcrumb-item">{{$shopper ? 'Editar' : 'Nuevo'}}</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card px-5">
        <div class="row pt-5">

            <div class="col-md-6 mb-3">
                <label class="form-label" for="nombre">Nombre (*)</label>
                <input value="{{$shopper ? $shopper->name : ''}}" class="form-control form-control-sm btn-square" id="nombre" placeholder="Ingrese nombre">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="email">Email</label>
                <input value="{{$shopper ? $shopper->email : ''}}" class="form-control form-control-sm btn-square" id="email" placeholder="Ingrese email">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="telefono">Teléfono</label>
                <input value="{{$shopper ? $shopper->telefono : ''}}" class="form-control form-control-sm btn-square" id="telefono" placeholder="Ingrese teléfono">
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" for="observaciones">Observaciones</label>
                <textarea class="form-control form-control-sm btn-square" id="observaciones" rows="3" placeholder="Ingrese observaciones">{{$shopper ? $shopper->observaciones : ''}}</textarea>
            </div>

            <div class="col-12 d-flex mt-4 mb-5">
                <button class="btn btn-primary my-auto ms-auto me-0 submitButton" type="button">{{$shopper ? 'Actualizar información' : 'Crear Mistery Shopper'}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).on('click','.submitButton', function() {
        if(!validar()) return false;
        $('.submitButton').prop('disabled',true);

        var url = "{{$shopper ? route('shoppers.update') : route('shoppers.store')}}";
        $.ajax({
            url:url,
            method:'POST',
            data:{
                nombre:$('#nombre').val(),
                email:$('#email').val(),
                telefono:$('#telefono').val(),
                observaciones:$('#observaciones').val(),
                id:"{{$shopper ? $shopper->id_encrypted : ''}}"
            },
            success:function(res) {
                if(res.estado == 200) {
                    window.location.href = "{{route('shoppers.lista')}}";
                } else {
                    $('.submitButton').prop('disabled',false);
                    notify('Error',res.mensaje || 'Error','danger');
                }
            }
        })
    });

    function validar() {
        if($('#nombre').val() == "") {
            notify('Advertencia','Debe completar el campo nombre','danger');
            return false;
        }
        return true;
    }
</script>
@endsection




