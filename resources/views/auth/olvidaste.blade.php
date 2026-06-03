@extends('layouts.master-noauth')
@section('title', '¿Olvidaste tu contraseña?')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid">
         <div class="login-card">
            <div>
               <div><a style="width: 10rem;" class="logo text-start" href="{{ route('index') }}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo_check360.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_check360.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                  <form class="theme-form needs-validation" novalidate="">
                     <h4>¿Olvidaste tu contraseña?</h4>
                     <p>Ingresa tu correo y te enviaremos una contraseña provisoria para que puedas ingresar a tu cuenta</p>
                     <div class="form-group">
                        <label class="col-form-label">Correo electronico</label>
                        <input class="form-control" type="email" id="email" required="" placeholder="Test@gmail.com">
                        <div class="invalid-feedback">Porfavor ingresa un correo valido.</div>
                     </div>
                     <div class="form-group mb-0">
                        <button class="btn btn-primary btn-block w-100 mt-3" type="button" id="btn-recuperar">Enviar contraseña </button>
                     </div>
                     <p class="mt-4 mb-0 text-center">¿Ya tienes cuenta?<a class="ms-2" href="{{ route('index') }}">Inicia sesion</a></p>
                  </form>
               </div>
            </div>
         </div>
      </div>
@endsection

@section('script')
<script>
    $('#btn-recuperar').click(function(){
        var email = $('#email').val();
        if(email == ''){
            notify('Error', 'Debes ingresar un correo', 'danger');
            return;
        }

        $.ajax({
            url: '{{ route('recuperar') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                correo: email,
                email: email
            },
            beforeSend: function(){
                $('#btn-recuperar').attr('disabled', true);
                $('#btn-recuperar').html('Enviando...');
            },
            success: function(resp){
                if(resp.estado == 200){
                    notify('Exito', resp.mensaje, 'success');
                    setTimeout(function(){
                        window.location.href = '{{ route('index') }}';
                    }, 2000);
                }else{
                    notify('Error', resp.mensaje, 'danger');
                    $('#btn-recuperar').attr('disabled', false);
                    $('#btn-recuperar').html('Enviar contraseña');
                }
            },
            error: function(){
                notify('Error', 'Ha ocurrido un error', 'danger');
                $('#btn-recuperar').attr('disabled', false);
                $('#btn-recuperar').html('Enviar contraseña');
            }
        });
    });
</script>
@endsection