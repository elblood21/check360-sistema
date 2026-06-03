@extends('layouts.master')
@section('title', ($visita ? 'Editar' : 'Nuevo')." visita")

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{$visita ? 'Editar' : 'Nuevo'}} visita</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('visitas.lista')}}'">Visitas</li>
<li class="breadcrumb-item">{{$visita ? 'Editar' : 'Nuevo'}}</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card px-5">
        <div class="row pt-5">

            <div class="col-md-6 mb-3">
                <label class="form-label" for="restaurante_id">Restaurante (*)</label>
                <select class="form-select form-select-sm btn-square" id="restaurante_id">
                    <option value="">Seleccione un restaurante</option>
                    @foreach($restaurantes as $restaurante)
                        <option value="{{$restaurante->id}}" {{($visita && $visita->restaurante_id == $restaurante->id) || ($restaurante_id && $restaurante_id == $restaurante->id) ? 'selected' : ''}}>{{$restaurante->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="shopper_id">Mistery Shopper (*)</label>
                <select class="form-select form-select-sm btn-square" id="shopper_id" {{!$visita && !$restaurante_id ? 'disabled' : ''}}>
                    <option value="">@if($visita) Cargando... @else Primero seleccione un restaurante @endif</option>
                    @if($visita && $shoppers->count() > 0)
                        @foreach($shoppers as $shopper)
                            <option value="{{$shopper->id}}" {{$visita->mistery_shopper_id == $shopper->id ? 'selected' : ''}}>{{$shopper->name}}</option>
                        @endforeach
                    @endif
                </select>
                <div class="form-text" id="shopper-help-text" style="display: none;"></div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="fecha_asignacion">Fecha de asignación (*)</label>
                <input type="date" value="{{$visita ? $visita->fecha_asignacion->format('Y-m-d') : ''}}" class="form-control form-control-sm btn-square" id="fecha_asignacion">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="hora_asignacion">Hora de asignación (*)</label>
                <input type="time" value="{{$visita && $visita->hora_asignacion ? date('H:i', strtotime($visita->hora_asignacion)) : ''}}" class="form-control form-control-sm btn-square" id="hora_asignacion">
            </div>

            <div class="col-12 d-flex mt-4 mb-5">
                <button class="btn btn-primary my-auto ms-auto me-0 submitButton" type="button">{{$visita ? 'Actualizar información' : 'Crear visita'}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Guardar el shopper_id actual si estamos editando
    var shopperIdActual = {{$visita ? $visita->mistery_shopper_id : 'null'}};
    
    // Cargar shoppers cuando se selecciona restaurante
    $(document).on('change', '#restaurante_id', function() {
        var restauranteId = $(this).val();
        var shopperSelect = $('#shopper_id');
        var helpText = $('#shopper-help-text');
        
        if (!restauranteId) {
            shopperSelect.html('<option value="">Primero seleccione un restaurante</option>').prop('disabled', true);
            helpText.text('Seleccione primero un restaurante para ver los shoppers disponibles');
            return;
        }
        
        shopperSelect.html('<option value="">Cargando...</option>').prop('disabled', true);
        helpText.text('Cargando shoppers disponibles...');
        
        $.ajax({
            url: '{{ route("visitas.get_shoppers") }}',
            method: 'POST',
            data: {
                restaurante_id: restauranteId,
                visita_id: "{{$visita ? $visita->id_encrypted : ''}}"
            },
            success: function(res) {
                if(res.estado == 200 && res.shoppers && res.shoppers.length > 0) {
                    var options = '<option value="">Seleccione un shopper</option>';
                    $.each(res.shoppers, function(i, shopper) {
                        // Comparar correctamente el ID (convertir ambos a número)
                        var selected = '';
                        if(shopperIdActual) {
                            if(parseInt(shopperIdActual) === parseInt(shopper.id)) {
                                selected = 'selected';
                            }
                        }
                        options += '<option value="' + shopper.id + '" ' + selected + '>' + shopper.name + '</option>';
                    });
                    shopperSelect.html(options).prop('disabled', false);
                    
                    // Forzar la selección del shopper actual si existe
                    if(shopperIdActual) {
                        shopperSelect.val(shopperIdActual);
                    }
                    
                    helpText.text('');
                } else {
                    shopperSelect.html('<option value="">No hay shoppers disponibles</option>').prop('disabled', true);
                    helpText.text(res.mensaje || 'No hay shoppers disponibles para este restaurante');
                }
            },
            error: function() {
                shopperSelect.html('<option value="">Error al cargar</option>').prop('disabled', true);
                helpText.text('Error al cargar los shoppers. Intente nuevamente.');
            }
        });
    });
    
    // Si hay restaurante seleccionado al cargar (edición), cargar shoppers
    @if($visita || $restaurante_id)
        $(document).ready(function() {
            // Esperar un momento para que el DOM esté completamente cargado
            setTimeout(function() {
                $('#restaurante_id').trigger('change');
            }, 100);
        });
    @endif

    $(document).on('click','.submitButton', function() {
        if(!validar()) return false;
        $('.submitButton').prop('disabled',true);

        var url = "{{$visita ? route('visitas.update') : route('visitas.store')}}";
        $.ajax({
            url:url,
            method:'POST',
            data:{
                shopper_id:$('#shopper_id').val(),
                restaurante_id:$('#restaurante_id').val(),
                fecha_asignacion:$('#fecha_asignacion').val(),
                hora_asignacion:$('#hora_asignacion').val(),
                id:"{{$visita ? $visita->id_encrypted : ''}}"
            },
            success:function(res) {
                if(res.estado == 200) {
                    notify('Exito', 'Visita ' + ($visita ? 'actualizada' : 'creada') + ' correctamente', 'success');
                    setTimeout(function() {
                        window.location.href = "{{route('visitas.lista')}}";
                    }, 1000);
                } else {
                    $('.submitButton').prop('disabled',false);
                    notify('Error',res.mensaje || 'Error','danger');
                }
            },
            error:function(xhr) {
                $('.submitButton').prop('disabled',false);
                var mensaje = 'Error al procesar la solicitud';
                if(xhr.responseJSON && xhr.responseJSON.mensaje) {
                    mensaje = xhr.responseJSON.mensaje;
                }
                notify('Error', mensaje, 'danger');
            }
        })
    });

    function validar() {
        if($('#restaurante_id').val() == "") {
            notify('Advertencia','Debe seleccionar un restaurante','danger');
            return false;
        }
        if($('#shopper_id').val() == "") {
            notify('Advertencia','Debe seleccionar un Mistery Shopper','danger');
            return false;
        }
        if($('#fecha_asignacion').val() == "") {
            notify('Advertencia','Debe seleccionar una fecha de asignación','danger');
            return false;
        }
        if($('#hora_asignacion').val() == "") {
            notify('Advertencia','Debe seleccionar una hora de asignación','danger');
            return false;
        }
        return true;
    }
</script>
@endsection




