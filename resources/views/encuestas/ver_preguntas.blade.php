@extends('layouts.master')
@section('title', 'Preguntas de Encuesta')

@section('css')
    
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css') }}">
<style>
    .dimension-card {
        margin-bottom: 2rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    .dimension-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .btn-agregar-pregunta {
        color: white !important;
        border-color: rgba(255, 255, 255, 0.3) !important;
        background-color: rgba(255, 255, 255, 0.2) !important;
    }
    .btn-agregar-pregunta:hover {
        background-color: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        color: white !important;
    }
    .pregunta-item {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
    }
    .pregunta-item:hover {
        background-color: #f8f9fa;
    }
    .pregunta-item:last-child {
        border-bottom: none;
    }
    .drag-handle {
        cursor: move;
        color: #999;
        font-size: 1.2rem;
        margin-right: 1rem;
        padding: 0.5rem;
    }
    .drag-handle:hover {
        color: #667eea;
    }
    .pregunta-content {
        flex: 1;
    }
    .tipo-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .tipo-icon {
        font-size: 0.9rem;
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #f0f0f0;
    }
    .opciones-tags-container {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.5rem;
        min-height: 50px;
        background-color: #fff;
    }
    .opciones-tags-container:focus-within {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .opcion-tag {
        font-size: 0.875rem;
        padding: 0.4rem 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: default;
        transition: all 0.2s;
    }
    .opcion-tag:hover {
        opacity: 0.9;
    }
    .opcion-tag i {
        cursor: pointer;
        font-size: 0.75rem;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .opcion-tag i:hover {
        opacity: 1;
    }
    #modal_opciones-input {
        border: none;
        box-shadow: none;
        padding: 0.25rem 0.5rem;
        outline: none;
        width: 100%;
    }
    #modal_opciones-input:focus {
        outline: none;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>Preguntas de Encuesta</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('encuestas.lista') }}">Encuestas</a></li>
<li class="breadcrumb-item">Preguntas</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Preguntas - {{ $encuesta->nombre }} ({{ $encuesta->tipo == 'entrada' ? 'Entrada' : 'Salida' }})</h5>
        </div>
        <div class="card-body">
            @if($encuesta->preguntas->count() > 0)
                @php
                    $preguntasPorDimension = $encuesta->preguntas->sortBy('orden')->groupBy('dimension');
                @endphp
                
                @foreach($preguntasPorDimension->sortKeys() as $dimension => $preguntas)
                <div class="dimension-card" data-dimension="{{ $dimension }}">
                    <div class="dimension-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="icofont icofont-layers"></i> Dimensión {{ $dimension ?? 'Sin dimensión' }}
                        </div>
                        <button type="button" class="btn btn-sm btn-light btn-agregar-pregunta" data-dimension="{{ $dimension ?? '' }}" data-encuesta-id="{{ encrypt($encuesta->id) }}">
                            <i class="icofont icofont-plus"></i> Agregar Pregunta
                        </button>
                    </div>
                    <div class="preguntas-list" data-dimension="{{ $dimension }}">
                        @foreach($preguntas->sortBy('orden') as $index => $pregunta)
                        <div class="pregunta-item" data-pregunta-id="{{ $pregunta->id }}" data-orden="{{ $pregunta->orden }}">
                            <div class="drag-handle">
                                <i class="icofont icofont-drag"></i>
                            </div>
                            <div class="pregunta-content">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="flex-grow-1">
                                        <span class="badge badge-primary me-2 orden-badge">{{ $pregunta->orden }}.-</span>
                                        <strong>{{ $pregunta->texto }}</strong>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            @php
                                                $tipoLabels = [
                                                    'opcion_multiple' => ['label' => 'Con opciones', 'icon' => 'icofont-list', 'color' => 'success'],
                                                    'escala_1_5' => ['label' => 'Escala 1-5', 'icon' => 'icofont-star', 'color' => 'primary'],
                                                ];
                                                $tipoInfo = $tipoLabels[$pregunta->tipo_respuesta] ?? ['label' => ucfirst(str_replace('_', ' ', $pregunta->tipo_respuesta)), 'icon' => 'icofont-question', 'color' => 'secondary'];
                                            @endphp
                                            <span class="badge badge-{{ $tipoInfo['color'] }} tipo-badge">
                                                <i class="icofont {{ $tipoInfo['icon'] }} tipo-icon"></i>
                                                {{ $tipoInfo['label'] }}
                                            </span>
                                        </div>
                                        <div class="dropleft">
                                            <button type="button" class="btn btn-default btn-sm" data-bs-toggle="dropdown" id="acciones{{ $pregunta->id }}">
                                                <i style="font-size:1.4rem;" class="icofont icofont-options"></i>
                                            </button>
                                            <ul class="dropdown-menu btns" aria-labelledby="acciones{{ $pregunta->id }}">
                                                <li style="cursor:pointer;padding:0.5rem;">
                                                    <a class="dropdown-item editar-pregunta" href="javascript:void(0)" data-id="{{ encrypt($pregunta->id) }}">
                                                        <i class="icofont icofont-pen"></i> Editar
                                                    </a>
                                                </li>
                                                <li style="cursor:pointer;padding:0.5rem;">
                                                    <a class="dropdown-item eliminar-pregunta" data-id="{{ encrypt($pregunta->id) }}" data-texto="{{ $pregunta->texto }}">
                                                        <i class="icofont icofont-trash"></i> Eliminar
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
            <div class="text-center py-5">
                <i class="icofont icofont-question" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">No hay preguntas en esta encuesta</p>
                <button type="button" class="btn btn-primary mt-3 btn-agregar-pregunta" data-dimension="" data-encuesta-id="{{ encrypt($encuesta->id) }}">
                    <i class="icofont icofont-plus"></i> Agregar Primera Pregunta
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Pregunta -->
<div class="modal fade" id="modalAgregarPregunta" tabindex="-1" aria-labelledby="modalAgregarPreguntaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarPreguntaLabel">Agregar Nueva Pregunta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label" for="modal_texto">Texto de la Pregunta (*)</label>
                        <textarea class="form-control form-control-sm btn-square" id="modal_texto" rows="3" placeholder="Ingrese el texto de la pregunta"></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="modal_tipo_respuesta">Tipo de Respuesta (*)</label>
                        <select class="form-select form-select-sm btn-square" id="modal_tipo_respuesta">
                            <option value="">Seleccione tipo</option>
                            <option value="escala_1_5">Escala 1-5</option>
                            <option value="si_no">Si / No</option>
                            <option value="opcion_multiple">Con opciones</option>
                            <option value="texto_libre">Texto libre</option>
                        </select>
                    </div>

                    <div class="col-12 mb-3" id="modal_opciones-container" style="display: none;">
                        <label class="form-label">Opciones (Para Escala 1-5 y Con opciones)</label>
                        <div class="opciones-tags-container" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.5rem; min-height: 50px; background-color: #fff;">
                            <div id="modal_opciones-tags" class="d-flex flex-wrap gap-2 mb-2" style="min-height: 30px;">
                                <!-- Los tags se agregarán aquí dinámicamente -->
                            </div>
                            <input type="text" class="form-control form-control-sm" id="modal_opciones-input" placeholder="Escribe una opción y presiona Enter" style="border: none; box-shadow: none; padding: 0.25rem 0.5rem;">
                        </div>
                        <small class="form-text text-muted">Escribe cada opción y presiona Enter para agregarla. Para Escala 1-5, puedes usar formato "1: Insuficiente" o solo "1".</small>
                        <input type="hidden" id="modal_opciones" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarPregunta">Guardar Pregunta</button>
            </div>
            <input type="hidden" id="modal_pregunta_id" value="">
            <input type="hidden" id="modal_dimension" value="">
            <input type="hidden" id="modal_orden" value="">
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar Sortable para cada dimensión
        $('.preguntas-list').each(function() {
            var dimension = $(this).data('dimension');
            var sortable = new Sortable(this, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    var items = $(this.el).find('.pregunta-item');
                    var newOrder = [];
                    items.each(function(index) {
                        var preguntaId = $(this).data('pregunta-id');
                        newOrder.push({
                            id: preguntaId,
                            orden: index + 1
                        });
                    });
                    
                    // Actualizar orden en el servidor
                    actualizarOrden(newOrder, dimension);
                }
            });
        });
    });

    function actualizarOrden(newOrder, dimension) {
        $.ajax({
            url: '{{ route("encuestas.actualizar_orden") }}',
            method: 'POST',
            data: {
                ordenes: newOrder,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if(res.estado == 200) {
                    // Actualizar los números de orden visualmente solo en la dimensión afectada
                    $('.preguntas-list[data-dimension="' + dimension + '"]').find('.pregunta-item').each(function(index) {
                        $(this).find('.orden-badge').text((index + 1) + '.-');
                        $(this).data('orden', index + 1);
                    });
                    notify("Éxito", "Orden actualizado correctamente", "success");
                } else {
                    notify("Error", res.mensaje || "Error al actualizar el orden", "danger");
                    location.reload();
                }
            },
            error: function() {
                notify("Error", "Error al actualizar el orden", "danger");
                location.reload();
            }
        });
    }

    $(document).on('click', '.eliminar-pregunta', function(e) {
        e.preventDefault();
        var preguntaId = $(this).data('id');
        var preguntaTexto = $(this).data('texto');
        
        swal("¿Está seguro de eliminar la pregunta: " + preguntaTexto + "?", {
            buttons: {
                cancel: "Cancelar",
                eliminar: {
                    text: "Eliminar",
                    value: "eliminar",
                }
            },
        }).then((value) => {
            if(value == "eliminar") {
                $.ajax({
                    url: '{{ route("encuestas.eliminar_pregunta") }}',
                    method: 'POST',
                    data: {id: preguntaId},
                    success: function(res) {
                        if(res.estado == 200) {
                            notify("Éxito", "Pregunta eliminada con éxito", "success");
                            location.reload();
                        } else {
                            notify("Error", res.mensaje || "Error al eliminar", "danger");
                        }
                    },
                    error: function() {
                        notify("Error", "Error al eliminar la pregunta", "danger");
                    }
                });
            }
        });
    });

    // Manejar apertura del modal para agregar pregunta
    $(document).on('click', '.btn-agregar-pregunta', function() {
        var dimension = $(this).data('dimension') || '';
        var encuestaId = $(this).data('encuesta-id');
        
        // Limpiar formulario
        resetearModal();
        $('#modal_pregunta_id').val('');
        $('#modal_dimension').val(dimension);
        
        // Calcular siguiente orden para la dimensión (último + 1)
        calcularSiguienteOrden(dimension);
        
        // Cambiar título y texto del botón
        $('#modalAgregarPreguntaLabel').text('Agregar Nueva Pregunta');
        $('#btnGuardarPregunta').text('Guardar Pregunta');
        
        // Guardar encuesta_id en el botón de guardar
        $('#btnGuardarPregunta').data('encuesta-id', encuestaId);
        $('#btnGuardarPregunta').data('modo', 'crear');
        
        // Mostrar modal
        var modal = new bootstrap.Modal(document.getElementById('modalAgregarPregunta'));
        modal.show();
    });

    // Manejar apertura del modal para editar pregunta
    $(document).on('click', '.editar-pregunta', function(e) {
        e.preventDefault();
        var preguntaId = $(this).data('id');
        
        if(!preguntaId) {
            notify('Error', 'No se pudo obtener el ID de la pregunta', 'danger');
            return;
        }
        
        // Codificar el ID para la URL (por si tiene caracteres especiales)
        var preguntaIdEncoded = encodeURIComponent(preguntaId);
        
        // Construir la URL usando la ruta definida
        var url = '{{ route("encuestas.get_pregunta", ["id" => ":id"]) }}'.replace(':id', preguntaIdEncoded);
        
        // Cargar datos de la pregunta
        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if(res.estado == 200) {
                    var pregunta = res.pregunta;
                    
                    // Limpiar y llenar formulario
                    resetearModal();
                    $('#modal_pregunta_id').val(pregunta.id);
                    $('#modal_texto').val(pregunta.texto);
                    $('#modal_tipo_respuesta').val(pregunta.tipo_respuesta);
                    // Guardar dimension y orden en campos hidden (no se muestran ni se modifican)
                    $('#modal_dimension').val(pregunta.dimension);
                    $('#modal_orden').val(pregunta.orden);
                    
                    // Mostrar opciones si el tipo requiere opciones
                    var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
                    if(tiposConOpciones.includes(pregunta.tipo_respuesta) && pregunta.opciones) {
                        // Renderizar tags desde las opciones
                        renderizarTags(pregunta.opciones);
                        $('#modal_opciones-container').show();
                    } else {
                        limpiarTags();
                        $('#modal_opciones-container').hide();
                    }
                    
                    // Cambiar título y texto del botón
                    $('#modalAgregarPreguntaLabel').text('Editar Pregunta');
                    $('#btnGuardarPregunta').text('Actualizar Pregunta');
                    
                    // Guardar encuesta_id (no necesario para editar pero por consistencia)
                    $('#btnGuardarPregunta').data('modo', 'editar');
                    
                    // Mostrar modal
                    var modal = new bootstrap.Modal(document.getElementById('modalAgregarPregunta'));
                    modal.show();
                } else {
                    notify('Error', res.mensaje || 'Error al cargar la pregunta', 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar pregunta:', xhr, status, error);
                var mensaje = 'Error al cargar la pregunta';
                if(xhr.responseJSON && xhr.responseJSON.mensaje) {
                    mensaje = xhr.responseJSON.mensaje;
                } else if(xhr.status === 404) {
                    mensaje = 'Pregunta no encontrada';
                } else if(xhr.status === 500) {
                    mensaje = 'Error del servidor al cargar la pregunta';
                }
                notify('Error', mensaje, 'danger');
            }
        });
    });

    // Calcular siguiente orden según la dimensión
    function calcularSiguienteOrden(dimension) {
        var maxOrden = 0;
        var preguntasList = $('.preguntas-list[data-dimension="' + dimension + '"]');
        if (preguntasList.length > 0) {
            preguntasList.find('.pregunta-item').each(function() {
                var orden = parseInt($(this).data('orden')) || 0;
                if (orden > maxOrden) {
                    maxOrden = orden;
                }
            });
        }
        $('#modal_orden').val(maxOrden + 1);
    }

    // Función para resetear el modal
    function resetearModal() {
        $('#modal_texto').val('');
        $('#modal_tipo_respuesta').val('');
        $('#modal_orden').val('');
        $('#modal_dimension').val('');
        limpiarTags();
        $('#modal_opciones-container').hide();
        $('#modal_pregunta_id').val('');
        $('#btnGuardarPregunta').prop('disabled', false);
        $('#modalAgregarPreguntaLabel').text('Agregar Nueva Pregunta');
        $('#btnGuardarPregunta').text('Guardar Pregunta');
    }

    // Función para renderizar tags desde un array
    function renderizarTags(opciones) {
        $('#modal_opciones-tags').empty();
        if(Array.isArray(opciones)) {
            opciones.forEach(function(opcion) {
                agregarTag(opcion);
            });
        }
        actualizarOpcionesHidden();
    }

    // Función para limpiar todos los tags
    function limpiarTags() {
        $('#modal_opciones-tags').empty();
        $('#modal_opciones-input').val('');
        actualizarOpcionesHidden();
    }

    // Función para agregar un tag
    function agregarTag(texto) {
        if(!texto || texto.trim() === '') return;
        
        // Verificar si ya existe
        var existe = false;
        $('#modal_opciones-tags .opcion-tag').each(function() {
            if($(this).data('valor') === texto.trim()) {
                existe = true;
                return false;
            }
        });
        
        if(existe) return;
        
        var tag = $('<span>', {
            class: 'badge badge-primary opcion-tag',
            style: 'font-size: 0.875rem; padding: 0.4rem 0.6rem; display: inline-flex; align-items: center; gap: 0.5rem; cursor: default;',
            'data-valor': texto.trim()
        });
        
        tag.append($('<span>').text(texto.trim()));
        tag.append($('<i>', {
            class: 'icofont icofont-close',
            style: 'cursor: pointer; font-size: 0.75rem;',
            click: function(e) {
                e.stopPropagation();
                $(this).closest('.opcion-tag').remove();
                actualizarOpcionesHidden();
            }
        }));
        
        $('#modal_opciones-tags').append(tag);
        $('#modal_opciones-input').val('');
        actualizarOpcionesHidden();
    }

    // Función para actualizar el campo hidden con las opciones
    function actualizarOpcionesHidden() {
        var opciones = [];
        $('#modal_opciones-tags .opcion-tag').each(function() {
            opciones.push($(this).data('valor'));
        });
        $('#modal_opciones').val(JSON.stringify(opciones));
    }

    // Manejar Enter en el input de opciones
    $(document).on('keydown', '#modal_opciones-input', function(e) {
        if(e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            var texto = $(this).val().trim();
            if(texto) {
                agregarTag(texto);
            }
        }
    });

    // Resetear modal al cerrar
    $('#modalAgregarPregunta').on('hidden.bs.modal', function () {
        resetearModal();
    });

    // Mostrar/ocultar campo de opciones según el tipo de respuesta
    $('#modal_tipo_respuesta').change(function() {
        var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
        if(tiposConOpciones.includes($(this).val())) {
            $('#modal_opciones-container').show();
            $('#modal_opciones-input').focus();
        } else {
            $('#modal_opciones-container').hide();
            limpiarTags();
        }
    });

    // Guardar pregunta desde el modal
    $('#btnGuardarPregunta').click(function() {
        if(!validarModal()) return false;
        
        $(this).prop('disabled', true);
        
        var modo = $(this).data('modo');
        var encuestaId = $(this).data('encuesta-id');
        var preguntaId = $('#modal_pregunta_id').val();
        
        var data = {
            texto: $('#modal_texto').val(),
            tipo_respuesta: $('#modal_tipo_respuesta').val(),
            dimension: $('#modal_dimension').val()
        };

        if(modo == 'crear') {
            // Al crear: incluir orden calculado y encuesta_id
            data.encuesta_id = encuestaId;
            data.orden = $('#modal_orden').val();
        } else {
            // Al editar: incluir id pero NO modificar orden (se mantiene el actual)
            data.id = preguntaId;
            // No se envía orden, se mantiene el que tiene la pregunta
        }

        var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
        if(tiposConOpciones.includes($('#modal_tipo_respuesta').val())) {
            // Obtener opciones de los tags
            actualizarOpcionesHidden();
            var opcionesJson = $('#modal_opciones').val();
            if(opcionesJson) {
                try {
                    var opciones = JSON.parse(opcionesJson);
                    if(opciones.length === 0) {
                        $('#btnGuardarPregunta').prop('disabled', false);
                        notify('Error', 'Debe agregar al menos una opción', 'danger');
                        return false;
                    }
                    data.opciones = JSON.stringify(opciones);
                } catch(e) {
                    $('#btnGuardarPregunta').prop('disabled', false);
                    notify('Error', 'Error al procesar las opciones', 'danger');
                    return false;
                }
            } else {
                $('#btnGuardarPregunta').prop('disabled', false);
                notify('Error', 'Debe agregar al menos una opción', 'danger');
                return false;
            }
        }

        var url = modo == 'crear' ? '{{ route("encuestas.guardar_pregunta") }}' : '{{ route("encuestas.actualizar_pregunta") }}';
        var mensajeExito = modo == 'crear' ? 'Pregunta creada con éxito' : 'Pregunta actualizada con éxito';

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(res) {
                if(res.estado == 200) {
                    notify('Éxito', mensajeExito, 'success');
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarPregunta'));
                    modal.hide();
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                } else {
                    $('#btnGuardarPregunta').prop('disabled', false);
                    notify('Error', res.mensaje || 'Error', 'danger');
                }
            },
            error: function() {
                $('#btnGuardarPregunta').prop('disabled', false);
                notify('Error', 'Error al ' + (modo == 'crear' ? 'crear' : 'actualizar') + ' la pregunta', 'danger');
            }
        });
    });

    function validarModal() {
        if($('#modal_texto').val().trim() == "") {
            notify('Advertencia', 'Debe completar el campo texto de la pregunta', 'danger');
            return false;
        }
        if($('#modal_tipo_respuesta').val() == "") {
            notify('Advertencia', 'Debe seleccionar el tipo de respuesta', 'danger');
            return false;
        }
        // Validar orden solo al crear
        var modo = $('#btnGuardarPregunta').data('modo');
        if(modo == 'crear' && ($('#modal_orden').val() == "" || $('#modal_orden').val() < 1)) {
            notify('Advertencia', 'Error al calcular el orden', 'danger');
            return false;
        }
        var tiposConOpciones = ['opcion_multiple', 'escala_1_5'];
        if(tiposConOpciones.includes($('#modal_tipo_respuesta').val())) {
            actualizarOpcionesHidden();
            var opcionesJson = $('#modal_opciones').val();
            if(!opcionesJson || opcionesJson === '[]') {
                notify('Advertencia', 'Debe agregar al menos una opción para este tipo de respuesta', 'danger');
                return false;
            }
            try {
                var opciones = JSON.parse(opcionesJson);
                if(!Array.isArray(opciones) || opciones.length === 0) {
                    notify('Advertencia', 'Debe agregar al menos una opción', 'danger');
                    return false;
                }
            } catch(e) {
                notify('Advertencia', 'Error al validar las opciones', 'danger');
                return false;
            }
        }
        return true;
    }
</script>
@endsection
