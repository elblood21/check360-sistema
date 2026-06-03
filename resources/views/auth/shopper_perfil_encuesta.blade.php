@extends('layouts.master-noauth')
@section('title', 'Completa tu Perfil')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<style>
    body {
        background-color: #f4f6f9;
    }
    .top-bar-survey {
        padding: 1rem 2rem;
        display: flex;
        justify-content: flex-end;
    }
    .survey-container {
        max-width: 800px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
    }
    .survey-step {
        display: none;
        animation: fadeInStep 0.4s ease forwards;
        width: 100%;
    }
    .survey-step.active {
        display: block;
    }
    @keyframes fadeInStep {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .progress-bar-custom {
        height: 8px;
        background-color: #f0f0f0;
        border-radius: 4px;
        margin-bottom: 30px;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    /* Estilos para botones de emoji (1-5) */
    .escala-emoji-btn {
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
    }
    
    .escala-emoji-btn[data-valor="1"]:hover {
        transform: scale(1.1);
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
    }
    .escala-emoji-btn[data-valor="2"]:hover {
        transform: scale(1.1);
        background-color: #fd7e14 !important;
        border-color: #fd7e14 !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(253, 126, 20, 0.4);
    }
    .escala-emoji-btn[data-valor="3"]:hover {
        transform: scale(1.1);
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
    }
    .escala-emoji-btn[data-valor="4"]:hover {
        transform: scale(1.1);
        background-color: #20c997 !important;
        border-color: #20c997 !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(32, 201, 151, 0.4);
    }
    .escala-emoji-btn[data-valor="5"]:hover {
        transform: scale(1.1);
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
    }
    
    .escala-emoji-btn[data-valor="1"].active {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
    }
    .escala-emoji-btn[data-valor="2"].active {
        background-color: #fd7e14 !important;
        border-color: #fd7e14 !important;
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(253, 126, 20, 0.4);
    }
    .escala-emoji-btn[data-valor="3"].active {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
    }
    .escala-emoji-btn[data-valor="4"].active {
        background-color: #20c997 !important;
        border-color: #20c997 !important;
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(32, 201, 151, 0.4);
    }
    .escala-emoji-btn[data-valor="5"].active {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
    }
    
    /* Estilos para botones Si/No */
    .si-no-btn {
        transition: all 0.3s ease;
        border-width: 2px;
        border-color: #dee2e6;
    }
    
    .si-no-btn[data-valor="Sí"]:hover {
        transform: translateY(-2px);
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
    }
    .si-no-btn[data-valor="No"]:hover {
        transform: translateY(-2px);
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
    }
    
    .si-no-btn[data-valor="Sí"].active {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }
    .si-no-btn[data-valor="No"].active {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }
    
    /* Estilos para botones de opciones */
    .opciones-btn {
        transition: all 0.3s ease;
        border-width: 2px;
    }
    .opciones-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .opciones-btn.active {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
    }
</style>
@endsection

@section('content')
<div class="top-bar-survey">
    <a href="{{ route('desconectarse') }}" class="btn btn-outline-danger btn-sm rounded-pill"><i class="icofont icofont-logout"></i> Cerrar sesión</a>
</div>
<div class="container-fluid" style="min-height: calc(100vh - 60px); display: flex; align-items: center; justify-content: center; padding-bottom: 2rem;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card survey-container p-4 shadow-sm border-0">
                <div class="card-header pb-0 border-0">
                    <h3 class="mb-1">Completa tu Perfil</h3>
                    <p class="text-muted">Queremos conocerte mejor para asignarte las mejores visitas.</p>
                </div>
                
                <div class="progress-bar-custom">
                    <div id="progressFill" class="progress-fill" style="width: 10%;"></div>
                </div>

                @if(count($preguntas) > 0)
                    @foreach($preguntas as $index => $pregunta)
                    <div class="survey-step {{ $index == 0 ? 'active' : 'hidden' }}" data-step="{{ $index }}" data-pregunta-id="{{ $pregunta->id }}">
                        <div class="mb-5">
                            <label class="form-label" style="font-size: 1.1rem; font-weight: 500;">{{ $pregunta->orden }}. {{ $pregunta->texto }}</label>
                            
                            @if($pregunta->tipo_respuesta == 'escala_1_5')
                                @php
                                    $textoOpcion1 = '';
                                    $textoOpcion5 = '';
                                    if($pregunta->opciones && is_array($pregunta->opciones)) {
                                        if(isset($pregunta->opciones[0]) && strpos($pregunta->opciones[0], ':') !== false) {
                                            $textoOpcion1 = trim(explode(':', $pregunta->opciones[0], 2)[1]);
                                        }
                                        if(isset($pregunta->opciones[4]) && strpos($pregunta->opciones[4], ':') !== false) {
                                            $textoOpcion5 = trim(explode(':', $pregunta->opciones[4], 2)[1]);
                                        }
                                    }
                                @endphp
                                <div class="escala-emoji-container" data-pregunta-id="{{$pregunta->id}}">
                                    <input type="hidden" class="respuesta-pregunta" data-pregunta-id="{{$pregunta->id}}" required>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="d-flex gap-2 mt-2 flex-wrap justify-content-center">
                                            <button type="button" class="btn btn-outline-secondary escala-emoji-btn" data-valor="1" data-pregunta-id="{{$pregunta->id}}" style="font-size: 2.5rem; padding: 0.75rem 1.25rem; border-radius: 10px; min-width: 80px;">
                                                😞
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary escala-emoji-btn" data-valor="2" data-pregunta-id="{{$pregunta->id}}" style="font-size: 2.5rem; padding: 0.75rem 1.25rem; border-radius: 10px; min-width: 80px;">
                                                😕
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary escala-emoji-btn" data-valor="3" data-pregunta-id="{{$pregunta->id}}" style="font-size: 2.5rem; padding: 0.75rem 1.25rem; border-radius: 10px; min-width: 80px;">
                                                😐
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary escala-emoji-btn" data-valor="4" data-pregunta-id="{{$pregunta->id}}" style="font-size: 2.5rem; padding: 0.75rem 1.25rem; border-radius: 10px; min-width: 80px;">
                                                🙂
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary escala-emoji-btn" data-valor="5" data-pregunta-id="{{$pregunta->id}}" style="font-size: 2.5rem; padding: 0.75rem 1.25rem; border-radius: 10px; min-width: 80px;">
                                                😊
                                            </button>
                                        </div>
                                        @if($textoOpcion1 || $textoOpcion5)
                                        <div class="d-flex gap-2 justify-content-center" style="width: 100%; max-width: 33rem; margin-top: 0.75rem;">
                                            @if($textoOpcion1)
                                                <div class="text-center" style="flex: 1; font-size: 0.75rem; color: #6c757d;">{{$textoOpcion1}}</div>
                                            @else
                                                <div style="flex: 1;"></div>
                                            @endif
                                            <div style="flex: 1;"></div>
                                            <div style="flex: 1;"></div>
                                            <div style="flex: 1;"></div>
                                            @if($textoOpcion5)
                                                <div class="text-center" style="flex: 1; font-size: 0.75rem; color: #6c757d;">{{$textoOpcion5}}</div>
                                            @else
                                                <div style="flex: 1;"></div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif($pregunta->tipo_respuesta == 'si_no')
                                <div class="si-no-container" data-pregunta-id="{{$pregunta->id}}">
                                    <input type="hidden" class="respuesta-pregunta" data-pregunta-id="{{$pregunta->id}}" required>
                                    <div class="d-flex gap-2 mt-2 justify-content-center">
                                        <button type="button" class="btn btn-outline-secondary si-no-btn" data-valor="Sí" data-pregunta-id="{{$pregunta->id}}" style="font-size: 1rem; padding: 0.6rem 1.2rem; font-weight: 500; border-radius: 8px;">
                                            ✓ Sí
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary si-no-btn" data-valor="No" data-pregunta-id="{{$pregunta->id}}" style="font-size: 1rem; padding: 0.6rem 1.2rem; font-weight: 500; border-radius: 8px;">
                                            ✗ No
                                        </button>
                                    </div>
                                </div>
                            @elseif($pregunta->tipo_respuesta == 'seleccion_unica')
                                <div class="seleccion-unica-container mt-3" data-pregunta-id="{{$pregunta->id}}">
                                    <select class="form-select form-select-lg respuesta-pregunta" data-pregunta-id="{{$pregunta->id}}" required style="border-radius: 8px; border-color: #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                                        <option value="">Seleccione una opción...</option>
                                        @if($pregunta->opciones)
                                            @php
                                                $opcionesArr = is_string($pregunta->opciones) ? json_decode($pregunta->opciones, true) : $pregunta->opciones;
                                            @endphp
                                            @if(is_array($opcionesArr))
                                                @foreach($opcionesArr as $opcion)
                                                    <option value="{{$opcion}}">{{$opcion}}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            @elseif($pregunta->tipo_respuesta == 'opciones' || $pregunta->tipo_respuesta == 'opcion_multiple')
                                <div class="opciones-container" data-pregunta-id="{{$pregunta->id}}">
                                    <input type="hidden" class="respuesta-pregunta" data-pregunta-id="{{$pregunta->id}}" required>
                                    <div class="d-flex flex-wrap gap-2 mt-2 justify-content-center">
                                        @if($pregunta->opciones)
                                            @foreach($pregunta->opciones as $opcion)
                                                <button type="button" class="btn btn-outline-primary opciones-btn" data-valor="{{$opcion}}" data-pregunta-id="{{$pregunta->id}}" style="font-size: 1rem; padding: 0.6rem 1.2rem; font-weight: 500; border-radius: 8px;">
                                                    {{$opcion}}
                                                </button>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @else
                                <textarea class="form-control form-control-sm btn-square respuesta-pregunta" data-pregunta-id="{{$pregunta->id}}" rows="3" placeholder="Ingrese su respuesta" required></textarea>
                            @endif
                        </div>

                        <div class="mt-5 d-flex justify-content-between">
                            @if($index > 0)
                                <button type="button" class="btn btn-light" onclick="prevStep({{ $index }})">Anterior</button>
                            @else
                                <div></div>
                            @endif

                            @if($index < count($preguntas) - 1)
                                <button type="button" class="btn btn-primary px-5" onclick="nextStep({{ $index }})">Siguiente</button>
                            @else
                                <button type="button" class="btn btn-success px-5" onclick="finalizarEncuesta()">Finalizar y Enviar</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center p-5">
                        <p>No hay preguntas configuradas para el perfil.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let currentStep = 0;
    const totalSteps = {{ count($preguntas) }};
    const respuestasGuardadas = {};

    function updateProgress() {
        const progress = ((currentStep + 1) / totalSteps) * 100;
        document.getElementById('progressFill').style.width = progress + '%';
    }

    // Manejar clic en botones de emoji
    $(document).on('click', '.escala-emoji-btn', function() {
        var valor = $(this).data('valor');
        var preguntaId = $(this).data('pregunta-id');
        
        $('.escala-emoji-btn[data-pregunta-id="' + preguntaId + '"]').removeClass('active');
        $(this).addClass('active');
        $('.respuesta-pregunta[data-pregunta-id="' + preguntaId + '"]').val(valor);
    });

    // Manejar clic en botones Si/No
    $(document).on('click', '.si-no-btn', function() {
        var valor = $(this).data('valor');
        var preguntaId = $(this).data('pregunta-id');
        
        $('.si-no-btn[data-pregunta-id="' + preguntaId + '"]').removeClass('active');
        $(this).addClass('active');
        $('.respuesta-pregunta[data-pregunta-id="' + preguntaId + '"]').val(valor);
    });

    // Manejar clic en botones de opciones
    $(document).on('click', '.opciones-btn', function() {
        var valor = $(this).data('valor');
        var preguntaId = $(this).data('pregunta-id');
        
        $('.opciones-btn[data-pregunta-id="' + preguntaId + '"]').removeClass('active');
        $(this).addClass('active');
        $('.respuesta-pregunta[data-pregunta-id="' + preguntaId + '"]').val(valor);
    });

    function guardarRespuesta(preguntaId, respuesta) {
        return $.ajax({
            url: '{{ route("shopper.guardar_respuesta_perfil") }}',
            method: 'POST',
            data: {
                pregunta_id: preguntaId,
                respuesta_texto: respuesta.respuesta_texto || null,
                respuesta_valor: respuesta.respuesta_valor || null
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    function nextStep(step) {
        const currentEl = document.querySelector(`[data-step="${step}"]`);
        const preguntaId = currentEl.dataset.preguntaId;
        const respuestaInput = document.querySelector(`.respuesta-pregunta[data-pregunta-id="${preguntaId}"]`);
        
        if (!respuestaInput || !respuestaInput.value || respuestaInput.value.trim() === '') {
            if (typeof swal !== 'undefined') {
                swal('Atención', 'Por favor responde la pregunta para continuar.', 'warning');
            } else {
                alert('Por favor responde la pregunta para continuar.');
            }
            return;
        }

        // Guardar respuesta
        const respuesta = {
            respuesta_texto: respuestaInput.tagName === 'TEXTAREA' ? respuestaInput.value : null,
            respuesta_valor: respuestaInput.tagName !== 'TEXTAREA' ? respuestaInput.value : null
        };

        guardarRespuesta(preguntaId, respuesta).done(function(res) {
            if (res.estado == 200) {
                respuestasGuardadas[preguntaId] = respuesta;
                
                currentEl.classList.remove('active');
                const nextEl = document.querySelector(`[data-step="${step + 1}"]`);
                if (nextEl) {
                    nextEl.classList.add('active');
                }
                currentStep++;
                updateProgress();
            } else {
                if (typeof swal !== 'undefined') {
                    swal('Error', res.mensaje || 'Error al guardar la respuesta', 'error');
                } else {
                    alert('Error: ' + (res.mensaje || 'Error al guardar la respuesta'));
                }
            }
        }).fail(function() {
            if (typeof swal !== 'undefined') {
                swal('Error', 'Error al guardar la respuesta', 'error');
            } else {
                alert('Error al guardar la respuesta');
            }
        });
    }

    function prevStep(step) {
        const currentEl = document.querySelector(`[data-step="${step}"]`);
        const prevEl = document.querySelector(`[data-step="${step - 1}"]`);

        currentEl.classList.remove('active');
        if (prevEl) {
            prevEl.classList.add('active');
        }
        currentStep--;
        updateProgress();
    }

    function finalizarEncuesta() {
        const currentEl = document.querySelector(`[data-step="${currentStep}"]`);
        const preguntaId = currentEl.dataset.preguntaId;
        const respuestaInput = document.querySelector(`.respuesta-pregunta[data-pregunta-id="${preguntaId}"]`);
        
        if (!respuestaInput || !respuestaInput.value || respuestaInput.value.trim() === '') {
            if (typeof swal !== 'undefined') {
                swal('Atención', 'Por favor responde la última pregunta.', 'warning');
            } else {
                alert('Por favor responde la última pregunta.');
            }
            return;
        }

        // Guardar última respuesta
        const respuesta = {
            respuesta_texto: respuestaInput.tagName === 'TEXTAREA' ? respuestaInput.value : null,
            respuesta_valor: respuestaInput.tagName !== 'TEXTAREA' ? respuestaInput.value : null
        };

        guardarRespuesta(preguntaId, respuesta).done(function(res) {
            if (res.estado == 200) {
                // Finalizar encuesta
                $.ajax({
                    url: '{{ route("shopper.finalizar_perfil") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.estado == 200) {
                            if (typeof swal !== 'undefined') {
                                swal('¡Excelente!', 'Tu perfil ha sido completado y enviado para revisión.', 'success');
                                setTimeout(function() {
                                    window.location.href = res.url;
                                }, 1500);
                            } else {
                                alert('¡Excelente! Tu perfil ha sido completado y enviado para revisión.');
                                window.location.href = res.url;
                            }
                        } else {
                            if (typeof swal !== 'undefined') {
                                swal('Error', res.mensaje || 'Error al finalizar el perfil', 'error');
                            } else {
                                alert('Error: ' + (res.mensaje || 'Error al finalizar el perfil'));
                            }
                        }
                    },
                    error: function() {
                        if (typeof swal !== 'undefined') {
                            swal('Error', 'Error al finalizar el perfil', 'error');
                        } else {
                            alert('Error al finalizar el perfil');
                        }
                    }
                });
            } else {
                if (typeof swal !== 'undefined') {
                    swal('Error', res.mensaje || 'Error al guardar la respuesta', 'error');
                } else {
                    alert('Error: ' + (res.mensaje || 'Error al guardar la respuesta'));
                }
            }
        }).fail(function() {
            if (typeof swal !== 'undefined') {
                swal('Error', 'Error al guardar la respuesta', 'error');
            } else {
                alert('Error al guardar la respuesta');
            }
        });
    }
    
    // Asegurar que el script se ejecute después de que todo esté cargado
    $(document).ready(function() {
        // Evitar errores de SimpleBar si el sidebar no está visible
        if ($('#sidebar-menu').length === 0 || $('#sidebar-menu').is(':hidden')) {
            // No hacer nada si el sidebar no existe
        }
    });
</script>
@endsection
