@extends('layouts.master')
@section('title', 'Responder pre-encuesta')

@section('css')
<style>
    /* Progress bar sutil */
    .survey-progress {
        height: 6px;
        background-color: #f0f0f0;
        border-radius: 10px;
        margin-bottom: 25px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #0075cd, #00c6ff);
        width: 0%;
        transition: width 0.5s ease;
    }

    /* Contenedor principal extendido */
    .survey-card {
        max-width: 100%;
        margin: 0 auto;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: none;
    }
    
    .dimension-badge {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 5px 12px;
        border-radius: 50px;
        background: #f0f7ff;
        color: #0075cd;
        font-weight: 700;
        margin-bottom: 10px;
        display: inline-block;
    }

    .question-box {
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 15px;
        background: #fff;
        transition: all 0.3s;
        border: 1px solid #f0f0f0;
    }
    .question-box.active-focus {
        border-color: #0075cd;
        box-shadow: 0 5px 15px rgba(0,117,205,0.08);
    }
    .question-text {
        font-size: 1.05rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    /* Rating Cards - Compactas y Coloridas */
    .rating-wrapper {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    }
    
    .rating-item {
        cursor: pointer;
        text-align: center;
        padding: 12px 5px;
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        background: #fff;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }
    
    .rating-item svg {
        width: 32px;
        height: 32px;
        margin-bottom: 5px;
        filter: grayscale(100%);
        opacity: 0.5;
        transition: all 0.3s;
    }
    
    .rating-item .val-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #999;
    }

    /* Colores por valor */
    .rating-item[data-valor="1"]:hover, .rating-item[data-valor="1"].active { border-color: #ff4d4d; background: #fff5f5; }
    .rating-item[data-valor="1"]:hover svg, .rating-item[data-valor="1"].active svg { filter: grayscale(0); opacity: 1; transform: scale(1.1); }
    .rating-item[data-valor="1"].active .val-label { color: #ff4d4d; }

    .rating-item[data-valor="2"]:hover, .rating-item[data-valor="2"].active { border-color: #ff944d; background: #fff9f5; }
    .rating-item[data-valor="2"]:hover svg, .rating-item[data-valor="2"].active svg { filter: grayscale(0); opacity: 1; transform: scale(1.1); }
    .rating-item[data-valor="2"].active .val-label { color: #ff944d; }

    .rating-item[data-valor="3"]:hover, .rating-item[data-valor="3"].active { border-color: #ffdb4d; background: #fffdf5; }
    .rating-item[data-valor="3"]:hover svg, .rating-item[data-valor="3"].active svg { filter: grayscale(0); opacity: 1; transform: scale(1.1); }
    .rating-item[data-valor="3"].active .val-label { color: #ccae00; }

    .rating-item[data-valor="4"]:hover, .rating-item[data-valor="4"].active { border-color: #77dd77; background: #f5fdf5; }
    .rating-item[data-valor="4"]:hover svg, .rating-item[data-valor="4"].active svg { filter: grayscale(0); opacity: 1; transform: scale(1.1); }
    .rating-item[data-valor="4"].active .val-label { color: #55aa55; }

    .rating-item[data-valor="5"]:hover, .rating-item[data-valor="5"].active { border-color: #2ecc71; background: #f0fbf5; }
    .rating-item[data-valor="5"]:hover svg, .rating-item[data-valor="5"].active svg { filter: grayscale(0); opacity: 1; transform: scale(1.1); }
    .rating-item[data-valor="5"].active .val-label { color: #27ae60; }

    /* Sí / No modernizado */
    .binary-wrapper {
        display: flex;
        gap: 15px;
        justify-content: center;
    }
    .binary-item {
        flex: 1;
        max-width: 150px;
        cursor: pointer;
        padding: 10px;
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        text-align: center;
        transition: all 0.2s;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .binary-item svg { width: 20px; height: 20px; }
    
    .binary-item[data-valor="Sí"]:hover, .binary-item[data-valor="Sí"].active { border-color: #2ecc71; color: #27ae60; background: #f0fbf5; }
    .binary-item[data-valor="No"]:hover, .binary-item[data-valor="No"].active { border-color: #ff4d4d; color: #ff4d4d; background: #fff5f5; }

    /* Opciones múltiples compactas */
    .tags-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }
    .tag-item {
        cursor: pointer;
        padding: 8px 16px;
        border-radius: 50px;
        border: 1px solid #ddd;
        font-size: 0.9rem;
        background: #fff;
        transition: all 0.2s;
    }
    .tag-item:hover, .tag-item.active {
        background: #0075cd;
        color: #fff;
        border-color: #0075cd;
    }

    .step-section {
        display: none;
    }
    .step-section.active {
        display: block;
        animation: slideUp 0.4s ease-out;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .error-shake {
        border-color: #ff4d4d !important;
        animation: shake 0.4s ease-in-out;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">
            
            <!-- Header Info -->
            <div class="card mb-3 border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #0075cd 0%, #005696 100%);">
                <div class="card-body p-3 text-white">
                    <div class="row align-items-center text-center text-md-start">
                        <div class="col-md-7">
                            <h5 class="mb-1 text-white">{{ $visita->restaurante->name ?? 'Restaurante' }}</h5>
                            <p class="mb-0 opacity-75 small"><i class="fa fa-calendar me-1"></i> {{ $visita->fecha_asignacion->format('d/m/Y') }} | <i class="fa fa-clock-o me-1"></i> {{ date('H:i', strtotime($visita->hora_asignacion)) }}</p>
                        </div>
                        <div class="col-md-5 text-md-end mt-2 mt-md-0">
                            <span class="badge bg-white text-primary px-3 py-2" style="border-radius: 8px;">Pre-Visita</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Survey Card -->
            <div class="card survey-card">
                <div class="card-body p-4">
                    
                    @php
                        $preguntasPorDimension = [];
                        foreach($preguntas as $pregunta) {
                            $dimObj = $pregunta->dimension_rel;
                            $dimName = $dimObj ? $dimObj->nombre : ($pregunta->dimension ?: 'General');
                            $preguntasPorDimension[$dimName][] = $pregunta;
                        }
                        $dimensiones = array_keys($preguntasPorDimension);
                        $totalSteps = count($dimensiones);
                    @endphp

                    <!-- Progress bar -->
                    <div class="d-flex justify-content-between align-items-end mb-1">
                        <span class="small fw-bold text-muted" id="stepIndicator">Sección 1 de {{ $totalSteps }}</span>
                        <span class="small text-muted" id="percentLabel">0%</span>
                    </div>
                    <div class="survey-progress">
                        <div class="progress-fill" id="progressBar"></div>
                    </div>

                    <form id="formEncuesta">
                        @foreach($dimensiones as $idx => $dim)
                        <div class="step-section {{ $idx == 0 ? 'active' : '' }}" data-step="{{ $idx }}">
                            
                            <div class="text-center mb-4">
                                <span class="dimension-badge">{{ $dim }}</span>
                                <h4 class="fw-bold">{{ $idx == 0 ? '¡Hola! Comencemos con ' . $dim : 'Continuamos con ' . $dim }}</h4>
                            </div>

                            <div class="row g-4">
                                @foreach($preguntasPorDimension[$dim] as $pIdx => $pregunta)
                                <div class="col-md-6">
                                    <div class="question-box h-100 mb-0" id="q-{{ $pregunta->id }}">
                                        <div class="question-text">
                                            <span class="text-primary me-2">#{{ $pIdx + 1 }}</span> {{ $pregunta->texto }}
                                        </div>
                                        
                                        @if($pregunta->tipo_respuesta == 'escala_1_5')
                                            <input type="hidden" class="respuesta-pregunta" name="respuestas[{{$pregunta->id}}]" data-pregunta-id="{{$pregunta->id}}" required>
                                            <div class="rating-wrapper">
                                                <!-- 1: Malo -->
                                                <div class="rating-item" data-valor="1" data-pregunta-id="{{$pregunta->id}}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                                    <span class="val-label">1</span>
                                                </div>
                                                <!-- 2: Regular -->
                                                <div class="rating-item" data-valor="2" data-pregunta-id="{{$pregunta->id}}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 15s1.5-2 4-2 4 2 4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                                    <span class="val-label">2</span>
                                                </div>
                                                <!-- 3: Neutro -->
                                                <div class="rating-item" data-valor="3" data-pregunta-id="{{$pregunta->id}}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="15" x2="16" y2="15"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                                    <span class="val-label">3</span>
                                                </div>
                                                <!-- 4: Bueno -->
                                                <div class="rating-item" data-valor="4" data-pregunta-id="{{$pregunta->id}}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 13s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                                    <span class="val-label">4</span>
                                                </div>
                                                <!-- 5: Excelente -->
                                                <div class="rating-item" data-valor="5" data-pregunta-id="{{$pregunta->id}}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 13s1.5 4 4 4 4-4 4-4"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                                    <span class="val-label">5</span>
                                                </div>
                                            </div>

                                        @elseif($pregunta->tipo_respuesta == 'si_no')
                                            <input type="hidden" class="respuesta-pregunta" name="respuestas[{{$pregunta->id}}]" data-pregunta-id="{{$pregunta->id}}" required>
                                            <div class="binary-wrapper">
                                                <div class="binary-item" data-valor="Sí" data-pregunta-id="{{$pregunta->id}}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    Sí
                                                </div>
                                                <div class="binary-item" data-valor="No" data-pregunta-id="{{$pregunta->id}}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                    No
                                                </div>
                                            </div>

                                        @elseif($pregunta->tipo_respuesta == 'opciones' || $pregunta->tipo_respuesta == 'opcion_multiple')
                                            <input type="hidden" class="respuesta-pregunta" name="respuestas[{{$pregunta->id}}]" data-pregunta-id="{{$pregunta->id}}" required>
                                            <div class="tags-wrapper">
                                                @if($pregunta->opciones)
                                                    @foreach($pregunta->opciones as $opt)
                                                        <div class="tag-item" data-valor="{{$opt}}" data-pregunta-id="{{$pregunta->id}}">{{$opt}}</div>
                                                    @endforeach
                                                @endif
                                            </div>

                                        @else
                                            <textarea class="form-control respuesta-pregunta" name="respuestas[{{$pregunta->id}}]" data-pregunta-id="{{$pregunta->id}}" rows="3" placeholder="Escribe tu comentario aquí..." style="border-radius: 12px; border: 1px solid #eee;" required></textarea>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4" id="btnBackReal" style="display: none; border-radius: 10px;">
                                <i class="fa fa-angle-left me-1"></i> Anterior
                            </button>
                            <div class="ms-auto d-flex gap-2">
                                <a href="{{route('visitas.ver', encrypt($visita->id))}}" class="btn btn-light px-4" id="btnExit" style="border-radius: 10px;">Salir</a>
                                <button type="button" class="btn btn-primary px-5" id="btnNext" style="border-radius: 10px; font-weight: bold;">
                                    Siguiente <i class="fa fa-angle-right ms-1"></i>
                                </button>
                                <button type="button" class="btn btn-success px-5" id="btnFinalize" style="display: none; border-radius: 10px; font-weight: bold;">
                                    Finalizar <i class="fa fa-check ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        let currentStep = 0;
        const totalSteps = {{ $totalSteps }};

        function updateUI() {
            // Secciones
            $('.step-section').removeClass('active');
            $(`.step-section[data-step="${currentStep}"]`).addClass('active');

            // Progress bar
            const percent = ((currentStep + 1) / totalSteps) * 100;
            $('#progressBar').css('width', percent + '%');
            $('#percentLabel').text(Math.round(percent) + '%');
            $('#stepIndicator').text(`Sección ${currentStep + 1} de ${totalSteps}`);

            // Botones
            if(currentStep === 0) {
                $('#btnBackReal').hide();
                $('#btnExit').show();
            } else {
                $('#btnBackReal').show();
                $('#btnExit').hide();
            }

            if(currentStep === totalSteps - 1) {
                $('#btnNext').hide();
                $('#btnFinalize').show();
            } else {
                $('#btnNext').show();
                $('#btnFinalize').hide();
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Selección de Rating
        $('.rating-item').click(function() {
            const pId = $(this).data('pregunta-id');
            const val = $(this).data('valor');
            $(`.rating-item[data-pregunta-id="${pId}"]`).removeClass('active');
            $(this).addClass('active');
            $(`input.respuesta-pregunta[data-pregunta-id="${pId}"]`).val(val).trigger('change');
            $(`#q-${pId}`).removeClass('error-shake active-focus');
        });

        // Selección Binaria (Sí/No)
        $('.binary-item').click(function() {
            const pId = $(this).data('pregunta-id');
            const val = $(this).data('valor');
            $(`.binary-item[data-pregunta-id="${pId}"]`).removeClass('active');
            $(this).addClass('active');
            $(`input.respuesta-pregunta[data-pregunta-id="${pId}"]`).val(val).trigger('change');
            $(`#q-${pId}`).removeClass('error-shake');
        });

        // Selección Tags
        $('.tag-item').click(function() {
            const pId = $(this).data('pregunta-id');
            const val = $(this).data('valor');
            $(`.tag-item[data-pregunta-id="${pId}"]`).removeClass('active');
            $(this).addClass('active');
            $(`input.respuesta-pregunta[data-pregunta-id="${pId}"]`).val(val).trigger('change');
            $(`#q-${pId}`).removeClass('error-shake');
        });

        // Focus visual en preguntas
        $('.question-box').click(function() {
            $('.question-box').removeClass('active-focus');
            $(this).addClass('active-focus');
        });

        function validateStep() {
            let valid = true;
            let firstError = null;
            $(`.step-section[data-step="${currentStep}"] .respuesta-pregunta`).each(function() {
                if($(this).prop('required') && !$(this).val()) {
                    valid = false;
                    const pId = $(this).data('pregunta-id');
                    $(`#q-${pId}`).addClass('error-shake');
                    if(!firstError) firstError = $(`#q-${pId}`);
                }
            });

            if(!valid && firstError) {
                $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 300);
                notify('Campo requerido', 'Por favor responde todas las preguntas para continuar.', 'warning');
            }
            return valid;
        }

        $('#btnNext').click(function() {
            if(validateStep()) {
                currentStep++;
                updateUI();
            }
        });

        $('#btnBackReal').click(function() {
            if(currentStep > 0) {
                currentStep--;
                updateUI();
            }
        });

        $('#btnFinalize').click(function() {
            if(!validateStep()) return;

            const respuestas = {};
            $('.respuesta-pregunta').each(function() {
                const pId = $(this).data('pregunta-id');
                const val = $(this).val();
                if(val) respuestas[pId] = val;
            });

            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: '{{route("visitas.guardar_entrada")}}',
                method: 'POST',
                data: {
                    visita_id: '{{encrypt($visita->id)}}',
                    respuestas: respuestas,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if(res.estado == 200 || res.success) {
                        notify('¡Excelente!', 'Encuesta guardada con éxito', 'success');
                        setTimeout(() => { window.location.href = "{{route('visitas.ver', encrypt($visita->id))}}"; }, 1500);
                    } else {
                        $btn.prop('disabled', false).text('Finalizar');
                        notify('Error', res.mensaje || 'Error al guardar', 'danger');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).text('Finalizar');
                    notify('Error', 'Error de conexión', 'danger');
                }
            });
        });

        updateUI();
    });
</script>
@endsection