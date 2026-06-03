@extends('layouts.master-noauth')
@section('title', 'Registro Mistery Shopper')

@section('content')
<div class="container pt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="text-center mb-4">
        <a class="logo" href="{{ route('index') }}">
          <img class="img-fluid" src="{{asset('assets/images/logo/logo_check360.png')}}" alt="Check 360" style="width: 10rem;">
        </a>
      </div>

      <h3 class="text-center mb-3">Regístrate como Mistery Shopper</h3>
      <p class="text-center mb-4">Completa el formulario para registrarte. Recibirás un correo de confirmación y, una vez que tu cuenta sea aprobada, te enviaremos tus credenciales de acceso por correo electrónico.</p>

      <form id="shopper-register-form" method="POST">
        @csrf
        <div class="card mb-3">
          <div class="card-header"><b>Datos personales</b></div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Nombre completo (*)</label>
                <input type="text" name="nombre" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Correo electrónico (*)</label>
                <input type="email" name="email" class="form-control" required>
                <div class="form-text">Recibirás un correo cuando tu cuenta sea aprobada.</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <div class="input-group">
                  <span class="input-group-text">+56</span>
                  <input type="text" name="telefono" id="telefono" class="form-control" placeholder="912345678" maxlength="9" pattern="[0-9]{9}">
                </div>
                <div class="form-text">Ingresa 9 dígitos (ejemplo: 912345678)</div>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label">Observaciones / Experiencia previa</label>
                <textarea name="observaciones" class="form-control" rows="3" placeholder="Cuéntanos sobre tu experiencia o interés en ser Mistery Shopper"></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
          <a href="{{ route('loginX') }}" class="btn btn-outline-secondary">Volver al login</a>
          <button type="submit" class="btn btn-primary">Registrarme</button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    // Validar y formatear teléfono
    $('#telefono').on('input', function() {
      var value = $(this).val().replace(/\D/g, ''); // Solo números
      $(this).val(value);
    });

    $('#shopper-register-form').submit(function(e) {
      e.preventDefault();
      var form = $(this);
      var submitBtn = $('#shopper-register-form button[type="submit"]');
      
      // Validar teléfono si está lleno
      var telefono = $('#telefono').val();
      if (telefono && telefono.length !== 9) {
        notify('Error', 'El teléfono debe tener exactamente 9 dígitos', 'danger', 4000);
        return;
      }
      
      submitBtn.prop('disabled', true).text('Registrando...');

      // Agregar prefijo +56 al teléfono antes de enviar
      var formData = form.serializeArray();
      var telefonoIndex = formData.findIndex(item => item.name === 'telefono');
      if (telefonoIndex !== -1 && formData[telefonoIndex].value) {
        formData[telefonoIndex].value = '+56' + formData[telefonoIndex].value;
      }

      $.ajax({
        url: "{{ route('shopper.registro.post') }}",
        method: "POST",
        data: $.param(formData),
        success: function(res) {
          if(res.estado == 200) {
            notify(
              'Solicitud Recibida',
              'Tu solicitud ha sido recibida correctamente. Ahora completa tu perfil.',
              'success',
              2000
            );
            setTimeout(function() {
              // Redirigir directamente a completar perfil
              window.location.href = res.url || "{{ route('shopper.completar_perfil') }}";
            }, 1500);
          } else {
            submitBtn.prop('disabled', false).text('Registrarme');
            notify('Error', res.mensaje || 'Ocurrió un error al procesar tu registro', 'danger', 4000);
          }
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false).text('Registrarme');
          var mensaje = 'Ocurrió un error. Por favor, intenta nuevamente.';
          if(xhr.responseJSON && xhr.responseJSON.mensaje) {
            mensaje = xhr.responseJSON.mensaje;
          } else if(xhr.responseJSON && xhr.responseJSON.message) {
            mensaje = xhr.responseJSON.message;
          }
          notify('Error', mensaje, 'danger', 4000);
        }
      });
    });
  });
</script>
@endsection
