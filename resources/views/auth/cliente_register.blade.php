@extends('layouts.master-noauth')

@section('content')
<div class="container pt-4">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <h3 style="margin-top: 4rem !important;margin-bottom: -0.3rem !important;">Registrate</h3>
      <p class="mt-2">Regístrate y gestiona el cumplimiento legal de tu empresa de manera fácil, rápida y eficiente</p>

      <form id="cliente-register-form" method="POST">
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="card mb-1">
              <div class="card-header"><b>Ingresa tus datos</b></div>
              <div class="card-body row ">
                <div class="mb-3 col-md-6">
                  <label class="form-label">Nombre (*)</label>
                  <input type="text" name="admin_nombre" class="form-control" required>
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label">RUT</label>
                  <input type="text" name="admin_rut" class="form-control">
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label">Correo electronico (*)</label>
                  <input type="email" name="admin_correo" class="form-control" required>
                  <div class="form-text">Se generará una contraseña y se enviará al correo proporcionado.</div>
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label">Teléfono</label>
                  <input type="text" name="admin_telefono" class="form-control">
                </div>
                
              </div>
            </div>
          </div>

          <div class="col-12 mt-4">
            <div class="card mb-3">
              <div class="card-header">Datos del cliente / empresa</div>
              <div class="card-body row">
                <div class="mb-3 col-md-6">
                  <label class="form-label">Razón social (*)</label>
                  <input type="text" name="razon_social" class="form-control" required>
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label">RUT (*)</label>
                  <input type="text" name="rut" class="form-control" required>
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label">Dirección</label>
                  <input type="text" name="direccion" class="form-control">
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label">Giro (*)</label>
                  <input type="text" name="giro" class="form-control">
                </div>
                <div class="mb-3 col-md-6 ">
                  <label class="form-label">Tamaño empresa (*)</label>
                  <select name="tamano_empresa" class="form-control">
                    <option value="micro">Micro</option>
                    <option value="pequeña">Pequeña</option>
                    <option value="mediana">Mediana</option>
                    <option value="grande">Grande</option>
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label">Cantidad de trabajadores (*)</label>
                  <input type="number" name="cantidad_trabajadores" class="form-control" min="0">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-3 mb-5">
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
    $('#cliente-register-form').submit(function(e) {
      e.preventDefault();
      var form = $(this);
      $('#cliente-register-form button[type="submit"]').prop('disabled', true).text('Registrando...');

      $.ajax({
        url: "{{ route('clientes.registro.post') }}",
        method: "POST",
        data: form.serialize(),
        success: function(res) {
          if(res.estado == 200) {
            alert('Registro exitoso. Se ha enviado una contraseña al correo electrónico proporcionado.');
            window.location.href = "{{ route('carpetas.lista') }}";
          } else {
            $('#cliente-register-form button[type="submit"]').prop('disabled', false).text('Registrarme');
            alert('Error: ' + res.mensaje);
          }
        },
        error: function(xhr) {
          $('#cliente-register-form button[type="submit"]').prop('disabled', false).text('Registrarme');
          alert('Ocurrió un error. Por favor, intenta nuevamente.');
        }
      });
    });
  });
</script>
@endsection