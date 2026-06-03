@extends('layouts.master')
@section('title', ($pregunta ? 'Editar' : 'Nueva').' Pregunta')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{$pregunta ? 'Editar' : 'Nueva'}} Pregunta</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('encuestas.lista')}}'">Encuestas</li>
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('encuestas.ver_preguntas', encrypt($encuesta->id))}}'">Preguntas</li>
<li class="breadcrumb-item">{{$pregunta ? 'Editar' : 'Nueva'}}</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card px-5">
        <div class="row pt-5">
            <div class="col-12 mb-3">
                <div class="alert alert-info">
                    <strong>Encuesta:</strong> {{ $encuesta->nombre }} ({{ $encuesta->tipo == 'entrada' ? 'Entrada' : 'Salida' }})
                </div>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" for="texto">Texto de la Pregunta (*)</label>
                <textarea class="form-control form-control-sm btn-square" id="texto" rows="3" placeholder="Ingrese el texto de la pregunta">{{$pregunta ? $pregunta->texto : ''}}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="tipo_respuesta">Tipo de Respuesta (*)</label>
                <select class="form-select form-select-sm btn-square" id="tipo_respuesta">
                    <option value="">Seleccione tipo</option>
                    <option value="escala_1_5" {{$pregunta && $pregunta->tipo_respuesta == 'escala_1_5' ? 'selected' : ''}}>Escala 1-5</option>
                    <option value="opcion_multiple" {{$pregunta && $pregunta->tipo_respuesta == 'opcion_multiple' ? 'selected' : ''}}>Con opciones</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="orden">Orden (*)</label>
                <input type="number" value="{{$pregunta ? $pregunta->orden : ''}}" class="form-control form-control-sm btn-square" id="orden" placeholder="Ingrese orden" min="1">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="dimension">Dimensión</label>
                <input value="{{$pregunta ? $pregunta->dimension : ''}}" class="form-control form-control-sm btn-square" id="dimension" placeholder="Ingrese dimensión">
            </div>

            <div class="col-12 mb-3" id="opciones-container" style="display: none;">
                <label class="form-label" for="opciones">Opciones (JSON - Para Escala 1-5 y Con opciones)</label>
                <textarea class="form-control form-control-sm btn-square" id="opciones" rows="5" placeholder='Ejemplo: ["Opción 1", "Opción 2", "Opción 3"]'>{{$pregunta && $pregunta->opciones ? json_encode($pregunta->opciones, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : ''}}</textarea>
                <small class="form-text text-muted">Ingrese las opciones en formato JSON array. Ejemplo: ["Muy bueno", "Bueno", "Regular", "Malo", "Muy malo"]</small>
            </div>

            <div class="col-12 d-flex mt-4 mb-5">
                <a href="{{ route('encuestas.ver_preguntas', encrypt($encuesta->id)) }}" class="btn btn-secondary my-auto me-2">Cancelar</a>
                <button class="btn btn-primary my-auto ms-auto me-0 submitButton" type="button">{{$pregunta ? 'Actualizar Pregunta' : 'Crear Pregunta'}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Mostrar/ocultar campo de opciones según el tipo de respuesta
    $('#tipo_respuesta').change(function() {
        var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
        if(tiposConOpciones.includes($(this).val())) {
            $('#opciones-container').show();
        } else {
            $('#opciones-container').hide();
        }
    });

    // Inicializar visibilidad del campo opciones
    var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
    if(tiposConOpciones.includes($('#tipo_respuesta').val())) {
        $('#opciones-container').show();
    }

    $(document).on('click','.submitButton', function() {
        if(!validar()) return false;
        $('.submitButton').prop('disabled',true);

        var url = "{{$pregunta ? route('encuestas.actualizar_pregunta') : route('encuestas.guardar_pregunta')}}";
        var data = {
            texto: $('#texto').val(),
            tipo_respuesta: $('#tipo_respuesta').val(),
            orden: $('#orden').val(),
            dimension: $('#dimension').val(),
            encuesta_id: "{{ encrypt($encuesta->id) }}"
        };

        var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
        if(tiposConOpciones.includes($('#tipo_respuesta').val())) {
            try {
                var opciones = JSON.parse($('#opciones').val());
                data.opciones = JSON.stringify(opciones);
            } catch(e) {
                $('.submitButton').prop('disabled',false);
                notify('Error', 'El formato JSON de las opciones es inválido', 'danger');
                return false;
            }
        }

        if($pregunta) {
            data.id = "{{ $pregunta ? encrypt($pregunta->id) : '' }}";
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(res) {
                if(res.estado == 200) {
                    notify('Éxito', '{{$pregunta ? "Pregunta actualizada" : "Pregunta creada"}} con éxito', 'success');
                    setTimeout(function() {
                        window.location.href = "{{ route('encuestas.ver_preguntas', encrypt($encuesta->id)) }}";
                    }, 1000);
                } else {
                    $('.submitButton').prop('disabled',false);
                    notify('Error', res.mensaje || 'Error', 'danger');
                }
            },
            error: function() {
                $('.submitButton').prop('disabled',false);
                notify('Error', 'Error al {{$pregunta ? "actualizar" : "crear"}} la pregunta', 'danger');
            }
        });
    });

    function validar() {
        if($('#texto').val().trim() == "") {
            notify('Advertencia', 'Debe completar el campo texto de la pregunta', 'danger');
            return false;
        }
        if($('#tipo_respuesta').val() == "") {
            notify('Advertencia', 'Debe seleccionar el tipo de respuesta', 'danger');
            return false;
        }
        if($('#orden').val() == "" || $('#orden').val() < 1) {
            notify('Advertencia', 'Debe ingresar un orden válido (mayor a 0)', 'danger');
            return false;
        }
        var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
        if(tiposConOpciones.includes($('#tipo_respuesta').val())) {
            if($('#opciones').val().trim() == "") {
                notify('Advertencia', 'Debe ingresar las opciones para este tipo de respuesta', 'danger');
                return false;
            }
            try {
                JSON.parse($('#opciones').val());
            } catch(e) {
                notify('Advertencia', 'El formato JSON de las opciones es inválido', 'danger');
                return false;
            }
        }
        return true;
    }
</script>
@endsection

