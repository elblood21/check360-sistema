<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario UNO - Check 360</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .form-container { max-width: 700px; margin: 3rem auto; background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .form-title { text-align: center; margin-bottom: 2rem; }
        .question { margin-top: 1.5rem; }
        .question small { color: #8b8eb1 !important; }
        .btn:hover {
            color: var(--bs-btn-hover-color);
            background-color: #b1935f;
            border-color: #af915e;
        }
        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: #DEBE88;
            --bs-btn-border-color: #DEBE88;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #DEBE88;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="form-title">¿Tu empresa cumple con la normativa laboral en Chile?</h2>
        <p>Completa este breve cuestionario y descubre si estás en riesgo de sanciones o demandas laborales.</p>
        <form id="cuestionarioForm">
            <!-- Iterar cuestionario -->
            @foreach($cuestionario as $i => $item)
            <div class="question">
                <label class="form-label">{{ ($i+1) . '. ' . $item['pregunta'] }}</label>
                @foreach($item['respuestas'] as $key => $respuesta)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pregunta_{{ $i }}" id="pregunta_{{ $i }}_{{ $key }}" value="{{ $key }}" required>
                    <label class="form-check-label" for="pregunta_{{ $i }}_{{ $key }}">{{ $respuesta }}</label>
                </div>
                @endforeach
                <small><i class="fa-solid fa-triangle-exclamation text-warning"></i> Riesgo: {{ $item['warning'] }}</small>
            </div>
            @endforeach

            <div class="mt-5 p-4 bg-light rounded border">
            <p class="mb-2" style="font-size:1.1rem;"><span style="font-size:1.3rem;">📌</span> <b>¿Tienes alguna respuesta negativa o con dudas?</b><br>
            Podrías estar en riesgo legal y operativo.</p>
            <p class="mb-2"><span style="font-size:1.3rem;">📩</span> <b>Solicita una evaluación gratuita ahora y protege a tu empresa.</b></p>
            <ul class="mb-0" style="list-style:none; padding-left:0;">
                <li><span style="color:#8b8eb1; font-size:1.2rem;">🔹</span> Evita multas y demandas</li>
                <li><span style="color:#8b8eb1; font-size:1.2rem;">🔹</span> Recibe asesoría personalizada</li>
                <li><span style="color:#8b8eb1; font-size:1.2rem;">🔹</span> Cumple con la normativa y fortalece tu gestión laboral</li>
            </ul>
        </div>

            <button type="button" class="btn btn-primary w-100 mt-4" id="abrirModalBtn">Solicitar evaluación</button>
        </form>
        
    </div>

    <!-- Modal para datos de empresa -->
    <div class="modal fade" id="empresaModal" tabindex="-1" aria-labelledby="empresaModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('formulario_uno.store') }}" id="empresaForm">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="empresaModalLabel">Datos de la Empresa</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="razon_social_modal" name="razon_social" required>
              </div>
              <div class="mb-3">
                <label for="rut" class="form-label">RUT <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="rut_modal" name="rut" required>
              </div>
              <div class="mb-3">
                <label for="correo_electronico" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="correo_electronico_modal" name="correo_electronico" required>
                <small class="text-muted">A este correo se enviará el informe con el resultado de la evaluación.</small>
              </div>
              <div class="mb-3">
                <label for="tamano_empresa" class="form-label">Tamaño de la Empresa <span class="text-danger">*</span></label>
                <select class="form-control" id="tamano_empresa_modal" name="tamano_empresa">
                  <option value="">Seleccione...</option>
                  <option value="Pequeña">Pequeña</option>
                  <option value="Mediana">Mediana</option>
                  <option value="Grande">Grande</option>
                </select>
              </div>
              <!-- Campo oculto para respuestas -->
              <input type="hidden" id="formulario_respuestas" name="formulario">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Solicitar evaluación</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.rut.chileno@2.0.2/jquery.rut.chileno.min.js"></script>
    <script src="https://sistema.check360.cl/assets/js/jquery/rut.js"></script>
    <script>

        $('#rut_modal').rut({
            formatOn: 'keyup',
            validateOn: 'change',
            minimumLength: 7,
            complete: function() {},
            invalid: function() {
                $('#rut_modal')[0].setCustomValidity('RUT inválido');
            },
            valid: function() {
                $('#rut_modal')[0].setCustomValidity('');
            }
        });
    
        var modalBtn = document.getElementById('abrirModalBtn');
        var empresaModal = document.getElementById('empresaModal');
        var modalInstance = bootstrap.Modal.getOrCreateInstance(empresaModal);
        modalBtn.addEventListener('click', function(e) {
            var form = document.getElementById('cuestionarioForm');
        // Validación y armado de respuestas
        var totalPreguntas = {{ count($cuestionario) }};
        var validas = true;
        var respuestas = [];
        for(var i=0; i<totalPreguntas; i++) {
            var radios = form.querySelectorAll('input[name="pregunta_'+i+'"]');
            var checked = false;
            var valor = null;
            radios.forEach(function(radio) { if(radio.checked) { checked = true; valor = radio.value; } });
            if(!checked) validas = false;
            respuestas.push({ pregunta: i, respuesta: valor });
        }
        if(!validas) {
            e.preventDefault();
            alert('Debes contestar todas las preguntas antes de continuar.');
            return false;
        } else {
            document.getElementById('formulario_respuestas').value = JSON.stringify(respuestas);
            modalInstance.show();
        }
        });
        
        document.getElementById('empresaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var razon = document.getElementById('razon_social_modal').value.trim();
            var rut = document.getElementById('rut_modal').value.trim();
            var correo = document.getElementById('correo_electronico_modal').value.trim();
            var rutValido = $.validateRut(rut);
            var correoValido = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(correo);
            if (!razon) {
                alert('El campo Razón Social es obligatorio.');
                e.preventDefault();
                return false;
            }
            if (!rut) {
                alert('El campo RUT es obligatorio.');
                e.preventDefault();
                return false;
            }
            if (!rutValido) {
                alert('El RUT ingresado no es válido.');
                e.preventDefault();
                return false;
            }
            if (!correo) {
                alert('El campo Correo Electrónico es obligatorio.');
                e.preventDefault();
                return false;
            }
            if (!correoValido) {
                alert('El correo electrónico ingresado no es válido.');
                e.preventDefault();
                return false;
            }
            
            $.ajax({
                url: "{{ route('formulario_uno.store') }}",
                method:'POST',
                headers: {
        			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        		},
                data:{
                    razon_social:$('#razon_social_modal').val(),
                    rut: $('#rut_modal').val(),
                    correo_electronico: $('#correo_electronico_modal').val(),
                    tamano_empresa: $('#tamano_empresa_modal').val(),
                    formulario: $('#formulario_respuestas').val()
                },
                success:function(res) {
                    alert("Formulario enviado con éxito, pronto serás contactado por uno de nuestros abogados");
                    setTimeout(function() {
                        window.location.href = "https://check360.cl"
                    }, 1000);
                }
            })
        })
    </script>
</body>
</html>
