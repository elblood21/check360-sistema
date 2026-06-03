@extends('layouts.master')
@section('title', 'Ver Mistery Shopper')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('breadcrumb-title')
<h3>Ver Mistery Shopper</h3>
@endsection

@section('breadcrumb-items')
<li style="cursor:pointer;" class="breadcrumb-item" onclick="window.location.href='{{route('shoppers.lista')}}'">Mistery Shoppers</li>
<li class="breadcrumb-item">Ver</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card px-5">
        <div class="row pt-5">

            <div class="col-md-6 mb-3">
                <label class="form-label"><strong>Nombre:</strong></label>
                <p>{{$shopper->name}}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label"><strong>Email:</strong></label>
                <p>{{$shopper->email ?? 'N/A'}}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label"><strong>Teléfono:</strong></label>
                <p>{{$shopper->telefono ?? 'N/A'}}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label"><strong>Aprobación:</strong></label>
                <p>
                    @if($shopper->aprobado == 1)
                        <span class="badge badge-success">Aprobado</span>
                    @else
                        <span class="badge badge-warning">Pendiente</span>
                    @endif
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label"><strong>Estado:</strong></label>
                <p>
                    @if($shopper->estado == 1)
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                </p>
            </div>

            @if($shopper->observaciones)
            <div class="col-12 mb-3">
                <label class="form-label"><strong>Observaciones:</strong></label>
                <p>{{$shopper->observaciones}}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Sección de Encuesta del Perfil --}}
    <div class="card px-5 mt-4">
        <div class="row pt-5">
            <div class="col-12 mb-4">
                <h5>Encuesta del Perfil</h5>
                <hr>
            </div>

            @if($shopper->respondio_encuesta == 1 && count($respuestas) > 0)
                @foreach($preguntas as $pregunta)
                    @if(isset($respuestas[$pregunta->id]))
                        @php
                            $respuesta = $respuestas[$pregunta->id];
                            $valorRespuesta = $respuesta['respuesta_valor'] ?? $respuesta['respuesta_texto'];
                        @endphp
                        <div class="col-12 mb-4">
                            <label class="form-label"><strong>{{ $pregunta->orden }}. {{ $pregunta->texto }}</strong></label>
                            <div class="mt-2">
                                @if($pregunta->tipo_respuesta == 'escala_1_5')
                                    <span class="badge badge-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        {{ $valorRespuesta }}/5
                                    </span>
                                @elseif($pregunta->tipo_respuesta == 'si_no')
                                    @if($valorRespuesta == 'Sí')
                                        <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ $valorRespuesta }}</span>
                                    @else
                                        <span class="badge badge-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ $valorRespuesta }}</span>
                                    @endif
                                @elseif($pregunta->tipo_respuesta == 'opciones' || $pregunta->tipo_respuesta == 'opcion_multiple')
                                    <span class="badge badge-info" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ $valorRespuesta }}</span>
                                @else
                                    <p class="mb-0">{{ $valorRespuesta }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="icofont icofont-info-circle"></i> Encuesta aún no respondida
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Botones de acción --}}
    <div class="d-flex mt-4 mb-5">
        <a href="{{route('shoppers.lista')}}" class="btn btn-secondary me-2">Volver</a>
        
        @if($shopper->aprobado == 0)
            {{-- Mostrar botones Aprobar y Rechazar si no está aprobado --}}
            <button type="button" class="btn btn-success me-2 aprobar-shopper" data-shopper-id="{{encrypt($shopper->id)}}" data-shopper-name="{{$shopper->name}}">
                <i class="icofont icofont-check"></i> Aprobar
            </button>
            <button type="button" class="btn btn-danger me-2 rechazar-shopper" data-shopper-id="{{encrypt($shopper->id)}}" data-shopper-name="{{$shopper->name}}">
                <i class="icofont icofont-close"></i> Rechazar
            </button>
        @else
            {{-- Mostrar botón Editar solo si está aprobado --}}
            <a href="{{route('shoppers.editar', encrypt($shopper->id))}}" class="btn btn-primary">
                <i class="icofont icofont-pen"></i> Editar
            </a>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    // Aprobar shopper
    $(document).on('click', '.aprobar-shopper', function() {
        var shopperId = $(this).data('shopper-id');
        var shopperName = $(this).data('shopper-name');
        
        swal("¿Está seguro de aprobar a " + shopperName + "?", {
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
                    data: {id: shopperId},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if(res.estado == 200) {
                            notify("�?xito", "Mistery Shopper aprobado correctamente", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notify("Error", res.mensaje || "Error al aprobar", "danger");
                        }
                    },
                    error: function() {
                        notify("Error", "Error al aprobar", "danger");
                    }
                });
            }
        });
    });

    // Rechazar shopper
    $(document).on('click', '.rechazar-shopper', function() {
        var shopperId = $(this).data('shopper-id');
        var shopperName = $(this).data('shopper-name');
        
        swal({
            title: "¿Está seguro de rechazar a " + shopperName + "?",
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
                        id: shopperId,
                        motivo: motivo
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if(res.estado == 200) {
                            notify("�?xito", "Registro rechazado correctamente", "success");
                            setTimeout(function() {
                                window.location.href = '{{route("shoppers.lista")}}';
                            }, 1000);
                        } else {
                            notify("Error", res.mensaje || "Error al rechazar", "danger");
                        }
                    },
                    error: function() {
                        notify("Error", "Error al rechazar", "danger");
                    }
                });
            }
        });
    });
</script>
@endsection




